<?php if ($hero = get_field('hero')) : ?>
  <?php
  $thumbnail_id = get_post_thumbnail_id( get_the_id() );
  ?>
  <?php if ($thumbnail_id) : ?>
    <?php
    $feat_img_url = wp_get_attachment_image_src($thumbnail_id, 'full');
    if (isset($feat_img_url[0])) {
      $feat_img_url = $feat_img_url[0];
    }
    ?>
    <style>
    .template-press .hero {
      background-image: url('<?php echo esc_url($feat_img_url); ?>');
    }
    @media (min-width: 992px) {
      .template-press .hero {
        background-image: url('<?php echo esc_url($feat_img_url); ?>');
      }
    }
    </style>
  <?php endif; ?>
  <?php if ($hero['black_overlay']) : ?>
  <style>
  .hero_overlay_black {
    position: relative;
  }
  .hero_overlay_black::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
  }
  </style>
  <?php endif; ?>
  <section id="hero" class="hero <?php echo $hero['black_overlay'] ? 'hero_overlay_black' : ''; ?> d-flex justify-content-center align-items-center">
    <div class="relative text-white fs-2x fs-lg-3x font-weight-normal text-underline_white mb-3">
      Featured Press
    </div>
  </section>
<?php endif; ?>
