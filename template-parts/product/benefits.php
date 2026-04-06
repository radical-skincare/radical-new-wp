
<?php
$benefits = get_field('benefits');
$enable = isset($benefits['enable']) ? $benefits['enable'] : true;
?>
<?php if ($enable) : ?>
  <section id="benefits" class="benefits product-section_video mb-4">
    <div class="container">
      <div class="row align-items-center">
        <?php if ($benefits) : ?>
          <?php
          $left_column_content_type = $benefits['left_column_content_type'];
          ?>
          <div class="col-lg-4 mb-3 mb-lg-0">
            <h3 class="text-center product-section_video_heading mb-0">Benefits of <br/>Radical</h3>
            <?php if ($left_column_content_type === 'image') : ?>
              <?php if ($image = $benefits['image']) : ?>
                <img src="<?php echo esc_html($image['url']); ?>" alt="<?php echo esc_html($image['alt']); ?>" class="how-to-apply_right-img rounded"/>
              <?php endif; ?>
            <?php else : ?>
              <?php if ($text = $benefits['text']) : ?>
                <div class="benefits_text"><?php echo $text; ?></div>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        <?php endif; ?>
        <?php if ($how_to_apply = get_field('how_to_apply_section')) : ?>
          <?php if (isset($how_to_apply['video'])) : ?>
            <?php
            $video = $how_to_apply['video'];
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
        <?php endif; ?>
      </div>
    </div>
  </section>
<?php endif; ?>
