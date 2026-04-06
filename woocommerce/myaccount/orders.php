<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders );

// customer is already logged in
$current_user_id = get_current_user_id();
$is_active_affiliate = function_exists('gigfiliate_is_active_affiliate') ? gigfiliate_is_active_affiliate($current_user_id) : false;

?>
<h2>My Orders</h2>
<div class="woocommerce_account_orders">
  <?php if ( $has_orders ) : ?>
    <div id="ordersAccordion" class="accordion">
      <?php
      $order_inc = 0;
      ?>
      <?php foreach ( $customer_orders->orders as $key => $customer_order ) : ?>
        <?php
        $order = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
        $order_id = $order->get_order_number();
        $item_count = $order->get_item_count() - $order->get_item_count_refunded();
        $order_status = wc_get_order_status_name( $order->get_status() );
        $is_order_for_customer = get_post_meta($order_id, 'gig_ordered_by', true);
        $order_items = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
        $billing_name = radical_formatted_billing_name($order);
        ?>
        <div class="accordion-item mb-3">
          <div role="tab" id="heading<?php echo esc_attr($key); ?>" class="accordion-item_header">
            <button class="btn accordion-item_btn lh-base text-left <?php echo ($order_inc === 0) ? '' : 'collapsed'; ?> mb-2"  data-toggle="collapse" data-target="#collapse<?php echo esc_attr($key); ?>" aria-expanded="<?php echo ($order_inc === 0) ? 'true' : 'false'; ?>" aria-controls="collapse<?php echo esc_attr($key); ?>">
              <div class="accordion-item_title d-flex align-items-center justify-content-between w-100">
                <div class="row align-items-center">
                  <div class="col-auto">
                    <h3 class="title title-d">
                      <?php echo esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() ); ?>
                    </h3>
                    <p class="fs-1x mb-0"><?php echo $billing_name; ?></p>
                  </div>
                  <div class="col-auto">
                    <span class="badge badge-<?php echo esc_attr(strtolower($order_status)); ?>">
                      <?php echo esc_html($order_status); ?>
                    </span>
                  </div>
                  <?php if ($is_order_for_customer) : ?>
                    <div class="col-auto">
                      <span class="badge badge-info">Ordered For Customer</span>
                    </div>
                  <?php endif; ?>
                  <?php if ($is_active_affiliate) : ?>
                    <div class="col-auto">
                      <?php if ($order_affiliate_remote_order_id = get_post_meta( $order_id, 'v_order_affiliate_remote_order_id', true )) : ?>
                        <?php
                        $order_affiliate_volume_type = get_post_meta( $order_id, 'v_order_affiliate_volume_type', true );
                        ?>
                        <span class="v-circle v-circle-<?php echo esc_attr(strtolower($order_affiliate_volume_type)); ?>-volume v-mr-1"></span>
                        <span class="volume-type" style="text-transform: capitalize;"><?php echo esc_html($order_affiliate_volume_type); ?></span>
                      <?php else : ?>
                        N/A
                      <?php endif; ?>
                    </div>
                  <?php endif; ?>
                </div>
                <div>
                  <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="btn btn-outline-dark mr-3 d-flex align-items-center">
                    View <i class="fa fa-arrow-right ml-1" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
              <span class="accordion-item_icon-show" aria-hidden="true">&plus;</span>
              <span class="accordion-item_icon-hide" aria-hidden="true">&#45;</span>
            </button>
          </div>
          <div id="collapse<?php echo esc_attr($key); ?>" class="collapse <?php echo !$order_inc ? 'show' : ''; ?>" aria-labelledby="heading<?php echo esc_attr($key); ?>" data-parent="#ordersAccordion">
            <div class="accordion-item_body text-dark-gray p-3">
              <div class="row mb-3" style="row-gap: 1rem;">
                <div class="col-lg-4">
                  <div class="card w-100">
                    <div class="card-body">
                      <h4 class="card-title mb-2">Payment Info</h4>
                      <div>
                        Placed on <time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>
                      </div>
                      <?php
                      /* translators: 1: formatted order total 2: total order items */
                      echo wp_kses_post( sprintf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ) );
                      ?>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="card mb-3 w-100">
                    <div class="card-body">
                      <h4 class="cart-title mb-2">Billing Address</h4>
                      <p class="mb-0"><?php echo $billing_name; ?></p>
                      <address class="card-text mb-0">
                        <?php echo radical_formatted_billing_address($order); ?>
                      </address>
                      <?php if ( $billing_email = $order->get_billing_email() ) : ?>
                        <a href="mailto:<?php echo esc_attr($billing_email); ?>" title="Email" class="d-block text-darker-gray"><i class="fa fa-envelope mr-2" aria-hidden="true"></i> <?php echo esc_html( $billing_email ); ?></a>
                      <?php endif; ?>
                      <?php if ( $billing_phone = $order->get_billing_phone() ) : ?>
                        <a href="tel:<?php echo esc_attr($billing_phone); ?>" title="Call" class="d-block text-darker-gray"><i class="fa fa-phone mr-2" aria-hidden="true"></i> <?php echo esc_html( $billing_phone ); ?></a>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="card mb-3 w-100">
                    <div class="card-body">
                      <h4 class="cart-title mb-2">Shipping Address</h4>
                      <p class="mb-0"><?php echo radical_formatted_shipping_name($order); ?></p>
                      <address class="card-text mb-0">
                        <?php echo radical_formatted_shipping_address($order); ?>
                      </address>
                      <?php if ( $shipping_phone = $order->get_shipping_phone() ) : ?>
                        <a href="tel:<?php echo esc_attr($shipping_phone); ?>" title="Call" class="d-block"><i class="fa fa-phone mr-2" aria-hidden="true"></i> <?php echo esc_html($shipping_phone); ?></a>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
              <?php if ($order_items) : ?>
                <div class="list-group mb-3">
                  <?php foreach ($order_items as $item) : ?>
                    <?php
                    if (!apply_filters('woocommerce_order_item_visible', true, $item)) {
                      continue;
                    }
                    $have_renewal_gift = get_post_meta($order->get_ID(), 'have_renewal_gift', true);
                    $gift_product = get_post_meta($order->get_ID(), 'product', true);
                    ?>
                    <div class="list-group-item">
                      <?php if ($have_renewal_gift && $gift_product && $gift_product == $item['product_id']) : ?>
                        <div class="badge badge-primary position-absolute" style="z-index:1">Gift</div>
                      <?php endif; ?>
                      <div class="row align-items-center">
                        <?php if ($image = wp_get_attachment_image_src(get_post_thumbnail_id($item['product_id']), 'single-post-thumbnail')) : ?>
                          <div class="col-sm-2">
                            <img src='<?php echo esc_url($image[0]); ?>' class='w-75 mx-auto d-block'/>
                          </div>
                        <?php endif; ?>
                        <div class="col-sm-10">
                          <a href="<?php echo esc_url(get_the_permalink($item['product_id'])); ?>" class="h4 d-block text-darker-gray mb-2"><?php echo esc_html($item['name']); ?></a>
                          <div class="row align-items-center w-100">
                            <div class="col-auto">
                              <label>Cost</label>
                              <div class="price">
                                <span class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol(); ?></span>
                                <?php echo esc_html($order->get_item_subtotal( $item, false, true )); ?>
                              </div>
                            </div>
                            <div class="col-auto text-center">
                              <label>Quantity</label>
                              <br/>
                              <?php echo esc_html($item['qty']); ?>
                            </div>
                            <div class="col">
                              <label>Total</label>
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
              <?php endif; ?>
              <div>
                <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="d-inline-block link-underline link-underline_darker-gray">
                  View Order Details <i class="fa fa-arrow-right ml-1" aria-hidden="true"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
        <?php
        $order_inc++;
        ?>
      <?php endforeach; ?>
    </div>
    <?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>
    <?php if ( 1 < $customer_orders->max_num_pages ) : ?>
      <div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
        <?php if ( 1 !== $current_page ) : ?>
          <a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>">
            <?php esc_html_e( 'Previous', 'woocommerce' ); ?>
          </a>
        <?php endif; ?>
        <?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
          <a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>">
            <?php esc_html_e( 'Next', 'woocommerce' ); ?>
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  <?php else : ?>
    <div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
      <a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
        <?php esc_html_e( 'Browse products', 'woocommerce' ); ?>
      </a>
      <?php esc_html_e( 'No order has been made yet.', 'woocommerce' ); ?>
    </div>
  <?php endif; ?>
<div>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
