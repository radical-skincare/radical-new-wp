<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
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

defined('ABSPATH') || exit();
?>

<?php get_header('shop'); ?>
<?php get_template_part('template-parts/modules/page/header'); ?>
<?php /* <?php // commented out banner block ?> */ ?>
  <div class="container py-5">
    <?php get_template_part('template-parts/shop-sales-notice'); ?>
    <?php
    /**
     * Hook: woocommerce_before_main_content.
     *
     * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
     * @hooked woocommerce_breadcrumb - 20
     * @hooked WC_Structured_Data::generate_website_data() - 30
     */
    do_action('woocommerce_before_main_content');
    ?>
    <div class="row">
      <div class="col-lg-3 mb-4 mt-lg-0">
        <?php get_template_part('template-parts/shop-sidebar'); ?>
      </div>
      <div class="col-lg-9">
        <div class="row">
          <div class="col">
            <?php
            do_action( 'woocommerce_before_shop_loop' );
            ?>
          </div>
        </div>
        <?php if (have_posts()) : ?>
          <?php
          $conditional_product_sale = get_field('conditional_product_sale', 'option');
          ?>
          <div class="row">
            <?php while ( have_posts() ) : the_post(); ?>
              <div class="col-lg-4 mb-4">
                <?php get_template_part('template-parts/content-product'); ?>
              </div>
            <?php endwhile; ?>
          </div>
          <?php
          /**
           * woocommerce_after_shop_loop hook.
           *
           * @hooked woocommerce_pagination - 10
           */
          do_action( 'woocommerce_after_shop_loop' );
          ?>
        <?php endif; ?>
      </div>
    </div>
    <?php
    /**
     * Hook: woocommerce_after_main_content.
     *
     * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
     */
    do_action('woocommerce_after_main_content');
    ?>
  </div>
<?php get_footer('shop'); ?>
