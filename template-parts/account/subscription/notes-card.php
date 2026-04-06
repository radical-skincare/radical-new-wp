<?php if ( $notes = $subscription->get_customer_order_notes() ) : ?>
  <div class="card">
    <div class="card-body">
      <h4 class="cart-title mb-0"><?php echo esc_html_e( 'Subscription Updates', 'woocommerce-subscriptions' ); ?></h4>
      <div class="list-group list-group-flush">
        <?php foreach ( $notes as $note ) : ?>
          <div class="list-group-item">
            <h5 class="mb-1"><?php echo esc_html(date_i18n( _x( 'l jS \o\f F Y, h:ia', 'date on subscription updates list. Will be localized', 'woocommerce-subscriptions' ), wcs_date_to_time( $note->comment_date ) )); ?></h5>
            <p class="mb-0"><?php echo $note->comment_content; ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
<?php endif; ?>
