<?php
/**
 * Radical Skincare Theme Functions
 */

// ACF fallback shims — must load before anything that calls get_field() etc.,
// so a missing ACF plugin degrades gracefully instead of fataling.
require_once get_template_directory() . '/inc/acf-fallback.php';

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
// Brand Partner admin settings page — loaded only as admin_menu callback
add_action('admin_menu', function () {
    add_submenu_page(
        'options-general.php',
        'Brand Partner - Settings',
        'Brand Partner',
        'manage_options',
        'brand-partner',
        function () {
            require_once get_template_directory() . '/inc/admin/brand-partner-settings-page.php';
        }
    );
});
require_once get_template_directory() . '/inc/admin/gigfiliate-wp.php';

// Integrations (WooCommerce-independent)
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
require_once get_template_directory() . '/inc/integrations/security.php';
// require_once get_template_directory() . '/inc/integrations/user-coupons.php'; // intentionally disabled

/**
 * WooCommerce-dependent integrations.
 *
 * These files reference WooCommerce classes/functions (e.g. WC_Email) at load
 * time, so requiring them without WooCommerce active causes a fatal error that
 * takes the whole site down. Plugins load before the theme, so checking for the
 * WooCommerce class here is reliable. If it is missing, skip the WooCommerce
 * code and show a clear admin notice instead of crashing.
 */
if (class_exists('WooCommerce')) {
    require_once get_template_directory() . '/inc/admin/vip-customers.php';
    require_once get_template_directory() . '/inc/admin/woocommerce.php';
    require_once get_template_directory() . '/inc/integrations/woocommerce.php';
    require_once get_template_directory() . '/inc/integrations/sitewide-discounts.php';
    require_once get_template_directory() . '/inc/integrations/threshold-discount.php';
    require_once get_template_directory() . '/inc/integrations/wc-subscriptions.php';
} else {
    add_action('admin_notices', function () {
        if (!current_user_can('activate_plugins')) {
            return;
        }
        $theme = wp_get_theme();
        printf(
            '<div class="notice notice-warning"><p><strong>%1$s:</strong> %2$s</p></div>',
            esc_html($theme->get('Name')),
            esc_html__(
                'WooCommerce is not active. Shop, cart, checkout, and product features are disabled until you install and activate WooCommerce.',
                'radical'
            )
        );
    });
}
