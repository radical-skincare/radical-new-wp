<?php
/**
 * Template Name: Trylacel
 */

get_header(); ?>

  <?php get_template_part('template-parts/hero/hero-image-right'); ?>
  <section class="container">
    <?php while (have_posts()) : the_post(); ?>
      <?php get_template_part('template-parts/modules/trylacel/content'); ?>
    <?php endwhile; ?>
    <?php get_template_part('template-parts/content/story-blog-system'); ?>
  </section>

<?php get_footer(); ?>
