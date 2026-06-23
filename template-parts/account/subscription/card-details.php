
<?php
$subscription = $args['subscription'];
$status = $args['status'] ?? $subscription->get_status();
?>
<section class="subscription-details mb-4">
  <div class="card mb-3">
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col-lg-3 pb-3 pb-lg-0">
          <div>
            <strong>Start Date</strong><br/>
            <?php echo esc_html($subscription->get_date_to_display('start_date')); ?>
          </div>
        </div>
        <div class="col-lg-3 pt-3 pb-3 pt-lg-0 pb-lg-0">
          <div>
            <strong>Last Shipment Date</strong><br/>
            <?php echo esc_html($subscription->get_date_to_display('last_order_date_created')); ?>
          </div>
        </div>
        <div class="col-lg-3 justify-content-between pt-3 pb-3 pt-lg-0 pb-lg-0">
          <div>
            <div class="mb-2">
              <?php if ($status === 'active' && get_field('subscription_enable_change_frequency', 'option')) : ?>
                <button type="button" class="link-underline link-underline_darker-gray text-primary" data-toggle="modal" data-target="#subscriptionChangeFrequencyModel">
                  <strong>Change Frequency</strong>
                  <i class="fa fa-refresh" aria-hidden="true"></i>
                  <span class="sr-only">Change Frequency</span>
                </button>
              <?php else : ?>
                <strong>Frequency</strong>
              <?php endif; ?>
            </div>
            <?php echo esc_html(radical_get_subscription_interval_period_text($subscription)); ?>
          </div>
        </div>
        <div class="col-lg-3 justify-content-between pt-3 pt-lg-0">
          <div>
            <?php if ($subscription->get_time('next_payment')) : ?>
              <div class="mb-1">
                <?php if ($status === 'active' && get_field('subscription_enable_change_next_order_date', 'option')) : ?>
                  <button dtype="button" class="link-underline link-underline_darker-gray text-primary" data-toggle="modal" data-target="#subChangeNextOrderDateModel">
                    <strong>Next Order</strong>
                    <i class="fa fa-calendar" aria-hidden="true"></i>
                    <span class="sr-only">Change Next Order Date</span>
                  </button>
                <?php else : ?>
                  <strong>Next Shipment</strong>
                <?php endif; ?>
              </div>
              <?php echo esc_html($subscription->get_date_to_display('next_payment')); ?>
            <?php else : ?>
              <strong>End Date</strong><br/>
              <?php echo esc_html($subscription->get_date_to_display('end_date')); ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
