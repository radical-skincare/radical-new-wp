<?php get_header(); ?>

<?php $site_url = get_site_url(); ?>
<div class="bg-lightest-gray py-5">
  <div class="container">
    <?php get_template_part('template-parts/search/page-header'); ?>
    <?php if (!have_posts()) : ?>
      <div class="alert alert-warning my-3">
        <?php echo esc_html__('Sorry, no results were found.', 'radical'); ?>
      </div>
    <?php endif; ?>
    <div id="search-results" class="row my-3">
      <?php while (have_posts()) : the_post(); ?>
        <?php get_template_part('template-parts/content-search'); ?>
      <?php endwhile; ?>
    </div>
    <div class="pagination mx-auto d-block text-center">
      <?php if (function_exists('radical_paginate_search')) { echo radical_paginate_search(); } ?>
    </div>
  </div>
</div>

<?php get_footer(); ?>
