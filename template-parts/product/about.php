
<?php
$about = get_field('about');
$enable = isset($about['enable']) ? $about['enable'] : true;
?>
<?php if ($enable) : ?>
  <section id="about" class="py-5">
    <div class="container text-center">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <h3 class="fs-2.5x mb-3">Look Good. Feel Good. Do Good.</h3>
          <div class="mb-5">
            <?php echo $about['description']; ?>
          </div>
        </div>
      </div>
      <div class="about-icons row justify-content-center">
        <div class="col-6 col-lg-2 mb-3">
          <img src="<?php echo get_template_directory_uri() . '/assets/images/product/rabbit.svg'; ?>" alt="Cruelty Free" class="d-block mx-auto mb-3"/>
          <p class="text-center text-uppercase mb-0">Cruelty Free</p>
        </div>
        <?php /* <div class="col-6 col-lg-2 mb-3">
          <img src="<?php echo get_template_directory_uri() . '/assets/images/product/gluten.svg'; ?>" alt="Gluten Free" class="d-block mx-auto mb-3"/>
          <p class="text-center text-uppercase mb-0">Gluten Free</p>
        </div> */ ?>
        <div class="col-6 col-lg-2 mb-3">
          <img src="<?php echo get_template_directory_uri() . '/assets/images/product/soy.svg'; ?>" alt="Soy Free" class="d-block mx-auto mb-3"/>
          <p class="text-center text-uppercase mb-0">Soy Free</p>
        </div>
        <?php /* <div class="col-6 col-lg-2 mb-3">
          <img src="<?php echo get_template_directory_uri() . '/assets/images/product/sugar.svg'; ?>" alt="Sugar Free" class="d-block mx-auto mb-3"/>
          <p class="text-center text-uppercase mb-0">Sugar Free</p>
        </div> */ ?>
        <div class="col-6 col-lg-2">
          <img src="<?php echo get_template_directory_uri() . '/assets/images/product/non-gmo.svg'; ?>" alt="GMO Free" class="d-block mx-auto mb-3"/>
          <p class="text-center text-uppercase mb-0">GMO Free</p>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>
