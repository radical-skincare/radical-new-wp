<?php

/*
* Black Friday Sitewide Discount
*/
add_action('woocommerce_cart_calculate_fees', function() {
	if (is_admin() && !defined('DOING_AJAX')) {
		return;
	}
  $gigfiliate_settings = json_decode(get_option('gigfiliate_settings'));
  $sitewide_discount = get_field('sitewide_discount', 'option');
  if (!$sitewide_discount['enable']) {
    return;
  }
  if ($sitewide_discount['enable_threshold'] && ((int)$sitewide_discount['threshold_order_subtotal'] > (int)WC()->cart->get_subtotal())) {
    return;
  }
  $percentage = (int)$sitewide_discount['percentage'];
  $percentage_fee = 0;
  $excluded_product_found = false;
  $sitewide_discount_excluded_product_ids = [];
  if ($sitewide_discount['excluded_product_ids'] && $sitewide_discount['excluded_product_ids'] !== '') {
    $sitewide_discount_excluded_product_ids = explode(',', $sitewide_discount['excluded_product_ids']);
  }
  // Add Gigfilliate Excluded Product IDS (Activation & Related Activation Products)
  $sitewide_discount_excluded_product_ids[] = $gigfiliate_settings->activation_product_id;
  $explode_str = (strpos($gigfiliate_settings->related_activation_products, ', ') !== false) ? ', ' : ',';
  $related_activation_products = explode($explode_str, $gigfiliate_settings->related_activation_products);
  if ($related_activation_products && !empty($related_activation_products)) {
    foreach ($related_activation_products as $related_activation_product_id) {
      $sitewide_discount_excluded_product_ids[] = $related_activation_product_id;
    }
  }
  // Foreach Cart Item check if the Sitewide Discount should be excluded from this product
  foreach( WC()->cart->get_cart() as $cart_item ) {
    $product_in_cart = $cart_item['product_id'];
    $quantity = $cart_item['quantity'];
    $price = (float)($cart_item['data']->get_price() * $quantity);
    if(!in_array($product_in_cart, $sitewide_discount_excluded_product_ids)){
      // Deduct Excluded Product Percentage Fee
      $newPrice = (float)(($price / 100) * $percentage);
      $percentage_fee += (float)$newPrice;
    } else {
      $excluded_product_found = true;
    }
  }
  $cart_row_text = '';
  $cart_row_text = $sitewide_discount['cart_row_text'] . ' (' . $sitewide_discount['percentage'] . '% OFF';
  if ($excluded_product_found) {
    $cart_row_text .= ' [Minus excluded sale products & categories]';
  }
  $cart_row_text .= ')';
	WC()->cart->add_fee($cart_row_text, -$percentage_fee);
});
