<?php
global $product;
$product_id = $product->get_id();
$product_slug = $product->get_slug();
$reviews_enabled = get_post_meta($product_id, '_enable_reviews', true);
?>
<style>
.fs-3x {
  font-size: 3rem;
}
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
<section id="about" class="bg-lightestgray py-5">
  <div class="container text-center">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <h3 class="fs-2.5x mb-3">Clinically Proven Results</h3>
        <div class="row">
          <div class="col-md-4 text-center">
            <em class="h1 text-pink fs-3x">77%</em>
            <p>Saw an improvement in firmness, smoother texture, smaller pores, and skin elasticity</p>
          </div>
          <div class="col-md-4 text-center">
            <em class="h1 text-pink fs-3x">80%</em>
            <p>Saw a lessening of crows' feet, lines and wrinkles.</p>
          </div>
          <div class="col-md-4 text-center">
            <em class="h1 text-pink fs-3x">81%</em>
            <p>Saw healthier skin</p>
          </div>
        </div>
        <div class="row">
          <div class="col text-center">
            <small class="text-muted">*Based on an independent consumer study.</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php
$reasons = [
  [
    'title' => 'Brightens Skin',
    'description' => 'Infused with AHA and BHA, these pads gently remove dead skin cells, revealing a radiant, glowing complexion.'
  ],
  [
    'title' => 'Smooth Texture',
    'description' => 'Improves uneven skin texture and minimizes the appearance of pores, leaving your skin silky smooth.'
  ],
  [
    'title' => 'Clinical Results',
    'description' => 'Proven to enhance skin firmness, texture, and elasticity while visibly reducing the appearance of fine lines and wrinkles.'
  ],
  [
    'title' => 'Convenient and Effective',
    'description' => 'Pre-soaked for easy use, they deliver professional-grade results from the comfort of your home.'
  ],
];
?>
<section id="four-reasons" class="py-5">
  <div class="container">
    <div class="row mb-3">
      <div class="col text-center">
        <h3 class="m-0">4 Reasons Everyone Loves Age-Defying Exfoliating Pads</h3>
      </div>
    </div>
    <div class="row" style="row-gap: 1rem;">
      <div class="col-lg-6 d-flex align-items-center order-2 order-lg-1">
        <div class="row" style="row-gap: 1rem;">
          <?php foreach ($reasons as $key => $reason) { ?>
            <div class="col-md-6">
              <h4><?= ($key + 1) ?>. <?= $reason['title'] ?></h4>
              <p><?= $reason['description'] ?></p>
            </div>
          <?php } ?>
        </div>
      </div>
      <div class="col-lg-6 order-1 order-lg-2">
        <div class="watch-video-img-wrapper position-relative">
          <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item rounded" src="https://www.youtube.com/embed/MaYYuetvEa8?si=f4U6rlUzHiImfEGX&controls=0&mute=1&autoplay=1&loop=1&playlist=MaYYuetvEa8" title="YouTube video player" frameborder="0" allow="autoplay; loop" referrerpolicy="strict-origin-when-cross-origin"></iframe>
          </div>
          <div class="inner-wrapper-watch-video-btn mask position-absolute" href="javascript:void(0)" title="Watch Video" data-iframe-src="MaYYuetvEa8">
            <div class="text-white" aria-label="Play Button" style="cursor: pointer;">
              <i class="fa fa-play-circle fa-5x" aria-hidden="true"></i>
              <span class="sr-only">Play</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section id="how-it-works" class="py-5">
  <div class="container">
    <div class="bg-medium-gray text-white p-3 rounded d-flex align-items-center">
      <div class="row align-items-center" style="row-gap: 1rem;">
        <div class="col-lg-3 text-center">
          <h3 class="text-white m-0">How It Works</h3>
        </div>
        <div class="col-lg-3">
          <div class="how-it-works-item">
            <img src="https://radicalskincare.com/wp-content/uploads/2025/01/Cleanse.jpg" alt="Cleanse"/>
            <div>
              <h4 class="m-0">1. Cleanse</h4>
              <p class="m-0">Begin with freshly cleansed, dry skin.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="how-it-works-item">
            <img src="https://radicalskincare.com/wp-content/uploads/2025/01/Swipe.jpg" alt="Swipe"/>
            <div>
              <h4 class="m-0">2. Swipe</h4>
              <p class="m-0">Gently glide a pad over your face, avoiding the eye area.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="how-it-works-item">
            <img src="https://radicalskincare.com/wp-content/uploads/2025/01/Moisturize-scaled.jpg" alt="Moisturize"/>
            <div>
              <h4 class="m-0">3. Moisturize</h4>
              <p class="m-0">Let it absorb, then follow with your favorite moisturizer.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php if ($product_slug === 'age-defying-exfoliating-pads-ad' || $product_slug === 'age-defying-exfoliating-pads-ad-bogo') : ?>
  <section id="buy-now-btn-ad" class="d-none d-lg-block pb-3">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-4">
          <a class="btn btn-primary w-100" href="/checkout/?add-to-cart=<?= $product_id ?>">Buy Now</a>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>
<section id="technology" class="technology product-section_video py-5">
  <div class="container">
    <div class="row align-items-center" style="row-gap: 1rem;">
      <div class="col-lg-6 mb-3 mb-lg-0">
        <h3 class="text-center">The Science Behind Radical Skincare</h3>
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
          <div class="featured-review_item">
            <h5 class="featured-review_name fs-1x">Joselina S. <small>Verified Buyer</small></h5>
            <div class="featured-review_stars">
              <?php for($i = 0; $i < 5; $i++) { ?>
                <i class="fa fa-star" aria-hidden="true"></i>
              <?php } ?>
            </div>
            <p class="featured-review_text">I love that they have an exfoliating effect and it is great for my acne prone skin! Recommended also for sensitive skin. Didn't irritate my skin. And no breakouts.</p>
          </div>
          <div class="featured-review_item">
            <h5 class="featured-review_name fs-1x">Lanie C. <small>Verified Buyer</small></h5>
            <div class="featured-review_stars">
              <?php for($i = 0; $i < 5; $i++) { ?>
                <i class="fa fa-star" aria-hidden="true"></i>
              <?php } ?>
            </div>
            <p class="featured-review_text">I love how exfoliating yet gentle these pads are. I also love their size which is enough to use for the entire face. I highly recommend this product.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section id="ingredients">
  <div class="container">
    <div class="row mb-3">
      <div class="col">
        <h3 class="m-0">Transformative Ingredients</h3>
      </div>
    </div>
    <div class="row" style="row-gap: 1rem;">
      <div class="col-lg-3">
        <div class="ingredient-item">
          <img src="https://radicalskincare.com/wp-content/uploads/2025/01/Apples.jpg" alt="Apples"/>
          <div>
            <h4 class="fs-1x">Malus Domestica</h4>
            <ul class="fs-0.75x">
              <li>Protects longevity of skin cells</li>
              <li>Combats chronological aging</li>
              <li>Preserves the viability of your skin</li>
              <li>Protect from UV damage</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="ingredient-item">
          <img src="https://radicalskincare.com/wp-content/uploads/2025/01/Cosmetic-Oil.jpg" alt="Cosmetic Oil"/>
          <div>
            <h4 class="fs-1x">Osilift</h4>
            <ul class="fs-0.75x">
              <li>Immediately visible</li>
              <li>Tones the skin and makes it firmer</li>
              <li>Designed to sculpt and smooth the skin</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="ingredient-item">
          <img src="https://radicalskincare.com/wp-content/uploads/2025/01/Fruits-Pineapple-Kiwi.jpg" alt="Fruits"/>
          <div>
            <h4 class="fs-1x">Alpha Hydroxy Acids</h4>
            <ul class="fs-0.75x">
              <li>Helps the skin absorb ingredients</li>
              <li>Exfoliates the layers of dead skin </li>
              <li>Clarifies pores</li>
              <li>Reduces oily build up</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="ingredient-item">
          <img src="https://radicalskincare.com/wp-content/uploads/2025/01/Spring.jpg" alt="Spring"/>
          <div>
            <h4 class="fs-1x">Witch Hazel</h4>
            <ul class="fs-0.75x">
              <li>Cleans excess residual on the skin</li>
              <li>Tightens the appearance of pores</li>
              <li>Smoothes Skin</li>
            </ul>
          </div>
        </div>
      </div>
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
<section id="about" class="py-5">
  <div class="container">
    <div class="about-icons row justify-content-center">
      <div class="col-6 col-lg-2 mb-3">
        <img src="<?php echo get_template_directory_uri() . '/assets/images/product/rabbit.svg'; ?>" alt="Cruelty Free" class="d-block mx-auto mb-3">
        <p class="text-center text-uppercase mb-0">Cruelty Free</p>
      </div>
      <div class="col-6 col-lg-2 mb-3">
        <img src="<?php echo get_template_directory_uri() . '/assets/images/product/soy.svg'; ?>" alt="Soy Free" class="d-block mx-auto mb-3">
        <p class="text-center text-uppercase mb-0">Soy Free</p>
      </div>
      <div class="col-6 col-lg-2">
        <img src="<?php echo get_template_directory_uri() . '/assets/images/product/non-gmo.svg'; ?>" alt="GMO Free" class="d-block mx-auto mb-3">
        <p class="text-center text-uppercase mb-0">GMO Free</p>
      </div>
    </div>
  </div>
</section>
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
