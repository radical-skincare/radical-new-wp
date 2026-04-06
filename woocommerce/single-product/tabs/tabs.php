<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$essentials_collection_product_id = get_field('essentials_collection_product_id', 'option');
$sweetheart = get_field('sweetheart');
$post_id = get_the_ID();

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */

global $product;
$product_slug = $product->get_slug();
?>
<?php if (get_field('enable_2025_design')) : ?>
  <?php get_template_part('template-parts/product/product-content-2025'); ?>
<?php else : ?>
  <?php if ($product_slug === 'advanced-peptide-antioxidant-serum' || $product_slug === 'advanced-peptide-antioxidant-serum-15ml') : ?>
    <?php get_template_part('template-parts/product/advanced-peptide-antioxidant-serum'); ?>
  <?php elseif ($product_slug === 'age-defying-exfoliating-pads' || $product_slug === 'age-defying-exfoliating-pads-ad' || $product_slug === 'age-defying-exfoliating-pads-ad-bogo') : ?>
    <?php get_template_part('template-parts/product/age-defying-exfoliating-pads'); ?>
  <?php elseif ($post_id === $essentials_collection_product_id) : ?>
    <?php get_template_part('template-parts/product/essentials-collection-content'); ?>
  <?php elseif (!is_null($sweetheart) && isset($sweetheart['enable']) && $sweetheart['enable']) : ?>
    <?php get_template_part('template-parts/product/sweetheart'); ?>
  <?php else : ?>
    <?php get_template_part('template-parts/product/main-content'); ?>
  <?php endif; ?>
<?php endif; ?>
