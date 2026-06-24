<?php
$site_url = get_site_url();
/*
 * Order Subscription
 */
function radical_order_subscription_gift_card($i, $related_order, $is_renewal_gift, $lastOrderDate) {

  $html = '<div class="col-sm py-2">
      <div class="card card-gift">
        <div class="card-body">
          <div class="card-gift_title-date-wrap ' . ( $is_renewal_gift ? 'mb-3' : '' ) . '">
            <h4 class="card-title mb-0">';
              if ($related_order) {
                $html .= '<a href="' . get_site_url() . '/account/view-order/' . $related_order->get_id() . '/" class="d-block link-underline link-underline_darker-gray">Order #' . ( $related_order->get_id() ) . '</a>';
              } else {
                $html .= '<span class="d-block link-underline link-underline_transparent">Order ' . ( $i ) . '</span>';
              }
            $html .= '</h4>
            <div class="text-muted small">' . ($related_order ? date('F j, Y', strtotime($related_order->get_date_created())) : $lastOrderDate ) . '</div>
          </div>';
          if ($is_renewal_gift) {
            $html .= '<p class="card-text"><i class="fa fa-gift" aria-hidden="true"></i> ' . $is_renewal_gift['note'] . '</p>';
          }
          $html .= '</div>
      </div>
    </div>';
  return $html;
}

function radical_order_subscription_gift_badge($i, $related_order, $is_renewal_gift, $lastOrderDate){
  $status = $related_order ? $related_order->get_status() : 'none';
  $icon = "";
  switch ($status) {
    case 'processing':
      $icon = "fa-hourglass-start text-warning";
      break;
    case 'delivered':
      $icon = "fa-check text-success";
      break;
    case 'completed':
      $icon = "fa-check text-success";
      break;
    case 'none':
      $icon = "fa-history text-info";
      break;
    default:
      $icon = "fa-times text-danger";
      break;
  }
  $html = "<div class='timeline-vertical-divider col-sm-1 text-center flex-column d-none d-sm-flex'>
          <div class='row h-50'>
            <div class='col border-right'>&nbsp;</div>
            <div class='col'>&nbsp;</div>
          </div>
          <div class='m-2'>
            <span class='badge border order-badge'><i class='fa $icon'></i></span>
          </div>
          <div class='row h-50'>
            <div class='col border-right'>&nbsp;</div>
            <div class='col'>&nbsp;</div>
          </div>
        </div>";
  return $html;
}

$gifts = get_field('gifts', 'option');
$enable_renewal_gifts = $gifts['enable_renewal_gifts'];

if (empty($subscription) || !is_a($subscription, 'WC_Subscription')) {
  return;
}
?>
<?php if ($gifts && $enable_renewal_gifts && isset($gifts['renewal_gifts']) && !empty($gifts['renewal_gifts'])) : ?>
  <section class="ror-gifts ps-timeline-sec">
    <div class="container">
      <a href="<?php echo $site_url; ?>/radical-on-repeat/#radical-on-repeat-gift" class="text-center d-block" target="_blank">Learn More <i class="fa fa-info-circle ml-1" aria-hidden="true"></i></a>
      <?php
      $maxThreshold = 0;
      $thresholdWithGifts = [];
      $lastOrderDate = null;
      ?>
      <?php for ($i = 0; $i < count($gifts['renewal_gifts']); $i++) : ?>
        <?php
        $thresholdWithGifts[$gifts['renewal_gifts'][$i]['threshold']] = $gifts['renewal_gifts'][$i];
        $maxThreshold = $maxThreshold < $gifts['renewal_gifts'][$i]['threshold'] ? ($gifts['renewal_gifts'][$i]['threshold']+1) : $maxThreshold;
        ?>
      <?php endfor; ?>
      <?php
      $orders_after = get_posts([
        'post_status'=> ['wc-processing', 'wc-completed', 'wc-delivered'],
        'post_type' => ['shop_order', 'shop_subscription'],
        'include' => $subscription->get_related_orders(),
        'numberposts' => $maxThreshold,
        'order' => 'ASC',
        'orderby' => 'ID',
        'date_query' => [
          [
            'after' => $gifts['enable_from'],
            'inclusive' => true,
          ],
        ],
      ]);
      ?>
      <?php for ($i = 1; $i < $maxThreshold; $i++) : ?>
        <?php
        $related_order = false;
        if (isset($orders_after[($i-1)])) {
          $related_order = wc_get_order($orders_after[($i-1)]->ID);
          $lastOrderDate = $related_order->get_date_created();
        } else {
          $lastOrderDate = (!$lastOrderDate) ? date('F j, Y', $subscription->get_time( 'start' )) : $lastOrderDate;
          $lastOrderDate = date('F j, Y', strtotime('+' . $subscription->get_billing_interval() . ' ' . $subscription->get_billing_period(), strtotime($lastOrderDate)));
        }
        $is_renewal_gift = isset($thresholdWithGifts[$i]) ? $thresholdWithGifts[$i] : false;
        ?>
        <div class="row no-gutters">
          <?php if (($i % 2) === 0) : ?>
            <?php echo radical_order_subscription_gift_card($i, $related_order, $is_renewal_gift, $lastOrderDate); ?>
            <?php echo radical_order_subscription_gift_badge($i, $related_order, $is_renewal_gift, $lastOrderDate); ?>
            <div class="col-sm"><!--spacer--></div>
          <?php else : ?>
            <div class="col-sm"><!--spacer--></div>
            <?php echo radical_order_subscription_gift_badge($i, $related_order, $is_renewal_gift, $lastOrderDate); ?>
            <?php echo radical_order_subscription_gift_card($i, $related_order, $is_renewal_gift, $lastOrderDate); ?>
          <?php endif; ?>
        </div>
      <?php endfor; ?>
    </div>
  </section>
<?php endif; ?>
