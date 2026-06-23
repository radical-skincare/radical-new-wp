
<?php
$subscription = $args['subscription'];
$action_links = $args['action_links'];
?>
<?php if ($subscription->get_time( 'next_payment' ) > 0) : ?>
  <div class="card mb-3">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h4 class="cart-title mb-0">Payment</h4>
        <?php if (isset($action_links['change_payment'])) : ?>
          <a href="<?php echo $action_links['change_payment']; ?>" title="Change Payment Method" class="text-darker-gray">
            <span class="sr-only">Change Payment Method</span>
            <i class="fa fa-cogs" aria-hidden="true"></i>
          </a>
        <?php endif; ?>
      </div>
      <span data-is_manual="<?php echo esc_attr( wc_bool_to_string( $subscription->is_manual() ) ); ?>" class="subscription-payment-method"><?php echo esc_html( $subscription->get_payment_method_to_display( 'customer' ) ); ?></span>
    </div>
  </div>
<?php endif; ?>
