
<?php if ($related_ids = get_post_meta(get_the_ID(), '_related_ids', true)) : ?>
  <section id="related" class="py-5">
    <div class="container">
      <h3 class="fs-2x text-center mb-3">Complete The Radical Regimen</h3>
      <div class="row justify-content-center">
        <?php foreach ($related_ids as $related_id) : ?>
          <?php
           $product = get_post($related_id);
          ?>
          <div class="col-lg-3 mb-3 mb-lg-0">
            <?php get_template_part('template-parts/content-product', null, ['product' => $product]); ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
<?php endif; ?>
