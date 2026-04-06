
<?php
$sitewide_discount = get_field('sitewide_discount', 'option');
?>
<?php if (isset($sitewide_discount['enable']) && $sitewide_discount['enable']) : ?>
  <?php
  $settings = $sitewide_discount['sale_popup_modal'];
  ?>
  <?php if (isset($settings['enable']) && $settings['enable']) : ?>
    <div class="modal fade" id="saleModal" tabindex="-1" role="dialog" aria-labelledby="saleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: .25rem;">
          <div class="position-relative modal-body p-0">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; top: 1rem; right: 0.5rem; z-index: 1; padding: 0.5rem; font-size: 1.75rem; width: 2.5rem; height: 2.5rem;">
              <i class="fa fa-close" aria-hidden="true"></i>
              <span class="sr-only">Close</span>
            </button>
            <?php echo $settings['content']; ?>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
<?php endif; ?>
