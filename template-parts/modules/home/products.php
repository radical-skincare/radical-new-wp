<?php if ($products_by_concern = get_field('products_by_concern')) : ?>
  <?php
  $filters = [];
  if ($products_by_concern['concerns']) {
    foreach ($products_by_concern['concerns'] as $tag_id) {
      $filters[] = json_decode(json_encode(get_term_by('ID', $tag_id, 'product_tag')), true);
    }
  }
  ?>
  <section id="products" class="products pb-5 position-relative">
    <div class="container">
      <?php if (!empty($filters)) : ?>
        <div class="products_tag-filters row justify-content-center mb-4">
          <?php foreach ($filters as $key => $filter) : ?>
            <div class="col-6 col-sm-4 col-lg-2">
              <label for="product-filter-key_<?php echo esc_attr($filter['term_id']); ?>" class="ff-orpheus <?php echo $key === 0 ? 'active' : ''; ?> text-capitalize fs-1.25x font-weight-light">
                <?php echo $filter['name']; ?>
                <input type="radio" id="product-filter-key_<?php echo esc_attr($filter['term_id']); ?>" class="d-none"/>
              </label>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="w-100">
          <div class="text-separator best-seller-heading text-center d-none" style="width: 156px;">
            <h2 class="text-separator_inner-text fs-0.75x text-darkgray">#BESTSELLERS</h2>
          </div>
          <?php foreach ($filters as $key => $filter) : ?>
            <?php
            $args = [
              'post_type' => 'product',
              'post_status' => 'publish',
              'numberposts' => 4,
              'orderby' => 'meta_value_num menu_order title',
              'order' => 'ASC',
              'tax_query' => [
                [
                  'taxonomy' => 'product_tag',
                  'field' => 'slug',
                  'terms' => [
                    $filter['name']
                  ],
                ]
              ],
              'meta_query' => [
                [
                  'key' => '_stock_status',
                  'value' => 'instock',
                  'compare' => '=',
                ]
              ]
            ];
            $products = get_posts($args);
            ?>
            <?php if ($products && count($products)) : ?>
              <div class="products-container product-filter-key_<?php echo esc_attr($filter['term_id']); ?> <?php echo ($key !== 0) ? 'd-none' : ''; ?> row justify-content-center">
                <?php foreach ($products as $product) : ?>
                  <div class="col-sm-6 col-lg-3 mb-4 mb-lg-0">
                    <?php get_template_part('template-parts/content', 'product'); ?>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php else : ?>
              <div class="products-container  product-filter-key_<?php echo esc_attr($filter['term_id']); ?> <?php echo ($key !== 0) ? 'd-none' : ''; ?> no-products w-100 text-center">
                No products found for this concern.
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>
<?php endif; ?>
