<?php
/**
 * Template Name: Press
 */

get_header(); ?>

  <?php while (have_posts()) : the_post(); ?>
    <?php get_template_part('template-parts/modules/press/hero'); ?>
    <?php get_template_part('template-parts/modules/press/content'); ?>
  <?php endwhile; ?>

<?php get_footer(); ?>
