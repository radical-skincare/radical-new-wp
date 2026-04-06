<div id="favoritesModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="leftSidebarModalLabel" aria-hidden="true">
  <div class="modal-dialog ml-auto mt-0 mr-0 mb-0" role="document">
    <div class="modal-content">
      <div class="modal-body py-0">
        <div class="favorites-container">
          <div class="d-flex flex-column h-100">
            <button type="button" class="btn ml-auto">
              <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/close.svg'); ?>" alt="Close" data-dismiss="modal"/>
            </button>
            <h4 class="text-center">Wishlist</h4>
            <p class="d-none text-center px-3" id="favorites_status"></p>
            <div class="row favorites-products-container flex-grow-1"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
