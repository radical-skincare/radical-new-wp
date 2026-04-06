<?php
$gifts = get_field('gifts', 'option');
?>
<?php if (isset($gifts['enable_renewal_gifts']) && $gifts['enable_renewal_gifts']) : ?>
  <section class="template-radical-repeat_gifts text-center bg-lightestgrey py-5 py-lg-10 container" id="radical-on-repeat-gift">
    <h2 class="section-heading mb-4">Earn Gifts</h2>
    <p>Active subscriptions earn gifts!</p>
    <div>
      <hr class="hr-red mb-4">
    </div>
    <?php if ($renewal_gifts = $gifts['renewal_gifts']) : ?>
      <div class="row justify-content-center" style="row-gap: 1rem;">
        <?php foreach ($renewal_gifts as $renewal_gift) : ?>
          <div class="col-md-3">
            <div class="step">
              <i class="fa fa-gift text-primary" style="font-size: 5rem;" aria-hidden="true"></i>
              <h3 class="step_heading mb-3 text-uppercase"><?php echo esc_html(radical_ordinal($renewal_gift['threshold'])); ?> Renewal Order Gift</h3>
              <p class="mb-0"><?php echo $renewal_gift['note']; ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
<?php endif; ?>
