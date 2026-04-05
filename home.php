<?php get_header(); ?>

<?php get_template_part('template-parts/page-header'); ?>
<?php if (!have_posts()) : ?>
  <div class="alert alert-warning">
    <?php echo esc_html__('Sorry, no results were found.', 'radical'); ?>
  </div>
  <?php get_search_form(false); ?>
<?php endif; ?>
<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('template-parts/content', get_post_type()); ?>
<?php endwhile; ?>
<?php echo get_the_posts_navigation(); ?>

<?php get_footer(); ?>
