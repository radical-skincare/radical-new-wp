<div class="card card-collection w-100 border-0">
  <div class="position-relative">
    <div class="position-absolute text-center py-1 px-2 bg-white fs-0.75x m-2" style="right: 0">
      <span class="fs-0.625x text-uppercase">Save</span><br>
      <span class="fs-0.75x font-weight-five"><?php echo esc_html($save_percentage); ?></span>
    </div>
    <img class="card-img-top" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($title); ?>">
  </div>
  <div class="card-body px-0">
    <h4 class="card-title"><?php echo esc_html($title); ?></h4>
    <div class="fs-1.5x font-weight-light text-dark-gray">
      <span class="border-dark border-right border-right-1 pr-2"><?php echo $sale_price; ?></span>
      <span class="font-weight-light text-light-gray"><?php echo $regular_price; ?></span>
      <span class="fs-0.75x pl-2 position-absolute">value</span>
    </div>
    <p class="card-text fs-0.75x text-dark-gray font-weight-light">
      <?php echo $description; ?>
    </p>
  </div>
</div>
