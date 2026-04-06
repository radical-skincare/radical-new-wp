<?php

/**
 * Admin new order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/admin-new-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails\HTML
 * @version 3.7.0
 */

defined('ABSPATH') || exit;
/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action('woocommerce_email_header', $email_heading, $email); ?>

<?php /* translators: %s: Customer billing full name */ ?>
<p><?php printf(esc_html__('Your %s subscription next payment date is: %s', 'woocommerce'), get_bloginfo('name'), date("F j, Y", $next_payment)); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<?php

/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action('woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email);

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action('woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email);

if ($skippable_products) { ?>
  <h3 style="color: #b20839; display: block; font-family: &quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 0 18px; text-align: left;">Skipped Product</h3>
  <p>You will not be charged for Skipped Product items on this upcoming Renewal.</p>
    <div style="margin-bottom: 40px;">
      <table class="td" cellspacing="0" cellpadding="6" border="1" style="color: #5c5c5c; border: 1px solid #e5e5e5; vertical-align: middle; width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" width="100%">
        <thead>
          <tr>
            <th class="td" scope="col" style="color: #5c5c5c; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left;" align="left">Product</th>
            <th class="td" scope="col" style="color: #5c5c5c; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left;" align="left">Price</th>
            <th class="td" scope="col" style="color: #5c5c5c; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left;" align="left">Quantity</th>
            <th class="td" scope="col" style="color: #5c5c5c; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left;" align="left">Total</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $skip_total_amount = 0;
          foreach ($skippable_products as $key => $item) {
            $skip_total_amount += $item['line_total'];
            ?>
            <tr class="order_item">
              <td class="td" style="color: #5c5c5c; border: 1px solid #e5e5e5; padding: 12px; text-align: left; vertical-align: middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; word-wrap: break-word;" align="left">
              <?php
                // Show title/image etc.
                $product = $item->get_product();
                $image = $product->get_image( array( 80, 80 ) );
                echo wp_kses_post( apply_filters( 'woocommerce_order_item_thumbnail', $image, $item ) );

                // Product name.
                echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false ) );

                // SKU.
                if ( $show_sku && $sku ) {
                  echo wp_kses_post( ' (#' . $sku . ')' );
                }

                // allow other plugins to add additional product information here.
                do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, $plain_text );

                wc_display_item_meta(
                  $item,
                  array(
                    'label_before' => '<strong class="wc-item-meta-label" style="float: ' . esc_attr( $text_align ) . '; margin-' . esc_attr( $margin_side ) . ': .25em; clear: both">',
                  )
                );
              ?>
              </td>
              <td class="td" style="color: #5c5c5c; border: 1px solid #e5e5e5; padding: 12px; text-align: left; vertical-align: middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" align="left">
                <?php 
                  echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) );
                ?>
              </td>
              <td class="td" style="color: #5c5c5c; border: 1px solid #e5e5e5; padding: 12px; text-align: left; vertical-align: middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" align="left">
              <?php
                $qty          = $item->get_quantity();
                $refunded_qty = $order->get_qty_refunded_for_item( $item_id );

                if ( $refunded_qty ) {
                  $qty_display = '<del>' . esc_html( $qty ) . '</del> <ins>' . esc_html( $qty - ( $refunded_qty * -1 ) ) . '</ins>';
                } else {
                  $qty_display = esc_html( $qty );
                }
                echo wp_kses_post( apply_filters( 'woocommerce_email_order_item_quantity', $qty_display, $item ) );
              ?>
              </td>
              <td class="td" style="color: #5c5c5c; border: 1px solid #e5e5e5; padding: 12px; text-align: left; vertical-align: middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" align="left">
                <?php
                  echo wc_price( wc_format_decimal( $item->get_total(), '' ), array( 'currency' => $order->get_currency() ) );
                  if ( $item->get_subtotal() !== $item->get_total() ) {
                    /* translators: %s: discount amount */
                    echo '<div style="color: '.get_option( 'woocommerce_email_base_color' ).';">' . sprintf( esc_html__( '%s discount', 'woocommerce' ), wc_price( wc_format_decimal( $item->get_subtotal() - $item->get_total(), '' ), array( 'currency' => $order->get_currency() ) ) ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                  }
                ?>
              </td>
            </tr>
          <?php } ?>
        </tbody>
        <tfoot>
          <tr>
            <th class="td" scope="row" colspan="3" style="color: #5c5c5c; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left; border-top-width: 4px;" align="left">Total:</th>
            <td class="td" style="color: #5c5c5c; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left; border-top-width: 4px;" align="left"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo $skip_total_amount; ?></span></td>
          </tr>
        </tfoot>
      </table>
    </div>
<?php
}

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action('woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email);

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ($additional_content) {
  echo wp_kses_post(wpautop(wptexturize($additional_content)));
}

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action('woocommerce_email_footer', $email);
