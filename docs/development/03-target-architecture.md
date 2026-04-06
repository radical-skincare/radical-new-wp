# Stage 3 — Target Architecture
**Track:** codebase-refactor
**Refactor ID:** REFACTOR-001
**Gate:** 3 — requires Justin's `A` before Stage 4 begins
**Target codebase:** `projects/radical-new-wp`
**Date:** 2026-04-05

---

## Controller Resolution (Risk Cleared)

The three Sage Controllers are resolved cleanly:

- **`App.php`** — provides `siteName()` (`get_bloginfo('name')`) and `title()` (conditional title logic). Both are 1–2 line inline replacements in templates. No special porting needed.
- **`FrontPage.php`** — completely empty. Nothing to port.
- **`ArchivePodcasts.php`** — registers two AJAX handlers (`get_podcasts`, `get_podcast`) and one static helper `getPodcasts()`. Move these directly into `inc/admin/podcasts.php` alongside the CPT registration. The `__construct()` AJAX registration becomes a standard `add_action` call at the bottom of that file. No Sage dependency — pure WP + WP_Query.

**Net result: zero Controller data-passing to worry about. All data access is standard WordPress functions in templates.**

---

## Target Directory Structure

```
radical-new-wp/
│
├── style.css                           # WP theme header (Name, Description, Version, Author, Tags)
├── index.php                           # WP fallback — get_header() + loop + get_footer()
├── functions.php                       # Require chain only — no logic
│
├── 404.php
├── archive.php
├── archive-podcasts.php
├── comments.php
├── front-page.php
├── header.php                          # wp_head(), site header, nav, announcements bar
├── footer.php                          # site footer HTML, wp_footer()
├── home.php
├── page.php
├── search.php
├── sidebar.php
├── single.php
│
├── template-account.php
├── template-brand-partner-enrollment.php
├── template-clean-conscious.php
├── template-clean-conscious-old.php    # Keep — may be assigned to a page
├── template-contact.php
├── template-faq.php
├── template-giving.php
├── template-hero-right-sidebar.php
├── template-holiday-gift-guide.php
├── template-home.php
├── template-impact-fund.php
├── template-join-brand-partners.php
├── template-mission.php
├── template-press.php
├── template-quiz.php
├── template-radical-repeat.php
├── template-radical-rituals.php
├── template-rewards.php
├── template-team.php
├── template-trylacel.php
├── template-valentines.php
│
├── woocommerce/
│   ├── archive-product.php
│   ├── content-product.php
│   ├── content-single-product.php
│   ├── single-product.php
│   ├── single-product-reviews.php
│   ├── checkout/
│   │   ├── form-change-payment-method.php
│   │   ├── form-pay.php
│   │   └── thankyou.php
│   ├── global/
│   │   └── quantity-input.php
│   ├── loop/
│   │   ├── orderby.php
│   │   └── result-count.php
│   ├── myaccount/
│   │   ├── dashboard.php
│   │   ├── dashboard/
│   │   │   ├── coupon-card.php
│   │   │   └── coupons.php
│   │   ├── form-add-payment-method.php
│   │   ├── form-login.php
│   │   ├── my-account.php
│   │   ├── my-subscriptions.php
│   │   ├── navigation.php
│   │   ├── orders.php
│   │   ├── payment-methods.php
│   │   ├── related-orders.php
│   │   ├── subscription-details.php
│   │   ├── subscription-totals-table.php
│   │   ├── subscription-totals.php
│   │   ├── view-order.php
│   │   └── view-subscription.php
│   ├── order/
│   │   ├── order-again.php
│   │   ├── order-details-customer.php
│   │   ├── order-details-item.php
│   │   └── order-details.php
│   └── single-product/
│       ├── add-to-cart/
│       │   ├── simple.php
│       │   └── variable.php
│       ├── meta.php
│       ├── price.php
│       ├── product-add-to-subscription-list.php
│       ├── product-existing-subscription-list.php
│       ├── product-image.php
│       ├── tabs/
│       │   ├── description.php
│       │   └── tabs.php
│       └── up-sells.php
│
├── template-parts/
│   ├── scroll-to-top.php
│   ├── page-header.php
│   ├── entry-meta.php
│   ├── shop-fall-swap-bundle-banner.php
│   ├── shop-sales-notice.php
│   ├── shop-sidebar.php
│   ├── content.php
│   ├── content-page.php
│   ├── content-single.php
│   ├── content-product.php
│   ├── content-search.php
│   ├── content-cta-earn-points.php
│   ├── content-cta-join.php
│   ├── content-cta-shop.php
│   ├── header/
│   │   ├── header.php                  # Main site header markup
│   │   ├── announcements.php
│   │   ├── countdown.php
│   │   ├── cyber-monday.php
│   │   ├── favorites.php
│   │   ├── left-sidebar.php
│   │   ├── mega-menu.php
│   │   └── search.php
│   ├── footer/
│   │   ├── footer.php                  # Main site footer markup
│   │   ├── klaviyo.php
│   │   └── mailchimp.php
│   ├── sidebar/
│   │   └── single.php
│   ├── content/
│   │   ├── blog.php
│   │   ├── feat-img.php
│   │   ├── intro.php
│   │   ├── none.php
│   │   ├── page-header.php
│   │   ├── page.php
│   │   ├── podcast-item.php
│   │   ├── single-feat-card.php
│   │   ├── single.php
│   │   └── story-blog-system.php
│   ├── product/
│   │   ├── about.php
│   │   ├── about2.php
│   │   ├── advanced-peptide-antioxidant-serum.php
│   │   ├── age-defying-exfoliating-pads.php
│   │   ├── as-seen-in.php
│   │   ├── before-and-after.php
│   │   ├── benefits.php
│   │   ├── countdown.php
│   │   ├── essentials-collection-content.php
│   │   ├── how-to-apply.php
│   │   ├── ingredients.php
│   │   ├── main-content.php
│   │   ├── product-content-2025.php
│   │   ├── related-products.php
│   │   ├── reviews.php
│   │   ├── sub-options.php
│   │   ├── sweetheart.php
│   │   ├── technology.php
│   │   └── terranea.php
│   ├── account/
│   │   ├── address-card.php
│   │   ├── breadcrumb.php
│   │   ├── cta.php
│   │   ├── page-header.php
│   │   ├── privacy-policy.php
│   │   ├── dashboard/
│   │   │   ├── recent-orders.php
│   │   │   └── recent-subscriptions.php
│   │   ├── order/
│   │   │   ├── accordion.php
│   │   │   ├── card-free-gift.php
│   │   │   ├── details.php
│   │   │   ├── details-products.php
│   │   │   ├── related-subscriptions.php
│   │   │   └── totals-table.php
│   │   ├── payment/
│   │   │   └── method-card.php
│   │   └── subscription/
│   │       ├── card-details.php
│   │       ├── notes-card.php
│   │       ├── payment-card.php
│   │       ├── ror-gifts.php
│   │       ├── table.php
│   │       └── totals-table.php
│   ├── checkout/
│   │   └── thankyou/
│   │       └── card-details.php
│   ├── components/
│   │   ├── active-subscriber-restricted.php
│   │   ├── brand-partner-exclusive.php
│   │   ├── lip-luster-waitlist.php
│   │   └── loader.php
│   ├── form/
│   │   └── login.php
│   ├── hero/
│   │   ├── hero-image-right.php
│   │   └── social-icons.php
│   ├── modal/
│   │   ├── delivery.php
│   │   ├── email-capture.php
│   │   ├── how-it-works.php
│   │   ├── login.php
│   │   ├── payment-method-edit-name.php
│   │   ├── quick-view.php
│   │   ├── sale.php
│   │   └── subscription-terms.php
│   ├── search/
│   │   └── page-header.php
│   └── modules/
│       ├── blog/
│       ├── clean-conscious/
│       ├── faq/
│       ├── flex/
│       ├── giving/
│       ├── home/
│       ├── impact-fund/
│       ├── join-brand-partners/
│       ├── mission/
│       ├── page/
│       ├── podcasts/
│       ├── press/
│       ├── radical-repeat/
│       ├── rewards/
│       ├── team/
│       └── trylacel/
│
├── inc/
│   ├── setup.php                       # add_theme_support, register_nav_menus, sidebars, image sizes, cache control
│   ├── enqueue.php                     # wp_enqueue_scripts — all CSS, JS, wp_localize_script
│   ├── filters.php                     # body_class, excerpt_more (Sage-specific filters dropped)
│   ├── helpers.php                     # display_sidebar() + any non-Sage utility functions
│   ├── class-wp-bootstrap-navwalker.php
│   ├── admin/
│   │   ├── acf.php                     # ACF options pages + JSON load/save paths
│   │   ├── brand-partner-settings-page.php
│   │   ├── gigfiliate-wp.php
│   │   ├── podcasts.php                # CPT + AJAX handlers moved from ArchivePodcasts.php Controller
│   │   ├── press-items.php
│   │   ├── stories.php
│   │   ├── vip-customers.php
│   │   └── woocommerce.php
│   └── integrations/
│       ├── woocommerce.php             # Main WC hooks (requires sub-files below)
│       ├── woocommerce/
│       │   ├── active-subscriber-discounts.php
│       │   ├── conditional-product-sale.php
│       │   ├── coupons.php
│       │   ├── payment-methods.php
│       │   ├── renewal-gift.php
│       │   ├── subscription-features.php
│       │   └── subscription-reminder-email.php
│       ├── affiliate-wp.php            # affiliate-wp-helpers.php (deprecated, keep)
│       ├── gigfiliate.php              # gigfiliate-wp.php + gigfiliate-wp-brand-partner-helpers.php
│       ├── wployalty.php
│       ├── yotpo.php
│       ├── twilio.php
│       ├── wc-subscriptions.php        # class-wc-subscription-email.php
│       ├── sitewide-discounts.php
│       ├── threshold-discount.php
│       ├── analyze-glow.php
│       ├── vip-customers.php
│       └── favorites.php
│
├── assets/
│   ├── css/
│   │   ├── main.css                    # All theme styles — compiled from SCSS, then reorganized
│   │   └── vendor/
│   │       ├── slick.css
│   │       ├── slick-theme.css
│   │       └── owl.carousel.min.css
│   ├── js/
│   │   ├── main.js                     # Primary JS — initializes all modules on DOM ready
│   │   ├── customizer.js
│   │   ├── modules/
│   │   │   ├── Global.js
│   │   │   ├── Header.js
│   │   │   ├── SingleProduct.js
│   │   │   ├── Search.js
│   │   │   ├── Favorites.js
│   │   │   ├── Login.js
│   │   │   ├── PageHero.js
│   │   │   ├── Sale.js
│   │   │   ├── SkinCareAddition.js
│   │   │   ├── RefillAddToCart.js
│   │   │   ├── BrandPartner.js
│   │   │   ├── AmbassadorEnrollment.js
│   │   │   ├── ArchivePodcasts.js
│   │   │   ├── ArchiveProducts.js
│   │   │   ├── Giving.js
│   │   │   ├── TemplateHome.js
│   │   │   ├── TemplatePress.js
│   │   │   ├── TemplateFAQ.js
│   │   │   ├── TemplateTrylacel.js
│   │   │   ├── MyAccount.js
│   │   │   ├── WoocommerceSubscription.js
│   │   │   ├── WoocommerceSubscriptionSearch.js
│   │   │   ├── Form.js
│   │   │   ├── EmailSubscribe.js
│   │   │   ├── ProductReviewModel.js
│   │   │   ├── ProductPurchaseOptions.js
│   │   │   └── CheckoutWC.js
│   │   └── vendor/
│   │       ├── slick.min.js            # From source scripts/lib/
│   │       ├── bootstrap.bundle.min.js # Bootstrap 4 + Popper bundled
│   │       ├── owl.carousel.min.js     # Download from CDN during Wave 7
│   │       └── smooth-scroll.min.js    # Download from CDN during Wave 7
│   ├── fonts/                          # Copied from resources/assets/fonts/
│   │   ├── star.eot
│   │   ├── star.svg
│   │   ├── star.ttf
│   │   └── star.woff
│   └── images/                         # Copied from resources/assets/images/
│
├── acf-json/                           # Copied from resources/acf-json/ (27 group files)
│
└── woocommerce/
    └── emails/                         # Copied from resources/woocommerce/emails/ (plain PHP — no conversion needed)
        ├── email-header.php
        ├── email-order-details.php
        ├── email-order-items.php
        ├── subscribtion-reminder.php
        ├── wlr-earn-point.php
        └── wlr-earn-reward.php
```

---

## `functions.php` — Exact Structure

```php
<?php
/**
 * Radical Skincare Theme Functions
 */

// Setup & enqueue
require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/filters.php';
require_once get_template_directory() . '/inc/helpers.php';

// Nav walker
require_once get_template_directory() . '/inc/class-wp-bootstrap-navwalker.php';

// Admin / CPTs / ACF
require_once get_template_directory() . '/inc/admin/acf.php';
require_once get_template_directory() . '/inc/admin/podcasts.php';
require_once get_template_directory() . '/inc/admin/press-items.php';
require_once get_template_directory() . '/inc/admin/stories.php';
require_once get_template_directory() . '/inc/admin/brand-partner-settings-page.php';
require_once get_template_directory() . '/inc/admin/vip-customers.php';
require_once get_template_directory() . '/inc/admin/gigfiliate-wp.php';
require_once get_template_directory() . '/inc/admin/woocommerce.php';

// Integrations
require_once get_template_directory() . '/inc/integrations/woocommerce.php';
require_once get_template_directory() . '/inc/integrations/sitewide-discounts.php';
require_once get_template_directory() . '/inc/integrations/threshold-discount.php';
require_once get_template_directory() . '/inc/integrations/gigfiliate.php';
require_once get_template_directory() . '/inc/integrations/api.php';
require_once get_template_directory() . '/inc/integrations/template-tags.php';
require_once get_template_directory() . '/inc/integrations/template-helpers.php';
require_once get_template_directory() . '/inc/integrations/favorites.php';
require_once get_template_directory() . '/inc/integrations/yotpo.php';
require_once get_template_directory() . '/inc/integrations/affiliate-wp.php';
require_once get_template_directory() . '/inc/integrations/wployalty.php';
require_once get_template_directory() . '/inc/integrations/vip-customers.php';
require_once get_template_directory() . '/inc/integrations/analyze-glow.php';
require_once get_template_directory() . '/inc/integrations/twilio.php';
require_once get_template_directory() . '/inc/integrations/wc-subscriptions.php';
// require_once get_template_directory() . '/inc/integrations/user-coupons.php'; // intentionally disabled
```

---

## `style.css` — Theme Header

```css
/*
Theme Name:  Radical Skincare
Theme URI:   https://radicalskincare.com
Description: Radical Skincare custom WordPress theme.
Author:      Vires
Version:     2.0.0
Tags:        woocommerce, custom
*/
```

---

## `inc/enqueue.php` — Full Enqueue Strategy

All `asset_path()` calls from the source are replaced with `get_template_directory_uri() . '/assets/...'`.

```php
<?php
add_action('wp_enqueue_scripts', function () {
    $uri = get_template_directory_uri();

    // ── External fonts ──────────────────────────────────────────────────────
    wp_enqueue_style('fontawesome',     'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');
    wp_enqueue_style('fonts-josefin',   'https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap');
    wp_enqueue_style('fonts-typekit',   'https://use.typekit.net/wcu8ruk.css');

    // ── Vendor CSS ───────────────────────────────────────────────────────────
    if (is_front_page()) {
        wp_enqueue_style('slick-css',   $uri . '/assets/css/vendor/slick.css');
        wp_enqueue_style('slick-theme', $uri . '/assets/css/vendor/slick-theme.css');
    }
    if (is_post_type_archive('podcasts') || is_post_type_archive('events')) {
        wp_enqueue_style('owl-carousel', $uri . '/assets/css/vendor/owl.carousel.min.css');
    }

    // ── Theme CSS ────────────────────────────────────────────────────────────
    wp_enqueue_style('radical/main', $uri . '/assets/css/main.css', [], '2.0.0');

    // ── Vendor JS ────────────────────────────────────────────────────────────
    if (is_front_page()) {
        wp_enqueue_script('slick-js', $uri . '/assets/js/vendor/slick.min.js', ['jquery'], null, true);
    }
    wp_enqueue_script('bootstrap-js', $uri . '/assets/js/vendor/bootstrap.bundle.min.js', ['jquery'], '4.3.1', true);

    // ── Theme JS ─────────────────────────────────────────────────────────────
    wp_enqueue_script('radical/main', $uri . '/assets/js/main.js', ['jquery', 'bootstrap-js'], '2.0.0', true);

    // ── Localize ─────────────────────────────────────────────────────────────
    $is_logged_in = is_user_logged_in();
    $localize = [
        'site_url'           => get_site_url(),
        'admin_ajax_url'     => admin_url('admin-ajax.php'),
        'rest_url'           => esc_url_raw(rest_url('/wp/v2')),
        'is_user_logged_in'  => $is_logged_in,
        'email_signup_modal' => get_field('email_signup_modal', 'option'),
        'sitewide_discount'  => get_field('sitewide_discount', 'option'),
        'radical_nonce'      => wp_create_nonce('radical_ajax_nonce'),
    ];
    $general_settings = json_decode(get_option('brand_partner_setings'));
    if ($is_logged_in) {
        $user_id = (int) get_current_user_id();
        $user    = get_user_by('ID', $user_id);
        $localize['current_user_id'] = $user_id;
        $localize['user_email']      = $user->user_email;
        if (!is_null($general_settings)) {
            if ($general_settings->affiliate_plugin === 'affiliate-wp') {
                $localize['affiliate_status'] = is_Brand_Partner_Active($user_id);
            } elseif ($general_settings->affiliate_plugin === 'gigfiliate') {
                $localize['affiliate_status'] = get_user_meta($user_id, 'v_affiliate_status', true);
            }
        }
        if (class_exists('affiliate_wp')) {
            $localize['affwp_mlm_default_affiliate_id'] = (int) affiliate_wp()->settings->get('affwp_mlm_default_affiliate', []);
        }
    }
    if (!is_null($general_settings)) {
        $localize['default_parent_affiliate_id'] = $general_settings->default_parent_affiliate_id ?? null;
        $localize['affiliate_plugin']            = $general_settings->affiliate_plugin ?? null;
    }
    wp_localize_script('radical/main', 'ThemeSettings', $localize);

    if (is_single() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}, 100);
```

---

## `inc/filters.php` — What Stays, What's Dropped

**Keep (updated):**
```php
// body_class — page slug + sidebar class
// Updated: remove Blade-artifact regex (/-blade(-php)?$/ and /^page-template-views/)
// Keep: page-slug addition + display_sidebar() class

// excerpt_more — "… Continued" link
```

**Drop entirely (Sage-specific, no plain-PHP equivalent needed):**
```php
// Template hierarchy Blade filter
// template_include Blade render
// comments_template Blade render
```

---

## `inc/setup.php` — What Moves Here

```php
// add_theme_support: title-tag, post-thumbnails, html5, customize-selective-refresh-widgets
// Soil plugin supports (safe to keep — Soil handles if active)
// register_nav_menus: 'navbar', 'primary_navigation', 'mobile-navbar'
// widgets_init: sidebar-primary, sidebar-footer, mega-menu, currency-converter-widget
// add_editor_style pointing to assets/css/main.css
// Cache-control header block for /account/brand-partner-customers and /checkout
```

---

## `inc/admin/podcasts.php` — Controller Logic Absorbed

The AJAX handlers from `ArchivePodcasts.php` Controller move here alongside the CPT registration:

```php
// CPT registration (from existing podcasts.php)
// getPodcasts() static method — becomes a standalone function radical_get_podcasts()
// wp_ajax_get_podcasts → calls radical_get_podcasts()
// wp_ajax_get_podcast  → inline query
// wp_ajax_nopriv versions of both
```

The `App::title()` and `App::siteName()` Controller methods are replaced inline in templates:
- `$title` from `App::title()` → use `wp_title()` or inline conditional with `get_the_archive_title()`, `get_search_query()`, `get_the_title()`, etc.
- `$siteName` → `get_bloginfo('name')` directly in template

---

## CSS Architecture

**Single file:** `assets/css/main.css`

**Generation strategy:**
1. Run `sass resources/assets/styles/main.scss compiled-baseline.css` in source repo (one-time)
2. Copy output to `assets/css/main.css` in new theme
3. Reorganize with section comments — no hand-conversion of SCSS nesting required

**Section order in `main.css`:**
```css
/* ── 1. CSS Custom Properties (design tokens) ─── */
/* ── 2. Base / Reset / Global ─────────────────── */
/* ── 3. Typography & Fonts ────────────────────── */
/* ── 4. Utilities ─────────────────────────────── */
/* ── 5. Layout ────────────────────────────────── */
/* ── 6. Header & Navigation ───────────────────── */
/* ── 7. Footer ────────────────────────────────── */
/* ── 8. Sidebar ───────────────────────────────── */
/* ── 9. Components (buttons, badges, forms) ───── */
/* ── 10. Modals ───────────────────────────────── */
/* ── 11. Shop / Archive Products ──────────────── */
/* ── 12. Single Product ───────────────────────── */
/* ── 13. Cart / Checkout ──────────────────────── */
/* ── 14. My Account / Subscriptions ───────────── */
/* ── 15. Page Templates ───────────────────────── */
/* ── 16. Modules (home, press, FAQ, etc.) ─────── */
/* ── 17. WooCommerce Overrides ────────────────── */
/* ── 18. WoLoyalty / Rewards ──────────────────── */
/* ── 19. Yotpo Reviews ────────────────────────── */
/* ── 20. TinyMCE Editor Styles ────────────────── */
/* ── 21. Responsive / Media Queries ───────────── */
```

---

## JS Architecture

**No bundler.** `assets/js/main.js` imports nothing via `import` statements — it uses plain IIFE or classic JS. Each module file exports its class as a global or is concatenated.

**Two options for Wave 7 (decide during implementation):**

**Option A — Single concatenated file (simpler):**
Concatenate all module files into `main.js` in the correct order. One HTTP request. Easy to maintain — just edit the relevant section.

**Option B — Separate enqueued files (more modular):**
Each module file enqueued individually via `wp_enqueue_script` with explicit dependencies on `radical/main-core`.

**Recommendation: Option A.** The existing Webpack bundle was one file anyway. Maintaining one `main.js` in plain JS is simpler than managing 25 separate `wp_enqueue_script` calls.

**Blade Template Conversion Rules (applied in every template file):**

| Blade | Plain PHP |
|---|---|
| `@extends('layouts.app')` | Remove — `get_header()` / `get_footer()` handles this |
| `@section('content') ... @endsection` | Remove section wrappers — content goes directly in file |
| `@include('partials.header')` | `get_template_part('template-parts/header/header')` |
| `@include('partials.foo.bar')` | `get_template_part('template-parts/foo/bar')` |
| `{{ $var }}` | `<?php echo esc_html($var); ?>` |
| `{!! $var !!}` | `<?php echo $var; ?>` |
| `@php ... @endphp` | `<?php ... ?>` |
| `@if(...) ... @endif` | `<?php if(...): ?> ... <?php endif; ?>` |
| `@foreach(...) ... @endforeach` | `<?php foreach(...): ?> ... <?php endforeach; ?>` |
| `@asset('styles/main.css')` | `<?php echo get_template_directory_uri(); ?>/assets/css/main.css` |
| `$siteName` (from Controller) | `<?php echo get_bloginfo('name'); ?>` |
| `$title` (from Controller) | inline conditional — see `inc/helpers.php` `radical_page_title()` |

---

## New Helper: `radical_page_title()`

To replace the `App::title()` Controller method cleanly, add to `inc/helpers.php`:

```php
function radical_page_title() {
    if (is_home()) {
        if ($home = get_option('page_for_posts', true)) {
            return get_the_title($home);
        }
        return __('Latest Posts', 'radical');
    }
    if (is_archive())  return get_the_archive_title();
    if (is_search())   return sprintf(__('Search Results for %s', 'radical'), get_search_query());
    if (is_404())      return __('Not Found', 'radical');
    return get_the_title();
}
```

---

## Gate 3 Checklist

- [x] Full directory tree defined — every file named
- [x] `functions.php` require chain written with exact file paths
- [x] `style.css` theme header defined
- [x] `inc/enqueue.php` fully specified — all handles, conditionals, localize object preserved
- [x] `inc/filters.php` — what stays vs. what's dropped documented
- [x] `inc/setup.php` — contents defined
- [x] Controller data-passing resolved — `ArchivePodcasts` AJAX moves to `inc/admin/podcasts.php`, `App::title()` becomes `radical_page_title()` helper
- [x] CSS architecture defined — single file, sass-compile baseline, section order
- [x] JS architecture defined — Option A (concatenated `main.js`), no bundler
- [x] Blade → PHP conversion reference table complete
- [x] WooCommerce email templates confirmed as plain PHP (copy-only, no conversion)
- [x] `user-coupons.php` confirmed disabled — stays disabled

**→ Type `A` to approve Gate 3 and I'll write Stage 4: Migration Plan + Claude Code CLI kickoff prompt**
