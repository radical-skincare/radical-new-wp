<?php
/**
 * Order details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.6.0
 */

defined( 'ABSPATH' ) || exit;

$order = wc_get_order( $order_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

if ( ! $order ) {
	return;
}

$site_url = get_site_url();
$current_user_id = get_current_user_id();
$order_items = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$show_customer_details = is_user_logged_in() && $order->get_user_id() === $current_user_id;
?>
  <div class="row mb-3">
    <div class="col">
      <h3 class="m-0">Order Details</h3>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-8">
      <?php
      set_query_var('order', $order);
      set_query_var('order_id', $order_id);
      get_template_part('template-parts/account/order/details-products');
      ?>
      <?php
      set_query_var('order', $order);
      set_query_var('order_id', $order_id);
      get_template_part('template-parts/account/order/accordion');
      ?>
      <?php
      set_query_var('order', $order);
      set_query_var('order_id', $order_id);
      get_template_part('template-parts/account/order/card-free-gift');
      ?>
    </div>
    <div class="col-lg-4">
      <?php
      set_query_var('order', $order);
      get_template_part('template-parts/account/order/totals-table');
      ?>
      <?php if ($show_customer_details) : ?>
        <?php
        set_query_var('order', $order);
        set_query_var('type', 'billing');
        get_template_part('template-parts/account/address-card');
        ?>
        <?php
        set_query_var('order', $order);
        set_query_var('type', 'shipping');
        get_template_part('template-parts/account/address-card');
        ?>
      <?php endif; ?>
    </div>
  </div>
