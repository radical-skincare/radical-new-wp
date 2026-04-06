<?php

function radical_check_is_new_customer_by_recent_order( $customer_email ) {
  /*
  $args = array(
    // 'limit' => -1,
    'limit' => 2,
    'orderby' => 'date',
    'order' => 'DESC',
    'status' => array('wc-processing', 'wc-completed'),
    'customer_email' => $customer_email,
    'exclude' => array( $order_id ),
    'return' => 'ids',
  );
  $meta_query = array(
    'relation' => 'AND',
    array(
      'key' => '_billing_email',
      'value' => $customer_email,
      'compare' => '=',
    ),
  );
  $customer_id = $order->get_customer_id();
  if ($customer_id) {
    $args['customer_id'] = $customer_id;
    $meta_query[] = array(
      'key' => '_customer_user',
      'value' => $customer_id,
      'compare' => '=',
    );
  }
  $args['customer_id'] = $meta_query;
  $customer_orders = wc_get_orders( $args );
  return empty( $customer_orders );
  */
  global $wpdb;
  $table = $wpdb->prefix . 'postmeta';
  $sql = "SELECT post_id FROM $table WHERE meta_key = '_billing_email' AND meta_value = '$customer_email'";
  $post_ids = $wpdb->get_results($sql);
  if (empty($post_ids)) {
    return true;
  }
  $valid_order_count = 0;
  foreach ($post_ids as $post_id) {
    $order = wc_get_order( (int)$post_id->post_id );
    $order_status  = $order->get_status();
    if ($order_status === 'processing' || $order_status === 'completed') {
      $valid_order_count++;
    }
    if ($valid_order_count > 1) {
      return false;
    }
  }
  return true;
}

add_action('gigfiliate_orders_after_subscription_relationship_column_head', 'radical_gigfiliate_orders_after_subscription_relationship_column_head');
add_action('gigfiliate_affiliate_orders_after_subscription_relationship_column_head', 'radical_gigfiliate_orders_after_subscription_relationship_column_head');
function radical_gigfiliate_orders_after_subscription_relationship_column_head() {
  ?>
  <th scope="col" class="manage-column column-new-customer">New Customer</th>
  <?php
}

/*
 * Radical is New Customer Email Array
 *
 * Store new customers in array so we don't have to fetch/calculate for subsequent orders
 */
$radical_is_new_customer_email_array = array();
$radical_is_not_new_customer_email_array = array();

add_action('gigfiliate_orders_after_subscription_relationship_column_body', 'radical_gigfiliate_orders_after_subscription_relationship_column_body');
add_action('gigfiliate_affiliate_orders_after_subscription_relationship_column_body', 'radical_gigfiliate_orders_after_subscription_relationship_column_body');
function radical_gigfiliate_orders_after_subscription_relationship_column_body($order_id) {
  global $radical_is_new_customer_email_array;
  global $radical_is_not_new_customer_email_array;
  $order = wc_get_order( $order_id );
  $customer_email = $order->get_billing_email();
  echo '<td>';
    if (!empty($radical_is_new_customer_email_array) && in_array($customer_email, $radical_is_new_customer_email_array)) {
      echo 'Yes';
    } if (!empty($radical_is_not_new_customer_email_array) && in_array($customer_email, $radical_is_not_new_customer_email_array)) {
      echo 'No';
    } else {
      $is_new_customer = radical_check_is_new_customer_by_recent_order( $customer_email );
      if ($is_new_customer) {
        $radical_is_new_customer_email_array[] = $customer_email;
        echo 'Yes';
      } else {
        $radical_is_not_new_customer_email_array[] = $customer_email;
        echo 'No';
      }
    }
  echo '</td>';
}

add_filter('gigfiliate_get_order_details', function($order_details , $order, $order_id) {
  $_wc_shipment_tracking_items = get_post_meta($order_id, '_wc_shipment_tracking_items', true);
  error_log(json_encode($_wc_shipment_tracking_items));
  if (!$_wc_shipment_tracking_items) {
    return $order_details;
  }
  $tracking_number = $_wc_shipment_tracking_items[0]['tracking_number'];
  $tracking_provider = $_wc_shipment_tracking_items[0]['tracking_provider'];
  $tracking_link = 'https://tools.usps.com/go/TrackConfirmAction?tRef=fullpage&tLc=2&text28777=&tLabels=' . $tracking_number . '&tABt=false';
  if ($tracking_provider == 'ups') {
    $tracking_link = 'https://www.ups.com/track?loc=null&tracknum=' . $tracking_number . '&requester=WT/trackdetails';
  }
  $order_details['customer']['tracking'] = [
    'ship_at' => $_wc_shipment_tracking_items[0]['date_shipped'],
    'shipping_provider' => $tracking_provider,
    'tracking_number' => $tracking_number,
    'tracking_link' => $tracking_link
  ];
  return $order_details;
}, 10, 3);

add_filter('gigfiliate_customers_product_item_in_stock', function($is_in_stock , $product_id) {
  $visibly_sold_out = get_field('visibly_sold_out', $product_id);
  if ($visibly_sold_out) {
    return false;
  }
  return $is_in_stock;
}, 10, 2);

/*
 * Gigfiliate Customers - Item Name Above Meta filter
 */
add_filter('gig_customer_item_name_above_meta', function($text, $customer_email) {
  $user = get_user_by('email', $customer_email);
  if ($user && function_exists('radical_user_has_active_subscription')) {
    // $has_active_subscriptions = get_user_meta($user->ID, 'has_active_subscriptions', true);
    $has_active_subscriptions = radical_user_has_active_subscription($user->ID);
    if (!$has_active_subscriptions) {
      $has_active_subscriptions = radical_has_active_subscription($customer_email, 'customer_email');
    }
    if (!$has_active_subscriptions) {
      $most_recent_subscription_id = get_posts([
        'post_type' => 'shop_subscription',
        'numberposts' => 1,
        'meta_key' => '_billing_email',
        'meta_value' => $customer_email,
        'post_status' => ['wc-active'], // Active subscription
        'fields' => 'ids', // return only IDs (instead of complete post objects)
      ]);
      $has_active_subscriptions = ($most_recent_subscription_id && count($most_recent_subscription_id) > 0);
    }
    if ($has_active_subscriptions) {
      $text = '<span class="badge badge-info gig-radical-on-repeat-badge" style="display: block; width: fit-content;"><i class="fa fa-refresh"></i> Has Subscription</span>';
    }
  }
  return $text;
}, 10, 2);

add_filter('gigfiliate_customer_get_products_query', function($query) {
  $bp_order_for_customer_cookie = apply_filters('gigfiliate-order-for-customer-cookie-name', 'wordpress_gigfiliate_placing_order_for_customer');
  $bp_order_for_customer_email = isset($_COOKIE[$bp_order_for_customer_cookie]) ? $_COOKIE[$bp_order_for_customer_cookie] : false;
  if (!$bp_order_for_customer_email) {
    return $query;
  }
  if (!get_field('restrict_valentines_category_products_active_subscribers', 'option')) {
    return $query;
  }
  if (radical_has_active_subscription($bp_order_for_customer_email, 'customer_email')) {
    return $query;
  }
  $tax_query = [
    'taxonomy' => 'product_cat',
    'field'    => 'slug',
    'terms'    => ['valentines'],
    'operator' => 'NOT IN',
  ];
  if (!$query['tax_query'] || !count($query['tax_query'])) {
    $query['tax_query'] = [];
  }
  $query['tax_query']['relation'] = "AND";
  $query['tax_query'][] = $tax_query;
  return $query;
});

add_action('woocommerce_thankyou', function($order_id) {
  $v_order_affiliate_id = get_post_meta($order_id, 'v_order_affiliate_id', true);
  if ($v_order_affiliate_id) {
    update_post_meta($order_id, '_wc_order_attribution_source_type', 'referral');
    update_post_meta($order_id, '_wc_order_attribution_utm_medium', 'website');
    update_post_meta($order_id, '_wc_order_attribution_utm_source', 'Brand Partner');
  }
}, 100, 1);

/*
 * Check Preferred Name / Nickname Uniqueness
 */
add_action( 'wp_ajax_radical_ajax_create_unique_nickname', 'radical_ajax_create_unique_nickname' );
add_action( 'wp_ajax_nopriv_radical_ajax_create_unique_nickname', 'radical_ajax_create_unique_nickname' );

function radical_ajax_create_unique_nickname() {
  $res = ['success' => false];
  if (! isset($_POST['action']) || $_POST['action'] !== 'radical_ajax_create_unique_nickname') {
    exit( json_encode($res) );
  }
  if (!isset($_POST['first_name']) || !isset($_POST['last_name'])) {
    $res['msg'] = 'Both first name and last name are required';
    exit( json_encode($res) );
  }
  $preferred_name = isset($_POST['preferred_name']) && $_POST['preferred_name'] && $_POST['preferred_name'] !== '' ? $_POST['preferred_name'] : false;
  $res['new_nickname_response'] = radical_create_unique_nickname($_POST['first_name'], $_POST['last_name'], $preferred_name);
  $res['success'] = true;
  exit( json_encode($res) );
}

function radical_create_unique_nickname($first_name, $last_name, $preferred_name = false) {
  $res = ['is_unique' => true];
  $nickname = strtolower($first_name) . strtolower($last_name);
  if ($preferred_name) {
    $nickname = strtolower($preferred_name);
  }
  $nickname = $original_nickname = preg_replace('/[^a-zA-Z0-9]/', '', $nickname);
  $i = 2;
  while (radical_does_nickname_already_exist( $nickname )) {
    $res['is_unique'] = false;
    $res['increment'] = $i;
    $nickname = $original_nickname . $i;
    $i++;
  }
  $res['nickname'] = $nickname;
  return $res;
}

function radical_does_nickname_already_exist( $nickname ) {
  global $wpdb;
  $results = $wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key = 'nickname' AND meta_value = '$nickname'");
  return !empty($results) ? true : false;
}

/*
 * Ajax Registration Validation
 */
add_action( 'wp_ajax_process_registration_validation', 'process_registration_validation' );
add_action( 'wp_ajax_nopriv_process_registration_validation', 'process_registration_validation' );

function process_registration_validation() {
	$res = array(
		'success' => false,
		'err_msg' => '',
	);
	if ( ! isset( $_POST['action'] ) || $_POST['action'] !== 'process_registration_validation' ) {
		$res['err_msg'] = 'Form action is not set.';
		exit( json_encode($res) );
	}
	$email = sanitize_text_field( $_POST['user_email'] );
	if ( email_exists($email) ) {
		$res['err_msg'] = 'This email already belongs to a user. You can <a href="javascript:void(0)" class="show-login" style="text-decoration: underline">login</a>, <a href="' . get_site_url() . '/account/lost-password/" target="_blank" style="text-decoration: underline">retrieve a forgotten password</a> or use a new unique email.';
		exit( json_encode($res) );
	}
	$res['success'] = true;
	exit( json_encode($res) );
}

/*
 * Brand Partner Register New User
 */
add_action( 'wp_ajax_radical_skincare_ajax_register_brand_partner', 'radical_skincare_ajax_register_brand_partner' );
add_action( 'wp_ajax_nopriv_radical_skincare_ajax_register_brand_partner', 'radical_skincare_ajax_register_brand_partner' );

function radical_skincare_ajax_register_brand_partner() {
	$res = array( 'success' => false );
	if ( !isset($_POST['action']) || $_POST['action'] !== 'radical_skincare_ajax_register_brand_partner' ) {
		exit( json_encode($res) );
	}
	$first_name = sanitize_text_field( $_POST['first_name'] );
	$last_name = sanitize_text_field( $_POST['last_name'] );
  $preferred_name = false;
  if (isset($_POST['preferred_name']) && $_POST['preferred_name'] !== '') {
    $preferred_name = sanitize_text_field( $_POST['preferred_name'] );
  }
	$user_email = sanitize_email( $_POST['user_email'] );
	$password = sanitize_text_field( $_POST['password'] );
	$username = radical_create_unique_username($first_name, $last_name, $preferred_name);
	$new_user_id = wp_create_user( $username, $password, $user_email );
	if (is_wp_error($new_user_id)) {
		$res['err_msg'] = 'Something wen\'t wrong registering a new user.';
		exit( json_encode($res) );
	}
	if ($first_name) {
		update_user_meta($new_user_id, 'first_name', $first_name);
	}
	if ($last_name) {
		update_user_meta($new_user_id, 'last_name', $last_name);
	}
  if ($preferred_name) {
    update_user_meta($new_user_id, 'radical_preferred_name', $preferred_name);
    update_user_meta($new_user_id, 'nickname', $username); // Have to set it = username so it's unique
  }
	// then log this user in
	$credentials = array(
		'user_login' => $user_email,
		'user_password' => $password,
		'remember' => false,
	);
	$res['result'] = radical_skincare_login( $credentials );
	$res['success'] = ($res['result'] === true) ? true : $res['success'];
	exit( json_encode($res) );
}

/**
 * Create Unique Username
 */
function radical_create_unique_username($first_name, $last_name, $preferred_name = false) {
	$username = strtolower($first_name) . strtolower($last_name);
  if ($preferred_name) {
    $username = strtolower($preferred_name);
  }
  $username = $original_username = preg_replace('/[^a-zA-Z0-9]/', '', $username);
	// $username = $original_username = str_replace(' ', '', $username);
	$i = 2;
	while (username_exists( $username )) {
		$username = $original_username . $i;
		$i++;
	}
	return $username;
}

/**
 * Brand Partner Login Failed
 * hook failed login
add_action( 'wp_login_failed', function ( $username ) {
	$referrer = $_SERVER['HTTP_REFERER'];  // where did the post submission come from?
	// if there's a valid referrer, and it's not the default log-in screen
	if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
		 wp_redirect( $referrer . '?form=login&login=failed' );  // let's append some information (login=failed) to the URL for the theme to use
		 exit;
	}
});

add_action( 'after_setup_theme', function () {
	if ( ! isset( $_POST['action'] ) || $_POST['action'] !== 'radical_login' ) {
		return;
	}
	$credentials = array(
		'user_login' => $_POST['email'],
		'user_password' => $_POST['password'],
		'remember' => ( isset( $_POST['remember'] ) ) ? true : false,
	);
	radical_skincare_login( $credentials );
} );
*/

/*
 * Ajax Login
 */
add_action( 'wp_ajax_radical_skincare_ajax_login', 'radical_skincare_ajax_login' );
add_action( 'wp_ajax_nopriv_radical_skincare_ajax_login', 'radical_skincare_ajax_login' );

function radical_skincare_ajax_login() {
	$res = array( 'success' => false );
	if ( ! isset( $_POST['action'] ) || $_POST['action'] !== 'radical_skincare_ajax_login' ) {
		exit( json_encode($res) );
	}
	if ( !isset($_POST['user_login']) || !isset($_POST['user_password']) ) {
		exit( json_encode($res) );
	}
	$credentials = array(
		'user_login' => $_POST['user_login'],
		'user_password' => $_POST['user_password'],
		'remember' => ( isset( $_POST['remember'] ) ) ? true : false,
	);
	$res['result'] = radical_skincare_login( $credentials );
	$res['success'] = ($res['result'] === true) ? true : $res['success'];
	exit( json_encode($res) );
}


function radical_skincare_login( $credentials ) {
	$signon_result = wp_signon( $credentials, is_ssl() );
	if ( is_wp_error($signon_result) ) {
		return $signon_result;
	}
	return radical_skincare_set_current_user( $signon_result );
}

function radical_skincare_set_current_user( $user ){
	wp_clear_auth_cookie();
	wp_set_current_user( $user->ID, $user->user_login );
	wp_set_auth_cookie( $user->ID );
	// add_user_meta( $user->ID, 'has_registered', true );
	return true;
}

function radical_add_affiliate_note( $user_id = false, $note_type = false, $note_content = false ) {
	if (!$user_id || !$note_type || !$note_content) {
		return false;
	}
	$data = array(
		'user_id' => $user_id,
		'type' => $note_type,
		'content' => $note_content
	);
	global $wpdb;
	return $wpdb->insert( $wpdb->prefix . 'gigfiliate_affiliatenotes', $data );
}

/*
 * Ajax Add Affilaite
 */
require get_template_directory() . '/inc/integrations/brand-partner/new-affiliate.php';

/*
 * Set upload or coach
 */
require get_template_directory() . '/inc/integrations/brand-partner/set-coach.php';

/*
 * Collects
 */
require get_template_directory() . '/inc/integrations/brand-partner/collections.php';

/*
 * Is In Cart
 * @params $product_id, $variation_id
 */
function radical_Is_In_Cart( $product_id, $variation_id = null ) {
	$in_cart = false;
	foreach( WC()->cart->get_cart() as $cart_item ) {
		$product_id_cart = $cart_item['product_id'];
		$variation_id_cart = $cart_item['variation_id'];
		if ( $product_id_cart === (int)$product_id ) {
			if ( isset( $variation_id_cart ) ) {
				if ( $variation_id_cart === (int)$variation_id ) {
					$in_cart = true;
				}
			} else {
				$in_cart = true;				
			}
		}
	}
	return $in_cart;
}

/*
 * Checkout Auto Approve Affiliate
require get_template_directory() . '/inc/integrations/brand-partner/checkout-auto-approve-affiliate.php';
 */

 /*
 * WooCommerce My Account
 */
require get_template_directory() . '/inc/integrations/brand-partner/woo-my-account.php';
