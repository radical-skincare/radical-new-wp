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
require_once get_template_directory() . '/inc/integrations/security.php';
// require_once get_template_directory() . '/inc/integrations/user-coupons.php'; // intentionally disabled
