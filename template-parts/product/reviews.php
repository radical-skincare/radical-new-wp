<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.3.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! comments_open() ) {
  return;
}

$count = $product->get_review_count();
$reviews = get_comments([
  'post_type' => 'product',
  'post_id' => get_the_ID()
]);
?>
<section class="woocommerce-Reviews container-fluid">
	<div id="comments" class="bg-lightestgray row py-5">
    <div class="col-12">
      <?php if ( $reviews ) : ?>
        <div class="commentList-wrap">
          <?php if ($count) : ?>
            <button class="slider-action commentList-wrap_slider-action commentList-wrap_slider-action_left" type="button">
              <img src="<?php echo get_template_directory_uri() . '/assets/images/arrow-top.svg'; ?>" alt="Arrow Top"/>
            </button>
          <?php endif; ?>
          <div class="commentlist">
            <?php foreach ($reviews as $key => $review) : ?>
              <div class="text-center px-3 single-comment">
                <?php if ($rating = get_comment_meta($review->comment_ID, 'rating', true)) : ?>
                  <div class="stars mb-2">
                    <?php for ($i = 1; $i <= $rating; $i++) : ?>
                      <span class="star-<?php echo $i; ?>"></span>
                    <?php endfor; ?>
                  </div>
                <?php endif; ?>
                <div class="comment_content mb-2">
                  <?php echo esc_html($review->comment_content); ?>
                </div>
                <div class="mb-2 font-weight-bold">
                  <?php echo esc_html($review->comment_author); ?>
                </div>
                <div class="font-weight-bold"><?php echo esc_html($key + 1); ?>/<?php echo esc_html($count); ?></div>
              </div>
            <?php endforeach; ?>
            <?php // wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
          </div>
          <?php if ($count) : ?>
            <button class="slider-action commentList-wrap_slider-action commentList-wrap_slider-action_right" type="button">
              <img src="<?php echo get_template_directory_uri() . '/assets/images/arrow-top.svg'; ?>" alt="Arrow Bottom"/>
            </button>
          <?php endif; ?>
        </div>
        <?php
        if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
          echo '<nav class="woocommerce-pagination">';
          paginate_comments_links(
            apply_filters(
              'woocommerce_comment_pagination_args',
              array(
                'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
                'next_text' => is_rtl() ? '&larr;' : '&rarr;',
                'type'      => 'list',
              )
            )
          );
          echo '</nav>';
        endif;
        ?>
      <?php else : ?>
        <div class="woocommerce-noreviews text-center">
          <?php esc_html_e( 'There are no reviews yet.', 'woocommerce' ); ?>
        </div>
      <?php endif; ?>
    </div>
	</div>
</section>
