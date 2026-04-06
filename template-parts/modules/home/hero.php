<section id="home-hero_container" class="home-hero_container position-relative">
  <?php
  $slider = get_field('slider');
  ?>
  <?php if ($slider) : ?>
    <?php
    $count = 0;
    foreach ($slider as $slide) {
      if ($slide['enable']) {
        $count++;
      }
    }
    ?>
    <div id="home-hero" class="home-hero">
      <?php foreach ($slider as $key => $slide) : ?>
        <?php if ($slide['enable']) : ?>
          <?php
          $style = $slide['style'];
          $social_icons_color = $slide['social_icons_color'] ?? 'white';
          ?>
          <?php if ($style === 'full-bg-img_content-right') : ?>
            <div class="home-hero_item home-hero_item-<?php echo esc_attr($key); ?> <?php echo $count > 1 ? 'slick-slide' : 'w-100'; ?> social-icons-color_<?php echo esc_attr($social_icons_color); ?>"
            data-desktop-image="<?php echo esc_url($slide['image']['url']); ?>" data-mobile-image="<?php echo esc_url($slide['mobile_image']['url']); ?>"
            style="background-image: url('<?php echo esc_url($slide['mobile_image']['url']); ?>');"
            >
              <style>
              @media (min-width: 992px) {
                .home-hero_item-<?php echo esc_attr($key); ?> {
                  background-image: url('<?php echo esc_url($slide['image']['url']); ?>') !important;
                }
              }
              </style>
              <div class="container">
                <div class="row align-items-center">
                  <div class="col-9 col-lg-5 offset-lg-7">
                    <?php if ($heading = $slide['heading']) : ?>
                      <div class="ff-orpheus fs-2x fs-lg-3x mb-3 text-white" style="line-height: 48px">
                        <?php echo $heading; ?>
                      </div>
                    <?php endif; ?>
                    <?php if ($content = $slide['content']) : ?>
                      <div class="text-white">
                        <?php echo $content; ?>
                      </div>
                    <?php endif; ?>
                    <?php if ($link = $slide['link']) : ?>
                      <a href="<?php echo esc_url($link['url']); ?>" class="btn btn-outline-white"><?php echo esc_html($link['title']); ?></a>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <?php get_template_part('template-parts/modules/home/hero/social-icons'); ?>
            </div>
          <?php elseif ($style === 'full-bg-img_content-center') : ?>
            <div class="home-hero_item home-hero_item-<?php echo esc_attr($key); ?> home-hero_item-full-bg-img_content-center <?php echo $count > 1 ? 'slick-slide' : 'w-100'; ?> bg-lightestgray pb-5 pb-lg-0 social-icons-color_<?php echo esc_attr($social_icons_color); ?>" data-desktop-image="<?php echo esc_url($slide['image']['url']); ?>" data-mobile-image="<?php echo esc_url($slide['mobile_image']['url']); ?>" style="position: relative;">
              <div class="container-fluid px-0">
                <?php if ($image = $slide['image']) : ?>
                  <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="home-hero_item-full-bg-img_content-center_img"/>
                <?php endif; ?>
                <div class="row justify-content-center home-hero_item-full-bg-img_content-center_row-card">
                  <div class="col-lg-6 col-xl-4">
                    <div class="px-3">
                      <div class="card home-hero_item-full-bg-img_content-center_card">
                        <div class="card-body text-center">
                          <?php if ($subheading = $slide['subheading']) : ?>
                            <span class="label-two"><?php echo $subheading; ?></span>
                          <?php endif; ?>
                          <?php if ($heading = $slide['heading']) : ?>
                            <div class="title-c">
                              <?php echo $heading; ?>
                            </div>
                          <?php endif; ?>
                          <?php if ($content = $slide['content']) : ?>
                            <div class="font-weight-light">
                              <?php echo $content; ?>
                            </div>
                          <?php endif; ?>
                          <?php if ($link = $slide['link']) : ?>
                            <a href="<?php echo esc_url($link['url']); ?>" class="link-underline link-underline_darker-gray" target="<?php echo esc_attr($link['target']); ?>"><?php echo esc_html($link['title']); ?></a>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php get_template_part('template-parts/modules/home/hero/social-icons'); ?>
            </div>
          <?php elseif ($style === 'content-left_image-right') : ?>
            <div id="hero" class="hero hero_image-right bg-lightestgray home-hero_item home-hero_item-<?php echo esc_attr($key); ?> <?php echo $count > 1 ? 'slick-slide' : 'w-100'; ?> social-icons-color_<?php echo esc_attr($social_icons_color); ?>">
              <div class="container-fluid">
                <div class="row">
                  <?php if ($image = $slide['image']) : ?>
                    <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="d-lg-none hero-image-mobile"/>
                  <?php endif; ?>
                  <div class="d-flex justify-content-center align-items-center col-lg-6 bg-lightestgray p-3 p-lg-5">
                    <div class="text-container p-3 p-lg-0 text-center">
                      <?php if ($subheading = $slide['subheading']) : ?>
                        <div class="fs-1.5x mb-1 font-weight-normal text-darker-gray text-uppercase">
                          <?php echo $subheading; ?>
                        </div>
                      <?php endif; ?>
                      <?php if ($heading = $slide['heading']) : ?>
                        <h2 class="fs-2x mb-3 text-darker-gray font-weight-normal">
                          <?php echo $heading; ?>
                        </h2>
                      <?php endif; ?>
                      <?php if ($content = $slide['content']) : ?>
                        <div class="fs-1.25x text-dark-gray font-weight-light">
                          <?php echo $content; ?>
                        </div>
                      <?php endif; ?>
                      <?php if ($link = $slide['link']) : ?>
                        <a href="<?php echo esc_url($link['url']); ?>" class="link-underline link-underline_darker-gray" target="<?php echo esc_attr($link['target']); ?>">
                          <?php echo $link['title']; ?>
                        </a>
                      <?php endif; ?>
                    </div>
                  </div>
                  <?php if ($image = $slide['image']) : ?>
                    <div class="d-none d-lg-block col-lg-6 hero-image-container" style="background-image: url('<?php echo esc_url($image['url']); ?>');">
                      <span class="sr-only"><?php echo esc_html($image['alt']); ?></span>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
              <?php get_template_part('template-parts/modules/home/hero/social-icons'); ?>
            </div>
          <?php elseif ($style === 'full-bg-img_link') : ?>
          <?php
            $link = $slide['link'];
          ?>
          <div class="home-hero_item hero_image-link home-hero_item-<?php echo esc_attr($key); ?> <?php echo $count > 1 ? 'slick-slide' : 'w-100'; ?> social-icons-color_<?php echo esc_attr($social_icons_color); ?>"
            data-desktop-image="<?php echo esc_url($slide['image']['url']); ?>" data-mobile-image="<?php echo esc_url($slide['mobile_image']['url']); ?>"
            style="background-image: url('<?php echo esc_url($slide['mobile_image']['url']); ?>');"
            >
              <style>
              @media (min-width: 992px) {
                .home-hero_item-<?php echo esc_attr($key); ?> {
                  background-image: url('<?php echo esc_url($slide['image']['url']); ?>') !important;
                }
              }
              </style>
              <a class="container" href="<?php echo esc_url($link['url']); ?>" target="<?php echo esc_attr($link['target']); ?>">
                <div class="row align-items-center">
                  <div class="col-9 col-lg-5 offset-lg-7">
                    <?php if ($heading = $slide['heading']) : ?>
                      <div class="ff-orpheus fs-2x fs-lg-3x mb-3 text-white" style="line-height: 48px">
                        <?php echo $heading; ?>
                      </div>
                    <?php endif; ?>
                    <?php if ($content = $slide['content']) : ?>
                      <div class="text-white">
                        <?php echo $content; ?>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </a>
              <?php get_template_part('template-parts/modules/home/hero/social-icons'); ?>
            </div>
          <?php endif; ?>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
    <?php if ($count > 1) : ?>
      <div class="home-hero_action-container">
        <a class="home-hero_left-action bg-white text-decoration-none p-2 mr-1" href="javascript:void(0)" role="button">
          <img src="<?php echo get_template_directory_uri() . '/assets/images/arrow-top.svg'; ?>" alt="Arrow Left"/>
        </a>
        <a class="home-hero_right-action bg-white text-decoration-none p-2" href="javascript:void(0)" role="button">
          <img src="<?php echo get_template_directory_uri() . '/assets/images/arrow-top.svg'; ?>" alt="Arrow Right"/>
        </a>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</section>
