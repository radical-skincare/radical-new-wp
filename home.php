<?php get_header(); ?>

<?php get_template_part('template-parts/modules/blog/hero'); ?>
<section class="my-5">
  <div class="container">
    <?php if (have_posts()) : ?>
      <?php while (have_posts()) : the_post(); ?>
        <?php get_template_part('template-parts/content/blog'); ?>
      <?php endwhile; ?>
      <?php if (function_exists('radical_skincare_pagination')) { radical_skincare_pagination(); } ?>
    <?php else : ?>
      <?php get_template_part('template-parts/content/none'); ?>
    <?php endif; ?>
  </div>
</section>
<?php get_template_part('template-parts/content/story-blog-system'); ?>

<?php get_footer(); ?>
