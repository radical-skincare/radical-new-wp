
<section id="technology" class="technology product-section_video py-5">
  <div class="container">
    <?php if ($technology = get_field('technology')) : ?>
      <div class="row align-items-center">
        <?php if (isset($technology['video']) && !empty($technology['video']) && !empty($technology['video']['image'])) : ?>
          <?php
          $video = $technology['video'];
          ?>
          <div class="col-lg-8 mb-3 mb-lg-0">
            <div class="watch-video-img-wrapper position-relative">
              <?php if ($img = $video['image']) : ?>
                <img src="<?php echo esc_html($img['url']); ?>" alt="<?php echo esc_html($img['alt']); ?>" class="w-100 img-fluid rounded z-depth-1-half"/>
              <?php endif; ?>
              <?php if (isset($video['youtube_id']) && !empty($video['youtube_id'])) : ?>
                <div class="inner-wrapper-watch-video-btn mask position-absolute" href="javascript:void(0)" title="Watch Video" data-iframe-src="<?php echo esc_html($video['youtube_id']); ?>">
                  <div class="text-white" aria-label="Play Button" style="cursor: pointer;">
                    <i class="fa fa-play-circle fa-5x" aria-hidden="true"></i>
                    <span class="sr-only">Play</span>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
        <?php endif; ?>
        <?php if (isset($technology['right_image']) && $technology['right_image']) : ?>
          <div class="col-lg-4">
            <h3 class="product-section_video_heading text-center mb-0">Behind The <br/>Ingredients</h3>
            <img src="<?php echo esc_html($technology['right_image']['url']); ?>" alt="<?php echo esc_html($technology['right_image']['alt']); ?>" class="technology_right-img rounded"/>
          </div>
        <?php endif; ?>
      </div>
    <?php elseif ($current_user_is_admin) : ?>
      <div class="alert alert-danger mx-auto" style="width: fit-content;">
        <p class="mb-0">Product apply info missing. Edit Product and add missing details.</p>
      </div>
    <?php endif; ?>
  </div>
</section>
