<?php get_header(); ?>

<div class="container pt-5 mb-5">
  <div class="row">
    <div class="col-lg-8">
      <?php if (wp_get_attachment_url(get_post_thumbnail_id($post->ID))) : ?>
        <?php get_template_part('template-parts/content/single-feat-card'); ?>
      <?php else : ?>
        <div class="mb-5">
          <?php get_template_part('template-parts/content/page-header'); ?>
        </div>
      <?php endif; ?>
      <?php while (have_posts()) : the_post(); ?>
        <?php get_template_part('template-parts/content/single'); ?>
        <?php
        $category = get_the_category();
        $counter = 0;
        if (isset($category[0])) {
            $counter = $category[0]->category_count;
        }
        ?>
        <?php if ($counter > 1) : ?>
          <?php if (function_exists('radical_skincare_post_navigation')) { radical_skincare_post_navigation(); } ?>
        <?php endif; ?>
        <?php
        if (comments_open() || get_comments_number()) {
            comments_template();
        }
        ?>
      <?php endwhile; ?>
    </div>
    <div class="post-sidebar-col col-lg-4">
      <?php get_template_part('template-parts/sidebar/single'); ?>
    </div>
  </div>
</div>

<?php get_footer(); ?>
