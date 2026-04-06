<section id="rewards_ways-to-earn" class="container bg-lightestgray py-5 mb-5">
  <div class="row justify-content-center mb-5">
    <div class="col-auto">
      <div class="text-separator" style="width: 256px;">
        <div class="text-separator_line"></div>
        <h2 class="text-separator_inner-text fs-0.75x text-uppercase"><?php echo $divider_text; ?></h2>
        <div class="text-separator_line"></div>
      </div>
    </div>
  </div>
  <?php if ($items) : ?>
    <div class="row d-flex justify-content-center" style="row-gap: 1rem;">
      <?php foreach ($items as $item) : ?>
        <div class="col-lg-4">
          <div class="h-100 card border-0">
            <div class="card-body text-center ff-orpheus text-lg-left p-lg-4">
              <?php if (isset($item['image'])) : ?>
                <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php echo esc_attr($item['image']['alt']); ?>"/>
              <?php endif; ?>
              <div class="card-title title-d"><?php echo $item['heading']; ?></div>
              <?php if (isset($item['subheading'])) : ?>
                <p><?php echo $item['subheading']; ?></p>
              <?php endif; ?>
              <div class="card-text text-dark-gray font-weight-light mb-3">
                <?php echo $item['description']; ?>
              </div>
              <?php if (isset($item['button']) && $item['button']) : ?>
                <a href="<?php echo esc_url($item['button']['url']); ?>" class="btn btn-outline-dark"><?php echo esc_html($item['button']['title']); ?></a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
