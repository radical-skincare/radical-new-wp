<?php

/*
 * User has Active Subscription
 */
function radical_user_has_active_subscription($user_id = false) {
  if (!$user_id) {
    $user_id = get_current_user_id();
  }
  $most_recent_subscription_id = get_posts([
    'post_type' => 'shop_subscription',
    'numberposts' => 1,
    'meta_key' => '_customer_user',
    'meta_value' => $user_id,
    'post_status' => ['wc-active'], // Active subscription
    'fields' => 'ids', // return only IDs (instead of complete post objects)
  ]);
  return ($most_recent_subscription_id && count($most_recent_subscription_id) > 0);
}

/**
 * Checks if a user has an active WooCommerce subscription.
 *
 * @param int|string $identifier User ID or customer email address.
 * @param string $type Either 'user_id' or 'customer_email'.
 * @return bool True if the user has an active subscription, false otherwise.
 */
function radical_has_active_subscription($identifier, $type = 'user_id') {
  if ( ! class_exists( 'WC_Subscriptions' ) ) {
    return 'WooCommerce Subscriptions plugin is not active.';
  }
  // Determine what kind of lookup we're doing
  $args = array(
    'subscription_status'  => 'wc-active',
    'subscriptions_per_page' => 1,
    'return'  => 'ids',
    'orderby' => 'date',
    'order'   => 'DESC'
  );
  if ($type === 'user_id') {
    $args['customer_id'] = $identifier;
  } else if ($type === 'customer_email') {
    // For email, we need to find the user_id first
    $user = get_user_by('email', $identifier);
    if (!$user) {
      return false; // No user found with this email
    }
    $args['customer_id'] = $user->ID;
  } else {
    return false; // Invalid type specified
  }
  // Retrieve the subscriptions
  $subscriptions = wcs_get_subscriptions($args);
  // If there are any subscriptions, return true, otherwise false
  return ($subscriptions && count($subscriptions) > 0);
}

/*
 * Checkout Cart Discount
 * If has Active Subscription and Products in Serums Category then award discount
add_action('woocommerce_cart_calculate_fees', function ($cart) {
  if (is_admin() && !defined('DOING_AJAX')) {
    return;
  }
  $active_subscriber_discounts = get_field('active_subscriber_discounts', 'option');
  $exit = true;
  $serums_discount_active = (isset($active_subscriber_discounts['serums_discount']) && $active_subscriber_discounts['serums_discount']);
  $sales_discount_active = (isset($active_subscriber_discounts['sale_category_discount']) && $active_subscriber_discounts['sale_category_discount']);
  if ($serums_discount_active) {
    $exit = false;
  } else if ($sales_discount_active) {
    $exit = false;
  }
  if ($exit) {
    return;
  }
  if (!is_user_logged_in()) {
    return;
  }
  $check_this_user_id = false;
  // if order for customer mode
  $bp_order_for_customer_cookie = apply_filters('gigfiliate-order-for-customer-cookie-name', 'wordpress_gigfiliate_placing_order_for_customer');
  $bp_order_for_customer_email = isset($_COOKIE[$bp_order_for_customer_cookie]) ? $_COOKIE[$bp_order_for_customer_cookie] : false;
  if ($bp_order_for_customer_email) {
    // then if current user does not have active subscription
    $customer = get_user_by('email', $bp_order_for_customer_email);
    $check_this_user_id = $customer ? (int)$customer->ID : false;
  } else {
    $check_this_user_id = get_current_user_id();
  }
  $exit = true;
  if (radical_user_has_active_subscription($check_this_user_id)) {
    $exit = false;
  }
  if ($bp_order_for_customer_email && radical_has_active_subscription($bp_order_for_customer_email, 'customer_email')) {
    $exit = false;
  }
  if ($exit) {
    return;
  }
  // Check if the cart has any products
  if (empty($cart->cart_contents)) {
    return;
  }
  $promo_discount = 0.15; // 15% discount
  if (isset($active_subscriber_discounts['serums_discount']) && $active_subscriber_discounts['serums_discount']) {
    // $promo_discount = 0.15; // 15% discount
    $serums_promo_discount = get_field('serums_promo_discount', 'option');
    $promo_discount = $serums_promo_discount ? $serums_promo_discount : $promo_discount;
  } else if (isset($active_subscriber_discounts['sale_category_discount']) && $active_subscriber_discounts['sale_category_discount']) {
    $promo_discount = 0.20; // 20% discount
  }
  $discount_amount = 0;
  $has_sale_product = false;
  foreach ($cart->cart_contents as $cart_item_key => $cart_item) {
    $is_sale_item = false;
    // Check if the product belongs to the 'serums' category
    if ($serums_discount_active && has_term('serums', 'product_cat', $cart_item['product_id'])) {
      $is_sale_item = true;
    } else if ($sales_discount_active && has_term('sale', 'product_cat', $cart_item['product_id'])) {
      $is_sale_item = true;
    }
    if ($is_sale_item) {
      $has_sale_product = true;
      $discount = $cart_item['line_subtotal'] * $promo_discount;
      $discount_amount += $discount;
      // Apply the discount directly to the line item
      $cart->cart_contents[$cart_item_key]['line_subtotal'] -= $discount;
      $cart->cart_contents[$cart_item_key]['line_total'] -= $discount;
    }
  }
  $cart_text = 'Active Subscriber Serums Discount';
  if ($sales_discount_active) {
    $cart_text = 'Active Subscriber Sale 20% Discount';
  }
  // If there are serums in the cart, add a fee to reflect the discount
  if ($has_sale_product) {
    $cart->add_fee(__($cart_text, 'woocommerce'), -$discount_amount, true, '');
  }
}, 10, 1);
 */

/*
 * Checkout Cart Discount
 * If has Active Subscription and Specific Products Discount setting true
 * Then Calculate Cart Discounts
 */
add_action('woocommerce_cart_calculate_fees', function ($cart) {
  if (is_admin() && !defined('DOING_AJAX')) {
    return;
  }
  if (!is_user_logged_in()) {
    return;
  }
  $active_subscriber_discounts = get_field('active_subscriber_discounts', 'option');
  $exit = true;
  $specific_products_discount_active = (isset($active_subscriber_discounts['specific_products_discount']) && $active_subscriber_discounts['specific_products_discount']);
  if ($specific_products_discount_active) {
    $exit = false;
  }
  if ($exit) {
    return;
  }
  $check_this_user_id = false;
  // if order for customer mode
  $bp_order_for_customer_cookie = apply_filters('gigfiliate-order-for-customer-cookie-name', 'wordpress_gigfiliate_placing_order_for_customer');
  $bp_order_for_customer_email = isset($_COOKIE[$bp_order_for_customer_cookie]) ? $_COOKIE[$bp_order_for_customer_cookie] : false;
  if ($bp_order_for_customer_email) {
    // then if current user does not have active subscription
    $customer = get_user_by('email', $bp_order_for_customer_email);
    $check_this_user_id = $customer ? (int)$customer->ID : false;
  } else {
    $check_this_user_id = get_current_user_id();
  }
  $exit = true;
  // Does User have Active Subscription
  if (radical_user_has_active_subscription($check_this_user_id)) {
    $exit = false;
  }
  if ($bp_order_for_customer_email && radical_has_active_subscription($bp_order_for_customer_email, 'customer_email')) {
    $exit = false;
  }
  if ($exit) {
    return;
  }
  // Check if the cart has any products
  if (empty($cart->cart_contents)) {
    return;
  }
  $promo_discount = 0.25; // 25% discount
  $discount_amount = 0;
  $has_sale_product = false;
  // $sale_product_ids = [21588]; // test
  $sale_product_ids = [2488,2483,17]; // live
  foreach ($cart->cart_contents as $cart_item_key => $cart_item) {
    $is_sale_item = false;
    if (in_array($cart_item['product_id'], $sale_product_ids)) {
      $is_sale_item = true;
    }
    if ($is_sale_item) {
      $has_sale_product = true;
      $discount = $cart_item['line_subtotal'] * $promo_discount;
      $discount_amount += $discount;
      // Apply the discount directly to the line item
      $cart->cart_contents[$cart_item_key]['line_subtotal'] -= $discount;
      $cart->cart_contents[$cart_item_key]['line_total'] -= $discount;
    }
  }
  // $cart_text = 'Active Subscriber Discount';
  $cart_text = 'VIP Discount (25% OFF Specific Products)';
  // If there are specific products ($sale_product_ids) in the cart, add a fee to reflect the cart discount
  if ($has_sale_product) {
    $cart->add_fee(__($cart_text, 'woocommerce'), -$discount_amount, true, '');
  }
}, 10, 1);

// Free Shipping for Active Subscribers
/*
add_filter('woocommerce_package_rates', function ($rates, $package) {
  // Check if the user is logged in
  if (!is_user_logged_in()) {
    return $rates;
  }
  // Get the current user ID
  $user_id = get_current_user_id();
  // if order for customer mode
  $bp_order_for_customer_cookie = apply_filters('gigfiliate-order-for-customer-cookie-name', 'wordpress_gigfiliate_placing_order_for_customer');
  $bp_order_for_customer_email = isset($_COOKIE[$bp_order_for_customer_cookie]) ? $_COOKIE[$bp_order_for_customer_cookie] : false;
  $exit = true;
  if ($bp_order_for_customer_email) {
    if (radical_has_active_subscription($bp_order_for_customer_email, 'customer_email')) {
      $exit = false;
    }
  } else if (radical_user_has_active_subscription($user_id)) {
    $exit = false;
  }
  if ($exit) {
    return $rates;
  }
  // Create a new rate for free shipping
  $free_shipping = new WC_Shipping_Rate(
    'free_shipping_vip', // ID for the rate
    'Free Shipping VIP',        // Label for the customer
    0,                      // Cost, 0 for free shipping
    array(),                // Meta data
    'free_shipping'         // Method ID
  );
  // Remove all existing rates and add only the free shipping rate
  $rates = array('free_shipping_vip' => $free_shipping);
  return $rates;
}, 10, 2);
*/

function radical_can_access_vip_product_product_cat($post_id) {
  $restrict_enabled = get_field('restrict_vip_product_category_products_active_subscribers', 'option');
  if (!$restrict_enabled) {
    return true;
  }
  // is vip_product category product?
  $is_vip_product = false;
  $terms = get_the_terms($post_id, 'product_cat');
  if ($terms && !is_wp_error($terms)) {
    foreach ($terms as $term) {
      if ($term->slug === 'vip_product') {
        $is_vip_product = true;
        break;
      }
    }
  }
  if (!$is_vip_product) {
    return true;
  }
  // check if logged in user has active subscription
  if (!is_user_logged_in()) {
    return false;
  }
  $current_user_id = get_current_user_id();
  if (radical_user_has_active_subscription($current_user_id)) {
    return true;
  }
  return false;
}

// VIP Active Subscriber - Discount Second Product in Category
/*
add_action('woocommerce_cart_calculate_fees', function ($cart) {
  if (is_admin() && !defined('DOING_AJAX')) return;
  // Check if user is logged in and has an active subscription
  if (!is_user_logged_in() || !wcs_user_has_subscription(get_current_user_id(), '', 'active')) {
    return;
  }
  $target_category = 'serums';
  foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
    $product = $cart_item['data'];
    // Check if product is in the target category
    if (has_term($target_category, 'product_cat', $product->get_id())) {
      // Check if quantity is exactly 2
      if ($cart_item['quantity'] >= 2) {
        $discount = $product->get_price() * 0.30; // 30% of one unit
        $product_name = $product->get_name();
        $cart->add_fee("30% Off 2nd $product_name (VIP Subscriber)", -$discount);
      }
    }
  }
}, 20, 1);
*/

/**
 * Automatically applies a discount coupon if the cart contains a subscription product
 * and the user has an active subscription.
 *
 * Conditions:
 * - Feature must be enabled via ACF option 'subscription_discount_enable'
 * - Discount code is pulled from ACF option 'subscription_discount_code'
 * - User must HAVE an active subscription
 * - Cart must include at least one subscription product (detected via 'wcsatt_data')
 * - Optional: Cart subtotal must meet or exceed $100 minimum threshold
 *
 * The coupon is automatically applied or removed based on these conditions during:
 * - Cart view
 * - Checkout billing step
 * - Custom summary hook (cfw_checkout_cart_summary)
 */
/*
function apply_subscription_discount_coupon() {
  // error_log('apply_subscription_discount_coupon');
  if (!get_field('subscription_discount_enable', 'option')) {
    return;
  }
  if (!get_field('subscription_discount_code', 'option')) {
    return;
  }
  $discount_code = get_field('subscription_discount_code', 'option'); // Change this to your actual coupon code
  $has_active_subscription = false;
  $cart_subtotal = WC()->cart->subtotal; // Get the cart subtotal
  $min_cart_amount = 100; // Minimum amount required to apply the discount
  $has_subscription_product = false;
  $valid_for_cart = true;
  if (is_user_logged_in()) {
    $user_id = get_current_user_id();
    $subscriptions = wcs_get_users_subscriptions($user_id);
    foreach ($subscriptions as $subscription) {
      if ($subscription->has_status('active') && $cart_subtotal >= $min_cart_amount) {
        $has_active_subscription = true;
        break;
      }
    }
  }
  foreach (WC()->cart->get_cart() as $cart_item) {
    if (isset($cart_item['wcsatt_data']) && $cart_item['wcsatt_data']) {
      $has_subscription_product = true;
      break;
    }
  }
  if (!$has_subscription_product) {
    $valid_for_cart = false;
  }
  if (!$has_active_subscription) {
    $valid_for_cart = false;
  }
  if (!$valid_for_cart) {
    if (WC()->cart->has_discount($discount_code)) {
      WC()->cart->remove_coupon($discount_code);
      wc_print_notices();
    }
    return;
  }
  if (!WC()->cart->has_discount($discount_code)) {
    WC()->cart->apply_coupon($discount_code);
    wc_print_notices();
  }
}
add_action('woocommerce_before_cart', 'apply_subscription_discount_coupon');
add_action('woocommerce_before_checkout_billing_form', 'apply_subscription_discount_coupon');
add_action('cfw_checkout_cart_summary', 'apply_subscription_discount_coupon');
*/
