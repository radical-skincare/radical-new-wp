
<section id="how-to-apply" class="how-to-apply product-section_video mb-4">
  <?php if ($how_to_apply = get_field('how_to_apply_section')) : ?>
    <?php if (isset($how_to_apply['steps']) && $how_to_apply['steps']) : ?>
      <div class="bg-lightestgray my-5">
        <div class="container py-5">
          <div class="how-to-apply_steps row justify-content-center equal">
            <?php foreach ($how_to_apply['steps'] as $key => $item) : ?>
              <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 d-table-cell">
                <div class="h-100 d-flex flex-column justify-content-between bg-white p-3 rounded">
                  <div class="ff-orpheus text-center fs-1.5x m-0">Step <?php echo radical_convert_number_to_word($key); ?></div>
                  <p class="ff-orpheus text-center text-darkgray fs-1x my-3"><?php echo $item['instructions']; ?></p>
                  <div class="ff-orpheus text-center fs-1.5x m-0"><?php echo ($key + 1) < 10 ? '0' . ($key + 1) : $key; ?></div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    <?php endif; ?>
  <?php elseif ($vars['current_user_is_admin']) : ?>
    <div class="container">
      <div class="alert alert-danger mx-auto" style="width: fit-content;">
        <p class="mb-0">Product apply info missing. Edit Product and add missing details.</p>
      </div>
    </div>
  <?php endif; ?>
</section>
