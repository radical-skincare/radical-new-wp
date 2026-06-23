<?php
/**
 * View Order
 *
 * Shows the details of a particular order on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/view-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

defined( 'ABSPATH' ) || exit;
if (!$order) {
  return;
}
$status = $order->get_status();
$gig_ordered_by = get_post_meta($order_id, 'gig_ordered_by', true);
$actions = wc_get_account_orders_actions( $order );
$subscriptions = wcs_get_subscriptions_for_order( $order, array( 'order_type' => 'any' ) );
?>
<style>
.woocommerce-order-details_action-btns {
  column-gap: 0.5rem;
}
</style>
<section class="woocommerce-order-details">
  <h2 class="woocommerce-order-details__title">Order #<?php echo esc_html($order_id); ?></h2>
  <div class="row mb-2">
    <div class="col">
      <p class="m-0">
        Status <span class="badge badge-<?php echo esc_attr($status); ?>"><?php echo esc_html($status); ?></span>
        <?php if ($gig_ordered_by) : ?>
          <span class="badge badge-info">Orderd For Customer</span>
        <?php endif; ?>
      </p>
    </div>
    <div class="woocommerce-order-details_action-btns col d-flex justify-content-end">
      <?php if ($actions) : ?>
        <?php foreach ($actions as $action) : ?>
          <?php if ($action['name'] === 'Pay') : ?>
            <a href="<?php echo esc_url($action['url']); ?>" class="btn btn-dark"><?php echo esc_html($action['name']); ?></a>
          <?php endif; ?>
        <?php endforeach; ?>
      <?php elseif ($status === 'completed') : ?>
        <button class="btn btn-dark" id="rePurchaseOrder" data-order_id="<?php echo esc_attr($order->get_ID()); ?>">Purchase Again</button>
      <?php endif; ?>
      <?php if ($subscriptions) : ?>
        <?php foreach ($subscriptions as $subscription_id => $subscription ) : ?>
          <a href="<?= esc_url( $subscription->get_view_order_url() ) ?>" class="btn btn-dark">Manage Subscription</a>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
  <?php get_template_part('template-parts/account/order/details', null, ['order' => $order, 'order_id' => $order_id]); ?>
  <?php
    do_action( 'woocommerce_view_order', $order_id );
  ?>
</section>
