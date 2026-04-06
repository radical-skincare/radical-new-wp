<?php

/*
 * Orders Customer Order Coupon(s) Applied Column
 */
add_filter( 'manage_edit-shop_order_columns', function ( $columns ) {
  $new_columns = array();
  foreach ( $columns as $column_key => $column_label ) {
    if ( 'order_total' === $column_key ) {
      $new_columns['order_coupons'] = __('Coupon(s)', 'woocommerce');
    }
    if('order_date' == $column_key){
      $new_columns['shipping_name'] = __('Shipping Full Name', 'woocommerce');
    }
    $new_columns[$column_key] = $column_label;
  }
  return $new_columns;
});

/*
 * Orders Display Customer Order Coupon(s) Applied
 */
add_action( 'manage_shop_order_posts_custom_column' , function ( $column ) {
  global $the_order, $post;
  if ( $column  == 'order_coupons' ) {
    if ( $coupons = $the_order->get_coupon_codes() ) {
      echo implode(', ', $coupons);
    }
  }
  if ( $column  == 'shipping_name' ) {
    echo $the_order->get_shipping_first_name() . ' ' . $the_order->get_shipping_last_name();
  }
});

/*
 * Coupons Published Column
 */
add_filter( 'manage_edit-shop_coupon_columns', function ( $columns ) {
  $new_columns = array();
  foreach ( $columns as $column_key => $column_label ) {
    if ('expiry_date' === $column_key) {
      $new_columns['customer_email'] = __('Allowed emails', 'woocommerce');
      $new_columns['published'] = __('Published', 'woocommerce');
    }
    $new_columns[$column_key] = $column_label;
  }
  return $new_columns;
});

/*
 * Orders Display Customer Order Coupon(s) Applied
 */
add_action( 'manage_shop_coupon_posts_custom_column' , function ( $column ) {
  global $post;
  if ( $column == 'customer_email' ) {
    $customer_email = get_post_meta($post->ID, 'customer_email', true);
    echo ($customer_email && is_array($customer_email) ? implode(',', $customer_email) : '');
  } else if ( $column == 'published' ) {
    echo $post->post_date;
  }
});
