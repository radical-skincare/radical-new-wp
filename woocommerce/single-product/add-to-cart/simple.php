<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

$post_id = get_the_ID();
// Custom urgency stock display
if ( $product->get_manage_stock() && $product->is_in_stock() ) {
	$stock_quantity = $product->get_stock_quantity();
	if ( $stock_quantity !== null && $stock_quantity > 0 ) {
		$stock_class = $stock_quantity <= 5 ? 'text-danger' : '';
		echo '<p class="stock in-stock ' . esc_attr( $stock_class ) . '">Hurry, there are only ' . esc_html( $stock_quantity ) . ' left in stock.</p>';
	} else {
		echo wc_get_stock_html( $product ); // WPCS: XSS ok. Fallback for edge cases
	}
} else {
	echo wc_get_stock_html( $product ); // WPCS: XSS ok. For products that don't manage stock
}
$is_lip_luster = false;
$visibly_sold_out = get_field('visibly_sold_out', $post_id);
$can_access_vip_product = radical_can_access_vip_product_product_cat($post_id);

?>
<?php if (radical_cannot_access_brand_partner_product($post_id)) : ?>
  <?php get_template_part('template-parts/components/brand-partner-exclusive'); ?>
  <?php
    return;
  ?>
<?php endif; ?>
<style>
.first-payment-date {
  display: none;
}
/* Stock urgency styling */
.stock.in-stock {
	font-weight: bold;
}
.stock.in-stock.text-danger {
  color: #dc3545 !important;
}
.stock.in-stock:not(.text-danger) {
  color: #000 !important;
}
</style>
<?php if ( $product->is_in_stock() && !$visibly_sold_out) : ?>
	<?php if (!$can_access_vip_product) : ?>
		<?php get_template_part('template-parts/components/active-subscriber-restricted'); ?>
	<?php elseif ($is_lip_luster) : ?>
		<?php get_template_part('template-parts/components/lip-luster-waitlist'); ?>
	<?php else : ?>
		<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>
		<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
			<?php get_template_part('template-parts/product/sub-options'); ?>
      <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
	    <div class="d-flex" style="column-gap: 0.5rem;">
				<?php
				do_action( 'woocommerce_before_add_to_cart_quantity' );
				woocommerce_quantity_input(
					array(
						'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
						'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
						'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
					)
				);
				do_action( 'woocommerce_after_add_to_cart_quantity' );
		    ?>
				<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button btn btn-primary" style="flex: 1;">
		      <?php echo esc_html( $product->single_add_to_cart_text() ); ?>
		    </button>
	    </div>
			<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
		</form>
		<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
	<?php endif; ?>
<?php endif; ?>
