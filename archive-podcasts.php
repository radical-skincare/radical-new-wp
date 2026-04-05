<?php get_header(); ?>

<div class="podcasts-wrapper mt-4">
  <?php get_template_part('template-parts/modules/podcasts/featured'); ?>
  <div class="container mb-4 mt-sm-5">
    <?php if (have_posts()) : ?>
      <div class="row mb-5">
        <div class="col-lg-4 order-2 order-lg-1">
          <?php get_template_part('template-parts/modules/podcasts/filters'); ?>
          <?php get_template_part('template-parts/modules/podcasts/left-sidebar'); ?>
        </div>
        <div class="col-lg-8 order-1 order-lg-2 mb-5 mb-lg-0">
          <?php get_template_part('template-parts/modules/podcasts/main-podcast-details'); ?>
        </div>
      </div>
    <?php else : ?>
      <?php get_template_part('template-parts/content/none'); ?>
    <?php endif; ?>
    <div class="my-5"></div>
    <?php get_template_part('template-parts/content-cta-shop'); ?>
  </div>
</div>

<?php get_footer(); ?>
