<?php
/**
 * Template Name: Brand Partner Program
 */

get_header(); ?>

<main>
  <div class="container py-5">
    <?php while (have_posts()) : the_post(); ?>
      <article <?php post_class(); ?>>
        <h1><?php the_title(); ?></h1>
        <div class="entry-content">
          <?php get_template_part('template-parts/brand-partner/program-content'); ?>
        </div>
      </article>
    <?php endwhile; ?>
  </div>
</main>

<?php get_footer(); ?>
