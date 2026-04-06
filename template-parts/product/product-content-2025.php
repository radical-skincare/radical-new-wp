<style>
.text-pink {
  color: #b21939;
}
.bg-dark-gray {
  background-color: #666;
}
.bg-medium-gray {
  background-color: #BBB;
}
.bg-very-light-gray {
  background-color: #eee;
}
.single-product .product-container .woocommerce-product-details__short-description {
  height: initial !important;
  text-overflow: initial !important;
  overflow: initial !important;
  font-size: 1rem;
}
.wlr-product-message {
  background-color: #eee;
  font-size: 1rem;
  line-height: initial !important;
  border-radius: 0.25rem !important;
  padding: 0.25rem !important;
  margin-bottom: 1rem;
}
.how-it-works-item {
  display: flex;
  align-items: center;
  column-gap: 1rem;
}
.how-it-works-item img {
  width: 4rem;
  min-width: 4rem;
  height: 4rem;
  border-radius: 50%;
  object-fit: cover;
}
.featured-review {
  display: flex;
  row-gap: 1rem;
  /*column-gap: 1rem;*/
}
.featured-review_item {
  padding: 0 1rem;
}
.featured-review_stars .fa {
  color: #b20738;
}
.ingredient-item {
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 0.5rem;
  background-color: #eee;
  column-gap: 1rem;
  overflow: hidden;
}
.ingredient-item img {
  width: 50%;
  height: 16rem;
  object-fit: cover;
}
.ingredient-item ul {
  padding-left: 1rem;
}
</style>
<?php
$as_seen_in = get_field('as_seen_in');
$enable = isset($as_seen_in['enable']) ? $as_seen_in['enable'] : true;
?>
<?php if ($enable) : ?>
  <?php get_template_part('template-parts/product/as-seen-in'); ?>
<?php endif; ?>
<?php
$section = get_field('clinically_proven_results_section');
?>
<?php if ($section && isset($section['enable']) && $section['enable']) : ?>
  <section id="about" class="bg-lightestgray py-5">
    <div class="container text-center">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <h3 class="fs-2.5x mb-3"><?php echo $section['heading']; ?></h3>
          <?php if ($results = $section['results']) : ?>
            <div class="row">
              <?php foreach($results as $result) : ?>
                <div class="col-md-4 text-center">
                  <em class="h1 text-pink fs-3x"><?php echo $result['stat']; ?></em>
                  <p><?php echo $result['description']; ?></p>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <?php if ($bottom_small_text = $section['bottom_small_text']) : ?>
            <div class="row">
              <div class="col text-center">
                <small class="text-muted"><?php echo $bottom_small_text; ?></small>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>
<?php if ($section = get_field('reasons_section')) : ?>
  <section id="four-reasons" class="py-5">
    <div class="container">
      <div class="row mb-5">
        <div class="col text-center">
          <h3 class="m-0"><?php echo $section['heading']; ?></h3>
        </div>
      </div>
      <div class="row" style="row-gap: 1rem;">
        <?php if ($reasons = $section['reasons']) : ?>
          <div class="col-lg-6 d-flex align-items-center order-2 order-lg-1">
            <div class="row" style="row-gap: 1rem;">
              <?php foreach ($reasons as $key => $reason) : ?>
                <div class="col-md-6">
                  <h4><?= ($key + 1) ?>. <?= $reason['title'] ?></h4>
                  <p><?= $reason['description'] ?></p>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
        <?php if ($youtube_video_id = $section['youtube_video_id']) : ?>
          <div class="col-lg-6 order-1 order-lg-2">
            <div class="watch-video-img-wrapper position-relative">
              <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item rounded" src="https://www.youtube.com/embed/<?= $youtube_video_id ?>?si=f4U6rlUzHiImfEGX&controls=0&mute=1&autoplay=1&loop=1&playlist=<?= $youtube_video_id ?>" title="YouTube video player" frameborder="0" allow="autoplay; loop" referrerpolicy="strict-origin-when-cross-origin"></iframe>
              </div>
              <div class="inner-wrapper-watch-video-btn mask position-absolute" href="javascript:void(0)" title="Watch Video" data-iframe-src="<?= $youtube_video_id ?>">
                <div class="text-white" aria-label="Play Button" style="cursor: pointer;">
                  <i class="fa fa-play-circle fa-5x" aria-hidden="true"></i>
                  <span class="sr-only">Play</span>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
<?php endif; ?>
<?php if ($section = get_field('how_it_works_section')) : ?>
  <section id="how-it-works" class="py-5">
    <div class="container">
      <div class="bg-medium-gray text-white p-3 rounded d-flex align-items-center">
        <div class="row align-items-center" style="row-gap: 1rem;">
          <div class="col-lg-3 text-center">
            <h3 class="text-white m-0"><?php echo $section['heading']; ?></h3>
          </div>
          <?php foreach ($section['steps'] as $step) : ?>
            <div class="col-lg-3">
              <div class="how-it-works-item">
                <?php if ($image = $step['image']) : ?>
                  <img src="<?php echo esc_html($image['url']); ?>" alt="<?php echo esc_html($image['alt']); ?>"/>
                <?php endif; ?>
                <div>
                  <h4 class="m-0"><?php echo $step['text']; ?></h4>
                  <p class="m-0"><?php echo $step['description']; ?></p>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>
<?php if ($section = get_field('technology_section')) : ?>
  <section id="technology" class="technology product-section_video py-5">
    <div class="container">
      <div class="row align-items-center" style="row-gap: 1rem;">
        <div class="col-lg-6 mb-3 mb-lg-0">
          <h3 class="text-center"><?php echo $section['heading']; ?></h3>
          <div class="watch-video-img-wrapper position-relative">
            <div class="embed-responsive embed-responsive-16by9">
              <iframe class="embed-responsive-item rounded" src="https://www.youtube.com/embed/rVhRDFcS2u4?si=f4U6rlUzHiImfEGX&controls=0&mute=1&autoplay=1&loop=1&playlist=rVhRDFcS2u4" title="YouTube video player" frameborder="0" allow="autoplay; loop" referrerpolicy="strict-origin-when-cross-origin"></iframe>
            </div>
            <div class="inner-wrapper-watch-video-btn mask position-absolute" href="javascript:void(0)" title="Watch Video" data-iframe-src="rVhRDFcS2u4">
              <div class="text-white" aria-label="Play Button" style="cursor: pointer;">
                <i class="fa fa-play-circle fa-5x" aria-hidden="true"></i>
                <span class="sr-only">Play</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <h3 class="product-section_video_heading text-center mb-0">Real Radical Results</h3>
          <div class="featured-reviews benefits_text">
            <?php foreach ($section['reviews'] as $review) : ?>
              <div class="featured-review_item">
                <h5 class="featured-review_name fs-1x"><?php echo $review['name']; ?> <small>Verified Buyer</small></h5>
                <div class="featured-review_stars">
                  <?php for($i = 0; $i < 5; $i++) { ?>
                    <i class="fa fa-star" aria-hidden="true"></i>
                  <?php } ?>
                </div>
                <p class="featured-review_text"><?php echo $review['review']; ?></p>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>
<?php if ($section = get_field('ingredients_section')) : ?>
  <section id="ingredients">
    <div class="container">
      <div class="row mb-3">
        <div class="col">
          <h3 class="m-0">Transformative Ingredients</h3>
        </div>
      </div>
      <div class="row" style="row-gap: 1rem;">
        <?php foreach ($section['ingredients'] as $ingredient) : ?>
          <div class="col-lg-3">
            <div class="ingredient-item">
              <?php if ($image = $ingredient['image']) : ?>
                <img src="<?php echo esc_html($image['url']); ?>" alt="<?php echo esc_html($image['alt']); ?>"/>
              <?php endif; ?>
              <div>
                <?php echo $ingredient['html']; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <?php if ($full_ingredients_list = get_field('full_ingredients_list')) : ?>
        <div class="mt-3">
          <button type="button" class="btn btn-outline-dark d-block mx-auto" data-toggle="modal" data-target="#fullIngredientsListModal">Full Ingredients List</button>
        </div>
        <div class="modal fade" id="fullIngredientsListModal" tabindex="-1" role="dialog" aria-labelledby="fullIngredientsListModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="fullIngredientsListModalLabel">Full Ingredients List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="top: 1.25rem; right: 1rem;">
                  <i class="fa fa-close" aria-hidden="true"></i>
                </button>
              </div>
              <div class="modal-body">
                <?php echo $full_ingredients_list; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </section>
<?php endif; ?>
<?php if ($section = get_field('trust_section')) : ?>
  <section id="about" class="py-5">
    <div class="container">
      <div class="about-icons row justify-content-center">
        <?php foreach ($section['items'] as $item) : ?>
          <div class="col-6 col-lg-2 mb-3">
            <img src="<?php echo get_template_directory_uri() . '/assets/images/product/' . $item['icon'] . '.svg'; ?>" alt="<?php echo esc_html($item['name']); ?>" class="d-block mx-auto mb-3">
            <p class="text-center text-uppercase mb-0"><?php echo esc_html($item['name']); ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
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
