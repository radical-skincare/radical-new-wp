# Radical Skincare — WordPress Theme

Custom WordPress + WooCommerce theme for [radicalskincare.com](https://radicalskincare.com).

Plain PHP templates, flat CSS, jQuery — no build tools, no Composer, no framework.

## Stack

| Layer | Technology |
|-------|-----------|
| Templates | Plain PHP (standard WP template hierarchy) |
| CSS | Single `assets/css/main.css` (compiled from SCSS, maintained as flat CSS) |
| JS | jQuery + individual module files, no bundler |
| PHP deps | None |
| JS deps | jQuery (WP core), Bootstrap 4.3.1, Slick 1.8.1, Owl Carousel 2.3.4, Smooth Scroll 16.1.3 |

## Directory Structure

```
radical-new-wp/
├── style.css                  # WP theme header
├── functions.php              # Require chain (no logic)
├── header.php / footer.php    # Site-wide layout
├── front-page.php             # Homepage
├── page.php / single.php      # Standard templates
├── archive.php / search.php / 404.php
├── template-*.php             # 21 custom page templates
├── inc/
│   ├── setup.php              # Theme supports, nav menus, sidebars
│   ├── enqueue.php            # All CSS/JS enqueue + wp_localize_script
│   ├── helpers.php            # Utility functions (radical_page_title, etc.)
│   ├── filters.php            # body_class, excerpt_more
│   ├── class-wp-bootstrap-navwalker.php
│   ├── admin/                 # CPT registration, ACF, admin pages
│   └── integrations/          # WooCommerce, Gigfiliate, Twilio, Yotpo, etc.
├── template-parts/            # 156 partial templates
│   ├── header/ footer/ sidebar/
│   ├── content/ product/ account/
│   ├── modal/ hero/ components/ form/
│   └── modules/               # Home, press, FAQ, rewards, etc.
├── woocommerce/               # 48 WC template overrides + email templates
├── assets/
│   ├── css/main.css           # All theme styles
│   ├── css/vendor/            # Slick, Owl Carousel
│   ├── js/main.js             # Module initializer
│   ├── js/modules/            # 29 JS modules
│   ├── js/vendor/             # Bootstrap, Slick, Owl, Smooth Scroll
│   ├── fonts/                 # Star icon font
│   └── images/                # Theme images and SVGs
└── acf-json/                  # 27 ACF field group JSON files
```

## Development

Edit PHP, CSS, and JS files directly. No build step required.

### Requirements

- WordPress 5.0+
- WooCommerce 7.0+
- Advanced Custom Fields PRO
- PHP 7.4+

### Theme Activation

1. Upload the theme folder to `wp-content/themes/`
2. Activate via Appearance > Themes
3. ACF field groups load automatically from `acf-json/`

### Custom Page Templates

21 templates available in the page editor:

Account, Brand Partner Enrollment, Clean Conscious, Clean Conscious (Old), Contact, FAQ, Giving, Hero Right Sidebar, Holiday Gift Guide, Home, Impact Fund, Join Brand Partners, Mission, Press, Quiz, Radical Repeat, Radical Rituals, Rewards, Team, Trylacel, Valentines

### Custom Post Types

- **Podcasts** — archive at `/podcasts/` with AJAX filtering
- **Press** — press items
- **Stories** — customer stories
- **VIP Customers** — VIP tier management

### Plugin Integrations

- WooCommerce (subscriptions, custom checkout, account pages)
- Advanced Custom Fields PRO (theme settings, product fields)
- Gigfiliate (brand partner/affiliate system)
- WoLoyalty (rewards program)
- Yotpo (product reviews)
- Twilio (SMS notifications)
- Affiliate WP (legacy, deprecated)

### Disabled Features

- `user-coupons.php` — intentionally disabled in `functions.php`

## Origin

Refactored from a Sage 9 (Blade/Webpack/Composer) theme to plain PHP for maintainability. The refactor preserves all functionality and visual output while removing the build toolchain dependency.
