<?php

/**
 * Payment Methods - Change Name
 */
add_action( 'wp_ajax_payment_methods_change_name', function() {
  check_ajax_referer('radical_ajax_nonce', 'nonce');
  $res = [
    'success' => true,
  ];
	if (!isset($_POST['action']) || $_POST['action'] !== 'payment_methods_change_name') {
    $response['success'] = false;
    exit(json_encode($res));
	}
  $user_id = (int)$_POST['user_id'];
  $cc_id = $_POST['cc_id'];
  $cc_name = $_POST['cc_name'];
  $payment_methods_names = get_user_meta($user_id, 'payment_methods_names', true);
  $found = false;
  if ($payment_methods_names) {
    $payment_methods_names = json_decode($payment_methods_names);
    if (!empty($payment_methods_names)) {
      foreach ($payment_methods_names as $payment_method_name) {
        if ($payment_method_name->id === $cc_id) {
          $payment_method_name->name = $cc_name;
          $found = true;
        }
      }
    }
  } else {
    $payment_methods_names = [];
  }
  if (!$found) {
    $payment_methods_names[] = [
      'id' => $cc_id,
      'name' => $_POST['cc_name'],
    ];    
  }
  update_user_meta($user_id, 'payment_methods_names', json_encode($payment_methods_names));
  $res['payment_methods_names'] = $payment_methods_names;
  exit(json_encode($res));
} );

add_filter('woocommerce_payment_gateway_get_saved_payment_method_option_html', function($html, $token, $class) {
  $cc = get_string_between($token->get_display_name(), 'ending in ', ' (');
  $expires = get_string_between($token->get_display_name(), 'expires ', ')');
  $expires = str_replace('/', '-', $expires);
  $name = radical_get_payment_method_name_by_id($cc . '_' . $expires);
  if (!$name) {
    return $html;
  }
  $original_label = '<label for="wc-%1$s-payment-token-%2$s">';
  $original_label = sprintf($original_label,
    esc_attr( $class->id ),
    esc_attr( $token->get_id() ),
  );
  $html = str_replace($original_label, $original_label .' <b>' . $name . '</b> ', $html);
  return $html;
}, 10, 3);
