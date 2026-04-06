
<?php
$args = [
  'numberposts' => '3',
  'meta_key' => '_customer_user',
  'meta_value' => $current_user_id,
  'orderby' => 'date',
  'order' => 'DESC',
  'post_type' => wc_get_order_types(),
  'post_status' => array_keys(wc_get_order_statuses()),
];
$orders = get_posts($args);
?>
<?php if ($orders) : ?>
  <div class="row mb-3">
    <div class="col">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h3 class="mb-0">My Recent Orders</h3>
        <a href="<?php echo esc_html($site_url); ?>/account/orders" class="link-underline link-underline_darker-gray">View All</a>
      </div>
      <div class="recent-orders">
        <div class="list-group list-group-flush">
          <?php foreach ($orders as $order) : ?>
            <?php
            $wc_order = wc_get_order($order->ID);
            $status_name = wc_get_order_status_name( $wc_order->get_status() );
            $status_name = str_replace(' payment', '', $status_name);
            $is_order_for_customer = get_post_meta($order->ID, 'gig_ordered_by', true);
            ?>
            <a href="<?php echo esc_html($site_url); ?>/account/view-order/<?php echo esc_html($wc_order->get_ID()); ?>/" class="list-group-item recent-orders_order p-3">
              <div class="row align-items-center" style="row-gap: 0.5rem">
                <div class="col-4 col-md-6 col-lg-2">
                  <strong>Order #</strong>
                  <br/>
                  <?php echo esc_html($wc_order->get_ID()); ?>
                </div>
                <div class="col-8 col-md-6 col-lg-5 overflow-hidden text-truncate">
                  <strong>Sent to</strong>
                  <?php if ($is_order_for_customer) : ?>
                    <br/>
                    <span class="badge badge-info">Ordered For Customer</span>
                  <?php endif; ?>
                  <br/>
                  <?php echo esc_html($wc_order->get_billing_email()); ?>
                </div>
                <div class="col-3 col-md-6 col-lg-2">
                  <strong>Total</strong>
                  <br/>
                  <?php echo $wc_order->get_formatted_order_total(); ?>
                </div>
                <div class="col-9 col-md-6 col-lg-3 d-flex align-items-center justify-content-end">
                  <span class="badge badge-<?php echo strtolower($status_name); ?> mr-2">
                    <?php echo esc_html($status_name); ?>
                  </span>
                  <i class="fa fa-angle-right" aria-hidden="true"></i>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
<?php else : ?>
  <div class="alert alert-info">No orders yet.</div>
<?php endif; ?>
