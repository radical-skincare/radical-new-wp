
<?php if ($hero = get_field('hero')) : ?>
  <?php
  $post_thumbnail_id = get_post_thumbnail_id(get_the_id());
  $feat_img_url = ($post_thumbnail_id ? wp_get_attachment_image_src( $post_thumbnail_id, "full")[0] : '');
  $link = $hero['link'];
  ?>
  <section id="hero" class="hero hero_image-right bg-lightestgray">
    <div class="container-fluid">
      <div class="row">
        <?php if ($feat_img_url) : ?>
          <img src="<?php echo esc_html($feat_img_url); ?>" alt="Join Brand Partners" class="d-lg-none hero-image-mobile"/>
        <?php endif; ?>
        <div class="d-flex justify-content-center align-items-center col-lg-6 bg-lightestgray p-3 p-lg-5">
          <div class="text-container p-3 p-lg-0 text-center">
            <?php if ($subtitle = $hero['subtitle']) : ?>
              <div class="fs-1.5x mb-1 font-weight-normal text-darker-gray text-uppercase">
                <?php echo $subtitle; ?>
              </div>
            <?php endif; ?>
            <?php if ($title = $hero['title']) : ?>
              <h2 class="fs-2x mb-3 text-darker-gray font-weight-normal">
                <?php echo $title; ?>
              </h2>
            <?php endif; ?>
            <?php if ($desc = $hero['description']) : ?>
              <p class="fs-1.25x text-dark-gray font-weight-light">
                <?php echo $desc; ?>
              </p>
            <?php endif; ?>
            <?php if ($link) : ?>
              <a href="<?php echo esc_html($link['url']); ?>" class="link-underline link-underline_darker-gray"
                target="<?php echo esc_html($link['target']); ?>"
                <?php if ($link['url'] === '#heroVideoModal') : ?>
                  data-toggle="modal" data-target="#heroVideoModal"
                <?php endif; ?>
                >
                <?php echo $link['title']; ?>
              </a>
            <?php endif; ?>
          </div>
        </div>
        <?php if ($feat_img_url) : ?>
          <div class="d-none d-lg-block col-lg-6 hero-image-container" style="background-image: url('<?php echo $feat_img_url; ?>');"></div>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <?php if ($link && $link['url'] === '#heroVideoModal') : ?>
    <?php
    $youtube_video_id = isset($hero['youtube_video_id']) ? $hero['youtube_video_id'] : false;
    ?>
    <div class="modal modal_video fade" id="heroVideoModal" tabindex="-1" role="dialog" aria-labelledby="heroModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-body p-0">
            <div class="embed-responsive embed-responsive-16by9">
              <iframe class="embed-responsive-item" src="" data-src="https://www.youtube.com/embed/<?php echo $youtube_video_id ? $youtube_video_id : ''; ?>" frameborder="0" allowfullscreen allow="autoplay; encrypted-media"></iframe>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
<?php endif; ?>
