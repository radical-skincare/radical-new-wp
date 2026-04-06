<?php

function send_message_using_twillo($get_shipping_phone, $shipment_message_text) {
  $twilio_account_sid = get_field('twilio_account_sid', 'option');
  $twilio_auth_token = get_field('twilio_auth_token', 'option');
  $twilio_from_number = get_field('twilio_from_number', 'option');
  if (!$twilio_account_sid || !$twilio_auth_token || !$twilio_from_number) {
    return;
  }
  $data = array(
    'Body' => $shipment_message_text,
    'From' => $twilio_from_number,
    'To' => $get_shipping_phone
  );

  $url = 'https://api.twilio.com/2010-04-01/Accounts/'.$twilio_account_sid.'/Messages.json';

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  curl_setopt($ch, CURLOPT_USERPWD, $twilio_account_sid.':'.$twilio_auth_token);
  $response = curl_exec($ch);
  curl_close($ch);
  return $response;
}
/**
 * SMS Twilio - Tracking Information
 */
add_action('gwt_tracking_information_added_in_order', function( $order_id ) {

  $radical_receive_shipment_message = get_post_meta($order_id, 'radical_receive_shipment_update_sms', true);
  if (!$radical_receive_shipment_message) {
    return;
  }

  $gwt_ship_at = get_post_meta($order_id, 'gwt_ship_at', true);
  $gwt_shipping_provider = get_post_meta($order_id, 'gwt_shipping_provider', true);
  $gwt_tracking_number = get_post_meta($order_id, 'gwt_tracking_number', true);

  $shipment_message_text = get_field('shipment_message_text', 'option');
  $enable_shipment_message_notification = get_field('enable_shipment_message_notification', 'option');
  if (!$enable_shipment_message_notification) {
    return;
  }
  
  $order = wc_get_order($order_id);
  $get_shipping_phone = $order->get_shipping_phone();

  if (!$gwt_ship_at || !$gwt_shipping_provider || !$gwt_tracking_number || !$shipment_message_text || !$get_shipping_phone) {
    return;
  }

  $tags = [
    '{order_id}' => $order_id,
    '{ship_at}' => $gwt_ship_at,
    '{shipping_provider}' => $gwt_shipping_provider,
    '{tracking_number}' => $gwt_tracking_number,
    '{customer_first_name}' => $order->get_shipping_first_name(),
    '{customer_last_name}' => $order->get_shipping_last_name()
  ];

  foreach ($tags as $key => $tag) {
    $shipment_message_text = str_replace($key, $tag, $shipment_message_text);
  }

  send_message_using_twillo($get_shipping_phone, $shipment_message_text);
});

/**
 * SMS Twilio - New Order Notification
 */
add_action( 'woocommerce_checkout_update_order_meta', function ($order_id) {
  if (!isset($_POST['receive-shipment-update-using-sms']) || $_POST['shipping_country'] != 'US') {
    return;
  }

  $order = wc_get_order($order_id);
  $get_shipping_phone = $order->get_shipping_phone();
  $new_order_message_text = get_field('new_order_message_text', 'option');

  $tags = [
    '{order_id}' => $order_id,
    '{customer_first_name}' => $order->get_shipping_first_name(),
    '{customer_last_name}' => $order->get_shipping_last_name()
  ];

  foreach ($tags as $key => $tag) {
    $new_order_message_text = str_replace($key, $tag, $new_order_message_text);
  }
  send_message_using_twillo($get_shipping_phone, $new_order_message_text);
  });