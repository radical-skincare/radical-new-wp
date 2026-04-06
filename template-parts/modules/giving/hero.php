<?php if ($video = get_field('video')) : ?>
  <section id="feat-video" class="hero">
    <div class="d-flex justify-content-center align-items-center mb-5 position-relative">
      <div id="radical-giving-feat-video">
        <img src="<?php echo esc_url($video['thumbnail']['url']); ?>" class="w-100" alt="Unstoppable Foundation Radical"/>
        <div class="mask position-absolute d-flex justify-content-center align-items-center">
          <p class="text-primary play-button" aria-label="Play Button">
            <i aria-hidden="true" class="fa fa-play-circle"></i>
          </p>
        </div>
      </div>
      <div id="radical-giving-feat-video-wrap" class="embed-responsive embed-responsive-16by9" style="display: none; background-image: url('<?php echo esc_url($video['preloaded_background_image']['url']); ?>')">
        <iframe class="embed-responsive-item" src="<?php echo esc_url($video['vimeo_url']); ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
      </div>
    </div>
  </section>
<?php endif; ?>
