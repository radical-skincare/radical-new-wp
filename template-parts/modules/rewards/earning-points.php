<?php if ($earning_points = get_field('earning_points')) : ?>
  <section class="getting-started mb-5" style="background-image: url('<?php echo esc_url($earning_points['image']['url']); ?>')">
    <div class="container d-flex align-items-end p-5">
      <div class="card col-12 col-lg-6 mx-auto border-0">
        <div class="card-body text-center p-lg-4">
          <div class="card-subtitle text-uppercase label-two">Earning Points</div>
          <div class="card-title text-darker-gray ff-orpheus fs-2x"><?php echo $earning_points['heading']; ?></div>
          <div class="card-text text-dark-gray font-weight-light">
            <?php echo $earning_points['content']; ?>
          </div>
      </div>
    </div>
  </section>
<?php endif; ?>
