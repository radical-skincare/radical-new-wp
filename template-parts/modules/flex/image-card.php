<?php
/**
 * Flex Image Card Module
 *
 * Expected variables: $img, $title, $text
 * Optional: $image_align, $subtitle, $link, $link_classes, $image_bg_color
 */
?>
<section class="flex_image-card <?php echo (isset($image_align) && $image_align == 'right') ? 'right-left' : 'left-right'; ?>">
  <div class="container bg-lightestgray py-5 px-lg-5">
    <div class="row">
      <?php if (!isset($image_align) || $image_align == 'left') : ?>
        <div class="d-flex col-lg-6 align-items-center">
          <?php if ($img) : ?>
            <img src="<?php echo esc_url($img['url']); ?>" alt="<?php echo esc_attr($img['alt']); ?>" class="flex_image-card_image" <?php echo isset($image_bg_color) ? 'style="background-color: ' . esc_attr($image_bg_color) . ';"' : ''; ?> loading="lazy"/>
          <?php endif; ?>
        </div>
      <?php endif; ?>
      <div class="col-lg-6 <?php echo (isset($image_align) && $image_align == 'right') ? 'order-lg-first order-last' : ''; ?>" style="z-index: 1">
        <div class="h-100 px-3 px-lg-0 d-lg-flex align-items-lg-center">
          <div class="card">
            <div class="card-body text-center text-lg-left p-lg-4">
              <?php if (isset($subtitle)) : ?>
                <div class="card-subtitle label-two"><?php echo $subtitle; ?></div>
              <?php endif; ?>
              <?php if (isset($title)) : ?>
                <div class="card-title ff-orpheus text-darker-gray fs-2x"><?php echo $title; ?></div>
              <?php endif; ?>
              <div class="card-text text-dark-gray <?php echo isset($link) ? 'mb-3' : ''; ?>">
                <?php echo $text; ?>
              </div>
              <?php if (isset($link) && $link) : ?>
                <a href="<?php echo esc_url($link['url']); ?>" class="<?php echo isset($link_classes) ? esc_attr($link_classes) : 'btn btn-dark'; ?>" target="<?php echo esc_attr($link['target']); ?>"><?php echo esc_html($link['title']); ?></a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <?php if (isset($image_align) && $image_align == 'right') : ?>
        <div class="d-flex col-lg-6 order-first order-lg-last align-items-center">
          <?php if ($img) : ?>
            <img src="<?php echo esc_url($img['url']); ?>" alt="<?php echo esc_attr($img['alt']); ?>" class="flex_image-card_image" <?php echo isset($image_bg_color) ? 'style="background-color: ' . esc_attr($image_bg_color) . ';"' : ''; ?> loading="lazy"/>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
