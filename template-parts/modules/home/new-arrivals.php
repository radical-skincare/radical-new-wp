<?php if ($new_arrivals = get_field('new_arrivals')) : ?>
  <?php if ($new_arrivals['enable']) : ?>
    <?php
    $products = get_posts(
      [
        'post_type' => 'product',
        'tax_query' => array(
          array(
            'taxonomy' => 'product_cat',
            'field' => 'term_id',
            'terms' => $new_arrivals['product_cat']
          )
        ),
        'orderby' => 'rand',
        'order' => 'ASC',
        'posts_per_page' => 4
      ]
    );
    ?>
    <?php if ($products) : ?>
      <section id="new-arrivals" class="new-arrivals py-5">
        <div class="container">
          <?php if ($heading = $new_arrivals['heading']) : ?>
            <div class="row justify-content-center mb-3">
              <div class="col-lg-8 col-xl-6 text-center">
                <h2 class="ff-orpheus text-darker-gray mb-0">
                  <?php echo $heading; ?>
                </h2>
              </div>
            </div>
          <?php endif; ?>
          <?php if ($notice = $new_arrivals['notice']) : ?>
            <div class="row">
              <div class="col">
                <div class="alert alert-info mx-auto" role="alert" style="width: fit-content;">
                  <i class="fa fa-info-circle mr-2" aria-hidden="true"></i> <?php echo $notice; ?></div>
              </div>
            </div>
          <?php endif; ?>
          <div class="row justify-content-center">
            <?php foreach ($products as $product) : ?>
              <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <?php
                get_template_part('template-parts/content', 'product');
                ?>
              </div>
            <?php endforeach; ?>
          </div>
          <?php if ($button = $new_arrivals['button']) : ?>
            <div class="row">
              <div class="col text-center">
                <a class="btn btn-darkergray mx-auto" href="<?php echo esc_url($button['url']); ?>"><?php echo esc_html($button['title']); ?></a>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </section>
    <?php endif; ?>
  <?php endif; ?>
<?php endif; ?>
