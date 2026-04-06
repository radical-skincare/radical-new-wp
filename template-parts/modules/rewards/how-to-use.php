<?php if ($how_to_use = get_field('how_to_use')) : ?>
  <section class="getting-started mb-5" style="background-image: url('<?php echo esc_url($how_to_use['image']['url']); ?>')">
    <div class="container d-flex align-items-end p-5">
      <div class="card col-12 col-lg-6 mx-auto border-0">
        <div class="card-body text-center p-lg-4">
          <div class="card-subtitle text-uppercase label-two">How To Use Your Points</div>
          <div class="card-title text-darker-gray ff-orpheus fs-2x"><?php echo $how_to_use['heading']; ?></div>
          <div class="card-text text-dark-gray font-weight-light">
            <?php echo $how_to_use['content']; ?>
          </div>
      </div>
    </div>
  </section>
<?php endif; ?>
