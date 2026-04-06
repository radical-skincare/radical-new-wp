<div id="trylacel-content" class="row my-5">
  <?php if ($content_columns = get_field('content_columns')) : ?>
    <?php foreach ($content_columns as $column) : ?>
      <div class="col-lg-4 mb-4 mb-lg-0">
        <div class="card">
          <?php if ($column['image']) : ?>
            <div class="trylacel-img_wrap mb-3">
              <img class="trylacel-img <?php echo esc_attr($column['image_classes']); ?>" src="<?php echo esc_url($column['image']['url']); ?>" alt="<?php echo esc_attr($column['image']['alt']); ?>" loading="lazy"/>
            </div>
          <?php endif; ?>
          <div class="card-body">
            <h4 class="card-title ff-orpheus text-darker-gray fs-2x"><?php echo $column['heading']; ?></h4>
            <h6 class="text-dark-gray"><?php echo $column['subheading']; ?></h6>
            <p class="card-text text-dark-gray mb-3">
              <?php echo $column['content']; ?>
            </p>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
