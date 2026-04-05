<?php get_header(); ?>

<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>
    <?php get_template_part('template-parts/content'); ?>
  <?php endwhile; ?>
  <?php the_posts_navigation(); ?>
<?php else : ?>
  <?php get_template_part('template-parts/content/none'); ?>
<?php endif; ?>

<?php get_footer(); ?>
