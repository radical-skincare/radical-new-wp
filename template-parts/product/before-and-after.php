<?php
global $product;
$before_and_after = get_field('before_and_after');
$hide_section = isset($before_and_after['hide_section']) ? $before_and_after['hide_section'] : false;
?>
<?php if (!$hide_section) : ?>
  <?php
  $background_img = get_field('background_image') ? get_field('background_image') : get_template_directory_uri() . '/assets/images/product/before-after-after.jpeg';
  $foreground_img = get_field('foreground_image') ? get_field('foreground_image') : get_template_directory_uri() . '/assets/images/product/before-after-before.jpeg';
  ?>
  <section id="before-and-after" class="container py-5">
    <div class="row justify-content-center mb-5">
      <div class="col-auto text-center">
        <div class="text-separator" style="width: 256px;">
          <div class="text-separator_line"></div>
          <h3 class="text-separator_inner-text text-uppercase fs-1x">Before & After</h3>
          <div class="text-separator_line"></div>
        </div>
      </div>
    </div>
    <div class="row justify-content-center align-items-center">
      <div class="col-12 col-lg-4 d-flex flex-column justify-content-center align-items-center mb-3">
        <div class='before-after-image-container'>
          <div class="img background-img" style="background-image: url('<?php echo $background_img; ?>');"></div>
          <div class="img foreground-img" style="background-image: url('<?php echo $foreground_img; ?>');"></div>
          <input type="range" min="1" max="100" value="50" class="slider" name="slider" id="before-after-slider"/>
          <div class="slider-button"></div>
        </div>
        <div>
          <i class="fa fa-angle-double-left" aria-hidden="true"></i>
          Swipe to see <strong class="text-primary">Radical</strong> Results
          <i class="fa fa-angle-double-right" aria-hidden="true"></i>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>
