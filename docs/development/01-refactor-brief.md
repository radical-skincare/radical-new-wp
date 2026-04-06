# Stage 1 — Refactor Brief
**Track:** codebase-refactor
**Refactor ID:** REFACTOR-001
**Gate:** 1 — requires Justin's `A` before Stage 2 begins

---

## Project

- **Title:** Radical Skincare WP Theme — Sage 9 → Plain PHP Refactor
- **Source codebase:** `projects/radical-wp`
- **Target codebase:** `projects/radical-new-wp`
- **Date:** 2026-04-05
- **Author:** Vires

---

## Why Refactor

The current theme is built on Sage 9 (roots.io), a WordPress starter framework that wraps the theme in Laravel-style conventions. Specific pain points:

**Build tooling is brittle.** Webpack 3 (2017-era), `node-sass` (deprecated, C++ binding with frequent install failures), and a complex multi-file webpack config (`webpack.config.js`, `webpack.config.optimize.js`, `webpack.config.watch.js`) mean making any CSS or JS change requires a full build pipeline to be healthy. The `.nvmrc` pins a specific Node version — any mismatch silently breaks the build.

**Blade templating adds indirection for no gain.** Every `.blade.php` template requires a developer to understand Sage's view layer on top of standard WordPress template hierarchy. `@extends`, `@yield`, `@section`, `@include`, `@php` directives obscure what is otherwise straightforward PHP. Someone familiar with WordPress but not Sage cannot read or edit these files confidently.

**The Controller pattern is unnecessary overhead.** Sage's `app/Controllers/` layer (backed by `soberwp/controller`) exists to pass data to Blade views. In a plain PHP theme, data is accessed directly in templates via standard WP functions — no controller abstraction is needed.

**Composer adds fragility without benefit.** The theme requires `illuminate/support` (a Laravel package), `roots/sage-lib`, `soberwp/controller`, and `roots/sage-woocommerce`. These are purely to support the Blade/Controller machinery. A plain PHP theme needs zero Composer dependencies.

**The result is a theme that behaves identically to end users** — the Blade templates compile to the same HTML a plain PHP template would produce. There is no user-visible benefit to the current stack's complexity.

---

## Source Stack

| Layer | Technology |
|---|---|
| Theme framework | Sage 9.0.10 (roots.io) |
| Templating | Blade (`.blade.php`) via `illuminate/view` |
| CSS preprocessor | SCSS via `node-sass` + `sass-loader` |
| JS bundler | Webpack 3.10 |
| PHP dependencies | Composer — `illuminate/support 5.6`, `roots/sage-lib ~9.0.9`, `soberwp/controller ~2.1.0`, `roots/sage-woocommerce ^1.0` |
| JS dependencies | jQuery 3.3.1, Bootstrap 4.3.1, Slick Carousel 1.8.1, Owl Carousel 2.3.4, Smooth Scroll 16.1.3, Popper.js 1.14.7, Datebook 7.0.8 |
| Build runtime | Node (pinned via `.nvmrc`), pnpm |

---

## Target Stack

| Layer | Technology |
|---|---|
| Theme framework | Standard WordPress theme — no framework |
| Templating | Plain PHP (`.php` files, standard WP template hierarchy) |
| CSS | Plain CSS — single `assets/css/main.css` (compiled from source SCSS once as a baseline, then maintained as flat CSS) |
| JS bundler | None — individual `.js` files enqueued via `wp_enqueue_scripts` |
| PHP dependencies | None — zero Composer |
| JS dependencies | jQuery (via WP core), Bootstrap 4 (local), Slick (local), Owl Carousel (local), Smooth Scroll (local if needed) |
| Build runtime | None — edit PHP/CSS/JS directly |

---

## Non-Negotiables (Must Preserve)

**WordPress templates:**
- [ ] All standard template hierarchy files (home, page, single, archive, search, 404)
- [ ] All 20+ custom page templates (`template-*.php`)
- [ ] `header.php`, `footer.php`, `sidebar.php`, `comments.php`
- [ ] All template parts (partials, content, modules)

**WordPress functionality:**
- [ ] All `add_theme_support()` declarations (post-thumbnails, html5, title-tag, etc.)
- [ ] All `register_nav_menus()` locations
- [ ] All custom image sizes
- [ ] All `wp_enqueue_scripts` handles and load order
- [ ] All WordPress hooks and filters from `app/filters.php`
- [ ] All helper functions from `app/helpers.php`

**Custom post types & taxonomy:**
- [ ] Podcasts CPT + archive template
- [ ] Press CPT
- [ ] Stories CPT
- [ ] Brand Partners CPT + all related pages/templates
- [ ] VIP Customers CPT

**ACF:**
- [ ] All 12 ACF field groups (JSON files in `resources/acf-json/`) — copy as-is
- [ ] `acf/settings/load_json` filter registered in new theme so ACF picks up the JSON

**WooCommerce:**
- [ ] All WooCommerce template overrides (archive, single product, content-product, checkout, myaccount, global, loop, order, single-product, emails)
- [ ] All WooCommerce hooks from `app/inc/woocommerce.php` and `app/inc/woocommerce/`

**Third-party plugin integrations (all PHP logic preserved):**
- [ ] Affiliate WP helpers
- [ ] Gigfiliate WP (custom affiliate system)
- [ ] WoLoyalty (rewards)
- [ ] Yotpo (reviews)
- [ ] Twilio (SMS)
- [ ] WC Subscriptions email customization
- [ ] Sitewide discounts
- [ ] User coupons
- [ ] Threshold discount
- [ ] Analyze Glow (product quiz)
- [ ] VIP Customers logic
- [ ] Favorites/wishlist

**Assets:**
- [ ] All fonts (star font files in `resources/assets/fonts/`)
- [ ] All images and SVGs in `resources/assets/images/`
- [ ] All WooCommerce email templates in `resources/woocommerce/emails/`

---

## Out of Scope

- No design changes — rendered HTML/CSS must be visually identical to end users
- No new features — any new functionality happens after cutover in a `new-feature` track
- No plugin upgrades or replacements
- No database or content changes
- No performance optimization (beyond removing the Webpack build overhead itself)
- Analyze Glow quiz and Brand Partner enrollment flows are complex — port faithfully, do not redesign

---

## Open Questions

| # | Question | Owner | Resolved? |
|---|---|---|---|
| 1 | Is there a staging environment to test the new theme before production cutover? | Justin | |
| 2 | Are there any active A/B tests or personalization scripts tied to specific CSS class names in the current theme? | Justin | |
| 3 | `resources/assets/styles/lib/` — any third-party CSS not covered by vendor JS? | Vires (check in Stage 2) | |

---

## Gate 1 Checklist

- [x] Pain points documented — clear rationale for refactor
- [x] Source stack fully identified (Sage 9, Blade, Webpack 3, Composer, node-sass)
- [x] Target stack agreed — plain PHP, flat CSS, no bundler, no Composer
- [x] Non-negotiables list complete
- [x] Out of scope clearly defined

**→ Type `A` to approve Gate 1 and proceed to Stage 2: Source Audit**
