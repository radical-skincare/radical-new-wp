<?php if ($selection_process = get_field('selection_process')) : ?>
  <section class="template-radical-repeat_wait-theres-more py-5 py-lg-10 container">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center">
        <?php if ($heading = $selection_process['heading']) : ?>
          <h2 class="<?php echo empty($selection_process['content']) ? 'mb-0' : 'mb-4'; ?>"><?php echo $heading; ?></h2>
        <?php endif; ?>
        <?php if ($content = $selection_process['content']) : ?>
          <div>
            <?php echo $content; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
<?php endif; ?>
