<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     1.6.4
 */

get_header();
?>

  <?php
  do_action('get_header', 'shop');
  do_action('woocommerce_before_main_content');
  $post_id = get_the_ID();
  $hide_content_sections = get_field('hide_content_sections');
  ?>
  <style>
  .product_hide-content-sections .write_a_review_container {
    display: none;
  }
  .product .variations .value select {
    min-height: 3rem;
    width: 100%;
  }
  </style>
  <?php if (!get_post_meta($post_id, '_enable_reviews', true)) : ?>
    <style>
    .write_a_review_container {
      display: none;
    }
    </style>
  <?php endif; ?>
  <main class="<?= $hide_content_sections ? 'product_hide-content-sections' : '' ?> py-3">
    <?php while (have_posts()) : ?>
      <?php
      the_post();
      $product_slug = get_post_field('post_name', get_the_ID());
      ?>
      <?php if ($product_slug === 'multi-brightening-serum') : ?>
<style>
.product-countdown .countdown-timer {
  padding: 8px 0;
  font-size: 14px;
}
.product-countdown .countdown-text {
  margin-right: 8px;
}
.product-countdown .countdown-display {
  font-weight: bold;
  letter-spacing: 1px;
}
.product-countdown .countdown-display_item {
  display: inline-block;
  background-color: #333333;
  color: white;
  padding: 8px;
  border-radius: 8px;
}
</style>
<div class="product-countdown">
  <div class="row justify-content-center">
    <div class="col-lg text-center">
      <div class="countdown-timer">
        <span class="d-block countdown-text">Sale ends in:</span>
        <div class="countdown-display" id="countdown-display">
          <span class="countdown-display_item">00</span>
          <span class="countdown-display_item-separator">:</span>
          <span class="countdown-display_item">00</span>
          <span class="countdown-display_item-separator">:</span>
          <span class="countdown-display_item">00</span>
          <span class="countdown-display_item-separator">:</span>
          <span class="countdown-display_item">00</span>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
(function($) {
'use strict';
$(document).ready(function() {
  // End date: January 11th 2026 11:59PM PST
  const endDate = new Date('2026-01-11T23:59:00-08:00');
  const $countdownItems = $('#countdown-display .countdown-display_item');
  function updateCountdown() {
    const now = new Date();
    const timeLeft = endDate - now;
    if (timeLeft <= 0) {
      $countdownItems.eq(0).text('00');
      $countdownItems.eq(1).text('00');
      $countdownItems.eq(2).text('00');
      $countdownItems.eq(3).text('00');
      return;
    }
    // Calculate days, hours, minutes, and seconds
    let days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
    let hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    let minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
    let seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
    // Format with leading zeros
    days = String(days).padStart(2, '0');
    hours = String(hours).padStart(2, '0');
    minutes = String(minutes).padStart(2, '0');
    seconds = String(seconds).padStart(2, '0');
    // Update individual span elements (days, hours, minutes, seconds)
    $countdownItems.eq(0).text(days);
    $countdownItems.eq(1).text(hours);
    $countdownItems.eq(2).text(minutes);
    $countdownItems.eq(3).text(seconds);
  }
  // Update immediately
  updateCountdown();
  // Update every second
  setInterval(updateCountdown, 1000);
});
})(jQuery);
</script>
      <?php endif; ?>
      <div class="product-container">
        <?php
        do_action('woocommerce_shop_loop');
        ?>
        <?php get_template_part('template-parts/shop-sales-notice'); ?>
        <?php
        wc_get_template_part('content', 'single-product');
        ?>
      </div>
    <?php endwhile; ?>
    </main>
  <?php
  do_action('woocommerce_after_main_content');
  do_action('get_footer', 'shop');
  ?>

<?php get_footer(); ?>
