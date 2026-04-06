
<?php
global $post;
$post_id = get_the_ID();
$current_user_is_admin = current_user_can('edit_others_pages');
$vars = [
  'post_id' => $post_id,
  'current_user_is_admin' => $current_user_is_admin
];
$hide_content_sections = get_field('hide_content_sections');
$terranea_product_id = get_field('terranea_product_id', 'option');
?>
<?php if ($post->post_name === 'glisten-glow-collection' || $post->post_name === 'natural-shimmer-highlighter') : ?>
  <?php get_template_part('template-parts/product/natural-shimmer-highlighter'); ?>
<?php endif; ?>
<?php if ($post_id === $terranea_product_id) : ?>
  <?php get_template_part('template-parts/product/terranea'); ?>
<?php elseif (!$hide_content_sections) : ?>
  <section class="product-content-nav sticky-top bg-lightestgray py-2">
    <div class="container my-auto">
      <ul class="nav hidden-sm-down d-flex justify-content-around scroll-spy-navbar" role="tablist">
        <?php
        $about = get_field('about');
        $enable = isset($about['enable']) ? $about['enable'] : true;
        ?>
        <?php if ($enable) : ?>
          <li class="nav-item">
            <a class="nav-link" href="#about">About</a>
          </li>
        <?php endif; ?>
        <?php
        $benefits = get_field('benefits');
        $enable = isset($benefits['enable']) ? $benefits['enable'] : true;
        ?>
        <?php if ($enable) : ?>
          <li class="nav-item">
            <a class="nav-link" href="#benefits">Benefits</a>
          </li>
        <?php endif; ?>
        <?php if ($how_to_apply_section = get_field('how_to_apply_section')) : ?>
          <li class="nav-item">
            <a class="nav-link" href="#how-to-apply">How To Apply</a>
          </li>
        <?php endif; ?>
        <?php
        $ingredients_enable = get_field('ingredients_enable');
        $ingredients_enable = is_null($ingredients_enable) ? true : $ingredients_enable;
        ?>
        <?php if ($ingredients_enable) : ?>
          <li class="nav-item">
            <a class="nav-link" href="#ingredients">Ingredients</a>
          </li>
        <?php endif; ?>
        <?php
        $reviews_enabled = get_post_meta($post_id, '_enable_reviews', true);
        ?>
        <?php if ($reviews_enabled) : ?>
          <li class="nav-item">
            <a class="nav-link" href="#comments">Reviews</a>
          </li>
        <?php endif; ?>
        <?php if ($related_ids = get_post_meta($post_id, '_related_ids', true)) : ?>
          <li class="nav-item">
            <a class="nav-link" href="#related">Pairing</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </section>
  <div class="product-desc-tab-content tab-content">
    <?php get_template_part('template-parts/product/about', null, $vars); ?>
    <?php get_template_part('template-parts/product/benefits'); ?>
    <?php get_template_part('template-parts/product/how-to-apply', null, $vars); ?>
    <?php if ($technology = get_field('technology')) : ?>
      <?php get_template_part('template-parts/product/technology', null, $vars); ?>
    <?php endif; ?>
    <?php if ($ingredients_enable) : ?>
      <?php get_template_part('template-parts/product/ingredients', null, $vars); ?>
    <?php endif; ?>
  </div>
  <?php
  $as_seen_in = get_field('as_seen_in');
  $enable = isset($as_seen_in['enable']) ? $as_seen_in['enable'] : true;
  ?>
  <?php if ($enable) : ?>
    <?php get_template_part('template-parts/product/as-seen-in'); ?>
  <?php endif; ?>
  <?php if ($reviews_enabled) : ?>
    <section id="reviews" class="reviews bg-lightestgray">
      <?php comments_template(); ?>
    </section>
  <?php endif; ?>
  <?php get_template_part('template-parts/product/related-products'); ?>
  <div class="modal" id="productVideoModal">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content rounded">
        <div class="modal-body p-0">
          <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item rounded" src="" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
