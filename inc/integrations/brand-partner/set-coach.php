<?php 
add_action( 'wp_ajax_process_set_mlm_connections', 'process_set_mlm_connections' );
add_action( 'wp_ajax_nopriv_process_set_mlm_connections', 'process_set_mlm_connections' );
function process_set_mlm_connections() {
	check_ajax_referer('radical_ajax_nonce', 'nonce');
	$response = array(
		"success" => false,
		"err_msg" => ""
	);

	if ( ! isset( $_POST['action'] ) || $_POST['action'] !== 'process_set_mlm_connections' ) {
		exit( json_encode($response) );
	}

	$user_id = get_current_user_id();
	global $wpdb;
	// get affiliate_id
	$table = $wpdb->prefix . "affiliate_wp_affiliates";
	$sql = "SELECT affiliate_id FROM $table WHERE user_id = $user_id";
	$result = $wpdb->get_results( $sql );
	$user_affiliate_id = (int)$result[0]->affiliate_id;

	if ( ! affwp_is_active_affiliate( $user_affiliate_id ) ) {
		// then exit cause (error) user must be an affiliate
		$response["err_msg"] = "User must be an affiliate.";
		exit( json_encode($response) );
	}

	$affiliate_parent_id = $_POST['affiliate_parent_id'];

	// if $affiliate_parent_id is not affiliate
	if ( ! affwp_is_active_affiliate( $affiliate_parent_id ) ) {
		// then exit cause (error) can only affiliates as coaches
		$response["err_msg"] = "Selected coach must be an affiliate.";
		exit( json_encode($response) );
	}

	// table affiliate_wp_mlm_connections
	$table = $wpdb->prefix . "affiliate_wp_mlm_connections";

	// get affiliate parent matrix_level
	$sql = "SELECT matrix_level FROM $table WHERE affiliate_id = $affiliate_parent_id";
	$matrix_level_result = $wpdb->get_results( $sql );
	$matrix_level = (int)$matrix_level_result[0]->matrix_level;

	$data = array(
		"affiliate_parent_id" => $affiliate_parent_id,
		"direct_affiliate_id" => $affiliate_parent_id,
		"matrix_level" => $matrix_level + 1, // increment by 1
	);

	// get results
	$sql = "SELECT * FROM $table WHERE affiliate_id = $user_affiliate_id";
	$result = $wpdb->get_results( $sql );
	// if result not empty row exists
	if ( ! empty( $result ) ) {
		// then update
		$where = array(
			'affiliate_id' => $user_affiliate_id
		);
		$wpdb->update( $wpdb->prefix . "affiliate_wp_mlm_connections", $data, $where  );
	} else {
		// else is new create row
		$data["affiliate_id"] = $user_affiliate_id;
		$wpdb->insert( $table, $data );
	}

	$response["success"] = true;
	exit( json_encode($response) );

}

add_action( 'wp_ajax_process_set_affiliate_referrer', 'process_set_affiliate_referrer' );
add_action( 'wp_ajax_nopriv_process_set_affiliate_referrer', 'process_set_affiliate_referrer' );
function process_set_affiliate_referrer() {
	check_ajax_referer('radical_ajax_nonce', 'nonce');
	$res = array( 'success' => false );
	if ( ! isset( $_POST['action'] ) || $_POST['action'] !== 'process_set_affiliate_referrer' ) {
		exit( json_encode($res) );
	}
	$current_user_id = get_current_user_id();
	$affiliate_parent_id = $_POST['affiliate_parent_id'];
	$affiliate_parent_user_id = gigfiliate_get_user_id_by_affiliate_id($affiliate_parent_id);
	$updated = update_user_meta($current_user_id, 'v_referrer_id', $affiliate_parent_id);
	$first_name = get_user_meta($affiliate_parent_user_id, 'first_name', true);
	$last_name = get_user_meta($affiliate_parent_user_id, 'last_name', true);
	radical_add_affiliate_note( $current_user_id, 'activity', "Referring Brand Partner $first_name $last_name chosen.");
	$res['success'] = true;
	exit( json_encode($res) );
}

add_action( 'wp_ajax_process_reset_affiliate_referrer', 'process_reset_affiliate_referrer' );
function process_reset_affiliate_referrer() {
  check_ajax_referer('radical_ajax_nonce', 'nonce');
  $current_user_id = get_current_user_id();
  $affiliate_parent_id = $_POST['affiliate_parent_id'];
  $affiliate_parent_user_id = gigfiliate_get_user_id_by_affiliate_id($affiliate_parent_id);
  delete_user_meta($current_user_id, 'v_referrer_id', $affiliate_parent_id);
  delete_user_meta($current_user_id, 'v_enrollment_no_referrer_chosen');
	$first_name = get_user_meta($affiliate_parent_user_id, 'first_name', true);
	$last_name = get_user_meta($affiliate_parent_user_id, 'last_name', true);
	radical_add_affiliate_note( $current_user_id, 'activity', "Removed Brand Partner $first_name $last_name as referring.");
	$res['success'] = true;
	exit( json_encode($res) );
}

add_action( 'wp_ajax_process_set_enrollment_no_referrer_chosen', 'process_set_enrollment_no_referrer_chosen' );
add_action( 'wp_ajax_nopriv_process_set_enrollment_no_referrer_chosen', 'process_set_enrollment_no_referrer_chosen' );
function process_set_enrollment_no_referrer_chosen() {
	check_ajax_referer('radical_ajax_nonce', 'nonce');
	$res = array( 'success' => false );
	if ( ! isset( $_POST['action'] ) || $_POST['action'] !== 'process_set_enrollment_no_referrer_chosen' ) {
		exit( json_encode($res) );
	}
	$updated = update_user_meta(get_current_user_id(), 'v_enrollment_no_referrer_chosen', true);
	$res['success'] = true;
	exit( json_encode($res) );
}
