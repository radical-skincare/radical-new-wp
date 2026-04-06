<?php if ($products_by_concern = get_field('products_by_concern')) : ?>
  <section id="products" class="products pb-5 position-relative">
    <div class="container">
      <?php if (!empty($products_by_concern['concerns_products'])) : ?>
        <div class="products_tag-filters row justify-content-center mb-4">
          <?php foreach ($products_by_concern['concerns_products'] as $key => $tab) : ?>
            <div class="col-6 col-sm-4 col-lg-2">
              <label for="product-filter-key_<?php echo esc_attr($key); ?>" class="ff-orpheus <?php echo $key === 0 ? 'active' : ''; ?> text-capitalize fs-1.25x font-weight-light">
                <?php echo $tab['concern']; ?>
                <input type="radio" id="product-filter-key_<?php echo esc_attr($key); ?>" class="d-none"/>
              </label>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="w-100">
          <div class="text-separator best-seller-heading text-center d-none" style="width: 156px;">
            <h2 class="text-separator_inner-text fs-0.75x text-darkgray">#BESTSELLERS</h2>
          </div>
          <?php foreach ($products_by_concern['concerns_products'] as $key => $tab) : ?>
            <?php
            $products = $tab['products'];
            ?>
            <?php if ($products && count($products)) : ?>
              <div class="products-container product-filter-key_<?php echo esc_attr($key); ?> <?php echo ($key !== 0) ? 'd-none' : ''; ?> row justify-content-center" style="row-gap: 1rem;">
                <?php foreach ($products as $product) : ?>
                  <div class="col-sm-6 col-lg-3 mb-4 mb-lg-0">
                    <?php get_template_part('template-parts/content', 'product'); ?>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php else : ?>
              <div class="products-container  product-filter-key_<?php echo esc_attr($key); ?> <?php echo ($key !== 0) ? 'd-none' : ''; ?> no-products w-100 text-center">
                No products found for this concern.
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>
<?php endif; ?>
