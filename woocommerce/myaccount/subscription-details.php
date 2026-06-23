<?php
/**
 * Subscription details table
 *
 * @author  Prospress
 * @package WooCommerce_Subscription/Templates
 * @since 2.2.19
 * @version 2.6.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$status = $subscription->get_status();
$status_name = wcs_get_subscription_status_name( $subscription->get_status() );
$subscription_id = $subscription->get_ID();
$actions = wcs_get_all_user_actions_for_subscription( $subscription, get_current_user_id() );
$action_links = [];
foreach ($actions as $key => $action) {
  $action['name'] = str_replace(" ", "_", $action['name']);
  $action_links[strtolower($action['name'])] = $action['url'];
}
$action_link_cancel = isset($action_links['pause']) ? $action_links['pause'] : false;
$interval = (int)$subscription->get_billing_interval();
$period = $subscription->get_billing_period() . ($interval > 1 ? 's' : '');
$gig_ordered_by = get_post_meta($subscription_id, 'gig_ordered_by', true);
?>
<h2>Subscription <?php esc_html_e( 'No', 'woocommerce-subscriptions' ); ?><?php echo esc_html($subscription_id); ?></h2>
<div class="d-flex align-items-center mb-2">
  <div class="col-6 px-0">
    <p class="m-0">
      Status <span class="badge badge-<?php echo esc_attr($status); ?>"><?php echo esc_html($status_name); ?></span>
      <?php if ($gig_ordered_by) : ?>
        <span class="badge badge-info">Orderd For Customer</span>
      <?php endif; ?>
    </p>
  </div>
  <div class="col-6 px-0 d-flex justify-content-end" id="subscription_action">
    <?php if ($action_link_cancel) : ?>
      <button id="btn-show-subscription-cancel-modal" class="link-underline link-underline_darker-gray" data-toggle="modal" data-target="#subscriptionCancelReason" data-cancel_link="<?php echo $action_link_cancel; ?>">Cancel</button>
    <?php endif; ?>
    <?php if ($status === 'active') : ?>
      <button class="link-underline link-underline_darker-gray ml-3" id="skip_whole_order_btn" data-subscription_id="<?php echo esc_attr($subscription_id); ?>">Skip Renewal Order</button>
    <?php endif; ?>
    <?php if (isset($action_links['renew_now'])) : ?>
      <a class="btn btn-darkergray ml-3" href="<?php echo $action_links['renew_now']; ?>">Purchase Now</a>
    <?php endif; ?>
    <?php if (isset($action_links['reactivate'])) : ?>
      <a class="btn btn-darkergray ml-3" href="<?php echo $action_links['reactivate']; ?>">Reactivate</a>
    <?php endif; ?>
  </div>
</div>
<?php if ($status === 'pending-cancel' && $subscription->get_time('end_date')) : ?>
  <div class="alert alert-danger">
    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> You have <b id="sub_end_date_formatted"><?php echo esc_html(str_replace('In ', '', $subscription->get_date_to_display('end_date'))); ?></b> to reactivate before your subscription is permanently cancelled.
  </div>
<?php endif; ?>
<?php
get_template_part('template-parts/account/subscription/card-details', null, [
  'status'       => $status,
  'subscription' => $subscription,
]);
?>
