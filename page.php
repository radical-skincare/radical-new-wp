<?php get_header(); ?>

<main>
  <?php get_template_part('template-parts/content/page-header'); ?>
  <div class="container py-5">
    <?php while (have_posts()) : the_post(); ?>
      <?php get_template_part('template-parts/content-page'); ?>
    <?php endwhile; ?>
  </div>
</main>

<?php get_footer(); ?>
