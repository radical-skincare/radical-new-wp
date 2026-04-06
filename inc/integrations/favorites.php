<?php

add_action('wp_ajax_add_product_to_favorites', function() {
  check_ajax_referer('radical_ajax_nonce', 'nonce');
  $product_id = $_POST['product_id'];
  $customer_id = get_current_user_id();
  $favorite_products = add_user_meta($customer_id, 'favorite_products', $product_id);
  return wp_send_json([
    'success' => $favorite_products,
  ]);
});

add_action('wp_ajax_remove_product_from_favorites', function() {
  check_ajax_referer('radical_ajax_nonce', 'nonce');
  $product_id = $_POST['product_id'];
  $customer_id = get_current_user_id();
  $favorite_products = delete_user_meta($customer_id, 'favorite_products', $product_id);
  return wp_send_json([
    'success' => $favorite_products,
  ]);
});

add_action('wp_ajax_favorite_products', function() {
  check_ajax_referer('radical_ajax_nonce', 'nonce');
  $customer_id = get_current_user_id();
  $favorite_products = get_user_meta($customer_id, 'favorite_products');
  $products = [];
  foreach ($favorite_products as $favorite_product) {
    $product = wc_get_product($favorite_product);
    if (!$product) {
      continue;
    }
    $new_product = [
      'id' => $favorite_product,
      'thumbnail_url' => wp_get_attachment_url($product->get_image_id()),
      'name' => $product->get_name(),
      'price' => $product->get_price(),
      'sku' => $product->get_sku(),
      'add_to_cart_url' => $product->add_to_cart_url(),
      'is_in_stock' => $product->is_in_stock(),
      'product_link' => $product->get_permalink(),
      'type' => $product->get_type(),
      'on_sale' => $product->is_on_sale(),
      'visibly_sold_out' => get_field('visibly_sold_out', $favorite_product)
    ];
    $products[] = $new_product;
  }
  return wp_send_json([
    'success' => true,
    'products' => $products,
  ]);
});