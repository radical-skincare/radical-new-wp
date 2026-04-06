<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
$post_id = get_the_ID();
?>
<div id="product-<?php echo esc_html($post_id); ?>" <?php wc_product_class("", $product ); ?>>
  <div class="container">
    <div class="d-lg-none">
      <?php echo woocommerce_breadcrumb(); ?>
    </div>
    <?php if ($success_alert_text = get_field('success_alert_text')) : ?>
      <div class="alert alert-success mb-3" role="alert">
        <?php echo $success_alert_text; ?>
      </div>
    <?php endif; ?>
    <?php if (str_contains(get_post_field('post_name', $post_id), 'age-defying-exfoliating-pads') && isset($_GET['applycoupon'])) : ?>
      <div class="alert alert-success mb-3" role="alert">Get 20% OFF, Use Coupon <strong style="text-transform: uppercase;"><?= $_GET['applycoupon'] ?></strong> at Checkout!</div>
    <?php endif; ?>
    <div class="row mb-5">
      <?php
      /**
       * Hook: woocommerce_before_single_product_summary.
       *
       * @hooked woocommerce_show_product_sale_flash - 10
       * @hooked woocommerce_show_product_images - 20
       */
      do_action( 'woocommerce_before_single_product_summary' );
      ?>
      <div class="d-flex col-lg-6 align-items-center">
        <div class="single-product_summary w-100">
          <div class="d-none d-lg-block">
            <?php echo woocommerce_breadcrumb(); ?>
          </div>
          <?php
          /**
           * Hook: woocommerce_single_product_summary.
           *
           * @hooked woocommerce_template_single_title - 5
           * @hooked woocommerce_template_single_rating - 10
           * @hooked woocommerce_template_single_price - 10
           * @hooked woocommerce_template_single_excerpt - 20
           * @hooked woocommerce_template_single_add_to_cart - 30
           * @hooked woocommerce_template_single_meta - 40
           * @hooked woocommerce_template_single_sharing - 50
           * @hooked WC_Structured_Data::generate_product_data() - 60
           */
          do_action( 'woocommerce_single_product_summary' );
          ?>
        </div>
      </div>
    </div>
  </div>
	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action( 'woocommerce_after_single_product_summary' );
	?>
</div>
<?php do_action( 'woocommerce_after_single_product' ); ?>
