<div id="podcasts-left-sidebar">
  <div id="podcasts-left-sidebar_loader-wrap" class="radical-loader-wrap">
    <?php get_template_part('template-parts/components/loader'); ?>
  </div>
  <div id="podcasts-left-sidebar_listing" post-per-page="<?php echo esc_attr(get_option( 'posts_per_page' )); ?>" offset="0">
    <?php if ($podcasts = ArchivePodcasts::getPodcasts('all', (int)get_option( 'posts_per_page' ))) : ?>
      <?php foreach ($podcasts['podcasts'] as $podcast) : ?>
        <?php get_template_part('template-parts/content/podcast-item'); ?>
      <?php endforeach; ?>
      <?php if (count($podcasts['podcasts']) < $podcasts['total_podcasts']) : ?>
        <a href="javascript:void(0)" id="load_more_podcasts" class="d-block link-underline link-underline_darker-gray mx-auto" style="width: fit-content;">Load More</a>
      <?php endif; ?>
    <?php else : ?>
      <div class="alert alert-info" role="alert">
        <i class="fa fa-info-circle mr-3" aria-hidden="true"></i>No upcoming podcasts found.
      </div>
    <?php endif; ?>
  </div>
</div>
