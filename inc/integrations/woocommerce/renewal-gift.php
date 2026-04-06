<?php
add_action('woocommerce_order_status_pending_to_processing_renewal_notification', 'radical_add_gift_on_order', 1, 2);
add_action('woocommerce_order_status_pending_to_completed_renewal_notification', 'radical_add_gift_on_order', 1, 2);
add_action('woocommerce_order_status_failed_to_processing_renewal_notification', 'radical_add_gift_on_order', 1, 2);
add_action('woocommerce_order_status_failed_to_completed_renewal_notification', 'radical_add_gift_on_order', 1, 2);
add_action('woocommerce_order_status_cancelled_to_processing_renewal_notification', 'radical_add_gift_on_order', 1, 2);
add_action('woocommerce_order_status_cancelled_to_completed_renewal_notification', 'radical_add_gift_on_order', 1, 2);

function radical_add_gift_on_order ($order_id) {
  $have_renewal_gift = get_post_meta($order_id, 'have_renewal_gift', true);
  if ($have_renewal_gift) {
    return;
  }

  $order = wc_get_order($order_id);

  $subscriptions = wcs_get_subscriptions_for_order($order, array('order_type' => array('parent', 'renewal')));

  // Check if order has any subscriptions
  if (!$subscriptions || !array_values($subscriptions)[0]) {
    return;
  }

  $first_subscription = array_values($subscriptions)[0];

  $gifts = get_field('gifts', 'option');
  $enable_renewal_gifts = $gifts['enable_renewal_gifts'];
  if (!$enable_renewal_gifts) {
    return;
  }

  if (date('Ymd', strtotime($gifts['enable_from'])) > date('Ymd')) {
    return;
  }

  $orders_after = get_posts([
    'post_status' => ['wc-processing', 'wc-completed', 'wc-delivered'],
    'post_type' => ['shop_order', 'shop_subscription'],
    'include' => $first_subscription->get_related_orders(),
    'date_query' => [
      [
        'after' => $gifts['enable_from'],
        'inclusive' => true,
      ],
    ],
  ]);

  if (!$orders_after || empty($orders_after)) {
    return;
  }
  
  foreach ($gifts['renewal_gifts'] as $renewal_gift) {
    if ($renewal_gift['threshold'] == count($orders_after)) {
      $order->add_order_note($renewal_gift['note']);
      update_post_meta($order_id, 'have_renewal_gift', true);
      if ($renewal_gift['product']) {
        $free_product = wc_get_product($renewal_gift['product']);
        $free_product->set_name( 'Free ' . $free_product->get_name() );
        $order->add_product($free_product, 1, ['subtotal' => 0, 'total' => '0']);
        update_post_meta($order_id, 'product', $renewal_gift['product']);
      }
    }
  }
}

add_action('woocommerce_subscriptions_email_order_details', 'radical_email_message', 1, 4);
add_action('woocommerce_email_order_details', 'radical_email_message', 1, 4);

function radical_email_message($order, $sent_to_admin, $plain_text, $email) {
  if ($sent_to_admin) {
    return;
  }
  $have_renewal_gift = get_post_meta($order->get_ID(), 'have_renewal_gift', true);
  $renewal_gift_product = get_post_meta($order->get_ID(), 'product', true);
  if (!$have_renewal_gift || !$renewal_gift_product) {
    return;
  }
  $product = wc_get_product($renewal_gift_product);
  if (!$product) {
    return;
  }
  ?>
    <div style="margin-top: 20px;margin-bottom: 20px;border: 1px solid #e5e5e5;max-width: 100%;padding: 20px;">
      <h3 style="color: #b20839; display: block; font-family: &quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 0 18px; text-align: center;">Thank You</h3>
      <p style='text-align:center;margin-bottom: 0;'>As A Gesture Of Appreciation For Your Loyalty, Please Enjoy The <b><?php echo $product->get_title();?></b> As Free Gift In Your Order.</p>
    </div>
  <?php
}
