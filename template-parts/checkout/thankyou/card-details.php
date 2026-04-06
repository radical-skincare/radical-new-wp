<section class="order-details__cards mb-4">
  <div class="card mb-3">
    <div class="card-body">
      <div class="row flex-wrap">
        <div class="col-lg-3">
          <div>
            <strong><?php echo esc_html_e( 'Order Number:', 'woocommerce' ); ?></strong><br/>
            <?php echo esc_html($order->get_order_number()); ?>
          </div>
        </div>
        <div class="col-lg-3">
          <div>
            <strong><?php echo esc_html_e( 'Total:', 'woocommerce' ); ?></strong><br/>
            <?php echo $order->get_formatted_order_total(); ?>
          </div>
        </div>
        <div class="col-lg-3">
          <div>
            <strong><?php echo esc_html_e( 'Payment method:', 'woocommerce' ); ?></strong><br/>
            <?php if ($order->get_payment_method_title()) : ?>
                <?php echo wp_kses_post( $order->get_payment_method_title() ); ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  </section>
