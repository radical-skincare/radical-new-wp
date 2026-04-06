<?php
$navigation = get_field('navigation', 'option');
?>
<section class="mega-menu py-4" main-menu="1" id="mega-menu">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 d-flex justify-content-between">
        <?php dynamic_sidebar('mega-menu'); ?>
      </div>
      <div class="col-lg-6">
        <div class="fs-1.25x mb-3">Radical best sellers</div>
        <?php if (isset($navigation['best_sellers'])) : ?>
          <?php
          $query = new WP_Query([
            'post_type' => 'product',
            'posts_per_page' => 3,
            'post__in' => $navigation['best_sellers']
          ]);
          ?>
          <div class="row">
            <?php while ($query->have_posts()) : ?>
              <?php
              $query->the_post();
              ?>
              <div class="col-lg-4">
                <?php
                $product = get_post();
                get_template_part('template-parts/content-product');
                ?>
              </div>
            <?php endwhile; ?>
            <?php wp_reset_query(); ?>
          </div>
        <?php else : ?>
          <p>No best sellers selected.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
