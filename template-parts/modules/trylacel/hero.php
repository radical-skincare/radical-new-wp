<?php
$feat_img_id = get_post_thumbnail_id( get_the_id() );
$feat_img_url = $feat_img_id ? wp_get_attachment_image_src($feat_img_id, "full")[0] : 'https://radicalskincare.com/wp-content/uploads/2022/01/Radical-on-Repeat.jpg';
?>
<style>
.hero-image-container {
  background-image: url('<?php echo esc_url($feat_img_url); ?>');
}
</style>
<section id="hero" class="hero bg-lightestgray">
  <div class="container-fluid">
    <div class="row">
      <img src="<?php echo esc_url($feat_img_url); ?>" alt="Trylacel Technology" class="d-lg-none hero-image-mobile"/>
      <div class="d-flex justify-content-center align-items-center col-lg-6 bg-lightestgray p-3 p-lg-5">
        <div class="text-container p-3 p-lg-0 text-center">
          <div class="fs-1.5x mb-1 font-weight-normal text-darker-gray text-uppercase">
            Trylacel Technology
          </div>
          <div class="fs-2x mb-3 text-darker-gray font-weight-normal">
            Discover What It Means For You!
          </div>
          <p class="fs-1.5x text-dark-gray font-weight-light">
            It Enables the maximum concentration of actives for maximum delivery to the skin, and for all skin types & It keeps the antioxidant potency and performance in the product to deliver maximum benefits and results quickly.
          </p>
          <button class="btn btn-outline-dark px-3 learn-more-btn"><i class="fa fa-play" aria-hidden="true"></i> Learn More</button>
        </div>
      </div>
      <div class="d-none d-lg-block col-lg-6 hero-image-container"></div>
    </div>
  </div>
</section>

<div class="modal fade" id="trylacelModal" tabindex="-1" role="dialog" aria-labelledby="trylacelModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-darkergray" id="trylacelModalLabel">Radical Breakthrough Trylacel<sup>&trade;</sup> Technology</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="top: 16px;right: 16px;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-0">
        <div class="embed-responsive embed-responsive-16by9">
          <iframe class="embed-responsive-item" src="" style="margin-bottom: -7px;" frameborder="0" allowfullscreen allow="autoplay; encrypted-media"></iframe>
        </div>
      </div>
    </div>
  </div>
</div>
