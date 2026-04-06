<?php

add_action( 'wp_ajax_process_new_affiliate', 'process_new_affiliate' );
add_action( 'wp_ajax_nopriv_process_new_affiliate', 'process_new_affiliate' );

function process_new_affiliate() {
	check_ajax_referer('radical_ajax_nonce', 'nonce');
	$response = array( 'success' => false );
	if ( ! isset( $_POST['action'] ) || $_POST['action'] !== 'process_new_affiliate' ) {
		$response['err_msg'] = 'Form action error.';
		exit( json_encode($response) );
	}
	$user_id = get_current_user_id();
  $old_user_data = get_userdata($user_id);
	$general_settings = json_decode( get_option( 'brand_partner_setings' ) );
	if ($general_settings->affiliate_plugin === 'affiliate-wp') {
		if ( isset($_POST['city']) ) {
			update_field( 'city', sanitize_text_field($_POST['city']), 'user_' . $user_id ); // Update ACF: Set City, State, Zip Code
		}
		if ( isset($_POST['state']) ) {
			update_field( 'state', sanitize_text_field($_POST['state']), 'user_' . $user_id ); // Update ACF: Set City, State, Zip Code
		}
		if ( isset($_POST['zip_code']) ) {
			update_field( 'zip_code', sanitize_text_field($_POST['zip_code']), 'user_' . $user_id ); // Update ACF: Set City, State, Zip Code
		}
		// Add an AffiliateWP affiliate
		$data = array(
			'user_id' => $user_id,
			// 'rate' => 35,
			// 'rate_type' => 'percentage',
			'payment_email' => $_POST['payment_email'],
			'status' => 'active',
		);
		$affiliate_id = affwp_add_affiliate( $data );
		$response["success"] = true;
		exit( json_encode($response) );
	}
	// else $general_settings->affiliate_plugin === 'gigfiliate'
	if ( isset($_POST['phone']) ) {
		update_user_meta($user_id, 'v_phone', $_POST['phone']);
	}
	if ( isset($_POST['address_line_1']) ) {
		update_user_meta($user_id, 'v_address_line_1', $_POST['address_line_1']);
	}
	if ( isset($_POST['address_line_2']) ) {
		update_user_meta($user_id, 'v_address_line_2', $_POST['address_line_2']);
	}
	if ( isset($_POST['city']) ) {
		update_user_meta($user_id, 'v_city', $_POST['city']);
	}
	if ( isset($_POST['state']) ) {
		update_user_meta($user_id, 'v_state', $_POST['state']);
	} else {
		$response['msg'] = 'State is required.';
		exit( json_encode($response) );
	}
	if ( isset($_POST['zip_code']) ) {
		update_user_meta($user_id, 'v_zip', $_POST['zip_code']);
	}
	if ( isset($_POST['paypal_email']) ) {
		update_user_meta($user_id, 'v_paypal_email', $_POST['paypal_email']);
	}
	update_user_meta($user_id, 'v_country', 'US');
	update_user_meta($user_id, 'v_affiliate_status', 'pending_approval');
	$response["success"] = true;
  do_action('gigfiliate_profile_update', $user_id, $old_user_data);
	exit( json_encode($response) );
}
