<?php if ($getting_started = get_field('getting_started')) : ?>
<style>
  .template-join-brand-partners .getting-started {
    background-image: url('<?php echo esc_url($getting_started["image"]["url"]); ?>');
  }
</style>
<section class="getting-started mb-5">
  <div class="container d-flex align-items-end p-5">
    <div class="card col-12 col-lg-6 mx-auto border-0">
      <div class="card-body text-center p-lg-4">
        <div class="card-subtitle text-uppercase label-two">Getting Started</div>
        <div class="card-title text-darker-gray ff-orpheus fs-2x"><?php echo $getting_started['heading']; ?></div>
        <div class="card-text text-dark-gray font-weight-light">
          <?php echo $getting_started['content']; ?>
        </div>
    </div>
  </div>
</section>
<?php endif; ?>
