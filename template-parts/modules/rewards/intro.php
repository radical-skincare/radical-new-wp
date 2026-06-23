<?php
$divider_text = $args['divider_text'];
$image = $args['image'];
$heading = $args['heading'];
$content = $args['content'];
?>
<section class="intro py-5">
  <?php if ($divider_text) : ?>
    <div class="container position-relative bg-lightestgray pt-5 mt-4 pb-5">
      <div class="d-block border border-1 border-dark my-4 mr-auto ml-auto vertical-line"></div>
      <div class="row d-flex justify-content-center">
        <div class="col-xl-6 py-4">
          <p class="text-center title-c"><?php echo $divider_text; ?></p>
        </div>
      </div>
    </div>
  <?php endif; ?>
  <div class="container px-lg-5" style="margin-top: -50px">
    <div class="row d-flex justify-content-center align-items-center">
      <div class="col-12 col-lg-6 px-lg-3 mb-3 mb-lg-0">
        <?php if ($image) : ?>
          <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="w-100 rounded"/>
        <?php endif; ?>
      </div>
      <div class="col-12 col-lg-6 px-lg-3 mb-3 mb-lg-0">
        <h2 class="title-d"><?php echo $heading; ?></h2>
        <p class="text-dark-gray font-weight-light"><?php echo $content; ?></p>
      </div>
    </div>
  </div>
</section>
