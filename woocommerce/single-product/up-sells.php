<?php
/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php if ($upsells) : ?>
  <section class="up-sells upsells container">
    <?php if ($heading = apply_filters( 'woocommerce_product_upsells_products_heading', __( 'You may also like&hellip;', 'woocommerce' ) )) : ?>
      <h3 class="fs-2x text-center mb-3"><?php echo $heading; ?></h3>
    <?php endif; ?>
    <div class="row justify-content-center">
      <?php foreach ($upsells as $upsell) : ?>
        <?php
        $product = get_post( $upsell->get_id() );
        ?>
        <div class="col-lg-3 mb-3 mb-lg-0">
          <?php
          set_query_var('product', $product);
          get_template_part('template-parts/content-product');
          ?>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
  <?php
  wp_reset_postdata();
  ?>
<?php endif; ?>
