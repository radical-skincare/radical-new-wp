<?php
/**
 * Template Name: Impact Fund
 */

get_header(); ?>

  <?php while (have_posts()) : the_post(); ?>
    <?php get_template_part('template-parts/hero/hero-image-right'); ?>
    <?php get_template_part('template-parts/content/intro'); ?>
    <?php get_template_part('template-parts/modules/impact-fund/selection-process'); ?>
    <?php get_template_part('template-parts/modules/impact-fund/nominated-organizations'); ?>
    <?php get_template_part('template-parts/modules/impact-fund/submit-nomination'); ?>
  <?php endwhile; ?>

<?php get_footer(); ?>
