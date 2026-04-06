<?php
/**
 * My Subscriptions section on the My Account page
 *
 * @author   Prospress
 * @category WooCommerce Subscriptions/Templates
 * @version  2.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$site_url = get_site_url();
?>
<style>
</style>
<?php if ( ! empty( $subscriptions ) ) : ?>
  <div class="existing-subscriptions list-group">
    <?php foreach ( $subscriptions as $subscription_id => $subscription ) : ?>
      <?php
      $items = [];
      $gig_ordered_by = get_post_meta($subscription->get_id(), 'gig_ordered_by', true);
      $skippable_items = get_post_meta($subscription->get_ID(), 'one_time_skippable_item', true);
      if ($skippable_items) {
        $skippable_items = json_decode($skippable_items, true);
      } else {
        $skippable_items = [];
      }
      foreach ($subscription->get_items() as $item) {
        $newItem = [];
        if ($image = wp_get_attachment_image_src(get_post_thumbnail_id($item['product_id']), 'single-post-thumbnail')) {
          $newItem['image'] = $image[0];
        }
        $newItem['name'] = $item['name'];
        $newItem['product_id'] = $item['product_id'];
        $newItem['qty'] = $item['qty'];
        $newItem['line_total'] = $item['line_total'];
        $newItem['product_id'] = $item['product_id'];
        $newItem['item_subtotal'] = $subscription->get_item_subtotal( $item, false, true );
        $newItem['is_skipped'] = in_array($item['product_id'], $skippable_items);
        $items[] = $newItem;
      }
      $show_next_payment = !$subscription->is_manual() && $subscription->has_status('active') && $subscription->get_time( 'next_payment' ) > 0;
      ?>
      <div class="existing-subscription list-group-item">
        <div class="row mb-3 align-items-start">
          <div class="<?php echo $show_next_payment ? 'col-6' : 'col'; ?>">
            <div>
              <h5 class="title title-d mb-0 d-inline">
                Subscription <?php echo esc_html( sprintf( _x( '#%s', 'hash before order number', 'woocommerce-subscriptions' ), $subscription->get_order_number() ) ); ?>
              </h5>
              <?php if ($gig_ordered_by) : ?>
                <span class="badge badge-info fs-6">Orderd For Customer</span>
              <?php endif; ?>
            </div>
            <p class="m-0"><?php echo esc_attr( $subscription->get_billing_email() ); ?></p>
          </div>
          <?php if ($show_next_payment) : ?>
            <div class="col-6">
              <p class="m-0">Next Payment: <?php echo esc_attr( $subscription->get_date_to_display( 'next_payment' ) ); ?></p>
              <small><?php echo esc_html($subscription->get_payment_method_to_display( 'customer' )); ?></small>
            </div>
          <?php endif; ?>
        </div>
        <?php foreach ($subscription->get_items() as $key => $item) : ?>
          <?php
          $product = $item->get_product();
          $image_src = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'single-post-thumbnail');
          $quantity = $item->get_quantity();
          ?>
          <div class="row mb-3 align-items-center">
            <div class="col-3">
              <?php if ($image_src) : ?>
                <div class="existing-subscription_img-wrap">
                  <img src="<?php echo esc_url($image_src[0]); ?>" alt="<?php echo esc_attr($image_src[1]); ?>" class="d-block mx-auto" style="height: 4rem;"/>
                  <?php if ($quantity > 1) : ?>
                    <span class="quantity"><?php echo esc_html($quantity); ?></span>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>
            <div class="col-9">
              <?php if (in_array($product->get_id(), $skippable_items)) : ?>
                <span class="badge badge-skipped">Skipped</span><br/>
              <?php endif; ?>
              <?php echo esc_html($product->get_name()); ?>
            </div>
          </div>
        <?php endforeach; ?>
        <div class="col d-flex justify-content-around align-items-center">
          <button type="button" class="link-underline link-underline_darker-gray subscription_quick_view"
            data-toggle="modal"
            data-target="#subscriptionQuickViewModel"
            data-id="<?php echo esc_attr($subscription->get_ID()); ?>"
            data-start_date="<?php echo esc_attr(date('F j, Y', $subscription->get_time('start_date'))); ?>"
            data-last_order_date="<?php echo esc_attr(date('F j, Y', $subscription->get_time('last_order_date_created'))); ?>"
            data-next_order_date="<?php echo esc_attr(date('F j, Y', $subscription->get_time('next_payment'))); ?>"
            data-items="<?php echo esc_attr(json_encode($items)); ?>"
            data-billing_name="<?php echo esc_attr(radical_formatted_billing_name($subscription)); ?>"
            data-billing_address="<?php echo esc_attr(radical_formatted_billing_address($subscription)); ?>"
            data-billing_email="<?php echo esc_attr($subscription->billing_email); ?>"
            data-shipping_name="<?php echo esc_attr(radical_formatted_shipping_name($subscription)); ?>"
            data-shipping_address="<?php echo esc_attr(radical_formatted_shipping_address($subscription)); ?>"
            data-shipping_email="<?php echo esc_attr($subscription->shipping_email); ?>"
            data-payment="<?php echo esc_attr(strip_tags($subscription->get_payment_method_to_display('customer'))); ?>"
            data-order_for_customer="<?php echo esc_attr($gig_ordered_by); ?>"
            data-view_more=<?php echo esc_url($site_url . '/account/view-subscription/' . $subscription->get_ID()); ?>>Quick View</button>
          <button type="button" class="btn btn-dark add_product_to_subscription" subscription_id="<?php echo esc_attr($subscription_id); ?>">Add to Subscription</button>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php if ( 1 < $max_num_pages ) : ?>
    <div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
      <?php if ( 1 !== $current_page ) : ?>
        <a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'subscriptions', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'woocommerce-subscriptions' ); ?></a>
      <?php endif; ?>

      <?php if ( intval( $max_num_pages ) !== $current_page ) : ?>
        <a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'subscriptions', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'woocommerce-subscriptions' ); ?></a>
      <?php endif; ?>
    </div>
  <?php endif; ?>
  <div class="modal fade" id="subscriptionQuickViewModel" tabindex="-1" role="dialog" aria-labelledby="subscriptionQuickViewModelTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div>
            <h5 class="modal-title" id="subscriptionQuickViewModel-subscription-no" style="display: inline">Subscription Quick View</h5>
            <span class="badge badge-info fs-6 subscriptionQuickViewModel_order-for-customer">Orderd For Customer</span>
          </div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p><strong>Payment:</strong> <span id="subscriptionQuickViewModel-payment-method"></span></p>
          <div class="card mb-3">
            <div class="card-body">
              <div class="row">
                <div class="col-sm-4">
                  <h5 class="card-title">Start Date</h5>
                  <p class="card-text" id="subscriptionQuickViewModel-start-date"></p>
                </div>
                <div class="col-sm-4">
                  <h5 class="card-title">Last Order Date</h5>
                  <p class="card-text" id="subscriptionQuickViewModel-last-order-date"></p>
                </div>
                <div class="col-sm-4">
                  <h5 class="card-title">Next Order</h5>
                  <p class="card-text" id="subscriptionQuickViewModel-next-order-date"></p>
                </div>
              </div>
            </div>
          </div>
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title">Products</h5>
              <div id="subscriptionQuickViewModel_productsList" class="list-group"></div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Billing Address</h5>
                  <div class="card-text">
                    <b id="subscriptionQuickViewModel-billing-address-name"></b><br>
                    <p id="subscriptionQuickViewModel-billing-address" class="mb-0"></p>
                    <p id="subscriptionQuickViewModel-billing-email" class="mb-0"></p>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Shipping Address</h5>
                  <div class="card-text">
                    <b id="subscriptionQuickViewModel-shipping-address-name"></b><br>
                    <p id="subscriptionQuickViewModel-shipping-address" class="mb-0"></p>
                    <p id="subscriptionQuickViewModel-shipping-email" class="mb-0"></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <a href="#" type="button" class="btn btn-darkergray" id="subscriptionQuickViewModel-view_more">View More</a>
        </div>
      </div>
    </div>
  </div>
<?php else : ?>
  <p class="no_subscriptions woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
    <?php if ( 1 < $current_page ) :
      printf( esc_html__( 'You have reached the end of subscriptions. Go to the %sfirst page%s.', 'woocommerce-subscriptions' ), '<a href="' . esc_url( wc_get_endpoint_url( 'subscriptions', 1 ) ) . '">', '</a>' );
    else :
      esc_html_e( 'You have no active subscriptions.', 'woocommerce-subscriptions' );
      ?>
      <a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
        <?php esc_html_e( 'Browse products', 'woocommerce-subscriptions' ); ?>
      </a>
    <?php endif; ?>
  </p>
<?php endif; ?>
