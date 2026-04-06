<style>
.timeline-item img {
  border-radius: 0.25rem;
}
</style>
<section class="timeline my-5">
  <div class="container">
    <div class="row mb-4">
      <div class="col text-center">
        <h2 class="font-weight-light mb-0">Our Story Continued...</h2>
      </div>
    </div>
    <?php if ($timeline = get_field('timeline')) : ?>
      <?php foreach ($timeline as $key => $timeline_item) : ?>
        <div class="timeline-item row">
          <?php if (($key + 1) % 2 == 0) : ?>
            <div class="d-flex align-items-center justify-content-center col-lg-5 py-2">
              <div class="card border-0">
                <div class="card-body pl-0">
                  <h4 class="card-title text-uppercase"><?php echo $timeline_item['heading']; ?></h4>
                  <div class="card-text font-weight-light"><?php echo $timeline_item['content']; ?></div>
                  <?php if ($timeline_item['image_position'] === 'below_content' && isset($timeline_item['image']['url'])) : ?>
                    <img src="<?php echo esc_url($timeline_item['image']['url']); ?>" alt="<?php echo esc_attr($timeline_item['heading']); ?>" class="w-100" loading="lazy"/>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php else : ?>
            <div class="d-flex align-items-center justify-content-center flex-column col-lg-5">
              <div class="year-container ff-orpheus text-center font-italic"><?php echo esc_html($timeline_item['year']); ?></div>
              <span class="sr-only"><?php echo esc_html($timeline_item['year']); ?></span>
              <?php if ($timeline_item['image_position'] === 'below_year' && isset($timeline_item['image']['url'])) : ?>
                <img src="<?php echo esc_url($timeline_item['image']['url']); ?>" alt="<?php echo esc_attr($timeline_item['heading']); ?>" class="w-100" loading="lazy"/>
              <?php endif; ?>
            </div>
          <?php endif; ?>
          <div class="col-lg-2 text-center flex-column d-none d-lg-flex">
            <div class="row h-50">
              <div class="col <?php echo $key != 0 ? 'border-right':''; ?> border-dark">&nbsp;</div>
              <div class="col">&nbsp;</div>
            </div>
            <div class="m-2">
              <span class="badge badge-pill bg-dark">&nbsp;</span>
            </div>
            <div class="row h-50">
              <div class="col border-right border-dark">&nbsp;</div>
              <div class="col">&nbsp;</div>
            </div>
          </div>
          <?php if (($key + 1) % 2 == 0) : ?>
            <div class="d-flex align-items-center justify-content-center flex-column col-lg-5 order-first order-lg-last">
              <div class="year-container ff-orpheus text-center font-italic"><?php echo esc_html($timeline_item['year']); ?></div>
              <span class="sr-only"><?php echo esc_html($timeline_item['year']); ?></span>
              <?php if ($timeline_item['image_position'] === 'below_year' && isset($timeline_item['image']['url'])) : ?>
                <img src="<?php echo esc_url($timeline_item['image']['url']); ?>" alt="<?php echo esc_attr($timeline_item['heading']); ?>" class="w-100" loading="lazy"/>
              <?php endif; ?>
            </div>
          <?php else : ?>
            <div class="d-flex align-items-center justify-content-center col-lg-5 py-2">
              <div class="card border-0">
                <div class="card-body pl-0">
                  <h4 class="card-title text-uppercase"><?php echo $timeline_item['heading']; ?></h4>
                  <div class="card-text font-weight-light"><?php echo $timeline_item['content']; ?></div>
                  <?php if ($timeline_item['image_position'] === 'below_content' && isset($timeline_item['image']['url'])) : ?>
                    <img src="<?php echo esc_url($timeline_item['image']['url']); ?>" alt="<?php echo esc_attr($timeline_item['heading']); ?>" class="w-100" loading="lazy"/>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
      <div class="row justify-content-center">
        <div class="col text-center">
          <div class="m-2">
            <span class="badge badge-pill bg-dark">&nbsp;</span>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>
