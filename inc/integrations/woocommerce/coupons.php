<?php

add_filter('woocommerce_apply_individual_use_coupon', function ($coupons_to_keep, $the_coupon, $applied_coupons) {
  global $woocommerce;
  foreach ($applied_coupons as $coupon_code) {
    $c = new WC_Coupon($coupon_code);
    if ($c->get_id() && get_post_meta($c->get_id(), 'work_with_individual', true)) {
      $coupons_to_keep[] = $coupon_code;
    }
  }
  return $coupons_to_keep;
}, 10, 3);

add_filter('woocommerce_apply_with_individual_use_coupon', function ($apply, $the_coupon, $individual_use_coupon, $applied_coupons) {
  if (!get_post_meta($the_coupon->get_id(), 'work_with_individual', true)) {
    return $apply;
  }
  global $woocommerce;
  $individual_coupons = [];
  foreach ($applied_coupons as $coupon_code) {
    $c = new WC_Coupon($coupon_code);
    if ($c->get_id() && get_post_meta($c->get_id(), 'individual_use', true) == 'yes') {
      $individual_coupons[] = $coupon_code;
    }
  }
  if (count($individual_coupons) >= count($applied_coupons)) {
    if (get_post_meta($the_coupon->get_id(), 'work_with_individual', true)) {
      $apply = true;
    }
  }
  return $apply;
}, 10, 4);

// handle_coupon_from_url
add_action('init', function () {
  // Check for the "applycoupon" query parameter
  if (isset($_GET['applycoupon']) && !empty($_GET['applycoupon'])) {
    $coupon_code = sanitize_text_field($_GET['applycoupon']); // Sanitize the input
    setcookie('wordpress_apply_coupon_code', $coupon_code, time() + 3600, '/'); // Set cookie for 1 hour
  }
});

// Add coupon to cart from cookie
add_action('woocommerce_before_cart', 'radical_apply_coupon_from_cookie');
add_action('woocommerce_before_checkout_form', 'radical_apply_coupon_from_cookie'); // Handle at checkout too
function radical_apply_coupon_from_cookie() {
  if (isset($_COOKIE['wordpress_apply_coupon_code'])) {
    $coupon_code = sanitize_text_field($_COOKIE['wordpress_apply_coupon_code']); // Retrieve and sanitize cookie value
    if (WC()->cart && !WC()->cart->has_discount($coupon_code)) {
      WC()->cart->apply_coupon($coupon_code); // Apply the coupon
      wc_add_notice(__('Coupon ' . $coupon_code . ' applied successfully!', 'woocommerce'), 'success'); // Optional success message
    }
    // Delete the cookie after applying the coupon
    setcookie('wordpress_apply_coupon_code', '', time() - 3600, '/'); // Expire the cookie
    unset($_COOKIE['wordpress_apply_coupon_code']); // Ensure it's removed from the global variable
  }
}

/*
 * Check Cart for Applied Coupons
 * Get the Allowed New Customer Coupons
 * If Customer is not a new customer then remove the coupon
 */
add_action('woocommerce_applied_coupon', function () {
  if (!is_user_logged_in()) {
    return;
  }
  // $coupon_code = class_exists('acf') ? get_field('free_gift_coupon_name', 'option') : 'newcustomer';
  $allowed_new_customer_only_coupons = get_field('allowed_new_customer_only_coupons', 'option');
  if (!$allowed_new_customer_only_coupons || $allowed_new_customer_only_coupons == '') {
    return;
  }
  $current_user_id = get_current_user_id();
  global $woocommerce;
  $all_applied_coupons = $woocommerce->cart->get_applied_coupons();
  $allowed_new_customer_only_coupons_ar = explode(',', $allowed_new_customer_only_coupons);
  // Then lookup if that order email address has been used before
  $args = [
    'numberposts' => 1,
    'meta_key' => '_customer_user',
    'meta_value' => $current_user_id,
    'post_type' => wc_get_order_types(),
    'post_status' => array_keys(wc_get_order_statuses()),
  ];
  $customer_has_orders = get_posts($args);
  // if no orders then exit, cause they are new customer
  if (!$customer_has_orders) {
    return;
  }
  foreach ($allowed_new_customer_only_coupons_ar as $coupon) {
    if (in_array($coupon, $all_applied_coupons)) {
      // If customer has ordered before then remove the coupon 'newcustomer'
      if ($customer_has_orders) {
        $woocommerce->cart->remove_coupon($coupon);
        wc_add_notice('Sorry, this coupon is only available to new customers.', 'error');      
      }
    }
  }
});

/*
 * if Cart contains Affiliate Required Enrollment Product then Remove all coupons from cart
 */
add_action('woocommerce_applied_coupon', function () {
  global $woocommerce;
  // cart contains empty, then exit
  if ($woocommerce->cart->cart_contents_count == 0) {
    return;
  }
  // if Cart contains Welcome Collection
  $cart_contains_required_affiliate_enrollment_product = false;
  foreach ($woocommerce->cart->get_cart() as $key => $val) {
    $_product = $val['data'];
    // live: 4239, local: 325
    if ($_product->id === 4239) { // TODO: use the vitalibis welcome product id
      $cart_contains_required_affiliate_enrollment_product = true;
    }
  }
  // does not contain required affiliate enrollment product then exit
  if (!$cart_contains_required_affiliate_enrollment_product) {
    return;
  }
  // then remove all coupons
  $woocommerce->cart->remove_coupons();
  return;
});

/**
 * If Coupon Excluded Products is in cart then return false else true
 **/
add_filter('woocommerce_coupon_is_valid', function ($true, $coupon, $that) {
  $coupon_excluded_products = get_field('coupon_excluded_products', 'option');
  if (!$coupon_excluded_products) {
    return $true;
  }
  if (!class_exists('WC')) {
    return $true;
  }
  foreach ($coupon_excluded_products as $product_id) {
    foreach (WC()->cart->get_cart() as $cart_item) {
      if ($cart_item['data']->get_id() == $product_id) {
        $true = false;
      }
    }
  }
  return $true;
}, 10, 3);

/**
 * If Coupon Excluded Products is in cart then show notice
 **/
add_filter('woocommerce_add_to_cart_validation', function ($passed, $product_id, $quantity, $variation_id = '', $variations = '') {
  $coupon_excluded_products = get_field('coupon_excluded_products', 'options');
  if (!$coupon_excluded_products) {
    return $passed;
  }
  $have_coupons = count(WC()->cart->get_coupons()) > 0;
  if ($have_coupons && in_array($product_id, $coupon_excluded_products)) {
    $passed = false;
    wc_add_notice('This Product is excluded from coupons. To add this product please remove the coupon code.', 'error');
  }
  if (!$have_coupons && in_array($product_id, $coupon_excluded_products)) {
    wc_add_notice('Coupon code will not work with this Product.');
  }
  return $passed;
}, 10, 5);

/**
 * If Coupon Excluded Products is in cart then show cart notice to apply coupon
 **/
add_action('woocommerce_before_calculate_totals', function () {
  $coupon_excluded_products = get_field('coupon_excluded_products', 'option');
  if (!$coupon_excluded_products) {
    return;
  }
  if (!count(WC()->cart->get_coupons())) {
    return;
  }
  $excluded_product = null;
  foreach ($coupon_excluded_products as $product_id) {
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
      if ($cart_item['data']->get_id() == $product_id) {
        if ($excluded_product) {
          $excluded_product .= ", ";
        }
        $excluded_product .= $cart_item['data']->get_title();
      }
    }
  }
  if ($excluded_product != null) {
    WC()->cart->remove_coupons();
    wc_add_notice('Please remove (' . $excluded_product . ') from cart to apply coupon.', 'error');
  }
}, 100);

/**
 * The following code will after "discount" line in order totals lines, displaying the applied coupons to the order:
 */
add_filter('woocommerce_get_order_item_totals', function ($total_rows, $order, $tax_display) {
  // Exit if there is no coupons applied
  if (sizeof($order->get_coupon_codes()) == 0) {
    return $total_rows;
  }
  $new_total_rows = []; // Initializing
  foreach ($total_rows as $key => $total) {
    $new_total_rows[$key] = $total;
    if ($key == 'discount') {
      $coupons_output = array();

      // Prefer coupon items attached to the order (contains discount/amount data)
      if (is_callable(array($order, 'get_items'))) {
        foreach ($order->get_items('coupon') as $coupon_item) {
          // Attempt several methods/keys to get the coupon code and amount for compatibility across WC versions
          $code = '';
          $amount = 0;
          if (is_callable(array($coupon_item, 'get_code'))) {
            $code = $coupon_item->get_code();
          } elseif (isset($coupon_item['code'])) {
            $code = $coupon_item['code'];
          }

          if (is_callable(array($coupon_item, 'get_discount'))) {
            $amount = $coupon_item->get_discount();
          } elseif (is_callable(array($coupon_item, 'get_amount'))) {
            $amount = $coupon_item->get_amount();
          } elseif (isset($coupon_item['discount'])) {
            $amount = $coupon_item['discount'];
          } elseif (isset($coupon_item['amount'])) {
            $amount = $coupon_item['amount'];
          }

          $amount = floatval($amount);
          // normalize to positive display value
          if ($amount < 0) {
            $amount = abs($amount);
          }

          if ($code) {
            $coupons_output[] = $code . ' (' . wc_price($amount, array('currency' => $order->get_currency())) . ')';
          }
        }
      }

      // Fallback: if no coupon items found, list applied coupon codes (without amounts)
      if (empty($coupons_output)) {
        $applied_coupons = $order->get_coupon_codes();
        if (!empty($applied_coupons)) {
          $coupons_output = $applied_coupons;
        }
      }

      if (!empty($coupons_output)) {
        $new_total_rows['coupon_codes'] = array(
          'label' => __('Applied coupons:', 'woocommerce'),
          'value' => implode(', ', $coupons_output),
        );
      }
    }
  }
  return $new_total_rows;
}, 10, 3);

// Fix for discount_renewal bug
add_action('wcs_after_early_renewal_setup_cart_subscription', function ($subscription) {
  $applied_coupons = WC()->cart->get_coupons();
  $order = wc_get_order($subscription->get_id());
  foreach ($applied_coupons as $applied_coupon) {
    WC()->cart->remove_coupon($applied_coupon);
  }
  foreach ($order->get_coupon_codes() as $old_coupons) {
    WC()->cart->apply_coupon($old_coupons);
  }
});
