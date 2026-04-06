<?php

add_filter('wcs_renewal_order_created', function ($new_order, $subscription) {
  $subscriptions_id =  $subscription->get_id();
  $skippable_items = get_post_meta($subscriptions_id, 'one_time_skippable_item', true);
  $global_skippable_products = get_field('subscription_global_skipped_products', 'option');
  if (!$skippable_items && !$global_skippable_products) {
    return $new_order;
  }
  if ($skippable_items) {
    $skippable_items = json_decode($skippable_items, true);
    foreach ( $new_order->get_items() as $item_id => $item ) {
      foreach ($skippable_items as $skippable_item ) {
        if ($item->get_product_id() == $skippable_item) {
          $new_order->remove_item($item_id);
        }
      }
    }
  }
  if ($global_skippable_products) {
    $global_skippable_products = explode(',', $global_skippable_products);
    foreach ( $new_order->get_items() as $item_id => $item ) {
      foreach ($global_skippable_products as $skippable_item ) {
        if ($item->get_product_id() == (int)$skippable_item) {
          $new_order->remove_item($item_id);
        }
      }
    }
  }
  // $new_order->calculate_totals();
  // $new_order->recalculate_coupons();
  $new_order->calculate_shipping();
  $new_order->calculate_totals( true );
  $new_order->save();
  delete_post_meta($subscriptions_id, 'one_time_skippable_item');
  return $new_order;
}, 10, 2);

/*
 * Skip Subscription Once (This function Ship's the whole Subscription)
 */
add_action('wp_ajax_wc_skip_subscription_once', function () {
  check_ajax_referer('radical_ajax_nonce', 'nonce');
  $subscription_id = $_POST['subscription_id'];
  $to_return = [
    'success' => false
  ];
  $subscription = wcs_get_subscription($subscription_id);
  if ($subscription->get_customer_id() != get_current_user_id()) {
    $to_return['error'] = 'You don\'t have access to this subscription';
    exit(json_encode($to_return));
  }
  $subscription_skip_history = get_post_meta($subscription_id, 'radical_subscription_skip_history', true);
  $subscription_skip_history = (($subscription_skip_history) ? json_decode($subscription_skip_history, true) : []);

  $number_of_skips_in_this_year = 0;
  $current_year = date('Y');
  foreach ($subscription_skip_history as $date) {
    if ($current_year == date('Y', strtotime($date))) {
      $number_of_skips_in_this_year++;
    }
  }
  if ($number_of_skips_in_this_year >= 3) {
    die(json_encode([
      'success' => false,
      'error' => 'You cant skip this subscription more then three times in year.'
    ]));
  }
  $subscription_skip_history[] = date('Y-m-d H:i:s');
  $interval = (int)$subscription->get_billing_interval();
  $period = $subscription->get_billing_period() . ($interval > 1 ? 's' : '');
  $next_payment_date = date('Y-m-d H:i:s', strtotime('+' . $interval . ' ' . $period, strtotime($subscription->get_date('next_payment'))));
  $dates = [
    'next_payment' => $next_payment_date
  ];
  try {
    $subscription->update_dates( $dates, 'gmt' );
    wp_cache_delete( $subscription_id, 'posts' );
    delete_post_meta($subscription_id, 'one_time_skippable_item');
    $to_return['success'] = true;
    update_post_meta($subscription_id, 'radical_subscription_skip_history', json_encode($subscription_skip_history));
    $note = 'Skipped whole order next shipment date is ' . $next_payment_date;
    $to_return['note'] = date_i18n('l jS \o\f F Y, h:ia') . '<br/>'.$note;
    if (!isset($_POST['send_email_notification']) || $_POST['send_email_notification'] == 'false') {
      radical_disable_email_on_new_note($note);
    }
    $subscription->add_order_note($note, true, true);
  } catch ( Exception $e ) {
    error_log($e->getMessage());
    $to_return['msg'] = $e->getMessage();
  }
  die(json_encode($to_return));
});

/*
 * Skip Subscription Product Once
 */
add_action('wp_ajax_wc_skip_subscription_product_once', function () {
  check_ajax_referer('radical_ajax_nonce', 'nonce');
  $subscription_id = $_POST['subscription_id'];
  $skippable_items = get_post_meta($subscription_id, 'one_time_skippable_item', true);
  if ( get_post_meta($subscription_id, '_customer_user', true) != get_current_user_id()) {
    exit(json_encode([
      'success' => false,
      'error' => 'You don\'t have access to this subscription'
    ]));
  }
  if ($skippable_items) {
    $skippable_items = json_decode($skippable_items, true);
  } else {
    $skippable_items = [];
  }
  $skippable_items[] = $_POST['product_id'];
  $skippable_items = array_unique($skippable_items);
  update_post_meta($subscription_id, 'one_time_skippable_item', json_encode($skippable_items));
  $subscription = wcs_get_subscription($subscription_id);
  $note = 'Skipped product ' . get_the_title($_POST['product_id']) . '. This product will not be shipped in the next subscription renewal.';
  if (!isset($_POST['send_email_notification']) || $_POST['send_email_notification'] == 'false') {
    radical_disable_email_on_new_note($note);
  }
  $subscription->add_order_note($note, true, true);
  die(json_encode([
    'success' => true,
    'note' => date_i18n('l jS \o\f F Y, h:ia') . '<br/>' . $note
  ]));
});

/*
 * Un Skip/Add It Back Subscription Product Once
 */
add_action('wp_ajax_wc_unskip_subscription_product_once', function () {
  check_ajax_referer('radical_ajax_nonce', 'nonce');
  $subscription_id = $_POST['subscription_id'];
  $skippable_items = get_post_meta($subscription_id, 'one_time_skippable_item', true);
  $skippable_items = $skippable_items ? json_decode($skippable_items, true) : [];
  if ( get_post_meta($subscription_id, '_customer_user', true) != get_current_user_id()) {
    exit(json_encode([
      'success' => false,
      'error' => 'You don\'t have access to this subscription'
    ]));
  }
  if (($key = array_search($_POST['product_id'], $skippable_items)) !== false) {
    unset($skippable_items[$key]);
  }
  $skippable_items = array_unique($skippable_items);
  update_post_meta($subscription_id, 'one_time_skippable_item', json_encode($skippable_items));
  $subscription = wcs_get_subscription($subscription_id);
  $note = 'Added back product ' . get_the_title($_POST['product_id']) . '. This product will be included in the next subscription renewal.';
  if (!isset($_POST['send_email_notification']) || $_POST['send_email_notification'] == 'false') {
    radical_disable_email_on_new_note($note);
  }
  $subscription->add_order_note($note, true, true);
  die(json_encode([
    'success' => true,
    'note' => date_i18n('l jS \o\f F Y, h:ia') . '<br/>' . $note
  ]));
});

/*
 * Handle On Change Subscription Next Payment
 */
add_action('wp_ajax_wc_update_subscription_next_payment', function () {
  check_ajax_referer('radical_ajax_nonce', 'nonce');
  $subscription_id = isset($_POST['subscription_id']) ? $_POST['subscription_id'] : null;
  $new_date = isset($_POST['new_date']) ? $_POST['new_date'] : null;
  $to_return = [
    'success' => false
  ];
  if (empty($subscription_id) || empty($new_date)) {
    $to_return['msg'] = "Please provide Subscription Id & New Date";
    die(json_encode($to_return));
    return;
  }
  $subscription = wcs_get_subscription($subscription_id);
  if ($subscription->get_customer_id() != get_current_user_id()) {
    $to_return['error'] = 'You don\'t have access to this subscription';
    exit(json_encode($to_return));
  }
  $next_payment_date = date('Y-m-d', strtotime($new_date)) . ' 00:00:00';
  $dates = [
    'next_payment' => $next_payment_date
  ];
  try {
    $subscription->update_dates($dates, 'gmt');
    wp_cache_delete($subscription_id, 'posts');
    $to_return['success'] = true;
  } catch (Exception $e) {
    error_log($e->getMessage());
    $to_return['msg'] = $e->getMessage();
  }
  $note = 'Next shipment date updated to ' . $next_payment_date;
  if (!isset($_POST['send_email_notification']) || $_POST['send_email_notification'] == 'false') {
    radical_disable_email_on_new_note($note);
  }
  $subscription->add_order_note($note, true, true);
  $to_return['note'] = date_i18n('l jS \o\f F Y, h:ia') . '<br/>' . $note;
  die(json_encode($to_return));
});

/*
 * Handle Product Quantity Change On Subscription Details Page
 */
add_action('wp_ajax_wc_update_subscription_product_quantity', function () {
  check_ajax_referer('radical_ajax_nonce', 'nonce');
  $subscription_id = isset($_POST['subscription_id']) ? $_POST['subscription_id'] : null;
  $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;
  $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : null;
  $to_return = [
    'success' => false
  ];
  if (empty($subscription_id) || empty($product_id) || empty($quantity)) {
    $to_return['msg'] = 'Please provide Subscription ID, Product ID and Quantity';
    die(json_encode($to_return));
    return;
  }
  $subscription = wcs_get_subscription($subscription_id);
  if ($subscription->get_customer_id() != get_current_user_id()) {
    $to_return['error'] = 'You don\'t have access to this subscription';
    exit(json_encode($to_return));
  }
  try {
    $sub_coupon_code = $subscription->get_coupon_codes();
    foreach( $subscription->get_items() as $item_id => $item ) {
      $_product = $item->get_product();
      if (!$_product) {
        continue;
      }
      if ($_product->get_Id() == $product_id) {
        $single_item_price = $subscription->get_item_subtotal( $item, false, true );
        $new_total = $quantity * $single_item_price;
        $item->set_quantity($quantity);
        $item->set_subtotal($quantity * $single_item_price);
        $item->set_total($new_total);
        $to_return['item_total'] = $new_total;
        $item->save();
      }
    }
    if ($sub_coupon_code) {
      foreach($sub_coupon_code as $coupon_code) {
        $subscription->remove_coupon($coupon_code);
      }
      $subscription->calculate_totals( true );
      foreach($sub_coupon_code as $coupon_code) {
        $subscription->apply_coupon($coupon_code);
      }  
    }
    $subscription->calculate_totals( true );
    $subscription->save();
    $to_return['totals'] = $subscription->get_order_item_totals();
    $to_return['savings'] = get_subscription_savings($subscription);
    $to_return['success'] = true;
    $note = 'Updated product ' . get_the_title($product_id) . ' quantity to ' .  $quantity . '.';
    if (!isset($_POST['send_email_notification']) || $_POST['send_email_notification'] == 'false') {
      radical_disable_email_on_new_note($note);
    }
    $subscription->add_order_note($note, true, true);
    $to_return['note'] = date_i18n('l jS \o\f F Y, h:ia') . '<br/>' . $note;
  } catch (Exception $e) {
    error_log($e->getMessage());
    $to_return['msg'] = $e->getMessage();
  }
  die(json_encode($to_return));
});

// Change subscription email message when shift to on hold
/*
add_filter('woocommerce_add_success', function ($message) {
  if ($message === 'Your subscription has been cancelled.') {
    $message = 'Your subscription has been paused.';
  }
  return $message;
}, 10, 1);
*/

// Ajax to shift subscription on hold
add_action('wp_ajax_shift_order_to_pause', function () {
  check_ajax_referer('radical_ajax_nonce', 'nonce');
  $subscription_id = $_POST['subscription_id'];
  $subscription = wcs_get_subscription($subscription_id);
  if ($subscription->get_customer_id() != get_current_user_id()) {
    exit(json_encode(['success' => false, 'error'=>'You don\'t have access to this subscription']));
  }
  wcs_get_subscription($subscription_id)->update_status('on-hold');
  exit(json_encode(['success' => true]));
});

// Show mark as skip in the email only
add_action('woocommerce_email_header', function () {
  add_action('woocommerce_order_item_meta_end', function ($item_id, $item, $order, $plain_text) {
    $skipable_items = get_post_meta($order->get_ID(), 'one_time_skippable_item', true);
    if ($skipable_items) {
      $skipable_items = json_decode($skipable_items, true);
    } else {
      $skipable_items = [];
    }
    if (array_search($item->get_product_id(), $skipable_items) !== false) {
      echo "  <b>(Mark as SKIP)</b>";
    }
  }, 10, 4);
});

add_action('woocommerce_before_edit_account_address_form', function() {
  if (isset($_GET['subscription'])) {
    $subscription_id = $_GET['subscription'];
    $order_by = get_post_meta($_GET['subscription'], 'gig_ordered_by', true);
    if ($order_by && preg_match('/\bbilling\b/i', $_SERVER['REQUEST_URI']) ) {
      ?>
      <style>
        #billing_email_field {
          display: none;
        }
        .woocommerce-address-fields p {
          width: 100%;
          display: inline-block;
        }
      </style>
      <?php
    }
    ?>
    <a class="link-underline link-underline_darker-gray mb-3 d-inline-block" href="<?php echo wc_get_endpoint_url('view-subscription', $subscription_id); ?>"> <i class="fa fa-arrow-left"></i> Subscription</a>
    <?php
  }
});

add_filter('woocommerce_subscription_status_name', function($status_name, $status) {
  if ($status == 'on-hold') {
    $status_name = 'Paused';
  }
  return $status_name;
}, 10, 2);

add_filter( 'woocommerce_add_to_cart_validation', function($passed, $product_id) {
  if (!isset($_GET['subscription_renewal_early']) || !isset($_GET['subscription_renewal']) || !$_GET['subscription_renewal']) {
    return $passed;
  }
  $sub_id = $_GET['subscription_renewal_early'];
  $skippable_items = get_post_meta($sub_id, 'one_time_skippable_item', true);
  if (!$skippable_items) {
    return $passed;
  }
  $skippable_items = json_decode($skippable_items, true);
  if (in_array($product_id, $skippable_items)) {
    $passed = false;
  }
  $global_skippable_products = get_field('subscription_global_skipped_products', 'option');
  if ($global_skippable_products) {
    $global_skippable_products = explode(',', $global_skippable_products);
    if (in_array($product_id, $global_skippable_products)) {
      $passed = false;
    }    
  }
  return $passed;
}, 10, 2 );  

add_action('woocommerce_customer_changed_subscription_to_cancelled', function ( $subscription ) {
  $subscription = ( ! is_object( $subscription ) ) ? wcs_get_subscription( $subscription ) : $subscription;
  $end_date = date('F j, Y', $subscription->get_time('end_date'));
  $note = "Customer cancelled subscription. Subscription pending cancellation status. You have until ($end_date) to reactive before subscription is permanently cancelled.";
  if (!isset($_POST['send_email_notification']) || $_POST['send_email_notification'] == 'false') {
    radical_disable_email_on_new_note($note);
  }
  $subscription->add_order_note($note, true, true);
}, 10);

add_action('woocommerce_customer_changed_subscription_to_active', function ( $subscription ) {
  $subscription = ( ! is_object( $subscription ) ) ? wcs_get_subscription( $subscription ) : $subscription;
  $note = 'Customer reactivated subscription.';
  if (!isset($_POST['send_email_notification']) || $_POST['send_email_notification'] == 'false') {
    radical_disable_email_on_new_note($note);
  }
  $subscription->add_order_note($note, true, true);
}, 10);

add_action('wp_ajax_add-product-to-subscription', function() {
  check_ajax_referer('radical_ajax_nonce', 'nonce');
  $subscription_id = isset($_POST['subscription_id']) ? $_POST['subscription_id'] : null;
  $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;
  $to_return = [
    'success' => false
  ];
  if (empty($subscription_id) || empty($product_id)) {
    $to_return['msg'] = "Please provide Subscription & Product Id";
    die(json_encode($to_return));
    return;
  }
  $subscription = wcs_get_subscription($subscription_id);
  if ($subscription->get_customer_id() != get_current_user_id()) {
    $to_return['error'] = 'You don\'t have access to this subscription';
    exit(json_encode($to_return));
  }
  $new_product = wc_get_product($product_id);
  $new_product_price = $new_product->get_price();
  $_wcsatt_schemes = get_post_meta( $product_id, '_wcsatt_schemes', true );
  foreach ($_wcsatt_schemes as $_wcsatt_scheme) {
    if ($_wcsatt_scheme['subscription_period_interval'] == $subscription->get_billing_interval() && $_wcsatt_scheme['subscription_period'] == $subscription->get_billing_period()) {
      if ($_wcsatt_scheme['subscription_regular_price']) {
        $new_product_price = $_wcsatt_scheme['subscription_regular_price'];
      }

      if ( $new_product->is_on_sale() && $_wcsatt_scheme['subscription_sale_price'])  {    
        $new_product_price = $_wcsatt_scheme['subscription_sale_price'];
      } 

      if ($_wcsatt_scheme['subscription_discount']) {
        $new_product_price = $new_product_price * ((100-$_wcsatt_scheme['subscription_discount']) / 100);
      }
    }
  }
  foreach ( $subscription->get_items() as $item_id => $item ) {
    $_product = $item->get_product();
    if (!$_product) {
      continue;
    }
    $sub_coupon_code = $subscription->get_coupon_codes();
    if ($_product->get_Id() == $product_id) {
      $single_item_price = $subscription->get_item_subtotal( $item, false, true );
      // this will prevent users from getting the same old price when the same product added again in subscription
      if ($new_product_price != $single_item_price) {
        continue;
      }
      $quantity = $item['qty'];
      $quantity += 1;
      $new_total = $quantity * $single_item_price;
      $item->set_quantity($quantity);
      $item->set_subtotal(($quantity * $single_item_price));
      $item->set_total($new_total);
      $item->save();
  
      $note = 'Updated product ' . $new_product->get_name() . ' quantity to ' .  $quantity . '.';
      radical_disable_email_on_new_note($note);
      $subscription->add_order_note($note, true, true);  
      $to_return['success'] = true;
    }
  }
  if (!$to_return['success']) {
    $subscription->add_product($new_product, 1, ['subtotal' => $new_product_price, 'total' => $new_product_price]);
    $note = $new_product->get_name().' has been added in the subscription.';
    radical_disable_email_on_new_note($note);
    $subscription->add_order_note($note, true, true);
  }

  if ($sub_coupon_code) {
    foreach($sub_coupon_code as $coupon_code) {
      $subscription->remove_coupon($coupon_code);
    }
    $subscription->calculate_totals( true );
  
    foreach($sub_coupon_code as $coupon_code) {
      $subscription->apply_coupon($coupon_code);
    }  
  }

  $subscription->calculate_totals( true );
  $subscription->save();
  $to_return['success'] = true;
  die(json_encode($to_return));
});

add_action('woocommerce_before_checkout_form', function() {
  global $woocommerce;
  $items = $woocommerce->cart->get_cart();
  $have_renewal_items = false;
  foreach ($items as $item) {
    if (isset($item['subscription_renewal'])) {
      $have_renewal_items = true;
    }
  }
  if (!$have_renewal_items) {
    return;
  }
  ?>
  <style>
  #cfw-coupons {
    display: none;
  }
  </style>
  <?php
});

function radical_disable_email_on_new_note($note) {
  add_filter('woocommerce_email_enabled_customer_note', function($is_enabled, $object, $instance) use ($note) {
    if (isset($instance->customer_note) && $instance->customer_note == $note) {
      $is_enabled = false;
    }
    return $is_enabled;
  }, 1, 3);
}

add_action('wp_ajax_radical_wc_update_subscription_frequency', function () {
  check_ajax_referer('radical_ajax_nonce', 'nonce');
  $res = ['success' => false];
  if (!isset($_POST['subscription_id']) || !isset($_POST['new_frequency'])) {
    $res['error'] = 'Subscription ID & New Frequency is required.';
    exit(json_encode($res));
  }
  $subscription_id = (int)$_POST['subscription_id'];
  $new_frequency = $_POST['new_frequency'];
  if ($new_frequency < 0 || $new_frequency > 2) {
    $res['error'] = 'Please provide valid new frequency.';
    exit(json_encode($res));
  }
  $subscription = wcs_get_subscription($subscription_id);
  if ($subscription->get_customer_id() != get_current_user_id()) {
    $res['error'] = 'You don\'t have access to this subscription';  
    exit(json_encode($res));
  }
  $new_frequency_text = $new_frequency == '1' ? 'every month' : 'every two months';

  $next_payment_date = date('Y-m-d', strtotime("+$new_frequency month")) . ' 00:00:00';
  $dates = [
    'next_payment' => $next_payment_date
  ];
  try {
    $subscription->update_dates($dates, 'gmt');
    update_post_meta($subscription_id, '_billing_interval', $new_frequency);
    wp_cache_delete($subscription_id, 'posts');
    $res['success'] = true;
  } catch (Exception $e) {
    error_log($e->getMessage());
    $res['msg'] = $e->getMessage();
    exit(json_encode($res));
  }
  $note = 'Next shipment date updated to ' . $next_payment_date . ' and Billing interval updated to ' . $new_frequency_text;
  if (isset($_POST['skip_change_frequency_send_email_notif']) && $_POST['skip_change_frequency_send_email_notif']) {
    radical_disable_email_on_new_note($note);
  }
  $subscription->add_order_note($note, true, true);
  exit(json_encode($res));
});

/*
 * Subscription - Change Next Order Date
 */
add_action('wp_ajax_radical_wc_update_subscription_new_order_date', function () {
  check_ajax_referer('radical_ajax_nonce', 'nonce');
  $res = [];
  if (!isset($_POST['subscription_id']) || !isset($_POST['next_order_date'])) {
    $res['error'] = 'Subscription ID & Next Order Date is required.';
    wp_die(json_encode($res));
  }
  $subscription_id = (int)$_POST['subscription_id'];
  $next_order_date = $_POST['next_order_date'];
  $subscription = wcs_get_subscription($subscription_id);
  if ($subscription->get_customer_id() != get_current_user_id()) {
    $res['error'] = 'You don\'t have access to this subscription';  
    wp_die(json_encode($res));
  }
  $next_payment_date = date('Y-m-d H:i:s', strtotime($next_order_date));
  $dates = [
    'next_payment' => $next_payment_date
  ];
  try {
    $subscription->update_dates($dates, wp_timezone_string());
    wp_cache_delete($subscription_id, 'posts');
    $res['success'] = true;
  } catch (Exception $e) {
    error_log($e->getMessage());
    $res['error'] = $e->getMessage();
    wp_die(json_encode($res));
  }
  $note = 'Next shipment date updated to ' . $next_payment_date;
  if (isset($_POST['skip_change_frequency_send_email_notif']) && $_POST['skip_change_frequency_send_email_notif']) {
    radical_disable_email_on_new_note($note);
  }
  $subscription->add_order_note($note, true, true);
  wp_die(json_encode($res));
});
