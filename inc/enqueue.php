<?php
/**
 * Theme Assets
 */
add_action('wp_enqueue_scripts', function () {
    $uri = get_template_directory_uri();

    // ── External fonts ──────────────────────────────────────────────────────
    wp_enqueue_style('fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');
    wp_enqueue_style('fonts-josefin', 'https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap');
    wp_enqueue_style('fonts-typekit', 'https://use.typekit.net/wcu8ruk.css');

    // ── Vendor CSS ───────────────────────────────────────────────────────────
    if (is_front_page() || is_product()) {
        wp_enqueue_style('slick-css', $uri . '/assets/css/vendor/slick.css', false, null);
        wp_enqueue_style('slick-theme', $uri . '/assets/css/vendor/slick-theme.css', false, null);
    }
    if (is_post_type_archive('podcasts') || is_post_type_archive('events')) {
        wp_enqueue_style('owl-carousel', $uri . '/assets/css/vendor/owl.carousel.min.css');
        wp_enqueue_script('owl-carousel-js', $uri . '/assets/js/vendor/owl.carousel.min.js', ['jquery'], '2.3.4', true);
    }

    // ── Theme CSS ────────────────────────────────────────────────────────────
    wp_enqueue_style('radical/main', $uri . '/assets/css/main.css', [], '2.0.0');

    // ── Vendor JS ────────────────────────────────────────────────────────────
    if (is_front_page() || is_product()) {
        wp_enqueue_script('slick-js', $uri . '/assets/js/vendor/slick.min.js', ['jquery'], null, true);
    }
    wp_enqueue_script('bootstrap-js', $uri . '/assets/js/vendor/bootstrap.bundle.min.js', ['jquery'], '4.3.1', true);

    // Some plugins (e.g. WP Loyalty Rules) call jQuery.noConflict() on the
    // frontend, which unsets the global `$`. Our module files reference bare
    // `$` directly (no bundler to scope it locally), so restore it here, right
    // before our own scripts enqueue — this runs after any earlier-priority
    // plugin script that may have called noConflict().
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
        wp_enqueue_script("radical/{$module}", $uri . "/assets/js/modules/{$module}.js", ['jquery', 'bootstrap-js'], '2.0.0', true);
    }

    // ── Theme JS ─────────────────────────────────────────────────────────────
    wp_enqueue_script('radical/main', $uri . '/assets/js/main.js', ['jquery', 'bootstrap-js', 'smooth-scroll'], '2.0.0', true);

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
}, 100);
