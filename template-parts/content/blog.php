<?php
global $post;
$feat_img_url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
$author = new WP_User( $post->post_author );
$author_id = $author->ID;
$author_display_name = $author->display_name;
$permalink = get_the_permalink();
$title = get_the_title();
?>
<article class="row bg-lightestgray py-3 rounded mb-4">
  <?php if ($feat_img_url) : ?>
    <div class="col-md-5 mb-3 mb-lg-0">
      <div class="rounded">
        <img src="<?php echo esc_url($feat_img_url); ?>" alt="<?php echo esc_attr($title); ?>" class="img-fluid w-100 rounded" loading="lazy"/>
        <a href="<?php echo esc_url($permalink); ?>" title="<?php echo esc_attr($title); ?>">
          <div class="mask rgba-white-slight"></div>
        </a>
      </div>
    </div>
  <?php endif; ?>
  <div class="<?php echo $feat_img_url ? 'col-md-7' : ''; ?>">
    <a href="<?php echo esc_url($permalink); ?>" class="ff-orpheus text-darker-gray fs-2x d-block" title="<?php echo esc_attr($title); ?>">
      <?php echo $title; ?>
    </a>
    <div class="text-dark-gray"><?php the_excerpt(); ?></div>
    <p class="post-meta text-dark-gray">
      <span class="entry-date">Published on <i class="fa fa-clock-o"></i> <span class="date"><?php the_time( 'F j, Y'); ?></span></span>
    </p>
    <a href="<?php echo esc_url($permalink); ?>" class="btn btn-darkergray" title="<?php echo esc_attr($title); ?>">
      <i class="fa fa-angle-right mr-1" aria-hidden="true"></i> Read more
    </a>
  </div>
</article>
