<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
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
$post_id = get_the_ID();
$wcsatt_schemes = get_post_meta($post_id, '_wcsatt_schemes', true);
?>
<div class="fs-1x mb-3">
  <?php if (!is_user_logged_in()) : ?>
    <button class="d-inline-block text-darkergray btn btn-light" data-toggle="modal" data-target="#loginModal" data-product_id=<?php echo esc_attr($post_id); ?>>
      <i class="fa fa-heart"></i>
      Add to favorites
    </button>
  <?php else : ?>
    <?php
    $exist_in_favourites = false;
    $favorites_products = get_user_meta(get_current_user_id(), 'favorite_products');
    $exist_in_favourites = ($favorites_products && in_array($post_id, $favorites_products));
    ?>
    <button id=<?php echo $exist_in_favourites ? 'remove_product_from_favorites' : 'add_product_to_favorites'; ?> class="favorites_action d-inline-block text-darkergray btn btn-light" data-product_id=<?php echo esc_attr($post_id); ?>>
      <i class="fa <?php echo $exist_in_favourites ? 'fa-times' : 'fa-heart-o'; ?>"></i>
      <div class="spinner-grow spinner-grow-sm"></div>
      <span class="favorites_action-text label-three"><?php echo $exist_in_favourites ? 'Remove from Favorites' : 'Add to Favorites'; ?></span>
    </button>
  <?php endif; ?>
</div>

<p class="subscription-product-terms-wrap fs-0.75x mb-0">
  <?php if ($wcsatt_schemes) : ?>
    <a href="javascript:void(0);" title="Subscription Product Terms" class="product-meta d-inline-block text-darkergray mr-2" data-toggle="modal" data-target="#subProductTermsModal">Subscription Product Terms</a>
    <span class="mr-2">|</span>
  <?php endif; ?>
  <a href="javascript:void(0);" title="Delivery &amp; Returns" class="product-meta d-inline-block text-darkergray" data-toggle="modal" data-target="#deliveryModal">Delivery &amp; Returns</a>
</p>
<?php get_template_part('template-parts/modal/subscription-terms'); ?>
<?php get_template_part('template-parts/modal/delivery'); ?>
