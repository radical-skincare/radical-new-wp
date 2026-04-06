<?php if ($hero = get_field('hero')) : ?>
  <section id="hero" class="hero container-fluid">
    <div class="row">
      <div class="order-2 order-lg-1 col-lg-6 hero-mobile-image-container bg-lightestgray d-flex justify-content-center align-items-center p-5">
        <div class="row justify-content-center">
          <div class="col-lg-8 text-center">
            <div class="fs-1.5x mb-1 font-weight-normal text-darker-gray text-uppercase">
              <?php echo $hero['subheading']; ?>
            </div>
            <div class="fs-2x mb-3 text-darker-gray ff-orpheus">
              <?php the_title(); ?>
            </div>
            <div class="text-dark-gray">
              <?php echo $hero['description']; ?>
            </div>
            <?php if ($btn = $hero['button']) : ?>
              <a href="<?php echo esc_url($btn['url']); ?>" class="btn btn-darkergray">
                <?php echo esc_html($btn['title']); ?>
              </a>
            <?php endif; ?>
          </div>
        </div>
        <?php get_template_part('template-parts/header/social-icons'); ?>
      </div>
      <?php if ($hero['slides']) : ?>
        <div class="order-1 order-lg-2 col-lg-6 hero-slider-container p-0 position-relative">
          <div class="hero-slider">
            <?php foreach ($hero['slides'] as $slide) : ?>
              <?php if ($slide['image']) : ?>
                <div>
                  <img src="<?php echo esc_url($slide['image']['url']); ?>" alt="<?php echo esc_attr($slide['image']['alt']); ?>" class="w-100"/>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
          <?php if (count($hero['slides']) > 1) : ?>
            <a class="slider-action left-slider-action bg-white p-2" href="javascript:void(0)" role="button">
              <img src="<?php echo get_template_directory_uri() . '/assets/images/arrow-top.svg'; ?>" alt="Arrow Left"/>
            </a>
            <a class="slider-action right-slider-action bg-white p-2" href="javascript:void(0)" role="button">
              <img src="<?php echo get_template_directory_uri() . '/assets/images/arrow-top.svg'; ?>" alt="Arrow Right"/>
            </a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>
<?php endif; ?>
