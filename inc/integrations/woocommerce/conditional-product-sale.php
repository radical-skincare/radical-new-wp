<?php

/*
 * Alter Product Pricing
 * 
 * If Cart amount is >= $75, then discount the travel kit down to $75
 * 
 * Travel Kit ID: 21694
 */

function radical_should_alter_product_price($product_id) {
  $product_is_in_cart = false;
  foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
    if ($product_id == $cart_item['data']->get_id()) {
      $product_is_in_cart = true;
    }
  }
  $cart_total = $product_is_in_cart ? (float)WC()->cart->total - 99: (float)WC()->cart->total;
  return ($cart_total >= 75) ? true : false;
}

/**
 * Alter Product Pricing Part 1 - WooCommerce Product
 */
add_filter( 'woocommerce_get_price_html', function( $price_html, $product ) {
  // If admin exit
  if (is_admin()) {
    return $price_html;
  }
  // If Price not NULL exit
  if ($product->get_price() === '') {
    return $price_html;
  }
  $settings = get_field('conditional_product_sale', 'option');
  if (!isset($settings['enable']) || !$settings['enable']) {
    return $price_html;
  }
  // If NOT Travel Kit then exit
  $product_id = $settings['product'];
  if ($product_id !== $product->get_id()) {
    return $price_html;    
  }
  // If Cart total >= $75 then apply discount
  if (radical_should_alter_product_price($product_id)) {
    $price_html = wc_price(75);
  }
  return $price_html;
}, 9999, 2 );

/**
 * Alter Product Pricing Part 2 - WooCommerce Cart/Checkout
 */
// woocommerce_after_calculate_totals - works on cart but doesnt seem to run at checkout for some reason
add_action( 'woocommerce_after_calculate_totals', function ( $cart ) {
  if (is_admin() && !defined('DOING_AJAX')) {
    return;
  }
  $settings = get_field('conditional_product_sale', 'option');
  if (!isset($settings['enable']) || !$settings['enable']) {
    return;
  }
  /*
   * Some other plugins that alter prices *may* hook into woocommerce_after_calculate_totals
  if (did_action('woocommerce_after_calculate_totals') >= 2 ) {
    return;
  }
  */
  // If Cart total >= $75 then apply discount
  if (((float)$cart->total - 99) >= 75) {
    $product_id = $settings['product'];
    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
      if ($product_id == $cart_item['data']->get_id()) {
        $cart_item['data']->set_price(75);
      }
    }
  }
}, 9999 );

add_action('woocommerce_cart_calculate_fees', function() {
  if (is_admin() && !defined('DOING_AJAX')) {
    return;
  }
  $settings = get_field('conditional_product_sale', 'option');
  if (!isset($settings['enable']) || !$settings['enable']) {
    return;
  }
  $product_quantity_in_cart = 0;
  // If Cart total >= $75 then apply discount
  if (((float)WC()->cart->get_subtotal() - 99) >= 75) {
    $product_id = $settings['product'];
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
      if ($product_id == $cart_item['data']->get_id()) {
        $product_quantity_in_cart = $cart_item['quantity'];
      }
    }
  }
  if ($product_quantity_in_cart) {
    $discount = $product_quantity_in_cart * 24;
    WC()->cart->add_fee('Discounted Travel Kit', -$discount);
  }
});
