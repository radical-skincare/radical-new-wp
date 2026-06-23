<?php
$podcast = $args['podcast'];
$is_carousel = $args['is_carousel'] ?? false;
?>
<?php if (is_object($podcast)) : ?>
<?php
$start_date = get_field('start_date', $podcast->ID);
$start_date_strtotime = strtotime($start_date);
?>
<div class="d-flex card card-podcast mb-3 card-podcast-item justify-content-between" post-id="<?php echo esc_attr($podcast->ID); ?>">
  <?php if ($is_carousel) : ?>
    <?php
    $feat_img_url = wp_get_attachment_url(get_post_thumbnail_id( $podcast->ID ));
    ?>
    <div style="background-image:url('<?php echo esc_url($feat_img_url); ?>')" class="card-podcast_feature-image w-100"></div>
  <?php endif; ?>
  <div class="card-body">
    <div class="row mx-0">
      <div class="col-4 card-podcast_play-col d-flex align-items-center justify-content-center">
        <div class="card-podcast_play py-3 mb-0">
          <i class="card-podcast_play-icon fa fa-play" aria-hidden="true"></i>
          <span class="sr-only">Play</span>
        </div>
      </div>
      <div class="d-flex col-8 card-podcast_title-col align-items-center">
        <div>
          <h3 class="card-podcast-title mb-1">
            <?php echo $podcast->post_title; ?>
          </h3>
          <div class="card-podcast_excerpt"><?php echo wp_strip_all_tags(get_the_excerpt($podcast->ID), true); ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
