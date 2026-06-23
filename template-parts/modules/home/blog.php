<?php
$featured_blog_post = get_field('featured_blog_post');
?>
<?php if (isset($featured_blog_post['enable']) && $featured_blog_post['enable']) : ?>
  <?php
  $post = get_posts([
    'posts_per_page' => 1
  ]);
  $post = $post[0];
  ?>
  <?php if ($post) : ?>
    <?php
    get_template_part('template-parts/modules/flex/image-card', null, [
      'img' => [
        'url' => wp_get_attachment_url( get_post_thumbnail_id($post->ID) ),
        'alt' => $post->post_title
      ],
      'subtitle' => 'LATEST BLOG POST',
      'title' => $post->post_title,
      'text' => get_the_excerpt($post->ID),
      'link' => [
        'url' => get_the_permalink($post->ID),
        'title' => 'Read More',
        'target' => '_self'
      ],
      'image_align' => 'left',
    ]);
    ?>
  <?php endif; ?>
<?php endif; ?>
