<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$site_url = get_site_url();
$current_user_id = $current_user->ID;
$is_active_affiliate = function_exists('gigfiliate_is_active_affiliate') ? gigfiliate_is_active_affiliate($current_user_id) : false;
$customer = new WC_Customer($current_user_id);
$first_name = get_user_meta($current_user_id, 'first_name', true);
$last_name = get_user_meta($current_user_id, 'last_name', true);
$shipping_address = $customer->get_shipping();
$saved_payment_methods = wc_get_customer_saved_methods_list( $current_user_id );
$payment_method = null;
foreach ($saved_payment_methods as $saved_payment_method) {
  foreach ($saved_payment_method as $saved_payment) {
    if ($saved_payment['is_default']) {
      $payment_method = $saved_payment;
    }
  }
}
?>
<style>
.recent-orders .badge,
.recent-subscriptions .badge {
  white-space: normal;
}
</style>
<div class="row">
  <div class="col-lg-8">
    <h1 class="font-weight-lighter mb-3" style="text-transform: capitalize;">Welcome back, <?php echo esc_html($first_name && $last_name ? $first_name . ' ' . $last_name : $current_user->display_name); ?></h1>
    <?php
    /**
     * My Account dashboard.
     *
     * @since 2.6.0
     */
    do_action('woocommerce_account_dashboard');

    /**
     * Deprecated woocommerce_before_my_account action.
     *
     * @deprecated 2.6.0
     */
    do_action('woocommerce_before_my_account');

    /**
     * Deprecated woocommerce_after_my_account action.
     *
     * @deprecated 2.6.0
     */
    do_action('woocommerce_after_my_account');
    ?>
    <?php echo do_shortcode('[gigfiliate_your_shopping_with]'); ?>
    <?php get_template_part('template-parts/account/dashboard/recent-orders', null, ['current_user_id' => $current_user_id, 'site_url' => $site_url]); ?>
    <?php get_template_part('template-parts/account/dashboard/recent-subscriptions', null, ['current_user_id' => $current_user_id, 'site_url' => $site_url]); ?>
    <?php include get_template_directory() . '/woocommerce/myaccount/dashboard/coupons.php'; ?>
  </div>
  <div class="col-lg-4">
    <h3 class="mb-3">My Settings</h3>
    <div class="card mb-3 w-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h4 class="cart-title mb-0">Account Info</h4>
          <a href="<?php echo esc_url(wc_get_endpoint_url('edit-account')); ?>" title="Edit Account Details">
            <span class="sr-only">Edit Account Details</span>
            <i class="fa fa-cogs text-darkergray" aria-hidden="true"></i>
          </a>
        </div>
        <strong class="mb-0"><?php echo esc_html($current_user->display_name); ?></strong>
        <p class="card-text"><?php echo esc_html($current_user->user_email); ?></p>
      </div>
    </div>
    <?php if ($shipping_address['address_1'] || $shipping_address['address_2']) : ?>
      <div class="card mb-3 w-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h4 class="cart-title mb-0">Primary Shipping Address</h4>
            <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address/shipping')); ?>" title="Edit Address">
              <span class="sr-only">Edit Address</span>
              <i class="fa fa-cogs text-darkergray" aria-hidden="true"></i>
            </a>
          </div>
          <strong class="mb-0"><?php echo esc_html($shipping_address['first_name']); ?> <?php echo esc_html($shipping_address['last_name']); ?></strong>
          <div class="card-text">
            <address><?php echo esc_html($shipping_address['address_1']); ?><?php echo $shipping_address['address_2'] ? '<br/>' . esc_html($shipping_address['address_2']) : ''; ?><br/><?php echo esc_html($shipping_address['city']); ?>, <?php echo esc_html($shipping_address['state']); ?>, <?php echo esc_html($shipping_address['postcode']); ?> <?php echo esc_html($shipping_address['country']); ?></address>
            <?php if (isset($shipping_address['email']) && isset($shipping_address['phone'])) : ?>
              <p>
                <?php if ($email = $shipping_address['email']) : ?>
                  <br/><i class="fa fa-envelope" aria-hidden="true"></i> <?php echo esc_html($email); ?> <br>
                <?php endif; ?>
                <?php if ($phone = $shipping_address['phone']) : ?>
                  <br/><i class="fa fa-phone" aria-hidden="true"></i> <?php echo esc_html($phone); ?>
                <?php endif; ?>
              </p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endif; ?>
    <?php if ($payment_method) : ?>
      <div class="card mb-3 mb-lg-0">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h4 class="cart-title">Default Payment Method</h4>
            <a href="<?php echo esc_url(wc_get_endpoint_url('payment-methods')); ?>" title="Edit Payment Methods">
              <span class="sr-only">Edit Payment Methods</span>
              <i class="fa fa-cogs text-darkergray" aria-hidden="true"></i>
            </a>
          </div>
          <div class="d-flex px-1">
            <div class="col-2 d-flex justify-content-center align-items-center px-0">
              <img src="<?php echo get_template_directory_uri() . '/assets/images/cards/' . str_replace(' ', '-', strtolower($payment_method['method']['brand'])) . '.svg'; ?>" alt="<?php echo esc_attr($payment_method['method']['brand']); ?>" class="w-75"/>
            </div>
            <div class="col-10">
              <?php if (!empty( $payment_method['method']['last4'])) : ?>
                <p class="mb-1"><b> <?php echo esc_html($payment_method['method']['brand']); ?> ****</b> <?php echo esc_html($payment_method['method']['last4']); ?></p>
              <?php else : ?>
                <?php echo esc_html( wc_get_credit_card_type_label( $payment_method['method']['brand'] ) ); ?>
              <?php endif; ?>
              <?php if (!empty( $payment_method['expires'])) : ?>
                <p class="mb-0">Expires <?php echo esc_html($payment_method['expires']); ?></p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>
