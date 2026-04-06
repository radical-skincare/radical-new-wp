
<?php
$frontpage_id = get_option( 'page_on_front' );
?>
<?php if ($press = get_field('press', $frontpage_id)) : ?>
  <section id="as-seen-in" class="bg-white py-5">
    <div class="container">
      <div class="row justify-content-center align-items-center mb-4">
        <div class="col-auto">
          <div class="text-separator" style="width: 164px;">
            <div class="text-separator_line"></div>
            <h3 class="text-separator_inner-text fs-1x">AS SEEN IN</h3>
            <div class="text-separator_line"></div>
          </div>
        </div>
      </div>
      <?php if ($press['press_items']) : ?>
        <div class="row align-items-center justify-content-center text-center">
          <?php foreach ($press['press_items'] as $key => $item) : ?>
            <?php if ($img = $item['image']) : ?>
              <div class="col-4 col-lg-2 mb-4 mb-lg-0" key="<?php echo esc_html($key); ?>">
                <img src="<?php echo esc_html($img['url']); ?>" alt="<?php echo esc_html($img['alt']); ?>" class="press-logo" style="height: <?php echo str_contains($img['url'], 'oprahwinfrey.svg') ? '4' : '2'; ?>rem;"/>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>
<?php endif; ?>
