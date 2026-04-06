<?php
/**
 * Template Name: Giving
 */

get_header(); ?>

  <div class="page-wrapper container mb-xxl-4 mt-xxl-4">
    <?php get_template_part('template-parts/modules/giving/hero'); ?>
    <div class="row">
      <div class="col">
        <?php while (have_posts()) : the_post(); ?>
          <?php get_template_part('template-parts/modules/giving/content'); ?>
        <?php endwhile; ?>
      </div>
    </div>
  </div>

<?php get_footer(); ?>
