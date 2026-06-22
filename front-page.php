<?php get_header(); ?>

  <h1 style="display: none;">Welcome to the era of Holistic Technology with Radical Skincare, where inner self meets outer beauty.</h1>
  <?php while (have_posts()) : the_post(); ?>
    <?php get_template_part('template-parts/modules/home/hero'); ?>
    <?php get_template_part('template-parts/modules/home/new-arrivals'); ?>
    <?php get_template_part('template-parts/modules/home/as-seen-in'); ?>
    <?php get_template_part('template-parts/modules/home/about'); ?>
    <?php get_template_part('template-parts/modules/home/image-carousel'); ?>
    <?php if ($scientific_link = get_field('scientific_link')) :
      $img = $scientific_link['image'];
      $subtitle = $scientific_link['subheading'];
      $title = $scientific_link['heading'];
      $text = $scientific_link['content'];
      $link = $scientific_link['link'];
      $image_align = 'left';
      get_template_part('template-parts/modules/flex/image-card');
    endif; ?>
    <?php get_template_part('template-parts/modules/home/trust-us'); ?>
    <?php get_template_part('template-parts/modules/home/products-static'); ?>
    <?php if ($mission = get_field('mission')) :
      $img = $mission['image'];
      $title = $mission['heading'];
      $text = $mission['description'];
      $image_align = 'right';
      $subtitle = '';
      $link = '';
      if (isset($mission['link']) && $mission['link']) {
        $link = $mission['link'];
      }
      get_template_part('template-parts/modules/flex/image-card');
    endif; ?>
    <?php get_template_part('template-parts/modules/home/blog'); ?>
    <?php if ($rewards = get_field('rewards')) :
      $img = $rewards['image'];
      $title = $rewards['heading'];
      $text = $rewards['description'];
      $image_align = 'right';
      $subtitle = '';
      $link = '';
      if (isset($rewards['link']) && $rewards['link']) {
        $link = $rewards['link'];
      }
      get_template_part('template-parts/modules/flex/image-card');
    endif; ?>
    <?php get_template_part('template-parts/modules/home/quote'); ?>
    <?php if ($become_a_brand_partner = get_field('become_a_brand_partner')) :
      $img = $become_a_brand_partner['image'];
      $subtitle = 'Become A Brand Partner';
      $title = $become_a_brand_partner['heading'];
      $text = $become_a_brand_partner['content'];
      $link = $become_a_brand_partner['link'];
      $image_align = 'left';
      get_template_part('template-parts/modules/flex/image-card');
    endif; ?>
    <section class="flex_image-card right-left">
      <div class="container bg-lightestgray py-5 px-lg-5">
        <div class="row">
          <div class="col-lg-6 order-lg-first order-last" style="z-index: 1">
            <div class="h-100 px-3 px-lg-0 d-lg-flex align-items-lg-center">
              <div class="card">
                <div class="card-body text-center text-lg-left p-lg-4">
                  <div class="card-title ff-orpheus text-darker-gray fs-2x">Virtual Gift Card</div>
                  <div class="card-text text-dark-gray mb-3">Give the gift of health skin.</div>
                  <p class="font-weight-bold">$25-$250</p>
                  <a href="https://radicalskincare.com/products/virtual-gift-card/" class="btn btn-dark" target="">Learn More</a>
                </div>
              </div>
            </div>
          </div>
          <div class="d-flex col-lg-6 order-first order-lg-last align-items-center">
            <img src="https://radicalskincare.com/wp-content/uploads/2026/02/Radical-Skincare-Gift-Card.jpg" alt="Gift Card" class="flex_image-card_image" loading="lazy" style="max-height: 384px; object-fit: cover;"/>
          </div>
        </div>
      </div>
    </section>
    <?php get_template_part('template-parts/modules/home/giving-back'); ?>
    <?php get_template_part('template-parts/modules/home/quick-links'); ?>
    <?php get_template_part('template-parts/modules/home/tag-radical'); ?>
  <?php endwhile; ?>

<?php get_footer(); ?>
