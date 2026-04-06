# Stage 5 — Build & Verify
**Track:** codebase-refactor
**Refactor ID:** REFACTOR-001
**Gate:** 5 — requires Justin's `A` after smoke test passes
**Date:** 2026-04-05

---

## Execution Log

| Wave | Status | Commit | Deliverables |
|---|---|---|---|
| Wave 1 — Foundation | ✅ Complete | `f8fdbf8` | style.css, functions.php, inc/setup, enqueue, helpers, filters |
| Wave 2 — Core Templates | ✅ Complete | `f0ac214` | header.php, footer.php, 10 template hierarchy files |
| Wave 3 — PHP Includes | ✅ Complete | `148a979` | 33 files — navwalker, 8 admin files, 18 integrations, podcasts AJAX handlers |
| Wave 4 — Custom Templates | ✅ Complete | `47556b8` | 21 template-*.php files with WP Template Name headers |
| Wave 5 — Template Parts | ✅ Complete | `f1d166e` | 156 partials — header, footer, content, product, account, modal, modules |
| Wave 6 — WooCommerce | ✅ Complete | `3d1adc3` | 48 files — 42 template overrides + 6 email templates |
| Wave 7 — Assets | ✅ Complete | `2c77f71` | Compiled CSS, 28 JS modules, 4 vendor JS, 3 vendor CSS |
| Wave 8 — Cleanup | ✅ Complete | `838f64e` | 27 ACF JSON, 4 fonts, 52 images. Zero forbidden patterns. |

**Final totals:** 275 PHP · 35 JS · 5 CSS · 27 ACF JSON · 4 fonts · 52 images

**Cleanup verification (all passed):**
- Zero `asset_path()` calls
- Zero `namespace App` declarations
- Zero `use Roots\` imports
- Zero `sage()` references
- Zero Blade syntax (`@extends`, `@section`, `@include`)
- Zero `.blade.php` files

---

## Smoke Test

> Run these against a local WordPress install with the new theme active and all production plugins installed.

### Activate Theme

1. Copy `radical-new-wp/` to `wp-content/themes/radical-new-wp/`
2. WP Admin → Appearance → Themes → Activate "Radical Skincare"
3. Confirm: no white screen, no fatal PHP error notice

**Result:** [ ] Pass / [ ] Fail — Notes:

---

### Core Pages

| Page | Test | Pass? | Notes |
|---|---|---|---|
| Homepage | Loads, header + footer visible | | |
| Standard page | Any WP page renders | | |
| Single blog post | Post renders with entry meta | | |
| Blog archive | Post list renders | | |
| Search results | Search form + results render | | |
| 404 | Not-found page renders | | |
| Sidebar | `get_sidebar()` renders where expected | | |
| Comments | Comment form renders on posts | | |

---

### Navigation & Header

| Item | Test | Pass? | Notes |
|---|---|---|---|
| Navbar menu | Assigned menu renders | | |
| Mobile menu | Opens/closes on mobile viewport | | |
| Mega menu | Renders if populated | | |
| Announcements bar | Renders if ACF field set | | |
| Search overlay | Opens on search icon click | | |
| Favorites icon | Renders in header | | |
| Header left sidebar | Renders if enabled | | |

---

### Custom Page Templates

Confirm each is selectable in WP page editor (Page Attributes → Template) and renders without errors:

| Template | Selectable? | Renders? |
|---|---|---|
| Home | | |
| Account | | |
| Contact | | |
| FAQ | | |
| Giving | | |
| Mission | | |
| Press | | |
| Team | | |
| Clean Conscious | | |
| Rewards | | |
| Radical Repeat | | |
| Quiz | | |
| Trylacel | | |
| Join Brand Partners | | |
| Brand Partner Enrollment | | |
| Impact Fund | | |
| Hero Right Sidebar | | |
| Holiday Gift Guide | | |
| Radical Rituals | | |
| Valentines | | |

---

### WooCommerce

| Area | Test | Pass? | Notes |
|---|---|---|---|
| Shop / archive | Product grid renders | | |
| Single product | Product page renders | | |
| Add to cart | AJAX add-to-cart works | | |
| Side cart | Cart sidebar opens | | |
| Cart page | Renders with items | | |
| Checkout | Form renders, can submit | | |
| Order confirmation | Thank you page renders | | |
| My Account | Dashboard renders | | |
| Orders list | My Account → Orders renders | | |
| Subscription list | My Account → Subscriptions renders | | |
| View subscription | Subscription detail page renders | | |
| Payment methods | My Account → Payment Methods renders | | |
| WC email templates | WP Admin → WooCommerce → Settings → Emails → preview renders | | |

---

### Custom Post Types

| CPT | WP Admin visible? | Archive renders? |
|---|---|---|
| Podcasts | | |
| Press | | |
| Stories | | |
| Brand Partners | | |
| VIP Customers | | |

---

### ACF

| Check | Pass? | Notes |
|---|---|---|
| WP Admin → Custom Fields — 27 field groups visible | | |
| Theme Settings options page visible in WP Admin sidebar | | |
| Brand Partner Settings options page visible | | |
| `get_field()` returns data on relevant templates | | |

---

### JavaScript Functionality

| Feature | Test | Pass? | Notes |
|---|---|---|---|
| Mobile menu | Open/close | | |
| Product image | Gallery / zoom works | | |
| Slick carousel | Home page carousel slides | | |
| Owl Carousel | Podcasts archive carousel | | |
| FAQ accordion | Expands/collapses | | |
| Modals | Login, email capture, sale, subscription terms | | |
| Smooth scroll | Anchor links scroll smoothly | | |
| Favorites / wishlist | Toggle works | | |
| Login form | Submits correctly | | |
| Email subscribe | Submits correctly | | |
| Purchase options | One-time vs. subscription toggle | | |
| Scroll to top | Button appears + works | | |

---

### Plugin Integrations

| Integration | Test | Pass? | Notes |
|---|---|---|---|
| WoLoyalty | Points/rewards display in account | | |
| Yotpo | Reviews widget loads on product pages | | |
| Sitewide discount | Discount applies if active | | |
| Threshold discount | Triggers at correct cart value | | |
| Favorites | Add/remove from wishlist | | |
| Gigfiliate | Brand Partner affiliate links functional | | |
| Affiliate WP | (Deprecated) no errors | | |
| Analyze Glow quiz | Quiz page loads and functions | | |
| VIP Customers | VIP tier logic active | | |

---

### Assets

| Check | Pass? | Notes |
|---|---|---|
| No 404s in DevTools → Network for CSS/JS/fonts/images | | |
| Josefin Sans font loads (Google Fonts) | | |
| Orpheus font loads (Typekit) | | |
| FontAwesome icons render | | |
| Star rating font renders (WooCommerce stars) | | |
| SVG icons render | | |

---

### Visual Regression

Side-by-side comparison against source theme (`radical-wp`) on the same WP install:

| Page | Match? | Differences |
|---|---|---|
| Homepage (desktop) | | |
| Homepage (mobile) | | |
| Shop page | | |
| Single product | | |
| Cart / Checkout | | |
| My Account | | |

---

## PHP Error Log Check

After running through the smoke test, check the PHP error log:
```bash
# Typical location — adjust for your local setup
tail -50 /var/log/nginx/error.log
# or
tail -50 ~/Local/sites/radical/logs/php/error.log
```

- [ ] Zero fatal errors
- [ ] Zero warnings related to new theme files (pre-existing WP/plugin warnings are acceptable)

---

## Gate 5 Checklist

- [ ] Theme activates without fatal errors
- [ ] All core pages render
- [ ] All 20 custom page templates selectable and rendering
- [ ] WooCommerce shop → checkout flow functional
- [ ] Subscription management functional
- [ ] All 27 ACF field groups loading
- [ ] No 404s for assets in DevTools
- [ ] All JS functionality working
- [ ] Visual regression check passed
- [ ] PHP error log clean

**→ Type `A` to approve Gate 5 and proceed to Stage 6: Cutover**
