
<?php
$current_user_id = $args['current_user_id'];
$site_url = $args['site_url'];
$args = [
  'customer_id' => $current_user_id,
  'subscription_status' => ['wc-active', 'wc-cancelled', 'wc-on-hold'],
  'subscriptions_per_page' => 3
];
$subscriptions = wcs_get_subscriptions($args);
?>
<?php if ($subscriptions) : ?>
  <div class="row mb-3">
    <div class="col">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h3 class="mb-0">My Recent Subscriptions</h3>
        <a href="<?php echo esc_html($site_url); ?>/account/subscriptions" class="link-underline link-underline_darker-gray">View All</a>
      </div>
      <div class="recent-subscriptions">
        <div class="list-group list-group-flush">
          <?php foreach ($subscriptions as $subscription) : ?>
            <?php
            $is_order_for_customer = get_post_meta($subscription->get_ID(), 'gig_ordered_by', true);
            $status = wcs_get_subscription_status_name( $subscription->get_status() );
            ?>
            <a href="<?php echo esc_html($site_url); ?>/account/view-subscription/<?php echo esc_html($subscription->get_ID()); ?>/" class="list-group-item recent-orders_order p-3">
              <div class="row align-items-center" style="row-gap: 0.5rem">
                <div class="col-4 col-md-6 col-lg-2">
                  <strong>Sub #</strong>
                  <br/>
                  <?php echo esc_html($subscription->get_ID()); ?>
                </div>
                <div class="col-8 col-md-6 col-lg-5 overflow-hidden text-truncate">
                  <strong>Sent to</strong>
                  <?php if ($is_order_for_customer) : ?>
                    <br/>
                    <span class="badge badge-info">Ordered For Customer</span>
                  <?php endif; ?>
                  <br/>
                  <?php echo esc_html($subscription->get_billing_email()); ?>
                </div>
                <div class="col-3 col-md-6 col-lg-2 px-0">
                  <strong>Total</strong>
                  <br/>
                  <?php echo $subscription->get_formatted_order_total(); ?>
                </div>
                <div class="col-9 col-md-6 col-lg-3 d-md-flex align-items-center justify-content-end">
                  <span class="badge badge-<?php echo strtolower( $status ); ?> mr-2"><?php echo esc_html($status); ?></span>
                  <i class="fa fa-angle-right" aria-hidden="true"></i>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
