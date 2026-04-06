# Stage 4 — Migration Plan
**Track:** codebase-refactor
**Refactor ID:** REFACTOR-001
**Gate:** 4 — requires Justin's `A` before Claude Code CLI execution begins
**Source:** `projects/radical-wp`
**Target:** `projects/radical-new-wp`
**Date:** 2026-04-05

---

## Prerequisites Before Running Claude Code CLI

1. Create the target directory: `mkdir -p ~/Documents/ViresBot/projects/radical-new-wp`
2. Init a git repo inside it: `cd ~/Documents/ViresBot/projects/radical-new-wp && git init`
3. Create a `CLAUDE.md` file (content in Section 1 below) — Claude Code CLI reads this first
4. Have `sass` CLI available (`npm install -g sass`) for Wave 7 CSS compilation
5. Source and target absolute paths:
   - **Source:** `~/Documents/ViresBot/projects/radical-wp`
   - **Target:** `~/Documents/ViresBot/projects/radical-new-wp` (current working directory when running CLI)

---

## Section 1 — CLAUDE.md for `radical-new-wp`

Create this file at the root of `radical-new-wp/` before invoking Claude Code CLI:

```markdown
# Radical Skincare — Plain PHP WordPress Theme

## What this is
A plain PHP refactor of `projects/radical-wp` (Sage 9 / Blade / Webpack).
Stack: PHP templates, flat CSS, jQuery, no Composer, no build tools.

## Source codebase (read-only reference)
Path: ~/Documents/ViresBot/projects/radical-wp
- Blade views: resources/views/
- PHP app layer: app/
- SCSS: resources/assets/styles/
- JS: resources/assets/scripts/
- ACF JSON: resources/acf-json/

## Target codebase (write here)
Path: ~/Documents/ViresBot/projects/radical-new-wp (current directory)
Follow the exact directory structure in docs/development/03-target-architecture.md.

## Cardinal rules
- NEVER create .blade.php files — all templates are plain .php
- NEVER use Composer — zero require_once for vendor/autoload.php
- NEVER use asset_path() — replace with get_template_directory_uri() . '/assets/...'
- NEVER use sage(), config(), template() helper functions
- NEVER reference App\ namespace or Sober\Controller
- NEVER modify files in the source codebase (radical-wp)
- All get_template_directory() calls in inc/ files are correct — do not change them

## Blade → PHP conversion cheat sheet
@extends('layouts.app')            → REMOVE (get_header/get_footer handles this)
@section('content') @endsection    → REMOVE (content goes directly in file)
@include('partials.foo.bar')       → get_template_part('template-parts/foo/bar')
@include('partials.header')        → get_template_part('template-parts/header/header')
{{ $var }}                         → <?php echo esc_html($var); ?>
{!! $var !!}                       → <?php echo $var; ?>
@php ... @endphp                   → <?php ... ?>
@if(...) @endif                    → <?php if(...): ?> <?php endif; ?>
@foreach(...) @endforeach          → <?php foreach(...): ?> <?php endforeach; ?>
@asset('styles/main.css')          → get_template_directory_uri() . '/assets/css/main.css'
$siteName (App controller var)     → get_bloginfo('name')
$title (App controller var)        → radical_page_title() [defined in inc/helpers.php]

## WooCommerce email templates
resources/woocommerce/emails/ → woocommerce/emails/
These are already plain PHP — copy without conversion.

## Disabled integrations (do not enable)
- user-coupons.php — intentionally commented out in source, keep disabled
- NOTUSED_subscription-features-skip_code.php — do not port

## JS approach
Single concatenated assets/js/main.js — no import/export, no bundler.
All modules use IIFE or class pattern with jQuery. Initialize on jQuery(document).ready.

## Wave order
Work strictly wave-by-wave. Commit after each wave. Do not skip ahead.
Wave 1: Foundation → Wave 2: Core Templates → Wave 3: PHP Includes →
Wave 4: Custom Templates → Wave 5: Template Parts → Wave 6: WooCommerce →
Wave 7: Assets → Wave 8: Cleanup
```

---

## Wave 1 — Foundation

**Goal:** A valid WordPress theme that activates without fatal errors. No content rendered yet.

**Files to create:**

### `style.css`
```css
/*
Theme Name:  Radical Skincare
Theme URI:   https://radicalskincare.com
Description: Radical Skincare custom WordPress theme — plain PHP refactor.
Author:      Vires
Author URI:  https://viressoftware.com
Version:     2.0.0
Tags:        woocommerce, custom
Text Domain: radical
*/
```

### `index.php`
Minimal fallback — `get_header()`, the loop with `get_template_part('template-parts/content')`, `get_footer()`.

### `functions.php`
Exact require chain from Stage 3 architecture. No logic — requires only.

### `inc/setup.php`
Source reference: `app/setup.php` → `add_action('after_setup_theme', ...)` + `add_action('widgets_init', ...)`

Include:
- All `add_theme_support()` declarations (title-tag, post-thumbnails, html5, customize-selective-refresh-widgets, soil-*)
- `register_nav_menus()` — locations: navbar, primary_navigation, mobile-navbar
- `widgets_init` — sidebars: sidebar-primary, sidebar-footer, mega-menu, currency-converter-widget (same config array as source)
- `add_editor_style('assets/css/main.css')`
- Cache-control header block for `/account/brand-partner-customers` and `/checkout` (copy verbatim from source `app/setup.php` bottom section)

### `inc/enqueue.php`
Exact content from Stage 3 architecture spec. All handles, conditionals, and `wp_localize_script` object preserved.

### `inc/helpers.php`
Source reference: `app/helpers.php` (bottom half only — drop all Sage container functions)

Include:
- `display_sidebar()` — copy verbatim, remove any Sage references
- `radical_page_title()` — new function (from Stage 3 spec)
- Any other non-Sage utility functions found below line ~80 of source `app/helpers.php`

### `inc/filters.php`
Source reference: `app/filters.php`

Include:
- `body_class` filter — keep page-slug logic + `display_sidebar()` class; update regex to remove `/-blade(-php)?$/` pattern (artifact no longer exists) and `'/^page-template-views/'` pattern
- `excerpt_more` filter — copy verbatim

Drop:
- All three Sage template/Blade filters (template hierarchy, template_include, comments_template)

### `screenshot.png`
Copy `resources/screenshot.jpg` → `screenshot.png` (or create a placeholder — WP expects PNG).

**Wave 1 commit:** `refactor: Wave 1 — theme foundation (style.css, functions.php, setup, enqueue, helpers, filters)`

**Wave 1 verify:** Theme appears in WP Admin → Appearance → Themes. Click Activate — no fatal PHP errors. White screen = fatal error; check PHP error log.

---

## Wave 2 — Core Templates

**Goal:** All standard WordPress template hierarchy files rendering with correct header/footer.

**Source reference for all files:** `resources/views/` + `resources/views/partials/`

**Conversion approach for every file in this wave:**
1. The `@extends('layouts.app')` directive means the file's content goes between the layout's header and footer. In plain PHP: `get_header()` at top, content in middle, `get_footer()` at bottom.
2. Remove `@section('content')` / `@endsection` wrappers.
3. Convert all Blade syntax using the cheat sheet in CLAUDE.md.

### `header.php`
Source: `resources/views/layouts/app.blade.php` (everything above `@yield('content')`) + `resources/views/partials/head.blade.php`

Structure:
```php
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php get_template_part('template-parts/header/header'); ?>
```

### `footer.php`
Source: `resources/views/layouts/app.blade.php` (everything below `@yield('content')`)

Structure:
```php
<?php get_template_part('template-parts/footer/footer'); ?>
<?php wp_footer(); ?>
</body>
</html>
```

### `sidebar.php`
Source: `resources/views/partials/sidebar.blade.php`

### `comments.php`
Source: `resources/views/comments.blade.php`

### `front-page.php`
Source: `resources/views/home.blade.php`

### `home.php`
Source: `resources/views/index.blade.php` (blog index)

### `page.php`
Source: `resources/views/page.blade.php`

### `single.php`
Source: `resources/views/single.blade.php`

### `archive.php`
Source: `resources/views/archive.blade.php`

### `archive-podcasts.php`
Source: `resources/views/archive-podcasts.blade.php`

### `search.php`
Source: `resources/views/search.blade.php`

### `404.php`
Source: `resources/views/404.blade.php`

**Wave 2 commit:** `refactor: Wave 2 — core WP template hierarchy (header, footer, page, single, archive, search, 404)`

**Wave 2 verify:** Homepage loads. A standard page loads. A single post loads. Archive loads. 404 page loads. No PHP errors. Header and footer visible on each (even if unstyled).

---

## Wave 3 — PHP Includes & Integrations

**Goal:** All business logic, custom post types, hooks, filters, and plugin integrations ported.

**Conversion rule for all files in this wave:** These are pure PHP — no Blade syntax. The only changes needed are:
- Remove `namespace App;` declarations
- Remove `use Roots\...` imports
- Replace `get_stylesheet_directory()` with `get_template_directory()` (same result for non-child themes, but consistent)
- Replace any `asset_path()` calls with `get_template_directory_uri() . '/assets/...'`

### `inc/class-wp-bootstrap-navwalker.php`
Source: `app/inc/class-wp-bootstrap-navwalker.php` — **copy verbatim**, no changes needed.

### `inc/admin/acf.php`
Source: `app/admin/acf.php`

Changes: Update `get_stylesheet_directory()` → `get_template_directory()` in the JSON path filters. Everything else verbatim.

### `inc/admin/podcasts.php`
Source: `app/admin/podcasts.php` + **AJAX handlers from `app/Controllers/ArchivePodcasts.php`**

Structure:
1. CPT registration (from existing `podcasts.php`)
2. `radical_get_podcasts($listing_type, $posts_per_page, $offset)` — standalone function equivalent of `ArchivePodcasts::getPodcasts()` (same WP_Query logic, no class needed)
3. `add_action('wp_ajax_get_podcasts', 'radical_ajax_get_podcasts')`
4. `add_action('wp_ajax_nopriv_get_podcasts', 'radical_ajax_get_podcasts')`
5. `add_action('wp_ajax_get_podcast', 'radical_ajax_get_podcast')`
6. `add_action('wp_ajax_nopriv_get_podcast', 'radical_ajax_get_podcast')`
7. `radical_ajax_get_podcasts()` function — equivalent of `ArchivePodcasts::ajaxGetPodcasts()`
8. `radical_ajax_get_podcast()` function — equivalent of `ArchivePodcasts::ajaxGetPodcast()`

### `inc/admin/press-items.php`
Source: `app/admin/press-items.php` — copy, remove namespace if present.

### `inc/admin/stories.php`
Source: `app/admin/stories.php` — copy, remove namespace if present.

### `inc/admin/brand-partner-settings-page.php`
Source: `app/admin/brand-partner-settings-page.php` — copy, update any `get_stylesheet_directory()` paths.

### `inc/admin/vip-customers.php`
Source: `app/admin/vip-customers.php` — copy verbatim.

### `inc/admin/gigfiliate-wp.php`
Source: `app/admin/gigfiliate-wp.php` — copy verbatim.

### `inc/admin/woocommerce.php`
Source: `app/admin/woocommerce.php` — copy verbatim.

### `inc/integrations/woocommerce.php`
Source: `app/inc/woocommerce.php`

This file requires its sub-files. Update the require paths from `app/inc/woocommerce/` to `inc/integrations/woocommerce/`:
```php
require_once get_template_directory() . '/inc/integrations/woocommerce/active-subscriber-discounts.php';
require_once get_template_directory() . '/inc/integrations/woocommerce/conditional-product-sale.php';
// etc.
```

### `inc/integrations/woocommerce/` (6 files)
Source: `app/inc/woocommerce/` — copy all **except** `NOTUSED_subscription-features-skip_code.php` (drop this file).

Files to copy:
- `active-subscriber-discounts.php`
- `conditional-product-sale.php`
- `coupons.php`
- `payment-methods.php`
- `renewal-gift.php`
- `subscription-features.php`
- `subscription-reminder-email.php`

### Remaining integration files
All copy verbatim — no Sage dependencies in any of these:

| Source | Target |
|---|---|
| `app/inc/sitewide-discounts.php` | `inc/integrations/sitewide-discounts.php` |
| `app/inc/threshold-discount.php` | `inc/integrations/threshold-discount.php` |
| `app/inc/gigfiliate-wp.php` | `inc/integrations/gigfiliate.php` |
| `app/inc/gigfiliate-wp-brand-partner-helpers.php` | Append to `inc/integrations/gigfiliate.php` |
| `app/inc/api.php` | `inc/integrations/api.php` |
| `app/inc/template-tags.php` | `inc/integrations/template-tags.php` |
| `app/inc/template-helpers.php` | `inc/integrations/template-helpers.php` |
| `app/inc/favorites.php` | `inc/integrations/favorites.php` |
| `app/inc/yotpo-reviews-integration.php` | `inc/integrations/yotpo.php` |
| `app/inc/affiliate-wp-helpers.php` | `inc/integrations/affiliate-wp.php` |
| `app/inc/wployalty.php` | `inc/integrations/wployalty.php` |
| `app/inc/vip-customers.php` | `inc/integrations/vip-customers.php` |
| `app/inc/analyze-glow.php` | `inc/integrations/analyze-glow.php` |
| `app/inc/twilio-integraiton.php` | `inc/integrations/twilio.php` |
| `app/inc/class-wc-subscription-email.php` | `inc/integrations/wc-subscriptions.php` |
| `app/inc/security.php` | `inc/integrations/security.php` + add to `functions.php` require |

**Wave 3 commit:** `refactor: Wave 3 — PHP includes, CPT registration, AJAX handlers, and all integrations`

**Wave 3 verify:** No PHP fatal errors. In WP Admin: Custom Fields → Field Groups visible. Custom post type menus (Podcasts, Press, Stories) appear in sidebar. WooCommerce active with no integration errors in error log.

---

## Wave 4 — Custom Page Templates

**Goal:** All 21 custom page templates selectable in WP page editor and rendering without errors.

**Source reference:** `resources/views/template-*.blade.php`

**Conversion approach for each file:**
1. Add WP template comment header at top
2. `get_header();`
3. Convert Blade content — use `get_template_part()`, `<?php ?>`, and `esc_html()` as needed
4. `get_footer();`

**Template comment header format:**
```php
<?php
/**
 * Template Name: [Human readable name matching source Blade @section title or filename]
 */
get_header();
```

**Template name derivations:**

| File | Template Name |
|---|---|
| `template-home.php` | Home |
| `template-account.php` | Account |
| `template-contact.php` | Contact |
| `template-faq.php` | FAQ |
| `template-giving.php` | Giving |
| `template-mission.php` | Mission |
| `template-press.php` | Press |
| `template-team.php` | Team |
| `template-clean-conscious.php` | Clean Conscious |
| `template-clean-conscious-old.php` | Clean Conscious (Old) |
| `template-rewards.php` | Rewards |
| `template-radical-repeat.php` | Radical Repeat |
| `template-quiz.php` | Quiz |
| `template-trylacel.php` | Trylacel |
| `template-join-brand-partners.php` | Join Brand Partners |
| `template-brand-partner-enrollment.php` | Brand Partner Enrollment |
| `template-impact-fund.php` | Impact Fund |
| `template-hero-right-sidebar.php` | Hero Right Sidebar |
| `template-holiday-gift-guide.php` | Holiday Gift Guide |
| `template-radical-rituals.php` | Radical Rituals |
| `template-valentines.php` | Valentines |

**Note on `template-brand-partner-enrollment.php`:** Source has a `brand-partner/enrollment/` sub-directory of views. Read `resources/views/brand-partner/enrollment.blade.php` and all files in `resources/views/brand-partner/enrollment/` when porting this template — they render sub-sections via `@include`.

**Wave 4 commit:** `refactor: Wave 4 — 21 custom page templates`

**Wave 4 verify:** WP Admin → Pages → edit any page → Page Attributes → Template dropdown shows all 21 templates. Select each, visit the page, confirm no PHP errors.

---

## Wave 5 — Template Parts

**Goal:** All `template-parts/` partials created, matching source `resources/views/partials/` and `resources/views/modules/`.

**Source reference:** `resources/views/partials/**/*.blade.php` + `resources/views/modules/**/*.blade.php`

**Conversion approach:** Same as Wave 4 — Blade syntax → PHP, `@include` → `get_template_part()`, variables → WP functions or passed `$args`.

**Priority order within Wave 5 (create in this sequence so dependencies resolve):**

1. **Header partials first** — `template-parts/header/header.php` is called by `header.php` created in Wave 2. Until this exists, every page is broken.
   - `template-parts/header/header.php` — from `views/partials/header.blade.php`
   - `template-parts/header/announcements.php`
   - `template-parts/header/countdown.php`
   - `template-parts/header/cyber-monday.php`
   - `template-parts/header/favorites.php`
   - `template-parts/header/left-sidebar.php`
   - `template-parts/header/mega-menu.php`
   - `template-parts/header/search.php`

2. **Footer partials**
   - `template-parts/footer/footer.php` — from `views/partials/footer.blade.php`
   - `template-parts/footer/klaviyo.php`
   - `template-parts/footer/mailchimp.php`

3. **Sidebar**
   - `sidebar.php` (update if needed)
   - `template-parts/sidebar/single.php`

4. **Root-level partials**
   - `template-parts/scroll-to-top.php`
   - `template-parts/page-header.php`
   - `template-parts/entry-meta.php`
   - `template-parts/shop-fall-swap-bundle-banner.php`
   - `template-parts/shop-sales-notice.php`
   - `template-parts/shop-sidebar.php`
   - `template-parts/content.php`
   - `template-parts/content-page.php`
   - `template-parts/content-single.php`
   - `template-parts/content-product.php`
   - `template-parts/content-search.php`
   - `template-parts/content-cta-earn-points.php`
   - `template-parts/content-cta-join.php`
   - `template-parts/content-cta-shop.php`

5. **Content sub-partials** (10 files in `template-parts/content/`)

6. **Product partials** (19 files in `template-parts/product/`)

7. **Account partials** (20 files in `template-parts/account/` including sub-dirs)

8. **Remaining partials** — checkout, components, form, hero, modal, search

9. **Module views** — all 16 module directories from `views/modules/`

**Wave 5 commit:** `refactor: Wave 5 — all template parts and partials`

**Wave 5 verify:** Homepage renders with header and footer. Navigate through a few pages. No `get_template_part` failures (check error log for "is-not-readable" warnings).

---

## Wave 6 — WooCommerce Template Overrides

**Goal:** All 43 WooCommerce Blade template overrides converted to plain PHP in `woocommerce/`.

**Source reference:** `resources/views/woocommerce/**/*.blade.php`

**Important:** WooCommerce overrides work by matching WC's own template file paths exactly. Do NOT rename files or change directory structure.

**Conversion approach:** These Blade templates often wrap standard WC PHP. The conversion is:
1. Remove any `@extends`, `@section`, `@endsection`, `@include` Blade wrappers
2. The inner PHP content (WC hooks, `woocommerce_*` function calls) passes through unchanged
3. Convert any `{{ }}`, `{!! !!}`, `@if`, `@foreach` Blade syntax

**Highest-complexity files (read source carefully before porting):**
- `woocommerce/myaccount/dashboard.php` — uses account partials heavily
- `woocommerce/myaccount/view-subscription.php` — subscription details with partials
- `woocommerce/single-product/add-to-cart/variable.php` — subscription purchase options
- `woocommerce/checkout/thankyou.php` — custom checkout confirmation

**WooCommerce email templates** — `woocommerce/emails/`
Source: `resources/woocommerce/emails/` — these are already plain PHP. **Copy verbatim, no conversion needed.**

**Wave 6 commit:** `refactor: Wave 6 — WooCommerce template overrides`

**Wave 6 verify:** Shop page loads. Single product page loads. Cart works. Checkout loads. My Account page loads. Subscription management page loads. WooCommerce email templates accessible in WP Admin → WooCommerce → Settings → Emails.

---

## Wave 7 — Assets (CSS + JS)

**Goal:** All styles and scripts functional. Theme looks and behaves identically to source.

### CSS

**Step 1 — Compile source SCSS to baseline:**
```bash
# Run from the source codebase directory
cd ~/Documents/ViresBot/projects/radical-wp
npx sass resources/assets/styles/main.scss /tmp/radical-compiled.css --no-source-map
```

**Step 2 — Copy baseline to target:**
```bash
cp /tmp/radical-compiled.css ~/Documents/ViresBot/projects/radical-new-wp/assets/css/main.css
```

**Step 3 — Reorganize into sections:**
Open `assets/css/main.css` and add the section comment headers from Stage 3 architecture to group related rules. This is organizational — do not change any actual CSS rules.

**Step 4 — Copy vendor CSS:**
```bash
cp resources/assets/styles/lib/slick.css      assets/css/vendor/slick.css
cp resources/assets/styles/lib/slick-theme.css assets/css/vendor/slick-theme.css
cp resources/assets/styles/lib/owl.carousel.min.css assets/css/vendor/owl.carousel.min.css
```

### JS

**Step 1 — Copy vendor JS:**
```bash
# Slick — already local
cp resources/assets/scripts/lib/slick.min.js assets/js/vendor/slick.min.js
```

Download from CDN:
- Bootstrap 4.3.1 bundle: `https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js` → `assets/js/vendor/bootstrap.bundle.min.js`
- Owl Carousel 2.3.4: `https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js` → `assets/js/vendor/owl.carousel.min.js`
- Smooth Scroll 16.1.3: `https://cdn.jsdelivr.net/npm/smooth-scroll@16.1.3/dist/smooth-scroll.polyfills.min.js` → `assets/js/vendor/smooth-scroll.min.js`

**Step 2 — Port utility files:**
- `assets/js/modules/Cookie.js` — from `scripts/util/Cookie.js` (copy, remove ES6 `export`)
- `assets/js/modules/Utilities.js` — from `scripts/util/Utilities.js` (copy, remove ES6 `export`)

**Step 3 — Port all module files (25 files):**
Source: `resources/assets/scripts/modules/` + `resources/assets/scripts/layouts/` + `resources/assets/scripts/misc/`
Target: `assets/js/modules/`

For each module file:
- Remove `import` statements at the top
- Remove ES6 `export { ClassName }` at the bottom
- Keep the class body — it uses `jQuery` already (not `$` from import)
- The `static onLoad()` pattern stays intact

**Step 4 — Write `assets/js/main.js`:**

```javascript
/**
 * Radical Skincare — Main JS
 * Plain jQuery, no bundler. All modules concatenated via enqueue order.
 */
jQuery(document).ready(function($) {

  // Smooth scroll
  if (typeof SmoothScroll !== 'undefined') {
    new SmoothScroll('a[href*="#"]', {
      header: '#main-header',
      offset: function() { return 64; }
    });
  }

  // Initialize all modules
  if (typeof Global !== 'undefined')                Global.onLoad();
  if (typeof Header !== 'undefined')                Header.onLoad();
  if (typeof Product !== 'undefined')               Product.onLoad();
  if (typeof Search !== 'undefined')                Search.onLoad();
  if (typeof Favorites !== 'undefined')             Favorites.onLoad();
  if (typeof Login !== 'undefined')                 Login.onLoad();
  if (typeof PageHero !== 'undefined')              PageHero.onLoad();
  if (typeof Sale !== 'undefined')                  Sale.onLoad();
  if (typeof SkinCareAddition !== 'undefined')      SkinCareAddition.onLoad();
  if (typeof RefillAddToCart !== 'undefined')       RefillAddToCart.onLoad();
  if (typeof BrandPartner !== 'undefined')          BrandPartner.onLoad();
  if (typeof AmbassadorEnrollment !== 'undefined')  AmbassadorEnrollment.onLoad();
  if (typeof ArchivePodcasts !== 'undefined')       ArchivePodcasts.onLoad();
  if (typeof Giving !== 'undefined')                Giving.onLoad();
  if (typeof TemplateHome !== 'undefined')          TemplateHome.onLoad();
  if (typeof TemplatePress !== 'undefined')         TemplatePress.onLoad();
  if (typeof TemplateFAQ !== 'undefined')           TemplateFAQ.onLoad();
  if (typeof MyAccount !== 'undefined')             MyAccount.onLoad();
  if (typeof Form !== 'undefined')                  Form.onLoad();
  if (typeof ArchiveProducts !== 'undefined')       ArchiveProducts.onLoad();
  if (typeof TemplateTrylacel !== 'undefined')      TemplateTrylacel.onLoad();
  if (typeof EmailSubscribe !== 'undefined')        EmailSubscribe.onLoad();
  if (typeof WoocommerceSubscription !== 'undefined') WoocommerceSubscription.onLoad();
  if (typeof ProductReviewModel !== 'undefined')    ProductReviewModel.onLoad();
  if (typeof ProductPurchaseOptions !== 'undefined') ProductPurchaseOptions.onLoad();

});
```

**Step 5 — Update `inc/enqueue.php`:**
Add individual `wp_enqueue_script` calls for each module file, each depending on `radical/main`. Or concatenate all module files into `main.js` directly (simpler — no separate enqueue needed).

**Recommendation:** Concatenate into `main.js` via a build step or by having Claude Code CLI append each module file's content into `main.js` in Wave 7, eliminating 25 separate enqueue calls.

**Step 6 — Copy `customizer.js`:**
Source: `resources/assets/scripts/customizer.js` → `assets/js/customizer.js`
Enqueue via `add_action('customize_preview_init', ...)` if present in source, otherwise standard enqueue.

**Wave 7 commit:** `refactor: Wave 7 — CSS (compiled from SCSS) and JS (modules, vendor libs, main.js)`

**Wave 7 verify:** Theme looks visually correct. Open browser DevTools → Network → no 404s for CSS or JS. Homepage carousel works. Navigation/mobile menu works. No console JS errors.

---

## Wave 8 — Remaining Assets & Cleanup

**Goal:** All remaining assets in place. Theme fully production-ready.

### Copy assets:

```bash
# ACF JSON — 27 group files
cp -r resources/acf-json/ acf-json/

# Fonts
cp -r resources/assets/fonts/ assets/fonts/

# Images (entire tree)
cp -r resources/assets/images/ assets/images/

# WooCommerce email templates (already plain PHP)
mkdir -p woocommerce/emails
cp resources/woocommerce/emails/*.php woocommerce/emails/
```

### Final cleanup checklist:

- [ ] Search entire `radical-new-wp/` for any remaining `asset_path(` — replace each
- [ ] Search for `namespace App` — remove any that crept in
- [ ] Search for `use Roots\` or `use Sober\` — remove any
- [ ] Search for `sage(` — remove any
- [ ] Search for `@extends`, `@section`, `@include` — these indicate un-converted Blade syntax
- [ ] Confirm `functions.php` require for `inc/integrations/security.php` is present (easy to miss)
- [ ] Confirm `acf-json/` directory exists and ACF loads the fields in WP Admin
- [ ] Confirm `acf_add_options_page` calls in `inc/admin/acf.php` work (Theme Settings + Brand Partner Settings in WP Admin sidebar)
- [ ] Remove any `vendor/` or `node_modules/` directories if accidentally created
- [ ] No `.blade.php` files anywhere in the target

**Wave 8 commit:** `refactor: Wave 8 — ACF JSON, fonts, images, WC emails, final cleanup`

**Wave 8 verify:**
- ACF → Custom Fields: all 27 field groups visible
- ACF Options Pages: "Theme Settings" and "Brand Partner Settings" in WP Admin sidebar
- Fonts load (check browser DevTools)
- Images load (spot-check a few)
- WC emails preview in WP Admin → WooCommerce → Settings → Emails

---

## Section 2 — Claude Code CLI Kickoff Prompt

First, set up the target repo:
```bash
cd ~/Documents/ViresBot/projects/radical-new-wp
git init
# Create CLAUDE.md (content from Section 1 above)
```

Then paste this into Claude Code CLI from inside `radical-new-wp/`:

---

```
Read CLAUDE.md before writing any code.

You are implementing REFACTOR-001: Radical Skincare WP Theme — Sage 9 to Plain PHP.

Source codebase (READ ONLY): ~/Documents/ViresBot/projects/radical-wp
Target codebase (WRITE HERE): ~/Documents/ViresBot/projects/radical-new-wp (current directory)

Full spec is in docs/development/ — read all four stage docs before beginning:
- 01-refactor-brief.md
- 02-source-audit.md
- 03-target-architecture.md
- 04-migration-plan.md (this document)

Work strictly wave-by-wave. Commit after each wave. Do not begin a wave until the previous wave's commit is done.

WAVE 1 — Foundation
Create: style.css, index.php, functions.php, inc/setup.php, inc/enqueue.php, inc/helpers.php (includes radical_page_title()), inc/filters.php, screenshot.png (copy from source resources/screenshot.jpg).
Verify: No fatal PHP errors when theme is activated.
Commit: "refactor: Wave 1 — theme foundation (style.css, functions.php, setup, enqueue, helpers, filters)"

WAVE 2 — Core Templates
Create: header.php (from layouts/app.blade.php top half + partials/head.blade.php), footer.php (layouts/app.blade.php bottom half), sidebar.php, comments.php, front-page.php, home.php, page.php, single.php, archive.php, archive-podcasts.php, search.php, 404.php.
Convert all Blade syntax using the cheat sheet in CLAUDE.md.
Verify: Homepage, page, single post, archive, 404 all load without PHP errors.
Commit: "refactor: Wave 2 — core WP template hierarchy"

WAVE 3 — PHP Includes & Integrations
Create all files in inc/ as specified in the migration plan Section Wave 3.
Key transformation: ArchivePodcasts Controller AJAX handlers + getPodcasts() move to inc/admin/podcasts.php as standalone functions (radical_get_podcasts, radical_ajax_get_podcasts, radical_ajax_get_podcast).
Do NOT port: NOTUSED_subscription-features-skip_code.php, user-coupons.php (keep disabled).
Verify: No PHP fatal errors. CPTs visible in WP Admin. WooCommerce active.
Commit: "refactor: Wave 3 — PHP includes, CPT registration, AJAX handlers, all integrations"

WAVE 4 — Custom Page Templates
Create all 21 template-*.php files from resources/views/template-*.blade.php.
Each needs: Template Name comment header, get_header(), converted content, get_footer().
Template names: see migration plan Wave 4 table.
Verify: All 21 templates selectable in WP page editor.
Commit: "refactor: Wave 4 — 21 custom page templates"

WAVE 5 — Template Parts
Create all template-parts/**/*.php files from resources/views/partials/ and resources/views/modules/.
Create header partials FIRST (template-parts/header/header.php is called by header.php).
Then footer, sidebar, root-level content partials, product, account, modal, form, hero, components, search, modules.
Verify: Header and footer render on all pages. No get_template_part failures.
Commit: "refactor: Wave 5 — all template parts and partials"

WAVE 6 — WooCommerce Templates
Create all 43 woocommerce/**/*.php files from resources/views/woocommerce/.
Copy woocommerce/emails/ from resources/woocommerce/emails/ verbatim (no conversion needed).
Verify: Shop, single product, cart, checkout, my account all render.
Commit: "refactor: Wave 6 — WooCommerce template overrides"

WAVE 7 — Assets
Step 1: Compile SCSS → run: npx sass ~/Documents/ViresBot/projects/radical-wp/resources/assets/styles/main.scss assets/css/main.css --no-source-map (run from radical-new-wp/)
Step 2: Copy vendor CSS from source styles/lib/ → assets/css/vendor/
Step 3: Copy slick.min.js from source scripts/lib/ → assets/js/vendor/
Step 4: Download to assets/js/vendor/: bootstrap.bundle.min.js (Bootstrap 4.3.1), owl.carousel.min.js (OWL 2.3.4), smooth-scroll.min.js (16.1.3) — use curl or fetch from CDN URLs in migration plan Wave 7
Step 5: Port all JS module files from scripts/modules/ and scripts/layouts/ to assets/js/modules/ — remove ES6 import/export, keep class bodies
Step 6: Write assets/js/main.js using the template in migration plan Wave 7 Step 4
Verify: No 404s in DevTools network tab. Homepage carousel works. No JS console errors.
Commit: "refactor: Wave 7 — CSS compiled from SCSS, JS modules, vendor assets"

WAVE 8 — Cleanup
Copy: acf-json/ from resources/acf-json/, assets/fonts/ from resources/assets/fonts/, assets/images/ from resources/assets/images/, woocommerce/emails/ from resources/woocommerce/emails/
Run final cleanup search (grep for asset_path, namespace App, use Roots, sage(, @extends, @section, @include) and fix any remaining issues.
Verify: ACF field groups load (27 groups). Fonts load. Images load.
Commit: "refactor: Wave 8 — ACF JSON, fonts, images, WC emails, final cleanup"

CRITICAL RULES (enforce throughout all waves):
- NEVER create .blade.php files
- NEVER write require_once for vendor/autoload.php
- NEVER use asset_path() — always get_template_directory_uri() . '/assets/...'
- NEVER use namespace App, sage(), config(), template()
- NEVER modify any file in the source codebase radical-wp
- Read the source file BEFORE writing the target — understand what it does first

Begin with Wave 1.
```

---

## Gate 4 Checklist

- [x] CLAUDE.md content written — ready to create in target dir before CLI run
- [x] All 8 waves defined with source references, transformation notes, and verification steps
- [x] ArchivePodcasts Controller → `inc/admin/podcasts.php` migration documented
- [x] CSS compile strategy documented (sass CLI, one-time baseline)
- [x] JS concatenation strategy documented (Option A — single main.js)
- [x] Vendor JS download sources documented (CDN URLs)
- [x] Exact commit messages written for each wave
- [x] Claude Code CLI kickoff prompt complete and self-contained
- [x] `security.php` integration confirmed included in functions.php require chain
- [x] Disabled files documented (user-coupons, NOTUSED file)

**→ Type `A` to approve Gate 4, then:**
1. `mkdir -p ~/Documents/ViresBot/projects/radical-new-wp/docs/development`
2. Create `CLAUDE.md` in `radical-new-wp/` from Section 1 above
3. Open Claude Code CLI from inside `radical-new-wp/`
4. Paste the kickoff prompt from Section 2
