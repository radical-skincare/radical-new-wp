
<table class="shop_table subscription_details">
	<tbody>
    <tr>
      <td><?php esc_html_e( 'Status', 'woocommerce-subscriptions' ); ?></td>
      <td>
        <span class="badge badge-<?php echo strtolower(esc_html( $status )); ?>"><?php echo esc_html($status); ?></span>
      </td>
    </tr>
		<?php do_action( 'wcs_subscription_details_table_before_dates', $subscription ); ?>
    <?php
    $dates_to_display = apply_filters( 'wcs_subscription_details_table_dates_to_display', array(
      'start_date'              => _x( 'Start date', 'customer subscription table header', 'woocommerce-subscriptions' ),
      'last_order_date_created' => _x( 'Last order date', 'customer subscription table header', 'woocommerce-subscriptions' ),
      'next_payment'            => _x( 'Next payment date', 'customer subscription table header', 'woocommerce-subscriptions' ),
      'end'                     => _x( 'End date', 'customer subscription table header', 'woocommerce-subscriptions' ),
      'trial_end'               => _x( 'Trial end date', 'customer subscription table header', 'woocommerce-subscriptions' ),
    ), $subscription );
    foreach ( $dates_to_display as $date_type => $date_title ) : ?>
			<?php $date = $subscription->get_date( $date_type ); ?>
			<?php if ( ! empty( $date ) ) : ?>
				<tr>
					<td><?php echo esc_html( $date_title ); ?></td>
					<td class="d-flex align-items-center">
            <?php if ($date_type == 'next_payment' && !empty($date_type)) : ?>
              <input type="date" class="form-control w-25 d-inline my-1 mr-1" data-subscription_id="<?php echo esc_html($subscription_id); ?>" id="next_payment_input" min="<?php echo date('Y-m-d', strtotime('+2 day')); ?>" value="<?php echo date('Y-m-d', strtotime('+1 day', $subscription->get_time('next_payment'))); ?>"/>
              <div id="next_payment_loader" class="d-none spinner-border" role="status">
                <span class="sr-only">Loading...</span>
              </div>
            <?php else : ?>
              <?php echo esc_html($subscription->get_date_to_display( $date_type )); ?>
            <?php endif; ?>
          </td>
				</tr>
			<?php endif; ?>
		<?php endforeach; ?>
		<?php do_action( 'wcs_subscription_details_table_after_dates', $subscription ); ?>
		<?php if ( WCS_My_Account_Auto_Renew_Toggle::can_user_toggle_auto_renewal( $subscription ) ) : ?>
			<tr>
				<td><?php esc_html_e( 'Auto renew', 'woocommerce-subscriptions' ); ?></td>
				<td>
					<div class="wcs-auto-renew-toggle">
						<?php
						$toggle_classes = array( 'subscription-auto-renew-toggle', 'subscription-auto-renew-toggle--hidden' );
						if ( $subscription->is_manual() ) {
							$toggle_label     = __( 'Enable auto renew', 'woocommerce-subscriptions' );
							$toggle_classes[] = 'subscription-auto-renew-toggle--off';
							if ( WC_Subscriptions::is_duplicate_site() ) {
								$toggle_classes[] = 'subscription-auto-renew-toggle--disabled';
							}
						} else {
							$toggle_label     = __( 'Disable auto renew', 'woocommerce-subscriptions' );
							$toggle_classes[] = 'subscription-auto-renew-toggle--on';
						}
            ?>
						<a href="#" class="<?php echo esc_attr( implode( ' ' , $toggle_classes ) ); ?>" aria-label="<?php echo esc_attr( $toggle_label ) ?>"><i class="subscription-auto-renew-toggle__i" aria-hidden="true"></i></a>
						<?php if ( WC_Subscriptions::is_duplicate_site() ) : ?>
								<small class="subscription-auto-renew-toggle-disabled-note"><?php echo esc_html__( 'Using the auto-renewal toggle is disabled while in staging mode.', 'woocommerce-subscriptions' ); ?></small>
						<?php endif; ?>
					</div>
				</td>
			</tr>
		<?php endif; ?>
		<?php do_action( 'wcs_subscription_details_table_before_payment_method', $subscription ); ?>
		<?php if ( $subscription->get_time( 'next_payment' ) > 0 ) : ?>
			<tr>
				<td><?php esc_html_e( 'Payment', 'woocommerce-subscriptions' ); ?></td>
				<td>
					<span data-is_manual="<?php echo esc_attr( wc_bool_to_string( $subscription->is_manual() ) ); ?>" class="subscription-payment-method"><?php echo esc_html( $subscription->get_payment_method_to_display( 'customer' ) ); ?></span>
				</td>
			</tr>
		<?php endif; ?>
		<?php do_action( 'woocommerce_subscription_before_actions', $subscription ); ?>
		<?php if ( ! empty( $actions ) ) : ?>
			<tr>
				<td><?php esc_html_e( 'Actions', 'woocommerce-subscriptions' ); ?></td>
				<td>
          <?php if (strtolower($status) === 'active') : ?>
            <a href="javascript:void(0)" class="button" id="btn-on_pause" subscription_id="<?php echo esc_html($subscription_id); ?>">
              Pause
            </a>
          <?php endif; ?>
					<?php foreach ( $actions as $key => $action ) : ?>
            <?php
            $action['name'] = ($action['name'] === 'Pause') ? 'Cancel' : $action['name'];
            $action['name'] = ($action['name'] === 'Renew now') ? 'Purchase Now' : $action['name'];
            ?>
            <a href="<?php echo esc_url( $action['url'] ); ?>" class="button <?php echo sanitize_html_class( $key ); ?>">
              <?php echo esc_html( $action['name'] ); ?>
            </a>
          <?php endforeach; ?>
				</td>
			</tr>
		<?php endif; ?>
		<?php do_action( 'woocommerce_subscription_after_actions', $subscription ); ?>
	</tbody>
</table>
