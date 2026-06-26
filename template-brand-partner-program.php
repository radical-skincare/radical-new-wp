<?php
/**
 * Template Name: Brand Partner Program
 */

get_header(); ?>

  <?php while (have_posts()) : the_post(); ?>
    <?php get_template_part('template-parts/brand-partner/program-content'); ?>
  <?php endwhile; ?>

<?php get_footer(); ?>
