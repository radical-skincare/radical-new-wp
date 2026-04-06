<?php
add_filter('woocommerce_email_classes', function ($email_classes) {
  // include our custom email class
  require_once(get_template_directory() . '/inc/integrations/wc-subscriptions.php');
  $email_classes['WC_Subscription_Reminder_Order_Email'] = new WC_Subscription_Reminder_Order_Email();
  return $email_classes;
});

/*
 * Schedule Radical on Repeat Reminder Email
 */
add_action('schedule_radical_on_repeat_reminder_email', function () {
  // Testing code
  // WC()->mailer();
  // do_action('radical_subscription_reminder_notification', '28626');
  // return;
  // Live code
  $shop_subscriptions = wcs_get_subscriptions([
    'subscriptions_per_page' => -1,
    'subscription_status' => ['active'],
    'meta_query' => [
      'relation' => 'AND',
      [
        'key' => '_schedule_next_payment',
        'value' => date('Y-m-d', strtotime("+1 week")) . ' 00:00:00',
        'compare' => '>='
      ],
      [
        'key' => '_schedule_next_payment',
        'value' => date("Y-m-d", strtotime("+1 week")) . ' 23:59:59',
        'compare' => '<='
      ]
    ]
  ]);
  if (!$shop_subscriptions) {
    return;
  }
  WC()->mailer();
  foreach ($shop_subscriptions as $shop_subscription) {
    do_action('radical_subscription_reminder_notification', $shop_subscription->get_ID());
  }
});

if (!wp_next_scheduled('schedule_radical_on_repeat_reminder_email')) {
  wp_schedule_event(time(), 'daily', 'schedule_radical_on_repeat_reminder_email');
}
