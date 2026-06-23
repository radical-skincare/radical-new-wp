# radical-new-wp Cutover Verification — Session Summary

**Date:** 2026-06-22 / 2026-06-23
**Scope:** Verify that `radical-new-wp` (plain-PHP rebuild of `radical-wp`, the old Sage 9 / Blade / Webpack theme) is a safe drop-in replacement, and fix anything broken.

## Starting state

- `radical-new-wp` was built across 8 documented "waves" by a previous Claude Code run (see `docs/development/04-migration-plan.md`, `05-build-and-verify.md`).
- The WordPress DB (`radical` DB, table prefix `wpuw_`) already had `radical-new-wp` set as the active theme (`template`/`stylesheet` options) — the switch had already happened, but **Gate 5 final verification was never run** (every row in the `05-build-and-verify.md` checklist was unchecked).
- Goal: go through the theme, verify all templates actually work, and fix anything broken — effectively finishing the verification that was skipped.

## Bugs found and fixed

### 1. Page-template postmeta still pointed at old Sage/Blade paths
The old theme stored custom page templates as Sage-style paths (`views/template-x.blade.php`); the new theme uses flat filenames (`template-x.php`). WordPress stores the selected template per-page in `_wp_page_template` postmeta as a literal filename, and this was never migrated — so **14 published pages** (Contact, FAQ, Team, Mission, Press, Trylacel, Giving, Impact Fund, Radical Rituals, Radical Repeat, Join Brand Partners, Brand Partner Enrollment, Consciously Clean, My Account) silently fell back to generic `page.php`, losing their real layout.

Verified live on page ID 56 ("Contact"): postmeta read `views/template-contact.blade.php`. Confirmed the wrong template was rendering by checking for the "Customer Service" sidebar card hardcoded in `template-contact.php` but absent from generic `page.php`.

**Fix:** built a WP Admin migration tool (per explicit user request, instead of running raw SQL by hand) — `inc/admin/migrate-page-templates.php`, exposed at **Tools → Migrate Page Templates**:
- `radical_migrate_page_templates_scan()` finds all `_wp_page_template` values matching `views/%.blade.php`, computes the candidate flat filename, and checks whether that file actually exists in the new theme.
- Dry-run table shows each page with old value → new value → status (Will migrate / Skipped — file not found).
- "Run Migration" button applies the fix via `update_post_meta()`. Safe to re-run (idempotent) — already-migrated rows simply stop matching the scan pattern.
- Rows whose target file doesn't exist (legacy/orphaned assignments predating even the old theme, e.g. `template-moment.blade.php`, `template-cart.blade.php`) are left untouched — these were already broken under the old theme too, not a regression.

Verified by running it locally: 21 rows migrated, Contact page confirmed rendering its real sidebar card afterward. Also separately confirmed (per a follow-up question from the user) that the remaining ~22 "skipped" rows are draft/private pages or duplicate/legacy pages that were never functional under the old theme either — not caused by this migration.

### 2. Homepage rendered the wrong content
The static front page (ID 44, "Home") was rendering a generic blog feed instead of the rich marketing homepage (hero, new-arrivals, carousel, etc).

Root cause: WordPress's native template hierarchy makes `front-page.php` win unconditionally for the front page, ignoring any page-template assignment — but the **old** Sage theme never had a `front-page.blade.php` file, so Sage's hierarchy filter fell through past `is_front_page()`/`is_home()` to `is_page()`, which used the page's *assigned* custom template, `template-home.blade.php` (the rich ACF homepage). The previous build agent's migration docs incorrectly assumed `front-page.php` should be sourced from `home.blade.php` (which is actually the *Posts Page* blog-listing template, used for the separate "Blog" page, ID 315).

Traced this precisely via WordPress core's `wp-includes/template-loader.php` foreach/break logic and `get_page_template()` source to confirm which file the old theme actually served before changing anything.

**Fix:** swapped the content —
- `front-page.php` now contains the rich homepage content (same as `template-home.php`).
- `home.php` (used for the Posts Page, ID 315 "Blog") now contains the blog-hero-listing content that used to incorrectly live in `front-page.php`.

Verified via curl: homepage now shows the hidden homepage marker text (`<h1 style="display:none">Welcome to the era of Holistic Technology...`) and no longer shows the blog hero; `/?page_id=315` (Blog) still loads fine with the blog listing.

### 3. Podcasts archive fatal error (HTTP 500)
`archive-podcasts.php` → `template-parts/modules/podcasts/left-sidebar.php` and `main-podcast-details.php` still called the old Sage `App\Controllers\ArchivePodcasts::getPodcasts()` static method, which doesn't exist in the new theme. The migration plan had correctly ported this to a standalone `radical_get_podcasts()` function in `inc/admin/podcasts.php` (same return shape), but two template-parts were never updated to call it.

**Fix:** changed both call sites from `ArchivePodcasts::getPodcasts(...)` to `radical_get_podcasts(...)`.

Verified via `wp-content/debug.log` (WP_DEBUG_LOG is enabled for this site) — the fatal (`Class "ArchivePodcasts" not found`) disappeared, and the archive now returns HTTP 200.

### 4. Frontend JS broken: `Uncaught TypeError: $ is not a function`
Reported by the user after the above fixes. Error originated in `Global.js`'s `scrollToTop()`, called from `Global.onLoad()`, called from `main.js`'s `jQuery(document).ready(...)` handler.

Root cause: the **WP Loyalty Rules** plugin's bundled script (`wlr-main.js`) calls `jQuery.noConflict()` unconditionally on every frontend page load. This unsets the global `$` alias (keeps `window.jQuery` intact). The old Sage theme was immune to this because Webpack bundled `import $ from 'jquery'` directly into each module's closure at build time — each module carried its own private reference to jQuery, regardless of what happened to the global `$`. The new theme has no bundler: all 25+ module files (`Global.js`, `Header.js`, etc.) reference the bare global `$` directly, so any other plugin's script calling `noConflict()` before ours breaks every module.

**Fix:** one line added to `inc/enqueue.php`:
```php
wp_add_inline_script('bootstrap-js', 'window.$ = jQuery;', 'after');
```
This restores `$` immediately before our own module scripts execute. Works reliably because the theme's `wp_enqueue_scripts` callback is registered at priority 100, after the Loyalty Rules plugin's default-priority-10 enqueue — guaranteeing our restore script always lands after the plugin's `noConflict()` call and before our modules run.

Verified in rendered page source: script order is `wlr-main.js` (calls `noConflict()`) → `bootstrap-js-js-after` (our `window.$ = jQuery;` restore) → `Global.js` and the rest of our modules, in that exact order.

**Pattern to watch for going forward:** any future JS bug in this theme should first check whether some other plugin's script calls `jQuery.noConflict()` before assuming the theme's own module code is wrong — this theme has no bundler, so all module files share one global `$`/`jQuery` namespace with every other plugin on the page.

## What's already confirmed solid (no action needed)

- CPT rewrite rules for `podcasts` and `story` are byte-identical between old and new theme.
- ACF JSON field-group file lists match; 42 ACF field groups load (docs said 27, actual count is higher — fine).
- Every `*.php` file in `radical-new-wp` passes `php -l`.
- `inc/enqueue.php` only references local asset paths that exist on disk.
- Sidebars/widgets and nav menu locations use identical IDs in both themes — global options, carried over automatically.
- `theme_mods_radical-new-wp` already had correct `nav_menu_locations`, confirming `after_switch_theme` ran successfully previously.
- All ~11 remaining migrated custom-template pages (FAQ, Team, Press, Trylacel, Giving, Radical Rituals, Radical Repeat, Join Brand Partners, Brand Partner Enrollment, Consciously Clean, Our Story) return HTTP 200 with no new errors after the fixes.

## Noted but out of scope (pre-existing, unrelated to the theme)

- Shop page (`?page_id=6`) redirects to a specific product permalink instead of a shop archive — caused by a slug collision between the "Products" page and the WooCommerce product permalink base, not theme code.
- `/checkout/` shows cart ("Basket") content — caused by the Checkout for WooCommerce plugin's cart-first checkout flow, not theme code.
- Remaining "skip-no-file" rows in the migration tool (Radical Living, the "Moments" chapter pages, Store Locator, old Affiliate/Ambassador pages, Radical Rx, Conquering Rosacea, duplicate Cart/Checkout pages) reference templates missing from the *old* theme too — already non-functional before this rebuild, several are draft/private.

## Still to verify (not done this session)

Full `docs/development/05-build-and-verify.md` checklist remains only partially covered:
- All 20 custom page templates — spot-checked for HTTP 200, not deep visual/functional review.
- WooCommerce checkout/cart/my-account flows — structural checks only (200 status), not actual add-to-cart / checkout submission.
- CPT archives (podcasts fixed and verified; press/stories not separately checked).
- JS functionality beyond the `$`/noConflict fix — carousels, modals, mobile menu, FAQ accordion, favorites — not manually tested in a browser.
- Visual regression vs. the old theme.

## Key files touched this session

- `inc/admin/migrate-page-templates.php` — new, the Tools → Migrate Page Templates admin tool.
- `functions.php` — added the require for the migration tool.
- `front-page.php`, `home.php` — content swapped (see Bug 2).
- `template-parts/modules/podcasts/left-sidebar.php`, `template-parts/modules/podcasts/main-podcast-details.php` — `ArchivePodcasts::getPodcasts()` → `radical_get_podcasts()`.
- `inc/enqueue.php` — added the `window.$ = jQuery;` restore inline script.

## Reference

Full investigation notes and original plan: `/Users/abdulrehman/.claude/plans/splendid-seeking-toucan.md`

## Follow-up: Gate 5 completed (2026-06-24)

The full `docs/development/05-build-and-verify.md` smoke-test checklist (left incomplete above) was finished in a follow-up session. A systemic bug was found and fixed across ~20 files: many Blade→PHP conversions used `set_query_var()`/bare variables before `get_template_part()` calls, which doesn't share scope the way Blade's `@include` did — this caused a fatal on every WooCommerce order confirmation page and several other My Account/podcast/product rendering bugs. See `05-build-and-verify.md`'s "Bugs found and fixed in this verification pass" section for the full list and details.
