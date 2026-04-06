<?php

/*
 * Export All Affiliates
 */
add_filter('gigfiliate_admin_export_all_affiliates_colheaders', function($column_headers) {
  $column_headers[] = 'Last Customer Order Date';
  return $column_headers;
}, 10, 1);

add_filter('gigfiliate_admin_export_all_affiliates_row', function($affiliate_row, $user_id, $gig_settings) {
  $affiliate_id = get_user_meta($user_id, 'v_affiliate_id', true);
  if (!$affiliate_id) {
    $affiliate_row[] .= '';
    return $affiliate_row;
  }
  $ordered_date = '';
  $args = array(
    'post_type' => 'shop_order',
    'post_status' => array('wc-completed', 'wc-processing'),
    'posts_per_page' => 1,
    'meta_query' => array(
      'relation' => 'AND',
      array(
        'key' => 'v_order_affiliate_remote_order_id',
        'value' => '',
        'compare' => '!=',
      ),
      array(
        'key' => 'v_order_affiliate_id',
        'value' => $affiliate_id,
        'compare' => '=',
      ),
      array(
        'key' => 'v_order_affiliate_volume_type',
        'value' => 'CUSTOMER',
        'compare' => '=',
      )
    )
  );
  $orders = get_posts( $args );
  if ($orders && !empty($orders)) {
    $post = $orders[0];
    $order = new WC_Order( $post->ID );
    $ordered_date = $order->get_date_created();
    if ($ordered_date && $ordered_date !== '') {
      $affiliate_row[] .= wc_format_datetime($ordered_date);
    } else {
      $affiliate_row[] .= '';
    }
  } else {
    $affiliate_row[] .= '';
  }
  return $affiliate_row;
}, 10, 3);
