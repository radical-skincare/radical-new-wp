<?php
/**
 * Theme Assets
 */
add_action('wp_enqueue_scripts', function () {
    $uri = get_template_directory_uri();

    // ── External fonts ──────────────────────────────────────────────────────
    // fontawesome's version is already pinned in its CDN URL, so reuse it
    // here too. Google Fonts/Typekit URLs carry no version of their own —
    // pass null so WP doesn't append its own ?ver=<wp_version> query string
    // to a third-party host.
    wp_enqueue_style('fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css', [], '4.6.3');
    wp_enqueue_style('fonts-josefin', 'https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap', [], null);
    wp_enqueue_style('fonts-typekit', 'https://use.typekit.net/wcu8ruk.css', [], null);

    // ── Vendor CSS ───────────────────────────────────────────────────────────
    if (is_front_page() || is_product()) {
        wp_enqueue_style('slick-css', $uri . '/assets/css/vendor/slick.css', false, '1.8.1');
        wp_enqueue_style('slick-theme', $uri . '/assets/css/vendor/slick-theme.css', false, '1.8.1');
    }
    if (is_post_type_archive('podcasts') || is_post_type_archive('events')) {
        wp_enqueue_style('owl-carousel', $uri . '/assets/css/vendor/owl.carousel.min.css', [], '2.3.4');
        wp_enqueue_script('owl-carousel-js', $uri . '/assets/js/vendor/owl.carousel.min.js', ['jquery'], '2.3.4', true);
    }

    // ── Theme CSS ────────────────────────────────────────────────────────────
    wp_enqueue_style('radical/main', $uri . '/assets/css/main.css', [], '2.0.1');

    // ── Vendor JS ────────────────────────────────────────────────────────────
    if (is_front_page() || is_product()) {
        wp_enqueue_script('slick-js', $uri . '/assets/js/vendor/slick.min.js', ['jquery'], '1.8.1', true);
    }
    wp_enqueue_script('bootstrap-js', $uri . '/assets/js/vendor/bootstrap.bundle.min.js', ['jquery'], '4.3.1', true);

    // Some plugins (e.g. WP Loyalty Rules) call jQuery.noConflict() on the
    // frontend, which unsets the global `$`. Our module files reference bare
    // `$` directly (no bundler to scope it locally). Restore it after bootstrap
    // (before modules parse) and again immediately before main.js runs; main.js
    // also re-assigns on jQuery ready (after deferred scripts execute).
    wp_add_inline_script('bootstrap-js', 'window.$ = jQuery;', 'after');

    // ── Smooth Scroll ────────────────────────────────────────────────────────
    wp_enqueue_script('smooth-scroll', $uri . '/assets/js/vendor/smooth-scroll.min.js', [], '16.1.3', true);

    // ── JS Modules ───────────────────────────────────────────────────────────
    $modules = [
        'Cookie', 'Utilities', 'Global', 'Header', 'SingleProduct', 'Search',
        'Favorites', 'Login', 'PageHero', 'Sale', 'SkinCareAddition',
        'RefillAddToCart', 'BrandPartner', 'AmbassadorEnrollment',
        'ArchivePodcasts', 'ArchiveProducts', 'Giving', 'TemplateHome',
        'TemplatePress', 'TemplateFAQ', 'TemplateTrylacel', 'MyAccount',
        'Form', 'EmailSubscribe', 'WoocommerceSubscription',
        'WoocommerceSubscriptionSearch', 'ProductReviewModel',
        'ProductPurchaseOptions', 'CheckoutWC',
    ];
    foreach ($modules as $module) {
        wp_enqueue_script("radical/{$module}", $uri . "/assets/js/modules/{$module}.js", ['jquery', 'bootstrap-js'], '4.0.2', true);
    }

    // ── Theme JS ─────────────────────────────────────────────────────────────
    wp_enqueue_script('radical/main', $uri . '/assets/js/main.js', ['jquery', 'bootstrap-js', 'smooth-scroll'], '4.0.2', true);
    wp_add_inline_script('radical/main', 'window.$ = jQuery;', 'before');

    // ── Localize ─────────────────────────────────────────────────────────────
    $is_logged_in = is_user_logged_in();
    $localize = [
        'site_url'           => get_site_url(),
        'admin_ajax_url'     => admin_url('admin-ajax.php'),
        'rest_url'           => esc_url_raw(rest_url('/wp/v2')),
        'is_user_logged_in'  => $is_logged_in,
        'email_signup_modal' => function_exists('get_field') ? get_field('email_signup_modal', 'option') : '',
        'sitewide_discount'  => function_exists('get_field') ? get_field('sitewide_discount', 'option') : '',
        'radical_nonce'      => wp_create_nonce('radical_ajax_nonce'),
    ];
    $general_settings = json_decode(get_option('brand_partner_setings'));
    if ($is_logged_in) {
        $user_id = (int) get_current_user_id();
        $user    = get_user_by('ID', $user_id);
        $localize['current_user_id'] = $user_id;
        $localize['user_email']      = $user->user_email;
        if (!is_null($general_settings) && $general_settings->affiliate_plugin === 'affiliate-wp') {
            if (function_exists('is_Brand_Partner_Active')) {
                $localize['affiliate_status'] = is_Brand_Partner_Active($user_id);
            }
        } elseif (!is_null($general_settings) && $general_settings->affiliate_plugin === 'gigfiliate') {
            $localize['affiliate_status'] = get_user_meta($user_id, 'v_affiliate_status', true);
        }
    }
    if (!is_null($general_settings)) {
        $localize['default_parent_affiliate_id'] = isset($general_settings->default_parent_affiliate_id) ? $general_settings->default_parent_affiliate_id : null;
        $localize['affiliate_plugin']            = isset($general_settings->affiliate_plugin) ? $general_settings->affiliate_plugin : null;
    }
    if (class_exists('affiliate_wp')) {
        $localize['affwp_mlm_default_affiliate_id'] = (int) affiliate_wp()->settings->get('affwp_mlm_default_affiliate', []);
    }
    wp_localize_script('radical/main', 'ThemeSettings', $localize);

    if (is_single() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}, 9999);
