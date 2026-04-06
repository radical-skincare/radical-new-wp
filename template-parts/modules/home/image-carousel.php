<?php if ($images_carousel = get_field('images_carousel')) : ?>
  <?php if ($images_carousel['items']) : ?>
    <section id="image-carousel" style="display: none;">
      <div class="slick-carousel">
        <?php foreach ($images_carousel['items'] as $item) : ?>
          <?php if ($image = $item['image']) : ?>
            <div>
              <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="w-100"/>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>
<?php endif; ?>
