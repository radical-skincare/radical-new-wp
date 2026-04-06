
<?php
$gigfiliate_settings = json_decode(get_option('gigfiliate_settings'));
// customer is already logged in
$current_user_id = get_current_user_id();
$is_active_affiliate = gigfiliate_is_active_affiliate($current_user_id);

$shipping_provider = get_post_meta($order_id, 'gwt_shipping_provider', true);
$tracking_number = get_post_meta($order_id, 'gwt_tracking_number', true);
$ship_at = get_post_meta($order_id, 'gwt_ship_at', true);
if ($ship_at) {
  $ship_at = date_create_from_format('m-d-Y', $ship_at);
  $ship_at = date_format($ship_at, "Y-m-d");
  $ship_at = strtotime($ship_at);
}
$tracking_link = "";
if ($shipping_provider && $tracking_number) {
  $tracking_link = 'https://tools.usps.com/go/TrackConfirmAction?tRef=fullpage&tLc=2&text28777=&tLabels=' . $tracking_number . '&tABt=false';
  if ($shipping_provider == 'ups') {
    $tracking_link = 'https://www.ups.com/track?loc=null&tracknum=' . $tracking_number . '&requester=WT/trackdetails';
  }
}
?>
<section class="order-details__cards mb-4">
  <div class="card mb-3">
    <div class="card-body">
      <div class="row flex-wrap">
        <div class="col-lg-3 pb-3 pb-lg-0">
          <div>
            <strong>Placed On</strong><br/>
            <time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>
          </div>
        </div>
        <div class="col-lg-3 py-3 py-lg-0">
          <div>
            <strong>Ship At</strong><br/>
            <?php if ($ship_at) : ?>
              <time datetime="<?php echo date('c', $ship_at); ?>"><?php echo date('F j, Y', $ship_at); ?></time>
            <?php else : ?>
              --
            <?php endif; ?>
          </div>
        </div>
        <div class="col-lg-3 justify-content-between py-3 py-lg-0">
          <div>
            <strong>Tracking Info</strong><br/>
            <?php if ($tracking_link) : ?>
              (<b class="text-uppercase"><?php echo esc_html($shipping_provider); ?></b>)
              <a href="<?php echo esc_html($tracking_link); ?>" class="link-underline link-underline_darkergray" target="_blank">
                <?php echo esc_html($tracking_number); ?>
              </a>
            <?php else : ?>
              --
            <?php endif; ?>
          </div>
        </div>
        <?php if ($is_active_affiliate) : ?>
          <div class="col-lg-3 justify-content-between pt-3 pt-lg-0">
            <div>
              <strong><?php echo esc_html($gigfiliate_settings->affiliate_term); ?> Details</strong><br/>
              <?php if ($order_affiliate_remote_order_id = get_post_meta( $order_id, 'v_order_affiliate_remote_order_id', true )) : ?>
                <?php
                $order_affiliate_volume_type = get_post_meta( $order_id, 'v_order_affiliate_volume_type', true );
                ?>
                <span class="v-circle v-circle-<?php echo strtolower($order_affiliate_volume_type); ?>-volume v-mr-1"></span>
                <span class="volume-type" style="text-transform: capitalize;"><?php echo esc_html($order_affiliate_volume_type); ?></span>
              <?php else : ?>
                N/A
              <?php endif; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
