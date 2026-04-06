<?php
$feat_img_url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
$author = new WP_User( $post->post_author );
$author_id = $author->ID;
$author_display_name = $author->display_name;
?>
<div class="mb-4">
  <?php echo radical_get_breadcrumb('mb-3'); ?>
  <img src="<?php echo esc_url($feat_img_url); ?>" class="img-fluid w-100 rounded mb-3" alt="<?php the_title_attribute(); ?>">
  <div class="text-left">
    <h1 class="ff-orpheus text-darker-gray fs-2x"><?php the_title(); ?></h1>
    <p class="post-meta">
      <span class="entry-date">
        Published on <i class="fa fa-clock-o"></i> <span class="date"><?php the_time( 'F j, Y'); ?></span>
      </span>
    </p>
    <?php if ($social_shares = get_field('social_shares', 'option')) : ?>
      <div class="social-shares social-icons mb-3 mb-md-0">
        <span class="d-inline-block mr-3" style="color: #747373;">Share:</span>
        <a href="<?php echo esc_url(radical_social_shares('linkedin', $post)); ?>" target="blank" class="open-share-window d-inline-block p-1 mr-3" style="width: 32px;">
          <span class="sr-only">Visit Tubular LinkedIn</span>
          <i class="fa fa-linkedin text-white" aria-hidden="true"></i>
        </a>
        <a href="<?php echo esc_url(radical_social_shares('twitter', $post)); ?>" target="blank" class="open-share-window d-inline-block p-1 mr-3" style="width: 32px;">
          <span class="sr-only">Visit Tubular Twitter</span>
          <i class="fa fa-twitter text-white" aria-hidden="true"></i>
        </a>
        <a href="<?php echo esc_url(radical_social_shares('facebook', $post)); ?>" target="blank" class="open-share-window d-inline-block p-1" style="width: 32px;">
          <span class="sr-only">Visit Tubular Facebook</span>
          <i class="fa fa-facebook text-white" aria-hidden="true"></i>
        </a>
      </div>
    <?php endif; ?>
    <hr/>
  </div>
</div>
