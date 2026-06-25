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
<section id="about" class="bg-lightestgray py-5">
  <div class="container text-center">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <h3 class="fs-2.5x mb-3">Clinically Proven Results</h3>
        <!-- <div class="mb-5">
          <p>80% of consumers saw an improvement in their skin's firmness, smoother skin texture, smaller pores and improvement in skin elasticity.</p>
          <p>Over 80% saw lessening in the appearance of crow's feet, fine lines and wrinkles.</p>
        </div> -->
        <div class="row">
          <div class="col-md-4 text-center">
            <em class="h1 text-pink fs-3x">135%</em>
            <p>Improvement in elasticity at 8 weeks</p>
          </div>
          <div class="col-md-4 text-center">
            <em class="h1 text-pink fs-3x">94%</em>
            <p>Panelists saw reduction in wrinkle severity in 14 days.</p>
          </div>
          <div class="col-md-4 text-center">
            <em class="h1 text-pink fs-3x">93%</em>
            <p>Panelists measured a 41% improvement skin firmness</p>
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
    'title' => 'Boosts Collagen',
    'description' => 'Packed with powerful peptides and botanicals, it helps stimulate collagen production for firmer, more youthful skin.'
  ],
  [
    'title' => 'Shields Against Free Radicals',
    'description' => 'With 11 potent antioxidants, it protects against environmental damage and oxidative stress that cause premature aging.'
  ],
  [
    'title' => 'Repairs & Prevents Aging',
    'description' => 'Clinically proven to repair past damage while defending against future signs of aging for long-term skin health.'
  ],
  [
    'title' => 'Hydrating  & Potent',
    'description' => 'Fast-absorbing yet deeply nourishing, it delivers maximum anti-aging benefits without feeling heavy or greasy.'
  ],
];
?>
<section id="four-reasons" class="py-5">
  <div class="container">
    <div class="row mb-5">
      <div class="col text-center">
        <h3 class="m-0">4 Reasons Everyone Loves Advanced Peptide Antioxidant Serum</h3>
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
            <iframe class="embed-responsive-item rounded" src="https://www.youtube.com/embed/j_dmHBUHKsI?si=f4U6rlUzHiImfEGX&controls=0&mute=1&autoplay=1&loop=1&playlist=j_dmHBUHKsI" title="YouTube video player" frameborder="0" allow="autoplay; loop" referrerpolicy="strict-origin-when-cross-origin"></iframe>
          </div>
          <div class="inner-wrapper-watch-video-btn mask position-absolute" href="javascript:void(0)" title="Watch Video" data-iframe-src="j_dmHBUHKsI">
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
            <img src="https://radicalskincare.com/wp-content/uploads/2025/02/Advanced-Peptide-Antioxidant-Serum-–-Apply.jpg" alt="Apply"/>
            <div>
              <h4 class="m-0">1. Apply</h4>
              <p class="m-0">After cleansing, smooth 1-2 pumps onto dry face and neck.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="how-it-works-item">
            <img src="https://radicalskincare.com/wp-content/uploads/2025/02/Advanced-Peptide-Antioxidant-Serum-Absorb-1.jpg" alt="Absorb"/>
            <div>
              <h4 class="m-0">2. Absorb</h4>
              <p class="m-0">Let the serum fully absorb before applying moisturizer.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="how-it-works-item">
            <img src="https://radicalskincare.com/wp-content/uploads/2025/02/Sun-Moon.jpg" alt="Repeat"/>
            <div>
              <h4 class="m-0">3. Repeat</h4>
              <p class="m-0">Use morning and night for best results.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
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
            <h5 class="featured-review_name fs-1x">Susan S. <small>Verified Buyer</small></h5>
            <div class="featured-review_stars">
              <?php for($i = 0; $i < 5; $i++) { ?>
                <i class="fa fa-star" aria-hidden="true"></i>
              <?php } ?>
            </div>
            <p class="featured-review_text"><strong>60 looking like 45</strong> Had my 60th bday, given a compliment saying I look 45 all due to my Radical regimen!</p>
          </div>
          <div class="featured-review_item">
            <h5 class="featured-review_name fs-1x">Karen L <small>Verified Buyer</small></h5>
            <div class="featured-review_stars">
              <?php for($i = 0; $i < 5; $i++) { ?>
                <i class="fa fa-star" aria-hidden="true"></i>
              <?php } ?>
            </div>
            <p class="featured-review_text"><strong>SERUM THAT WORKS!</strong> I began using this antioxidant peptide serum a few years ago and absolutely saw the difference in fewer fine lines and wrinkles! People do not believe I am 75! This is one product I never want to run out of so I have it on Subscription!</p>
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
          <img src="https://radicalskincare.com/wp-content/uploads/2025/02/Sodium-Hyaluronic-Acid-optimized-scaled.jpg" alt="Sodium Hyaluronate"/>
          <div>
            <h4 class="fs-1x">Sodium Hyaluronate</h4>
            <ul class="fs-0.75x">
              <li>Deeply hydrates and plumps skin</li>
              <li>Enhances moisture retention</li>
              <li>Reduces the appearance of fine lines</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="ingredient-item">
          <img src="https://radicalskincare.com/wp-content/uploads/2025/02/Dipalmitoyl-Hydroxyproline-optimized.jpg" alt="Dipalmitoyl Hydroxyproline"/>
          <div>
            <h4 class="fs-1x">Dipalmitoyl Hydroxyproline</h4>
            <ul class="fs-0.75x">
              <li>Boosts collagen production</li>
              <li>Improves skin firmness and elasticity</li>
              <li>Smooths the look of wrinkles</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="ingredient-item">
          <img src="https://radicalskincare.com/wp-content/uploads/2025/02/Arganyl-optimized.jpg" alt="Arganyl"/>
          <div>
            <h4 class="fs-1x">Arganyl</h4>
            <ul class="fs-0.75x">
              <li>Protects against oxidative stress</li>
              <li>Strengthens the skin barrier</li>
              <li>Defends against environmental aging</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="ingredient-item">
          <img src="https://radicalskincare.com/wp-content/uploads/2025/02/Argireline-optimized.jpg" alt="Argireline"/>
          <div>
            <h4 class="fs-1x">Argireline</h4>
            <ul class="fs-0.75x">
              <li>Reduces expression lines and wrinkles</li>
              <li>Relaxes facial tension for a smoother look</li>
              <li>Provides a Botox-like effect without injections</li>
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
<?php if (get_post_meta(get_the_ID(), '_enable_reviews', true)) : ?>
  <section id="reviews" class="reviews bg-lightestgray">
    <?php comments_template(); ?>
  </section>
<?php endif; ?>
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
