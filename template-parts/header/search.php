<div id="searchModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="leftSidebarModalLabel" aria-hidden="true">
  <div class="modal-dialog ml-auto mt-0 mr-0 mb-0" role="document">
    <div class="modal-content">
      <div class="modal-body py-0">
        <div class="search-container">
          <div class="d-flex flex-column h-100">
            <button type="button" class="btn ml-auto">
              <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/close.svg'); ?>" alt="Close" data-dismiss="modal"/>
            </button>
            <div class="position-relative mb-3">
              <input type="text" id="search-bar" class="search-bar w-100 py-2 border-top-0 border-right-0 border-left-0" placeholder="Search Products">
              <a href="javascript:void(0)" class="clear-search position-absolute float-right d-none" data-dismiss="modal">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/close.svg'); ?>" alt="Close"/>
                Clear
              </a>
            </div>
            <p class="d-none text-center px-3" id="search_status"></p>
            <div class="row search-products-container flex-grow-1"></div>
            <div class="search-button-container py-3">
              <button id="perform_search" class="btn btn-darkergray w-100" disabled>Search</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
