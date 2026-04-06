<?php
/**
 * Template Name: Contact
 */

get_header(); ?>

  <?php get_template_part('template-parts/content/page-header'); ?>
  <div class="container my-5">
    <div class="row">
      <div class="col-lg-8">
        <?php if (have_posts()) : ?>
          <?php while (have_posts()) : the_post(); ?>
            <?php get_template_part('template-parts/content/page'); ?>
          <?php endwhile; ?>
        <?php endif; ?>
      </div>
      <div class="col-lg-4">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Customer Service</h4>
            <div class="card-text">
              <?php echo get_field('right_sidebar'); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php get_template_part('template-parts/content/story-blog-system'); ?>

<?php get_footer(); ?>
