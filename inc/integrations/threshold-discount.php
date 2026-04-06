<?php

/*
* Black Friday Sitewide Discount
*/
add_action('woocommerce_cart_calculate_fees', function() {
	// if (is_admin() && !defined('DOING_AJAX')) {
	// 	return;
	// }
  $gigfiliate_settings = json_decode(get_option('gigfiliate_settings'));
  $threshold_discount = get_field('threshold_discount', 'option');
  if (!$threshold_discount || !$threshold_discount['enable_threshold']) {
    return;
  }
  $threshold_discount_excluded_product_ids = [];
  $only_select_certain_products = [];
  $total_cart_amount = 0;
  $total_discount_awarded = 0;

  if ($threshold_discount['excluded_product_ids'] && $threshold_discount['excluded_product_ids'] !== '') {
    $threshold_discount_excluded_product_ids = explode(',', $threshold_discount['excluded_product_ids']);
  }
  // Add Gigfilliate Excluded Product IDS (Activation & Related Activation Products)
  $threshold_discount_excluded_product_ids[] = $gigfiliate_settings->activation_product_id;
  if ($gigfiliate_settings->related_activation_products) {
    $explode_str = (strpos($gigfiliate_settings->related_activation_products, ', ') !== false) ? ', ' : ',';
    $related_activation_products = explode($explode_str, $gigfiliate_settings->related_activation_products);
    if ($related_activation_products && !empty($related_activation_products)) {
      foreach ($related_activation_products as $related_activation_product_id) {
        $threshold_discount_excluded_product_ids[] = $related_activation_product_id;
      }
    }
  }

  $selected_categories = $threshold_discount['selected_categories'];
  if ($selected_categories) {
    $args = [
      'limit' => -1,
      'category' => [],
    ];
    foreach ($selected_categories as $key => $selected_category) {
      $args['category'][] = $selected_category->slug;
    }
    $products = wc_get_products( $args );
    if ($products) {
      foreach ($products as $key => $product) {
        $only_select_certain_products[] = $product->get_id();
      }
    }
  }

  // Foreach Cart Item check if the Threshold Discount should be excluded from this product
  foreach( WC()->cart->get_cart() as $cart_item ) {
    $product_in_cart = $cart_item['product_id'];
    $price = (float)$cart_item['line_total'];
    if(in_array($product_in_cart, $threshold_discount_excluded_product_ids)){
      continue;
    }
    if(count($only_select_certain_products) && !in_array($product_in_cart, $only_select_certain_products)){
      continue;
    }
    $total_cart_amount += (float)$price;
  }
  if ($total_cart_amount >= 75 && $total_cart_amount <= 99) {
    $total_discount_awarded = 20;
  }
  if ($total_cart_amount >= 100 && $total_cart_amount <= 149) {
    $total_discount_awarded = 30;
  }
  if ($total_cart_amount >= 150) {
    $total_discount_awarded = 50;
  }
  if ($total_discount_awarded == 0) {
    return;
  }
  $cart_row_text = $threshold_discount['cart_row_text'];
  $applied_coupons = WC()->cart->get_applied_coupons();
  if ($applied_coupons && count($applied_coupons)) {
    $cart_row_text .= ' (After Coupon Discount)';
  }
  WC()->cart->add_fee($cart_row_text, -$total_discount_awarded);
});
