<?php if ($products_by_concern = get_field('products_by_concern')) : ?>
  <section id="trust-us-we-get-it" class="pt-5 mb-4">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6 text-center">
          <h2 class="ff-orpheus text-darker-gray">
            <?php echo $products_by_concern['heading']; ?>
          </h2>
          <div class="text-dark-gray">
            <?php echo $products_by_concern['description']; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>
