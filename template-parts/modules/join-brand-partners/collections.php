<?php if ($collections = get_field('collections')) : ?>
  <section class="container my-5">
    <?php if ($collections) : ?>
      <div class="row d-flex">
        <?php foreach ($collections as $collection) : ?>
          <?php if ($product = $collection['product']) : ?>
            <?php
            $wc_product = wc_get_product($product->ID);
            $currency_symbol = get_woocommerce_currency_symbol();
            ?>
            <div class="col-12 col-lg-4">
              <?php
              $img_url = isset($collection['image']) ? $collection['image']['url'] : wp_get_attachment_url($wc_product->get_image_id());
              $title = $wc_product->get_title();
              $sale_price = '<span class="woocommerce-Price-currencySymbol">' . $currency_symbol . '</span>' . $wc_product->get_sale_price();
              $regular_price = '<span class="woocommerce-Price-currencySymbol">' . $currency_symbol . '</span>' . $wc_product->get_regular_price();
              $save_percentage = $collection['save_percentage'];
              $description = $collection['description'];
              get_template_part('template-parts/modules/flex/collection-card');
              ?>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
<?php endif; ?>
