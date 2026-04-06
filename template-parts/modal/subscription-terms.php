
<div id="subProductTermsModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="subProductTermsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="subProductTermsModalLabel" class="modal-title quick-view-title m-0" class="m-0">Subscription Product Terms and Conditions</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="top: 1.25rem; right: 1rem;">
          <i class="fa fa-close" aria-hidden="true"></i>
        </button>
      </div>
      <div class="modal-body" style="height: 100%; max-height: 550px; overflow-y: scroll;">
        <div class="row">
          <div class="col">
            <?php if ($page_id = get_field('subscription_product_terms_page', 'option')) : ?>
              <?php if ($subscription_product_terms_page = get_post( $page_id )) : ?>
                <?php echo $subscription_product_terms_page->post_content; ?>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
