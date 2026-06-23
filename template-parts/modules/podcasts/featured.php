<?php
$featured_podcasts = get_posts([
  'post_type'  => 'podcasts',
  'meta_key'   => 'featured',
  'meta_value' => 1,
]);
?>
<section id="featured-podcast-section">
  <div class="w-100">
    <div id="featured-podcast-section_hero-card" class="card pt-4" style="background-image: url('<?php echo esc_url(get_field('hero_background_image', 'option')); ?>')">
      <div class="card-body">
        <div class="row">
          <div class="col-lg-8">
            <div class="card-text text-dark">
              <h1 class="text-dark mb-3">Podcasts</h1>
              <?php echo get_field('hero_description', 'option'); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="featured-podcasts-carousel-wrap" style="display: none;">
    <h3 class="text-dark mb-3" style="margin-left: 20px;">Featured</h3>
    <div id="featured-podcasts-carousel" class="owl-carousel">
      <?php foreach ($featured_podcasts as $podcast) : ?>
        <div class="mx-2 mx-lg-4">
          <?php get_template_part('template-parts/content/podcast-item', null, ['podcast' => $podcast, 'is_carousel' => true]); ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
