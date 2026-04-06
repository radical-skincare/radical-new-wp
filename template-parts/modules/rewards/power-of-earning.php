<section id="rewards_ways-to-earn" class="container bg-lightestgray py-5 mb-5">
  <div class="row justify-content-center mb-5">
    <div class="col-auto">
      <div class="text-separator" style="width: 256px;">
        <div class="text-separator_line"></div>
        <h2 class="text-separator_inner-text fs-0.75x text-uppercase">Power of Sharing & Earning</h2>
        <div class="text-separator_line"></div>
      </div>
    </div>
  </div>
  <?php if ($items) : ?>
    <div class="row d-flex justify-content-center" style="row-gap: 1rem;">
      <?php foreach ($items as $item) : ?>
        <div class="col-lg-4">
          <div class="h-100 card border-0">
            <div class="card-body text-center ff-orpheus text-lg-left p-lg-4">
              <div class="card-title title-d"><?php echo $item['heading']; ?></div>
              <div class="card-text text-dark-gray font-weight-light mb-3">
                <?php echo $item['description']; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
