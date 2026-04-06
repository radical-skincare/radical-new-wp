<?php
/**
 * Template Name: Hero Right Sidebar
 */

get_header(); ?>

  <?php while (have_posts()) : the_post(); ?>
    <?php get_template_part('template-parts/modules/flex/hero-right-slider'); ?>
  <?php endwhile; ?>

<?php get_footer(); ?>
