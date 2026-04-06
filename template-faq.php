<?php
/**
 * Template Name: FAQ
 */

get_header(); ?>

  <?php while (have_posts()) : the_post(); ?>
    <?php get_template_part('template-parts/content/page-header'); ?>
    <?php get_template_part('template-parts/modules/faq/content'); ?>
  <?php endwhile; ?>

<?php get_footer(); ?>
