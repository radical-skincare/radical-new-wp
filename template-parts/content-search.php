<?php
$site_url = get_site_url();
$post_type = get_post_type();
if ($post_type == 'podcasts') {
  $permalink = $site_url . '/podcasts';
} else if ($post_type == 'press_item') {
  $permalink = $site_url . '/press';
} else {
  $permalink = get_the_permalink();
}
?>
<div class="col-lg-4 mb-4">
  <div class="card h-100 border-0">
    <div class="card-body d-flex flex-column justify-content-between">
      <div>
        <span class="card_post-type card_post-type_<?php echo esc_attr(str_replace('_', '-', $post_type)); ?> d-inline-block text-uppercase fs-0.75x font-weight-bold mb-2">
          <?php echo esc_html($post_type === 'podcasts' ? 'podcast' : str_replace('_', ' ', $post_type)); ?>
        </span>
        <h4 class="card-title"><?php the_title(); ?></h4>
      </div>
      <div class="mb-2">
        <?php echo radical_get_clean_excerpt(get_the_excerpt(), 100); ?>
      </div>
      <div>
        <a class="link-underline link-underline_darker-gray view-btn" href="<?php echo esc_url($permalink); ?>">View More</a>
      </div>
    </div>
  </div>
</div>
