<?php
$feat_img_id = get_post_thumbnail_id( get_the_id() );
$feat_img_url = $feat_img_id ? wp_get_attachment_image_src($feat_img_id, "full")[0] : '';
?>
<section id="hero" class="hero align-items-lg-center" style="background-image: url('<?php echo esc_url($feat_img_url); ?>');">
  <div class="container-fluid">
    <div class="row">
      <img src="<?php echo esc_url($feat_img_url); ?>" alt="Radical on Repeat" class="d-lg-none hero-image-mobile w-100"/>
      <div class="container-lg text-container relative" style="z-index: 1;">
        <div class="d-flex justify-content-center align-items-center col-lg-6 p-3 p-lg-5">
          <div class="p-3 p-lg-0">
            <h1 class="fs-2x fs-lg-3x mb-3 font-weight-normal" style="line-height: 48px">
              Sisters on a Mission.
            </h1>
            <p class="fs-1.5x ml-lg-4 pr-lg-3 pl-lg-4 border border-white border-top-0 border-right-0 border-bottom-0 border-left-lg-1" style="line-height: 40px">Strong skincare with strong values, our commitment to inspire and empower is more than skin deep.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
