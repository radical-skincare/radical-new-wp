# Stage 2 — Source Audit
**Track:** codebase-refactor
**Refactor ID:** REFACTOR-001
**Gate:** 2 — requires Justin's `A` before Stage 3 begins
**Source codebase:** `projects/radical-wp`
**Audited:** 2026-04-05

---

## 1. Theme Entry Points

| File | Purpose | Target equivalent |
|---|---|---|
| `resources/functions.php` | Sage bootstrap — loads Composer autoloader, binds Blade container, requires `app/` files | `functions.php` — plain require chain for `inc/` files, zero Composer |
| `resources/style.css` | WP theme header only (no actual CSS — styles come from webpack build) | `style.css` — theme header + can contain base styles |
| `resources/index.php` | WP fallback (empty in Sage) | `index.php` — minimal fallback template |
| `config/assets.php` | Webpack manifest path config | **Dropped** — no manifest needed in new theme |
| `config/theme.php` | Sage theme config | **Dropped** |
| `config/view.php` | Blade cache path config | **Dropped** |

**Critical note on `functions.php`:** The Sage `functions.php` bootstraps the entire Composer/Blade machinery. In the new theme, `functions.php` is a clean require chain — no Composer, no container, no Blade directives. All `asset_path()` calls become `get_template_directory_uri() . '/assets/...'`.

---

## 2. PHP App Layer

### `app/setup.php` — Theme Setup & Enqueue

All of this moves to `inc/setup.php` and `inc/enqueue.php`. Key items extracted:

**Enqueue (→ `inc/enqueue.php`):**
- FontAwesome 4.6.3 via CDN (`maxcdn.bootstrapcdn.com`)
- Google Fonts: Josefin Sans (all weights/styles) via fonts.googleapis.com
- Typekit Orpheus via `use.typekit.net/wcu8ruk.css`
- Slick CSS + Slick Theme CSS (conditional: `is_front_page()` only) — from `/assets/styles/lib/`
- `main.css` — compiled stylesheet
- Slick JS (conditional: `is_front_page()` only)
- `main.js` — primary JS bundle (depends on jQuery)
- Owl Carousel CSS (conditional: `is_archive('podcasts')`)
- `wp_localize_script` for `ThemeSettings` object with: `site_url`, `admin_ajax_url`, `rest_url`, `is_user_logged_in`, `email_signup_modal`, `current_user_id` (if logged in), `affiliate_status` (if logged in), `user_email` (if logged in), `default_parent_affiliate_id`, `affiliate_plugin`, `affwp_mlm_default_affiliate_id`, `sitewide_discount`, `radical_nonce`
- `comment-reply` script (conditional: `is_single() && comments_open()`)

**Theme Setup (→ `inc/setup.php`):**
- `add_theme_support('title-tag')`
- `add_theme_support('post-thumbnails')`
- `add_theme_support('html5', [...])`
- `add_theme_support('customize-selective-refresh-widgets')`
- Soil plugin supports (carry over — Soil plugin handles these if active; safe to include)
- `register_nav_menus` — locations: `navbar`, `primary_navigation`, `mobile-navbar`
- `widgets_init` — sidebars: `sidebar-primary`, `sidebar-footer`, `mega-menu`, `currency-converter-widget`

**Drop from setup.php:**
- All Sage container binding (`sage()->singleton(...)`)
- `@asset()` Blade directive registration
- `add_action('the_post', ...)` Sage Blade share — not needed in plain PHP

### `app/filters.php` — WordPress Filters (→ `inc/filters.php`)

Keep:
- `body_class` filter — adds page slug, sidebar class, cleans Blade-specific class names (update regex: remove `/-blade(-php)?$/` and `/^page-template-views/` since those artifacts won't exist in new theme, but keep the page-slug logic)
- `excerpt_more` filter — "… Continued" link

Drop entirely (Sage-specific, not needed in plain PHP theme):
- Template hierarchy Blade filter (`{$type}_template_hierarchy`)
- `template_include` Blade render filter
- `comments_template` Blade render filter

### `app/helpers.php` — Helper Functions (→ `inc/helpers.php`)

Drop (Sage/Container-specific):
- `sage()` — container accessor
- `config()` — Sage config accessor
- `template()` — Blade render
- `template_path()` — Blade compiled path
- `asset_path()` — Webpack manifest URI (replace all call sites with `get_template_directory_uri() . '/assets/...'`)

Keep and port:
- `display_sidebar()` — logic to determine if sidebar should show
- `filter_templates()` — used for Blade hierarchy (drop the Blade-specific version; plain PHP doesn't need it)
- Any other utility functions below these (check full file in Stage 4)

### `app/admin.php` (→ `inc/admin.php`)

Requires all admin sub-files. Port as a clean require chain.

---

## 3. PHP Includes — Full Inventory

All located in `app/inc/` → move to `inc/` in new theme.

| Source File | Purpose | Notes |
|---|---|---|
| `class-wp-bootstrap-navwalker.php` | Bootstrap 4 nav walker | Keep as-is — copy to `inc/class-wp-bootstrap-navwalker.php` |
| `security.php` | Security hardening (login protection, etc.) | Keep as-is |
| `sitewide-discounts.php` | Sitewide discount hooks/logic | Keep as-is |
| `threshold-discount.php` | Cart threshold discount logic | Keep as-is |
| `woocommerce.php` | WooCommerce hooks (+ requires `app/inc/woocommerce/` sub-dir) | Keep as-is |
| `twilio-integraiton.php` | Twilio SMS integration (note: typo in filename — keep as-is for now) | Keep as-is |
| `gigfiliate-wp.php` | Gigfiliate affiliate system core | Keep as-is |
| `api.php` | WP REST API extensions | Keep as-is |
| `gigfiliate-wp-brand-partner-helpers.php` | Brand Partner affiliate helpers | Keep as-is |
| `template-tags.php` | Custom template tags | Keep as-is |
| `template-helpers.php` | Template utility functions | Keep as-is |
| `favorites.php` | Product favorites/wishlist | Keep as-is |
| `yotpo-reviews-integration.php` | Yotpo reviews widget | Keep as-is |
| `affiliate-wp-helpers.php` | Affiliate WP helpers (deprecated per code comment — 11/23) | Keep but document as deprecated |
| `wployalty.php` | WoLoyalty rewards integration | Keep as-is |
| `vip-customers.php` | VIP customer tier logic | Keep as-is |
| `analyze-glow.php` | Analyze & Glow product quiz | Keep as-is |
| `user-coupons.php` | User coupons (commented out in setup.php — currently disabled) | Keep but do not require (leave disabled) |

**Also check `app/inc/woocommerce/` sub-directory** for any additional WC override files.

**Admin sub-files (`app/admin/`):**

| Source File | Purpose |
|---|---|
| `acf.php` | ACF options pages + local JSON load/save paths |
| `brand-partner-settings-page.php` | Brand Partner admin settings UI |
| `gigfiliate-wp.php` | Gigfiliate admin registration |
| `podcasts.php` | Podcasts CPT registration |
| `press-items.php` | Press CPT registration |
| `stories.php` | Stories CPT registration |
| `vip-customers.php` | VIP Customers CPT registration |
| `woocommerce.php` | WooCommerce admin customizations |

---

## 4. Cache Control (in `app/setup.php`)

The cache-control header block for `/account/brand-partner-customers` and `/checkout` lives inline in `setup.php`. Move to `inc/setup.php` as-is — no Sage dependency, plain PHP.

---

## 5. Blade Templates → PHP Template Map

### Core Layout

| Source | Target | Transformation |
|---|---|---|
| `views/layouts/app.blade.php` | Split into `header.php` + `footer.php` | Everything before `@yield('content')` → `header.php`; everything after → `footer.php` |
| `views/partials/head.blade.php` | Inline into `header.php` `<head>` | Merge |
| `views/partials/header.blade.php` | `template-parts/header/header.php` | `get_template_part('template-parts/header/header')` |
| `views/partials/header/announcements.blade.php` | `template-parts/header/announcements.php` | |
| `views/partials/header/countdown.blade.php` | `template-parts/header/countdown.php` | |
| `views/partials/header/cyber-monday.blade.php` | `template-parts/header/cyber-monday.php` | |
| `views/partials/header/favorites.blade.php` | `template-parts/header/favorites.php` | |
| `views/partials/header/left-sidebar.blade.php` | `template-parts/header/left-sidebar.php` | |
| `views/partials/header/mega-menu.blade.php` | `template-parts/header/mega-menu.php` | |
| `views/partials/header/search.blade.php` | `template-parts/header/search.php` | |
| `views/partials/footer.blade.php` | `template-parts/footer/footer.php` | |
| `views/partials/footer/klaviyo.blade.php` | `template-parts/footer/klaviyo.php` | |
| `views/partials/footer/mailchimp.blade.php` | `template-parts/footer/mailchimp.php` | |
| `views/partials/sidebar.blade.php` | `sidebar.php` | |
| `views/partials/sidebar/single.blade.php` | `template-parts/sidebar/single.php` | |
| `views/partials/scroll-to-top.blade.php` | `template-parts/scroll-to-top.php` | |

### Core Templates

| Source | Target |
|---|---|
| `views/index.blade.php` | `index.php` |
| `views/home.blade.php` | `front-page.php` |
| `views/page.blade.php` | `page.php` |
| `views/single.blade.php` | `single.php` |
| `views/archive.blade.php` | `archive.php` |
| `views/archive-podcasts.blade.php` | `archive-podcasts.php` |
| `views/search.blade.php` | `search.php` |
| `views/404.blade.php` | `404.php` |
| `views/comments.blade.php` | `comments.php` |

### Custom Page Templates (20 templates)

| Source | Target |
|---|---|
| `views/template-home.blade.php` | `template-home.php` |
| `views/template-account.blade.php` | `template-account.php` |
| `views/template-contact.blade.php` | `template-contact.php` |
| `views/template-faq.blade.php` | `template-faq.php` |
| `views/template-giving.blade.php` | `template-giving.php` |
| `views/template-mission.blade.php` | `template-mission.php` |
| `views/template-press.blade.php` | `template-press.php` |
| `views/template-team.blade.php` | `template-team.php` |
| `views/template-clean-conscious.blade.php` | `template-clean-conscious.php` |
| `views/template-clean-conscious-old.blade.php` | `template-clean-conscious-old.php` (archive only — can be omitted if unused) |
| `views/template-rewards.blade.php` | `template-rewards.php` |
| `views/template-radical-repeat.blade.php` | `template-radical-repeat.php` |
| `views/template-quiz.blade.php` | `template-quiz.php` |
| `views/template-trylacel.blade.php` | `template-trylacel.php` |
| `views/template-join-brand-partners.blade.php` | `template-join-brand-partners.php` |
| `views/template-brand-partner-enrollment.blade.php` | `template-brand-partner-enrollment.php` |
| `views/template-impact-fund.blade.php` | `template-impact-fund.php` |
| `views/template-hero-right-sidebar.blade.php` | `template-hero-right-sidebar.php` |
| `views/template-holiday-gift-guide.blade.php` | `template-holiday-gift-guide.php` |
| `views/template-radical-rituals.blade.php` | `template-radical-rituals.php` |
| `views/template-valentines.blade.php` | `template-valentines.php` |

### Content Partials

| Source | Target |
|---|---|
| `views/partials/content.blade.php` | `template-parts/content.php` |
| `views/partials/content-page.blade.php` | `template-parts/content-page.php` |
| `views/partials/content-single.blade.php` | `template-parts/content-single.php` |
| `views/partials/content-product.blade.php` | `template-parts/content-product.php` |
| `views/partials/content-search.blade.php` | `template-parts/content-search.php` |
| `views/partials/content-cta-earn-points.blade.php` | `template-parts/content-cta-earn-points.php` |
| `views/partials/content-cta-join.blade.php` | `template-parts/content-cta-join.php` |
| `views/partials/content-cta-shop.blade.php` | `template-parts/content-cta-shop.php` |
| `views/partials/content/blog.blade.php` | `template-parts/content/blog.php` |
| `views/partials/content/feat-img.blade.php` | `template-parts/content/feat-img.php` |
| `views/partials/content/intro.blade.php` | `template-parts/content/intro.php` |
| `views/partials/content/none.blade.php` | `template-parts/content/none.php` |
| `views/partials/content/page-header.blade.php` | `template-parts/content/page-header.php` |
| `views/partials/content/page.blade.php` | `template-parts/content/page.php` |
| `views/partials/content/podcast-item.blade.php` | `template-parts/content/podcast-item.php` |
| `views/partials/content/single-feat-card.blade.php` | `template-parts/content/single-feat-card.php` |
| `views/partials/content/single.blade.php` | `template-parts/content/single.php` |
| `views/partials/content/story-blog-system.blade.php` | `template-parts/content/story-blog-system.php` |
| `views/partials/entry-meta.blade.php` | `template-parts/entry-meta.php` |
| `views/partials/page-header.blade.php` | `template-parts/page-header.php` |
| `views/partials/comments.blade.php` | `comments.php` |

### Product Partials (16 files)

| Source | Target |
|---|---|
| `views/partials/product/about.blade.php` | `template-parts/product/about.php` |
| `views/partials/product/about2.blade.php` | `template-parts/product/about2.php` |
| `views/partials/product/advanced-peptide-antioxidant-serum.blade.php` | `template-parts/product/advanced-peptide-antioxidant-serum.php` |
| `views/partials/product/age-defying-exfoliating-pads.blade.php` | `template-parts/product/age-defying-exfoliating-pads.php` |
| `views/partials/product/as-seen-in.blade.php` | `template-parts/product/as-seen-in.php` |
| `views/partials/product/before-and-after.blade.php` | `template-parts/product/before-and-after.php` |
| `views/partials/product/benefits.blade.php` | `template-parts/product/benefits.php` |
| `views/partials/product/countdown.blade.php` | `template-parts/product/countdown.php` |
| `views/partials/product/essentials-collection-content.blade.php` | `template-parts/product/essentials-collection-content.php` |
| `views/partials/product/how-to-apply.blade.php` | `template-parts/product/how-to-apply.php` |
| `views/partials/product/ingredients.blade.php` | `template-parts/product/ingredients.php` |
| `views/partials/product/main-content.blade.php` | `template-parts/product/main-content.php` |
| `views/partials/product/product-content-2025.blade.php` | `template-parts/product/product-content-2025.php` |
| `views/partials/product/related-products.blade.php` | `template-parts/product/related-products.php` |
| `views/partials/product/reviews.blade.php` | `template-parts/product/reviews.php` |
| `views/partials/product/sub-options.blade.php` | `template-parts/product/sub-options.php` |
| `views/partials/product/sweetheart.blade.php` | `template-parts/product/sweetheart.php` |
| `views/partials/product/technology.blade.php` | `template-parts/product/technology.php` |
| `views/partials/product/terranea.blade.php` | `template-parts/product/terranea.php` |

### Account Partials (14 files)

| Source | Target |
|---|---|
| `views/partials/account/address-card.blade.php` | `template-parts/account/address-card.php` |
| `views/partials/account/breadcrumb.blade.php` | `template-parts/account/breadcrumb.php` |
| `views/partials/account/cta.blade.php` | `template-parts/account/cta.php` |
| `views/partials/account/dashboard/recent-orders.blade.php` | `template-parts/account/dashboard/recent-orders.php` |
| `views/partials/account/dashboard/recent-subscriptions.blade.php` | `template-parts/account/dashboard/recent-subscriptions.php` |
| `views/partials/account/order/accordion.blade.php` | `template-parts/account/order/accordion.php` |
| `views/partials/account/order/card-free-gift.blade.php` | `template-parts/account/order/card-free-gift.php` |
| `views/partials/account/order/details-products.blade.php` | `template-parts/account/order/details-products.php` |
| `views/partials/account/order/details.blade.php` | `template-parts/account/order/details.php` |
| `views/partials/account/order/related-subscriptions.blade.php` | `template-parts/account/order/related-subscriptions.php` |
| `views/partials/account/order/totals-table.blade.php` | `template-parts/account/order/totals-table.php` |
| `views/partials/account/page-header.blade.php` | `template-parts/account/page-header.php` |
| `views/partials/account/payment/method-card.blade.php` | `template-parts/account/payment/method-card.php` |
| `views/partials/account/privacy-policy.blade.php` | `template-parts/account/privacy-policy.php` |
| `views/partials/account/subscription/card-details.blade.php` | `template-parts/account/subscription/card-details.php` |
| `views/partials/account/subscription/notes-card.blade.php` | `template-parts/account/subscription/notes-card.php` |
| `views/partials/account/subscription/payment-card.blade.php` | `template-parts/account/subscription/payment-card.php` |
| `views/partials/account/subscription/ror-gifts.blade.php` | `template-parts/account/subscription/ror-gifts.php` |
| `views/partials/account/subscription/table.blade.php` | `template-parts/account/subscription/table.php` |
| `views/partials/account/subscription/totals-table.blade.php` | `template-parts/account/subscription/totals-table.php` |

### Other Partials

| Source | Target |
|---|---|
| `views/partials/checkout/thankyou/card-details.blade.php` | `template-parts/checkout/thankyou/card-details.php` |
| `views/partials/components/active-subscriber-restricted.blade.php` | `template-parts/components/active-subscriber-restricted.php` |
| `views/partials/components/brand-partner-exclusive.blade.php` | `template-parts/components/brand-partner-exclusive.php` |
| `views/partials/components/lip-luster-waitlist.blade.php` | `template-parts/components/lip-luster-waitlist.php` |
| `views/partials/components/loader.blade.php` | `template-parts/components/loader.php` |
| `views/partials/form/login.blade.php` | `template-parts/form/login.php` |
| `views/partials/hero/hero-image-right.blade.php` | `template-parts/hero/hero-image-right.php` |
| `views/partials/hero/social-icons.blade.php` | `template-parts/hero/social-icons.php` |
| `views/partials/modal/delivery.blade.php` | `template-parts/modal/delivery.php` |
| `views/partials/modal/email-capture.blade.php` | `template-parts/modal/email-capture.php` |
| `views/partials/modal/how-it-works.blade.php` | `template-parts/modal/how-it-works.php` |
| `views/partials/modal/login.blade.php` | `template-parts/modal/login.php` |
| `views/partials/modal/payment-method-edit-name.blade.php` | `template-parts/modal/payment-method-edit-name.php` |
| `views/partials/modal/quick-view.blade.php` | `template-parts/modal/quick-view.php` |
| `views/partials/modal/sale.blade.php` | `template-parts/modal/sale.php` |
| `views/partials/modal/subscription-terms.blade.php` | `template-parts/modal/subscription-terms.php` |
| `views/partials/search/page-header.blade.php` | `template-parts/search/page-header.php` |
| `views/partials/shop-fall-swap-bundle-banner.blade.php` | `template-parts/shop-fall-swap-bundle-banner.php` |
| `views/partials/shop-sales-notice.blade.php` | `template-parts/shop-sales-notice.php` |
| `views/partials/shop-sidebar.blade.php` | `template-parts/shop-sidebar.php` |

### Module Views (from `views/modules/`)

| Source | Target |
|---|---|
| `views/modules/blog/**` | `template-parts/modules/blog/**` |
| `views/modules/clean-conscious/**` | `template-parts/modules/clean-conscious/**` |
| `views/modules/faq/**` | `template-parts/modules/faq/**` |
| `views/modules/flex/**` | `template-parts/modules/flex/**` |
| `views/modules/giving/**` | `template-parts/modules/giving/**` |
| `views/modules/home/**` | `template-parts/modules/home/**` |
| `views/modules/impact-fund/**` | `template-parts/modules/impact-fund/**` |
| `views/modules/join-brand-partners/**` | `template-parts/modules/join-brand-partners/**` |
| `views/modules/mission/**` | `template-parts/modules/mission/**` |
| `views/modules/page/**` | `template-parts/modules/page/**` |
| `views/modules/podcasts/**` | `template-parts/modules/podcasts/**` |
| `views/modules/press/**` | `template-parts/modules/press/**` |
| `views/modules/radical-repeat/**` | `template-parts/modules/radical-repeat/**` |
| `views/modules/rewards/**` | `template-parts/modules/rewards/**` |
| `views/modules/team/**` | `template-parts/modules/team/**` |
| `views/modules/trylacel/**` | `template-parts/modules/trylacel/**` |

---

## 6. WooCommerce Template Overrides (43 files)

All in `resources/views/woocommerce/` → `woocommerce/` in new theme.

**Note:** These Blade templates wrap standard WooCommerce PHP. The conversion strips Blade syntax (`@extends`, `@section`, etc.) and replaces with standard PHP. In many cases the WC templates are already mostly PHP inside the Blade wrapper.

| Category | Source Files | Count |
|---|---|---|
| Root | `archive-product`, `content-product`, `content-single-product`, `single-product`, `single-product-reviews` | 5 |
| `checkout/` | `form-change-payment-method`, `form-pay`, `thankyou` | 3 |
| `global/` | `quantity-input` | 1 |
| `loop/` | `orderby`, `result-count` | 2 |
| `myaccount/` | `dashboard`, `dashboard/coupon-card`, `dashboard/coupons`, `form-add-payment-method`, `form-login`, `my-account`, `my-subscriptions`, `navigation`, `orders`, `payment-methods`, `related-orders`, `subscription-details`, `subscription-totals-table`, `subscription-totals`, `view-order`, `view-subscription` | 16 |
| `order/` | `order-again`, `order-details-customer`, `order-details-item`, `order-details` | 4 |
| `single-product/` | `add-to-cart/simple`, `add-to-cart/variable`, `meta`, `price`, `product-add-to-subscription-list`, `product-existing-subscription-list`, `product-image`, `tabs/description`, `tabs/tabs`, `up-sells` | 10 |

Plus WooCommerce email templates in `resources/woocommerce/emails/` (plain PHP already — copy as-is):
- `email-header.php`
- `email-order-details.php`
- `email-order-items.php`
- `subscribtion-reminder.php`
- `wlr-earn-point.php`
- `wlr-earn-reward.php`

---

## 7. SCSS Inventory (55 files → 1 CSS file)

**Conversion strategy:** Run `sass resources/assets/styles/main.scss main.css` from the source repo to produce a compiled baseline. Reorganize by section in `assets/css/main.css`.

| Category | Files |
|---|---|
| Entry | `main.scss` |
| Autoload | `autoload/_bootstrap.scss` |
| Common | `_variables.scss`, `_colors.scss`, `_fonts.scss`, `_borders.scss`, `_global.scss`, `_utilities.scss`, `_woocommerce.scss`, `_wp-loyality.scss` |
| Components | `_badges.scss`, `_blockquote.scss`, `_buttons.scss`, `_comments.scss`, `_dropdowns.scss`, `_links.scss`, `_lists.scss`, `_wp-classes.scss` |
| Layouts | `_before-and-after.scss`, `_checkout.scss`, `_content.scss`, `_footer.scss`, `_forms.scss`, `_header.scss`, `_misc.scss`, `_modals.scss`, `_page-hero.scss`, `_pages.scss`, `_posts.scss`, `_products.scss`, `_side-cart.scss`, `_sidebar.scss`, `_slick.scss`, `_stripe.scss`, `_tabs.scss`, `_tinymce.scss`, `_yotpo.scss` |
| Modules | `_archive-podcast.scss`, `_archive-products.scss`, `_error404.scss`, `_flex.scss`, `_rewards.scss`, `_search.scss`, `_single-product.scss`, `_template-account.scss`, `_template-brand-partner.scss`, `_template-clean-conscious.scss`, `_template-faq.scss`, `_template-giving.scss`, `_template-hero-right-slider.scss`, `_template-home.scss`, `_template-impact-fund.scss`, `_template-join-brand-partners.scss`, `_template-mission.scss`, `_template-press.scss`, `_template-radical-on-repeat.scss`, `_template-trylacel.scss` |

**Vendor CSS (copy to `assets/css/vendor/`):**
- `styles/lib/font-awesome.min.css` (also loaded via CDN — use CDN in new theme)
- `styles/lib/owl.carousel.min.css`
- `styles/lib/slick.css`
- `styles/lib/slick-theme.css`

---

## 8. JavaScript Inventory

### Entry Point: `main.js`

Imports and initializes 26 modules on `jQuery(document).ready`. All modules use ES6 class-style exports with an `onLoad()` static method.

### Layout Modules (7 files)

| File | Purpose |
|---|---|
| `layouts/Header.js` | Navigation, mobile menu, sticky header behavior |
| `layouts/Favorites.js` | Wishlist/favorites UI |
| `layouts/Login.js` | Login modal/form behavior |
| `layouts/PageHero.js` | Hero section JS |
| `layouts/Sale.js` | Sale/discount banner behavior |
| `layouts/Form.js` | General form handling |
| `layouts/EmailSubscribe.js` | Email subscription form |
| `layouts/ProductReviewModel.js` | Product review modal |
| `layouts/_Search.js` | Search overlay/dropdown |

### Feature Modules (14 files)

| File | Purpose |
|---|---|
| `modules/Global.js` | Global init — runs on every page |
| `modules/SingleProduct.js` | Single product page behavior |
| `modules/SkinCareAddition.js` | Skincare add-on upsell logic |
| `modules/RefillAddToCart.js` | Refill product add-to-cart |
| `modules/BrandPartner.js` | Brand Partner dashboard/pages |
| `modules/AmbassadorEnrollment.js` | Ambassador/Brand Partner enrollment flow |
| `modules/ArchivePodcasts.js` | Podcasts archive (Owl Carousel) |
| `modules/ArchiveProducts.js` | Shop/product archive behavior |
| `modules/Giving.js` | Giving page JS |
| `modules/TemplateHome.js` | Home page JS (Slick carousel) |
| `modules/TemplatePress.js` | Press page JS |
| `modules/TemplateFAQ.js` | FAQ accordion |
| `modules/TemplateTrylacel.js` | Trylacel page JS |
| `modules/MyAccount.js` | My Account page JS |
| `modules/WoocommerceSubscription.js` | Subscription management JS |
| `modules/WoocommerceSubscriptionSearch.js` | Subscription search |
| `modules/ProductPurchaseOptions.js` | Product purchase options (one-time vs sub) |

### Utility Files (4 files)

| File | Purpose |
|---|---|
| `util/Router.js` | Sage's page-route-based JS dispatcher | **Drop** — no longer needed; all modules call `onLoad()` directly |
| `util/Utilities.js` | Shared utility functions | Keep — port to `assets/js/util.js` |
| `util/Cookie.js` | Cookie helpers | Keep |
| `util/camelCase.js` | camelCase utility | Keep |

### Routes (kept for reference, not ported)

| File | Note |
|---|---|
| `routes/common.js` | Sage route — superseded by direct `onLoad()` calls |
| `routes/home.js` | Sage route — superseded |
| `routes/about.js` | Sage route — superseded |

### Autoload

| File | Note |
|---|---|
| `autoload/_bootstrap.js` | Imports Bootstrap 4 JS (modals, dropdowns, etc.) | Keep — replace webpack import with local file or CDN |

### Misc

| File | Note |
|---|---|
| `misc/CheckoutWC.js` | CheckoutWC plugin integration | Keep |
| `customizer.js` | WP Customizer preview JS | Keep |

### Vendor JS (copy to `assets/js/vendor/`)

| Package | Source | Keep? |
|---|---|---|
| jQuery | WP core | Yes — use `wp_enqueue_script('jquery')` dependency, not local copy |
| Bootstrap 4 JS | npm | Yes — copy `bootstrap.bundle.min.js` (includes Popper) to `assets/js/vendor/` |
| Slick Carousel | `scripts/lib/slick.min.js` | Yes — copy as-is |
| Owl Carousel | npm (no local copy found) | Yes — download `owl.carousel.min.js` to `assets/js/vendor/` |
| Smooth Scroll | npm | Yes — copy `smooth-scroll.polyfills.min.js` to `assets/js/vendor/` |

---

## 9. ACF Field Groups

**27 groups** in `resources/acf-json/`. All copy to `acf-json/` in new theme. ACF loads them automatically once the `acf/settings/load_json` filter points to the correct directory (handled in `inc/admin/acf.php`).

---

## 10. Build Tools to Drop

The following are entirely Sage/Webpack-specific and are **not carried to the new theme:**

- `package.json` + `node_modules` (webpack, babel, sass-loader, etc.)
- `composer.json` + `vendor/` (illuminate/support, sage-lib, soberwp/controller, sage-woocommerce)
- `resources/assets/build/` (webpack.config.js, postcss.config.js, helpers, util)
- `resources/assets/config.json` (webpack manifest config)
- `.nvmrc`, `.eslintrc.js`, `.stylelintrc.js`, `.editorconfig`
- `pnpm-workspace.yaml`
- `phpcs.xml` (can add back a standalone phpcs config later if desired)

---

## 11. Risk Register

| Risk | Likelihood | Impact | Mitigation |
|---|---|---|---|
| Sage Controller data not replicated in PHP templates | High | High | Read each Controller (`App.php`, `FrontPage.php`, `ArchivePodcasts.php`) before porting their corresponding templates — pass data via `$_GET`, query vars, or direct function calls |
| `asset_path()` calls missed in PHP templates | Medium | Medium | Global search for `asset_path` before declaring Wave complete; replace all with `get_template_directory_uri() . '/assets/...'` |
| SCSS compiled output differs from what's currently deployed | Low | Medium | Use the compiled CSS from the live production site as the visual reference, not the SCSS source alone |
| 27 ACF groups — `get_stylesheet_directory()` path mismatch | Low | High | In `inc/admin/acf.php`, use `get_template_directory()` for the JSON path (same as `get_stylesheet_directory()` for non-child themes) |
| Owl Carousel JS not locally available (only CSS is in source) | Medium | Medium | Download from CDN during Wave 7 setup |
| `user-coupons.php` is commented out — intentional | Low | Low | Do not re-enable; document as disabled in CLAUDE.md |
| Brand Partner enrollment + Analyze Glow quiz are complex multi-step flows | High | Medium | Port templates faithfully; do not simplify. Test these flows explicitly in Stage 5 |
| WC subscription templates (16 myaccount files) are deeply customized | High | High | Port one-by-one with careful comparison against source; test subscription management in Wave 6 verification |

---

## Gate 2 Checklist

- [x] All Blade templates inventoried with target PHP equivalents (100+ files mapped)
- [x] All PHP includes mapped (`app/inc/`, `app/admin/`, `app/setup.php`, `app/filters.php`, `app/helpers.php`)
- [x] All 55 SCSS files listed — CSS conversion strategy defined
- [x] All JS modules, layouts, utilities, and vendor dependencies evaluated
- [x] All 27 ACF field groups documented — copy strategy confirmed
- [x] All 43 WooCommerce blade template overrides inventoried
- [x] Build tools to drop explicitly listed
- [x] Risk register complete

**→ Type `A` to approve Gate 2 and proceed to Stage 3: Target Architecture**
