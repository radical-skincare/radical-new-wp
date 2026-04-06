<?php

add_action( 'wp_ajax_radical_add_collection_to_cart', 'radical_add_collection_to_cart' );
add_action( 'wp_ajax_nopriv_radical_add_collection_to_cart', 'radical_add_collection_to_cart' );
function radical_add_collection_to_cart() {
	check_ajax_referer('radical_ajax_nonce', 'nonce');
	$response = array(
		'success' => true,
	);
	if ( !isset( $_POST['action'] ) || $_POST['action'] !== 'radical_add_collection_to_cart' ) {
		$response['success'] = false;
		exit( json_encode($response) );
	}
	$product_id = (int)$_POST['product_id'];
	$quantity = 1;
	// global $woocommerce;
	$response['success'] = WC()->cart->add_to_cart( $product_id, $quantity ); //, $variation 
	exit( json_encode($response) );

}

add_action( 'wp_ajax_radical_remove_collection_from_cart', 'radical_remove_collection_from_cart' );
add_action( 'wp_ajax_nopriv_radical_remove_collection_from_cart', 'radical_remove_collection_from_cart' );
function radical_remove_collection_from_cart() {
	check_ajax_referer('radical_ajax_nonce', 'nonce');
	$response = array(
		'success' => true,
	);
	if ( !isset( $_POST['action'] ) || $_POST['action'] !== 'radical_remove_collection_from_cart' ) {
		$response['success'] = false;
		exit( json_encode($response) );
	}
	$product_id = (int)$_POST['product_id'];
	// global $woocommerce;
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		if ( $cart_item['product_id'] == $product_id ) {
			$response['success'] = WC()->cart->remove_cart_item( $cart_item_key );
			$response['found'] = 'test'; // todo: remove?
		}
	}
	exit( json_encode($response) );
}
