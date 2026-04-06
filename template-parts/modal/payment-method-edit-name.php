
<div class="modal fade" id="paymentMethodEditNameModal" tabindex="-1" role="dialog" aria-labelledby="paymentMethodEditNameLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentMethodEditNameLabel">Edit Payment Method</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_payment-method-update-name" method="POST">
          <input type="hidden" name="cc_id" value=""/>
          <div class="row">
            <div class="col-9">
              <div class="form-outline">
                <label for="cc-name" class="form-label">Payment Method Name</label>
                <input class="form-control form-control-lg" type="text" id="cc-name" name="cc_name" required/>
              </div>
            </div>
            <div class="col-3 pl-0">
              <button type="submit" class="btn btn-darkergray w-100" style="height: 3.5rem;" name="action" value="payment_methods_change_name">Update</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
