<?php
/**
 * Template Name: Clean Conscious (Old)
 */

get_header(); ?>

  <div class="page-wrapper mt-md-4">
    <div class="page-container container card mb-md-4">
      <div class="row">
        <div class="col p-0">
          <?php while (have_posts()) : the_post(); ?>
            <?php get_template_part('template-parts/modules/clean-conscious/content-old'); ?>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
  </div>

<?php get_footer(); ?>
