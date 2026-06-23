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
?>
<h2>Subscriptions</h2>
<style>
.product-item-details .col-auto {
  position: relative;
}
.product-item-details .col-auto .times,
.product-item-details .col-auto .equal {
  position: absolute;
  bottom: 0;
  right: -0.25rem;
  line-height: 1rem;
  font-size: 1rem;
}
.product-item-details .col-auto .equal {
  bottom: 0.25rem;
}
#subscriptionsAccordion .list-group-item .badge {
  position: absolute;
  top: 0;
  right: 0.5rem;
}
#subscriptionsAccordion .list-group-item .badge + .badge {
  right: 5rem;
}
</style>
<div class="woocommerce_account_subscriptions">
	<?php if ( !empty( $subscriptions ) ) : ?>
    <div id="subscriptionsAccordion" class="accordion">
      <?php
      $subscription_inc = 0;
      ?>
      <?php foreach ( $subscriptions as $subscription_id => $subscription ) : ?>
        <?php
        $sub_status = $subscription->get_status();
        $total = $subscription->get_total();
        $next_payment = $total;
        $one_time_skippable_item = get_post_meta($subscription->get_ID(), 'one_time_skippable_item', true);
        if ($one_time_skippable_item) {
          $one_time_skippable_item = json_decode($one_time_skippable_item, true);
          foreach ($one_time_skippable_item as $one_time_skipable_item) {
            foreach ($subscription->get_items() as $item_id => $subscription_items) {
              if ($subscription_items->get_ID() == $item_id) {
                $next_payment -= $subscription_items->get_product()->get_price();
              }
            }
          }
        }
        $is_order_for_customer = get_post_meta($subscription->get_ID(), 'gig_ordered_by', true);
        $skippable_items = get_post_meta($subscription->get_ID(), 'one_time_skippable_item', true);
        $global_skippable_products = get_field('subscription_global_skipped_products', 'option');
        if ($skippable_items) {
          $skippable_items = json_decode($skippable_items, true);
        } else {
          $skippable_items = [];
        }
        if ($global_skippable_products) {
          $global_skippable_products = explode(',', $global_skippable_products);
        } else {
          $global_skippable_products = [];
        }
        $billing_name = radical_formatted_billing_name($subscription);
        $view_subscription_url = $subscription->get_view_order_url();
        ?>
        <div class="accordion-item mb-3">
          <div role="tab" id="heading<?php echo esc_attr($subscription_id); ?>" class="accordion-item_header">
            <button class="btn accordion-item_btn lh-base text-left <?php echo ($subscription_inc === 0) ? '' : 'collapsed'; ?> mb-2"  data-toggle="collapse" data-target="#collapse<?php echo esc_attr($subscription_id); ?>" aria-expanded="<?php echo ($subscription_inc === 0) ? 'true' : 'false'; ?>" aria-controls="collapse<?php echo esc_attr($subscription_id); ?>">
              <div class="accordion-item_title d-flex align-items-center justify-content-between w-100">
                <div class="d-flex align-items-center">
                  <h2 class="title title-d mb-0 mr-3">
                    <?php echo esc_html( sprintf( _x( '#%s', 'hash before order number', 'woocommerce-subscriptions' ), $subscription->get_order_number() ) ); ?>
                    <p class="fs-1x mb-0"><?php echo $billing_name; ?></p>
                  </h2>
                  <span class="badge badge-<?php echo esc_attr($sub_status); ?> mr-3">
                    <?php echo esc_attr( wcs_get_subscription_status_name( $sub_status ) ); ?>
                  </span>
                  <?php if ($is_order_for_customer) : ?>
                    <span class="badge badge-info">Ordered For Customer</span>
                  <?php endif; ?>
                </div>
                <div>
                  <a href="<?php echo esc_url( $view_subscription_url ); ?>" class="btn btn-outline-dark mr-3 d-flex align-items-center">
                    View <i class="fa fa-arrow-right ml-1" aria-hidden="true"></i>
                  </a>
                  <?php do_action( 'woocommerce_my_subscriptions_actions', $subscription ); ?>
                </div>
              </div>
              <span class="accordion-item_icon-show" aria-hidden="true">&plus;</span>
              <span class="accordion-item_icon-hide" aria-hidden="true">&#45;</span>
            </button>
          </div>
          <div id="collapse<?php echo esc_attr($subscription_id); ?>" class="collapse <?php echo !$subscription_inc ? 'show' : ''; ?>" aria-labelledby="heading<?php echo esc_attr($subscription_id); ?>" data-parent="#subscriptionsAccordion">
            <div class="accordion-item_body text-dark-gray p-3">
              <div class="row mb-3" style="row-gap: 1rem;">
                <div class="col-lg-4">
                  <div class="card w-100">
                    <div class="card-body">
                      <h4 class="card-title mb-2">Payment Info</h4>
                      Next Payment date: <?php echo $subscription->get_date_to_display( 'next_payment' ); ?>
                      <br/>
                      Recurring Total: <?php echo get_woocommerce_currency_symbol() . $total . ' / ' . radical_get_subscription_interval_period_text($subscription); ?>
                      <br/>
                      Next Payment: <?php echo get_woocommerce_currency_symbol() . $next_payment; ?>
                      <br/>
                      <?php echo $subscription->get_payment_method_to_display( 'customer' ); ?>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <?php get_template_part('template-parts/account/address-card', null, ['subscription' => $subscription, 'type' => 'billing']); ?>
                </div>
                <div class="col-lg-4">
                  <?php get_template_part('template-parts/account/address-card', null, ['subscription' => $subscription, 'type' => 'shipping']); ?>
                </div>
              </div>
              <div class="list-group mb-3">
                <?php foreach ($subscription->get_items() as $item) : ?>
                  <?php
                  if (!apply_filters('woocommerce_order_item_visible', true, $item)) {
                    continue;
                  }
                  $can_get_product = $item->get_product();
                  $is_product_skipped = $can_get_product && in_array($item->get_product()->get_id(), $skippable_items);
                  $out_of_stock = false;
                  if (!$is_product_skipped) {
                    $is_product_skipped = $can_get_product && in_array($item->get_product()->get_id(), $global_skippable_products);
                    $out_of_stock = $is_product_skipped;
                  }
                  ?>
                  <div class="list-group-item">
                    <div class="row align-items-center">
                      <?php if ($image = wp_get_attachment_image_src(get_post_thumbnail_id($item['product_id']), 'single-post-thumbnail')) : ?>
                        <div class="col-sm-2">
                          <img src='<?php echo esc_url($image[0]); ?>' class='w-75 mx-auto d-block'/>
                        </div>
                      <?php endif; ?>
                      <div class="col-sm-10">
                        <div class="d-flex align-items-center justify-content-between">
                          <h4 class="mb-0 d-inline"><?php echo esc_html($item['name']); ?></h4>
                          <?php if ($is_product_skipped) : ?>
                            <span class="badge badge-skipped">Skipped</span>
                          <?php endif; ?>
                          <?php if ($out_of_stock) : ?>
                            <div class="badge badge-sold-out">Sold Out</div>
                          <?php endif; ?>
                        </div>
                        <div class="product-item-details row align-items-center w-100">
                          <div class="col-auto">
                            <label class="m-0">Cost</label>
                            <div class="price">
                              <span class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol(); ?></span>
                              <?php echo esc_html($subscription->get_item_subtotal( $item, false, true )); ?>
                            </div>
                            <span class="times">*</span>
                          </div>
                          <div class="col-auto text-center">
                            <label class="m-0">Quantity</label>
                            <div><?php echo esc_html($item['qty']); ?></div>
                            <span class="equal">=</span>
                          </div>
                          <div class="col">
                            <label class="m-0">Total</label>
                            <div class="price">
                              <span class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol(); ?></span>
                              <?php echo esc_html($item['line_total']); ?>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
              <div>
                <a href="<?php echo esc_url( $view_subscription_url ); ?>" class="link-underline link-underline_darker-gray">
                  Manage Subscription <i class="fa fa-cogs ml-1" aria-hidden="true"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
        <?php
        $subscription_inc++;
        ?>
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
  <?php else : ?>
		<p class="no_subscriptions woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
			<?php if ( 1 < $current_page ) : ?>
				<?php printf( esc_html__( 'You have reached the end of subscriptions. Go to the %sfirst page%s.', 'woocommerce-subscriptions' ), '<a href="' . esc_url( wc_get_endpoint_url( 'subscriptions', 1 ) ) . '">', '</a>' ); ?>
			<?php else : ?>
				<?php
        esc_html_e( 'You have no active subscriptions.', 'woocommerce-subscriptions' );
				?>
				<a class="link-underline link-underline_darker-gray" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
					<?php esc_html_e( 'Browse products', 'woocommerce-subscriptions' ); ?>
				</a>
      <?php endif; ?>
		</p>
  <?php endif; ?>
  <div class="text-center">
    <a href="<?php echo esc_url(get_site_url()); ?>/radical-on-repeat/" class="btn btn-darkergray mt-5" target="_blank">Learn More about Radical on Repeat</a>
  </div>
</div>
