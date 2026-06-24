<?php
/**
 * Related Orders table on the View Subscription page
 *
 * @author   Prospress
 * @category WooCommerce Subscriptions/Templates
 * @version  2.6.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$gigfiliate_settings = json_decode(get_option('gigfiliate_settings'));
$status = wcs_get_subscription_status_name( $subscription->get_status() );
$current_user_id = get_current_user_id();
$is_active_affiliate = function_exists('gigfiliate_is_active_affiliate') ? gigfiliate_is_active_affiliate($current_user_id) : false;
?>
<div class="accordion" id="subscriptionDetailsAccordion">
  <div class="accordion-item mb-3">
    <div class="accordion-item_header" id="subscriptionDetailsHeading1" role="tab">
      <button aria-controls="subscriptionDetailsCollapse1" aria-expanded="<?php echo strtolower($status) === 'active' ? 'false' : ''; ?>" class="btn accordion-item_btn lh-base text-left mb-2 <?php echo strtolower($status) === 'active' ? 'collapsed' : ''; ?>" data-target="#subscriptionDetailsCollapse1" data-toggle="collapse">
        <div class="accordion-item_title">
          <h2 class="title title-d mb-0">Subscription Renewal Orders</h2>
        </div>
        <span aria-hidden="true" class="accordion-item_icon-show">+</span> <span aria-hidden="true" class="accordion-item_icon-hide">-</span>
      </button>
    </div>
    <div aria-labelledby="subscriptionDetailsHeading1" class="collapse <?php echo strtolower($status) === 'active' ? '' : 'show'; ?>" data-parent="#subscriptionDetailsAccordion" id="subscriptionDetailsCollapse1">
      <div class="accordion-item_body text-dark-gray p-3">
        <table class="shop_table shop_table_responsive my_account_orders woocommerce-orders-table woocommerce-MyAccount-orders woocommerce-orders-table--orders">
          <thead>
            <tr>
              <th class="order-number woocommerce-orders-table__header woocommerce-orders-table__header-order-number">
                <span class="nobr"><?php esc_html_e( 'Order', 'woocommerce-subscriptions' ); ?></span>
              </th>
              <th class="order-date woocommerce-orders-table__header woocommerce-orders-table__header-order-date woocommerce-orders-table__header-order-date">
                <span class="nobr"><?php esc_html_e( 'Date', 'woocommerce-subscriptions' ); ?></span>
              </th>
              <th class="order-status woocommerce-orders-table__header woocommerce-orders-table__header-order-status">
                <span class="nobr"><?php esc_html_e( 'Status', 'woocommerce-subscriptions' ); ?></span>
              </th>
              <?php if ($is_active_affiliate) : ?>
                <th class="order-affiliate-details woocommerce-orders-table__header woocommerce-orders-table__header-affiliate-details">
                  <span class="nobr"><?php echo esc_html($gigfiliate_settings->affiliate_term); ?></span>
                </th>
              <?php endif; ?>
              <th class="order-total woocommerce-orders-table__header woocommerce-orders-table__header-order-total">
                <span class="nobr"><?php echo esc_html_x( 'Total', 'table heading', 'woocommerce-subscriptions' ); ?></span>
              </th>
              <th class="order-actions woocommerce-orders-table__header woocommerce-orders-table__header-order-actions">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ( $subscription_orders as $subscription_order ) : ?>
              <?php
              $order = wc_get_order( $subscription_order );
              if ( ! $order ) {
                continue;
              }
              $item_count = $order->get_item_count();
              $order_date = $order->get_date_created();
              $order_status = $order->get_status();
              ?>
              <tr class="order woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order_status ); ?>">
                <td class="order-number woocommerce-orders-table__cell woocommerce-orders-table__cell-order-number" data-title="<?php esc_attr_e( 'Order Number', 'woocommerce-subscriptions' ); ?>">
                  <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
                    <?php echo sprintf( esc_html_x( '#%s', 'hash before order number', 'woocommerce-subscriptions' ), esc_html( $order->get_order_number() ) ); ?>
                  </a>
                </td>
                <td class="order-date woocommerce-orders-table__cell woocommerce-orders-table__cell-order-date" data-title="<?php esc_attr_e( 'Date', 'woocommerce-subscriptions' ); ?>">
                  <time datetime="<?php echo esc_attr( $order_date->date( 'Y-m-d' ) ); ?>" title="<?php echo esc_attr( $order_date->getTimestamp() ); ?>"><?php echo wp_kses_post( $order_date->date_i18n( wc_date_format() ) ); ?></time>
                </td>
                <td class="order-status woocommerce-orders-table__cell woocommerce-orders-table__cell-order-status" data-title="<?php esc_attr_e( 'Status', 'woocommerce-subscriptions' ); ?>" style="white-space: nowrap;">
                  <span class="badge badge-<?php echo esc_attr(strtolower($order_status)); ?>"><?php echo esc_html( wc_get_order_status_name( $order_status ) ); ?></span>
                </td>
                <?php if ($is_active_affiliate) : ?>
                  <td class="order-affiliate-details woocommerce-orders-table__cell woocommerce-orders-table__cell-order-affiliate-details" data-title="<?php echo esc_attr($gigfiliate_settings->affiliate_term); ?>" style="white-space: nowrap;">
                    <?php if ($order_affiliate_remote_order_id = get_post_meta( $order_id, 'v_order_affiliate_remote_order_id', true )) : ?>
                      <?php
                      $order_affiliate_volume_type = get_post_meta( $order_id, 'v_order_affiliate_volume_type', true );
                      ?>
                      <span class="v-circle v-circle-<?php echo esc_attr(strtolower($order_affiliate_volume_type)); ?>-volume v-mr-1"></span>
                      <span class="volume-type" style="text-transform: capitalize;"><?php echo esc_html($order_affiliate_volume_type); ?></span>
                    <?php else : ?>
                      N/A
                    <?php endif; ?>
                  </td>
                <?php endif; ?>
                <td class="order-total woocommerce-orders-table__cell woocommerce-orders-table__cell-order-total" data-title="<?php echo esc_attr_x( 'Total', 'Used in data attribute. Escaped', 'woocommerce-subscriptions' ); ?>">
                  <?php
                  // translators: $1: formatted order total for the order, $2: number of items bought
                  echo wp_kses_post( sprintf( _n( '%1$s for %2$d item', '%1$s for %2$d items', $item_count, 'woocommerce-subscriptions' ), $order->get_formatted_order_total(), $item_count ) );
                  ?>
                </td>
                <td class="order-actions woocommerce-orders-table__cell woocommerce-orders-table__cell-order-actions">
                  <?php
                  $actions = array();
                  if ( $order->needs_payment() && wcs_get_objects_property( $order, 'id' ) == $subscription->get_last_order( 'ids', 'any' ) ) {
                    $actions['pay'] = array(
                      'url'  => $order->get_checkout_payment_url(),
                      'name' => esc_html_x( 'Pay', 'pay for a subscription', 'woocommerce-subscriptions' ),
                    );
                  }
                  if ( in_array( $order->get_status(), apply_filters( 'woocommerce_valid_order_statuses_for_cancel', array( 'pending', 'failed' ), $order ) ) ) {
                    $redirect = wc_get_page_permalink( 'myaccount' );
                    if ( wcs_is_view_subscription_page() ) {
                      $redirect = $subscription->get_view_order_url();
                    }
                    $actions['cancel'] = array(
                      'url'  => $order->get_cancel_order_url( $redirect ),
                      'name' => esc_html_x( 'Cancel', 'an action on a subscription', 'woocommerce-subscriptions' ),
                    );
                  }
                  $actions['view'] = array(
                    'url'  => $order->get_view_order_url(),
                    'name' => esc_html_x( 'View', 'view a subscription', 'woocommerce-subscriptions' ),
                  );
                  $actions = apply_filters( 'woocommerce_my_account_my_orders_actions', $actions, $order );

                  if ( $actions ) {
                    foreach ( $actions as $key => $action ) {
                      echo wp_kses_post( '<a href="' . esc_url( $action['url'] ) . '" class="link-underline link-underline_darker-gray ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>' );
                    }
                  }
                  ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="accordion-item mb-3">
    <div class="accordion-item_header" id="subscriptionDetailsHeading0" role="tab">
      <button aria-controls="subscriptionDetailsCollapse0" aria-expanded="true" class="btn accordion-item_btn lh-base text-left mb-2" data-target="#subscriptionDetailsCollapse0" data-toggle="collapse">
        <div class="accordion-item_title">
          <h2 class="title title-d mb-0"><?php esc_html_e( 'Subscription Updates', 'woocommerce-subscriptions' ); ?></h2>
        </div>
        <span aria-hidden="true" class="accordion-item_icon-show">+</span> <span aria-hidden="true" class="accordion-item_icon-hide">-</span>
      </button>
    </div>
    <div aria-labelledby="subscriptionDetailsHeading0" class="collapse show" data-parent="#subscriptionDetailsAccordion" id="subscriptionDetailsCollapse0">
      <div class="accordion-item_body text-dark-gray p-3">
        <div id="subscription-updates" class="list-group">
          <?php if ( $notes = $subscription->get_customer_order_notes() ) : ?>
            <?php foreach ( $notes as $note ) : ?>
              <div class="list-group-item">
                <p class="mb-0">
                  <?php echo esc_html(date_i18n( _x( 'l jS \o\f F Y, h:ia', 'date on subscription updates list. Will be localized', 'woocommerce-subscriptions' ), wcs_date_to_time( $note->comment_date ) )); ?>
                  <br/>
                  <?php echo $note->comment_content; ?>
                </p>
              </div>
            <?php endforeach; ?>
          <?php else : ?>
            <div class="list-group-item no-subscription-updates">No subscription updates yet.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <div class="accordion-item mb-3">
    <div class="accordion-item_header" id="subscriptionDetailsHeading2" role="tab">
      <button aria-controls="subscriptionDetailsCollapse2" aria-expanded="false" class="btn accordion-item_btn lh-base text-left mb-2 collapsed" data-target="#subscriptionDetailsCollapse2" data-toggle="collapse">
        <div class="accordion-item_title">
          <h2 class="title title-d mb-0">Radical on Repeat Gifts</h2>
        </div>
        <span aria-hidden="true" class="accordion-item_icon-show">+</span> <span aria-hidden="true" class="accordion-item_icon-hide">-</span>
      </button>
    </div>
    <div aria-labelledby="subscriptionDetailsHeading2" class="collapse" data-parent="#subscriptionDetailsAccordion" id="subscriptionDetailsCollapse2">
      <div class="accordion-item_body text-dark-gray p-3">
        <?php get_template_part('template-parts/account/subscription/ror-gifts', null, ['subscription' => $subscription]); ?>
      </div>
    </div>
  </div>
</div>

<?php do_action( 'woocommerce_subscription_details_after_subscription_related_orders_table', $subscription ); ?>
