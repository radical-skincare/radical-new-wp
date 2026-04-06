<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.1
 */

defined( 'ABSPATH' ) || exit;
// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
  return;
}

global $product;

$columns = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes = apply_filters(
  'woocommerce_single_product_image_gallery_classes',
  array(
    'woocommerce-product-gallery',
    'woocommerce-product-gallery--' . ( $post_thumbnail_id ? 'with-images' : 'without-images' ),
    'woocommerce-product-gallery--columns-' . absint( $columns ),
    'images',
  )
);
$feat_image_url = wp_get_attachment_url( $post_thumbnail_id );
$gallery_images = $product->get_gallery_image_ids();
$gallery_images_count = !empty($gallery_images) ? count($gallery_images) : false;
?>
<style>
.single-product .product-container .woocommerce-product-gallery .product-gallery-slider.hide-carousel-arrows .slider-action,
.single-product .product-container .woocommerce-product-gallery .product-gallery-slider.hide-carousel-arrows .slider-action {
  display: none !important;
}
.vertical-slider.slick:not(.slick-initialized) {
  flex-direction: column;
}
.vertical-slider.slick:not(.slick-initialized) .item {
  width: 100%;
}
.horizontal-slider.slick:not(.slick-initialized) .item {
  flex-basis: 0;
  flex-grow: 1;
  max-width: 100%;
}
</style>
<div class="woocommerce-product-gallery col-lg-6" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0;">
	<div class="row align-items-center">
    <?php if ($gallery_images_count) : ?>
      <div class="product-gallery-slider col-lg-2 d-none d-lg-flex flex-column justify-content-center align-items-center <?php echo $gallery_images_count >= 4 ? 'show-carousel-arrows' : 'hide-carousel-arrows'; ?>">
        <button class="slider-action top-slider-action" type="button">
          <img src="<?php echo get_template_directory_uri() . '/assets/images/arrow-top.svg'; ?>" alt="Arrow Up"/>
        </button>
        <div class="vertical-slider slick">
          <div class="item">
            <img src="<?php echo esc_url($feat_image_url); ?>" class="w-100"/>
          </div>
          <?php foreach ($gallery_images as $index => $item) : ?>
            <div class="item">
              <img src="<?php echo esc_url(wp_get_attachment_url( $item )); ?>" class="w-100"/>
            </div>
          <?php endforeach; ?>
        </div>
        <button class="slider-action bottom-slider-action" type="button">
          <img src="<?php echo get_template_directory_uri() . '/assets/images/arrow-top.svg'; ?>" alt="Arrow Bottom" class="bottom-slider-action_img"/>
        </button>
      </div>
    <?php endif; ?>
    <figure class="woocommerce-product-gallery_main-img-wrap col-lg-<?php echo $gallery_images_count ? '10' : '12'; ?> p-lg-0 h-100 mb-3 mb-lg-0">
      <?php
      $sale_label = get_field('on_sale_label', $product->get_id());
      ?>
      <?php if ($product->is_on_sale()) : ?>
        <?php if ($sale_label) : ?>
          <span class="on-sale"><?php echo esc_html($sale_label); ?></span>
        <?php else : ?>
          <span class="on-sale">Sale!</span>
        <?php endif; ?>
      <?php elseif ($sale_label) : ?>
        <span class="on-sale"><?php echo esc_html($sale_label); ?></span>
      <?php endif; ?>
      <img src="<?php echo esc_url($feat_image_url); ?>" id="product_main-img" class="w-100"/>
    </figure>
    <?php if ($gallery_images_count) : ?>
      <div class="col-12 d-lg-none">
        <div class="product-gallery-slider <?php echo $gallery_images_count >= 4 ? 'show-carousel-arrows' : 'hide-carousel-arrows'; ?>">
          <button class="slider-action left-slider-action" type="button">
            <img src="<?php echo get_template_directory_uri() . '/assets/images/arrow-top.svg'; ?>" alt="Arrow Top" class="left-slider-action_img"/>
          </button>
          <div class="horizontal-slider slick">
            <div class="item">
              <img src="<?php echo esc_url($feat_image_url); ?>" class="w-100"/>
            </div>
            <?php foreach ($gallery_images as $index => $item) : ?>
              <div class="item">
                <img src="<?php echo esc_url(wp_get_attachment_url( $item )); ?>" class="w-100"/>
              </div>
            <?php endforeach; ?>
          </div>
          <button class="slider-action right-slider-action" type="button">
            <img src="<?php echo get_template_directory_uri() . '/assets/images/arrow-top.svg'; ?>" alt="Arrow Bottom" class="right-slider-action_img"/>
          </button>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>
