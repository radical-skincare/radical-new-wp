
<div id="product-quickview-modal" class="modal fade product-quickview-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content p-2">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#333" class="bi bi-x-lg" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"/>
          <path fill-rule="evenodd" d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"/>
        </svg>
        <span style="font-size: 12px">Close</span>
      </button>
      <div class="product-loader">
        <div class="spinner-border" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>
      <div class="row">
        <div class="d-flex align-items-center col-lg-4 mb-3 mb-lg-0">
          <img class="product-quickview-modal_thumbnail w-100" src=""/>
        </div>
        <div class="col-lg-8 py-4">
          <div id="product-quickview-modal_name" class="h1 fs-2x"></div>
          <div id="product-quickview-modal_price" class="h2 fs-1.5x"></div>
          <p id="product-quickview-modal_description" class="fs-1x"></p>
          <div class="product-quickview-modal_action_container mt-auto mb-0 d-block">
            <a class="link-underline link-underline_darker-gray mr-3" id="product-quickview-modal_action_view_more">View Product</a>
            <a href="#" action="button" data-quantity="1" class="btn btn-darkergray add_to_cart_button" data-product_id="" data-product_sku="" aria-label="" rel="nofollow" id="product-quickview-modal_action_add_to_cart">Add to cart</a>
            <div class="dropdown card-overlay-add-to-cart single-add-to-cart-button-dropdown d-inline" style="max-width: 180px;">
              <button class="add_to_cart_button btn btn-darkergray px-2 dropdown-toggle" type="button" id="product-quickview-modal_action_dropdown_quick_view" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-product_id="" data-product_sku="" aria-label="" rel="nofollow">
                <span class="added_to_cart_label font-weight-normal">Added to Cart</span>
                <span class="add_to_cart_label font-weight-normal">Add to Cart</span>
              </button>
              <div class="dropdown-menu" aria-labelledby="single_add_to_cart_button_dropdown_quick_view" style="margin-top: -16px; margin-left: 4px;">
                <a class="dropdown-item one-time-purchase-action" href="javascript:void(0);">One Time Purchase</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
