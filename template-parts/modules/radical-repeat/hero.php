<?php
$feat_img_id = get_post_thumbnail_id( get_the_id() );
$feat_img_url = $feat_img_id ? wp_get_attachment_image_src($feat_img_id, "full")[0] : 'https://radicalskincare.com/wp-content/uploads/2022/01/Radical-on-Repeat.jpg';
?>
<style>
.hero-image-mobile {
  height: calc(50vh - 82px);
  width: 100%;
  object-fit: cover;
}
.hero-image-container {
  background-image: url('<?php echo esc_url($feat_img_url); ?>');
}
</style>
<section id="hero" class="hero bg-lightestgray">
  <div class="container-fluid">
    <div class="row">
      <img src="<?php echo esc_url($feat_img_url); ?>" alt="Radical on Repeat" class="d-lg-none hero-image-mobile"/>
      <div class="d-flex justify-content-center align-items-center col-lg-6 bg-lightestgray p-3 p-lg-5">
        <div class="text-container p-3 p-lg-0 text-center">
          <div class="fs-1.5x mb-1 font-weight-normal text-darker-gray text-uppercase">
            Radical On Repeat
          </div>
          <div class="fs-2x mb-3 text-darker-gray font-weight-normal">
            Replenishment Program
          </div>
          <p class="fs-1.5x text-dark-gray font-weight-light">
            Get Radical with our Radical on Repeat Replenishment Program and save 10% on every order! Take the hassle out of ordering and never run out of product again! You can customize your replenishment order or cancel at any time. We make joining Radical on Repeat simple with only a few steps to follow.
          </p>
        </div>
      </div>
      <div class="d-none d-lg-block col-lg-6 hero-image-container"></div>
    </div>
  </div>
</section>
