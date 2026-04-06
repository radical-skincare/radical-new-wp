<?php

/*
 * Extend API - Register Rest Fields
 */
add_action( 'rest_api_init', function () {
  // this function allows you to extend an existing WP endpoint
  register_rest_field( 'press_item', 'radical_skincare_additional_meta', [
      'get_callback'    => 'radical_skincare_additional_press_item_data',
      'update_callback' => null,
      'schema'          => null,
    ]
  );
  register_rest_field( 'story', 'radical_skincare_additional_meta', [
      'get_callback'    => 'radical_skincare_additional_story_data',
      'update_callback' => null,
      'schema'          => null,
    ]
  );
  register_rest_field( 'product', 'radical_skincare_additional_meta', [
      'get_callback'    => 'radical_skincare_additional_product_data',
      'update_callback' => null,
      'schema'          => null,
    ]
  );
});

function radical_skincare_additional_press_item_data($arr, $field_name, $request) {
  $array_data = [];
  // featured image
  $array_data['thumbnail'] = get_the_post_thumbnail_url( $arr['id'], 'full' );
  return $array_data;
}

function radical_skincare_additional_story_data($arr, $field_name, $request) {
  $array_data = [];
  // featured image
  $array_data['thumbnail'] = get_the_post_thumbnail_url( $arr['id'], 'full' );
  if ( class_exists('acf') ) {
    $array_data['youtube_video_id'] = get_field( 'youtube_video_id',  $arr['id']);
  }
  return $array_data;
}

function radical_skincare_additional_product_data($arr, $field_name, $request) {
  $array_data = [];
  $array_data['thumbnail'] = get_the_post_thumbnail_url( $arr['id'], 'full' );
  $array_data['_wcsatt_schemes'] = get_post_meta( $arr['id'], '_wcsatt_schemes', true );
  $array_data['cbd_product'] = in_array(get_the_ID(), get_field('cbd_products', 'option'));
  return $array_data;
}
 
/*
 * Register API Routes
 */
add_action( 'rest_api_init', function () {
  $version = '1';
  $namespace = 'userservices/v' . $version;
  // register_rest_route() handles more arguments
  /*
  register_rest_route( $namespace, '/get_username_by_email', array(
      'methods'  => 'POST',
      // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
      'callback' => 'radical_skincare_get_username_by_email',
      'args' => array( 
      ),
  ) );
  */
  $version = '1';
  $namespace = 'affservices/v' . $version;
  // register_rest_route() handles more arguments
  register_rest_route( $namespace, '/ambassadors', array(
    'methods'  => 'GET',
    // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
    'callback' => 'radical_skincare_ambassadors',
    // 'args' => array( 
    // ),
  ));
});

/*
 * Get Username By Email
 */
function radical_skincare_get_username_by_email( WP_REST_Request $request ) {
  $parameters = $request->get_params();
  $email = $parameters['email'];
  global $wpdb;
  $table = $wpdb->prefix . "users";
  $sql = $wpdb->prepare("SELECT ID, user_login FROM $table WHERE user_email = %s", $email);
  $results = $wpdb->get_results($sql);
  if ( ! empty($results) ) {
    return new WP_REST_Response( $results[0], 200 );
  }
  // else email does not exist in database
  return new WP_REST_Response( false, 200 );
}

/*
 * Return Active Ambassadors
 */
function radical_skincare_ambassadors( WP_REST_Request $request ) {
  $ambassadors = array();
  $general_settings = json_decode( get_option( 'brand_partner_setings' ) );
  $permanent_ambassador_user_ids = explode(", ", $general_settings->permanent_ambassador_user_ids);
  if ( ! empty($permanent_ambassador_user_ids) ) {
    foreach($permanent_ambassador_user_ids as $this_user_id) {
      $affiliate_id = get_Affiliate_ID_By_User_ID ( (int)$this_user_id );
      $user_info = get_userdata( $this_user_id );
      // Add Permament Ambassadors to endpoint
      $ambassadors[] = array(
        'affiliate_id' => (int)$affiliate_id,
        'user_id' => (int)$this_user_id,
        'profile_pic_url' => get_avatar_url( $this_user_id ),
        'name' => $user_info->display_name,
        'aff_site' => get_field( 'aff_site', 'user_' . $this_user_id),
        'city' => get_field( 'city', 'user_' . $this_user_id),
        'state' => get_field( 'state', 'user_' . $this_user_id),
        'zip' => get_field( 'zip_code', 'user_' . $this_user_id),
      );
    }
  }
  global $wpdb;
  $table = $wpdb->prefix . "affiliate_wp_affiliates";
  $sql = "SELECT affiliate_id, user_id FROM $table WHERE status = 'active'";
  $results = $wpdb->get_results($sql);
  foreach( $results as $this_ambassador ) {
    if ( is_Brand_Partner_Active ( $this_ambassador->user_id ) ) {
      $user_info = get_userdata( $this_ambassador->user_id );
      // Add Active Ambassadors to endpoint
      $ambassadors[] = array(
        'affiliate_id' => (int)$this_ambassador->affiliate_id,
        'user_id' => (int)$this_ambassador->user_id,
        'profile_pic_url' => get_avatar_url( $this_user_id ),
        'name' => $user_info->display_name,
        'aff_site' => get_field( 'aff_site', 'user_' . $this_user_id),
        'city' => get_field( 'city', 'user_' . $this_ambassador->user_id),
        'state' => get_field( 'state', 'user_' . $this_ambassador->user_id),
        'zip' => get_field( 'zip_code', 'user_' . $this_ambassador->user_id),
      );
    }
  }
  return $ambassadors;
}