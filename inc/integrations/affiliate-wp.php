<?php

/*
 * Function affwp_get_User_ID_By_Affiliate_ID
 *
 * @params (int) $affiliate_id * required
 *
 * Error: return (bool) false
 * Success: return (int) user_id
 *
*/
function affwp_get_User_ID_By_Affiliate_ID ( $affiliate_id ) {

	// if is not logged in OR $user_id is not set
	if ( ! isset( $affiliate_id ) ) {
		return false;
	}

    // get user_id
    global $wpdb;
    $table = $wpdb->prefix . "affiliate_wp_affiliates";
    $sql = "SELECT user_id FROM $table WHERE affiliate_id = $affiliate_id";
    $result = $wpdb->get_results( $sql );

    // if result is empty
    if ( empty($result) ) {
		return false;
    }

    return (int)$result[0]->user_id;

}

/*
 * Function get_Affiliate_ID_By_User_ID
 *
 * @params (int) $user_id * required
 *
 * Error: return (bool) false
 * Success: return (int) affiliate_id
 *
*/
function get_Affiliate_ID_By_User_ID ( $user_id ) {
	$general_settings = json_decode( get_option( 'brand_partner_setings' ) );
	if ($general_settings->affiliate_plugin === 'affiliate-wp') {
		// if is not logged in OR $user_id is not set
		if ( ! isset( $user_id ) ) {
			return false;
		}
		// get affiliate_id
		global $wpdb;
		$table = $wpdb->prefix . "affiliate_wp_affiliates";
		$sql = "SELECT affiliate_id FROM $table WHERE user_id = $user_id";
		$result = $wpdb->get_results( $sql );
		// if result is empty
		if ( empty($result) ) {
			return false;
		}
		return (int)$result[0]->affiliate_id;
	}
	return trim(get_user_meta($user_id, 'v_affiliate_id', true));
}

/*
 * Function is_Brand_Partner_Active
 *
 * @params $user_id * required
 *
 * Error: return (bool) false
 * Success: returns (int) subscription id
 *
*/
function is_Brand_Partner_Active( $user_id ) {
	// if $user_id is not set
	if ( ! isset( $user_id ) ) {
		return false;
	}
	// get Ambassador Welcome Kit ID
	if (get_user_meta($user_id, 'v_affiliate_status', true) === 'active') {
		return get_user_meta($user_id, 'v_affiliate_id', true);
	}
	return false;
}

function is_Permament_Ambassador( $user_id ) {

	// if $user_id is not set
	if ( ! isset( $user_id ) ) {
		return false;
	}
	$general_settings = json_decode( get_option( 'brand_partner_setings' ) );
	$permanent_ambassador_user_ids = explode(", ", $general_settings->permanent_ambassador_user_ids);
	if ( ! empty($permanent_ambassador_user_ids) ) {
		foreach($permanent_ambassador_user_ids as $this_user_id) {
			if ( $user_id === (int)$this_user_id) {
				return true;
			}
		}
	}
	return false;

}

/**
 * Rx Is Affiliate Payment Email Set
 * 
 * @params 
 * $affiliate_id (int)
 */
function rx_is_affiliate_payment_email_set( $affiliate_id ) {
    global $wpdb;
    $table = $wpdb->prefix . "affiliate_wp_affiliates";
    $sql = "SELECT payment_email FROM $table WHERE affiliate_id = $affiliate_id";
    $results = $wpdb->get_results( $sql );
    if (empty($results)) {
        return false;
    }
    if ($results[0]->payment_email === "") {
        return false;
	}
    return true;
}

/**
 * AffiliateWP Admin Referrals Table
 * 
 * @params
 * $actions (array), $user_object (object)
 */
add_filter('affwp_referral_row_actions', function ($actions, $user_object) {
    if ( isset($actions[0]) ) {
        if ( ! rx_is_affiliate_payment_email_set( $user_object->affiliate_id ) ) {
            $actions[0] = '<a href="' . get_site_url() . '/wp-admin/admin.php?page=affiliate-wp-affiliates&action=edit_affiliate&affiliate_id=' . $user_object->affiliate_id . '" style="color: red;">Payment Missing</a>';
        }
    }
	return $actions;
}, 10, 2);