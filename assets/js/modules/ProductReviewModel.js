
const ProductReviewModel = {
  currentPage: 1,
  perPage: 8,
  continuouslyScroll: true,
  onLoad: function() {
    if (!$('#selectProductToReviewModel').length) {
      return
    }
    this.onShow()
    this.onSearch()
    this.onClearSearch()
    this.addButtonInWpLoyality()
    this.socialShare()
    const $search_products_container = $('.review-search-products-container-modal-body')
    $search_products_container.on('scroll', ProductReviewModel.loadMore)
  },
  addButtonInWpLoyality: function(){
    $('.wlr-campaign-container p.wlr-name').map((k, e) => {
      if ($(e).text().toLowerCase().search('review') >= 0) {
        $(e).parent().parent().find('.wlr-campaign-points').parent().append(`
          <button type="button" class="btn btn-small btn-dark" data-toggle="modal" data-target="#selectProductToReviewModel" style="font-family: Josefin Sans,sans-serif;font-size: 12px;font-weight: 300;">
            Review Product
          </button>
        `)
      }
    })
  },
  socialShare: function() {
    jQuery('.wlr-social-share .wlr-social-text').map((k, e) => {
      const $socialElement = jQuery(e);
      const $socialParentElement = $socialElement.parent();

      if ($socialElement.text().toLowerCase().search('facebook') >= 0) {
        $socialParentElement.hide()
        jQuery('.wlr-campaign-container p.wlr-name').map((k, ne) => {
          if (jQuery(ne).text().toLowerCase().search('facebook') >= 0) {
            jQuery(ne).parent().parent().find('.wlr-campaign-points').parent().append(`
            <div class="wlr-date" style="position:relative;float:right;">
                <a style="background:#333333;" onclick="`+ $socialParentElement.attr('onclick') +`">
                  <span class="wlr"> Share </span>
                </a>
            </div>
            `)
          }
        })
      }
    })
  },
  loadMore: function(e) {
    if (!ProductReviewModel.continuouslyScroll) {
      return
    }
    const $currentElm = $(e.currentTarget);
    if (Math.round(($currentElm[0].scrollHeight - $currentElm.scrollTop())) == Math.round($currentElm.outerHeight())) {
      ProductReviewModel.performSearch($('#review-search-bar').val())
    }
  },
  onShow: function() {
    $('#selectProductToReviewModel').on('shown.bs.modal', function () {
      $('#review-search-bar').focus()
    })
  },
  onSearch: function() {
    const $search_bar = $('#review-search-bar')
    let timeout
    $search_bar.on('input, keyup', function() {
      const newSearch = $(this).val()
      if (newSearch !== '') {
        $('.clear-review-search').removeClass('d-none')
        if (timeout) {
          clearTimeout(timeout)
        }
        timeout = setTimeout(function() {
          $('.review-search-products-container').html('')
          ProductReviewModel.performNewSearch(newSearch)
        }, 1000)
      } else {
        $('.clear-review-search').addClass('d-none')
      }
    })
  },
  onClearSearch: function() {
    const $clear_search = $('.clear-review-search')
    const $search_products_container = $('.review-search-products-container')
    const search_status = $('#review_search_status')
    $clear_search.on('click', function() {
      ProductReviewModel.continuouslyScroll = true
      ProductReviewModel.currentPage = 1
      $('#review-search-bar').val('')
      $(this).addClass('d-none')
      search_status.addClass('d-none')
      $search_products_container.scrollTop(0)
      $search_products_container.html('')
    })
  },
  performSearch: function(search) {
    /* global ThemeSettings */
    const $search_products_container = $('.review-search-products-container')
    const search_status = $('#review_search_status')
    search_status.addClass('d-none')
    let search_products_container_html = ''
    for (let i = 0; i < 6; i++) {
      search_products_container_html += '<div class="col-md-4 placeholder-item-wrapper"><div class="placeholder-item"></div></div>'
    }
    $search_products_container.append(search_products_container_html)
    ProductReviewModel.continuouslyScroll = false
    $.post(ThemeSettings.admin_ajax_url,
      {
        action: 'search_product',
        search,
        page: ProductReviewModel.currentPage,
      },
      function(response) {
        search_products_container_html = ''
        response = JSON.parse(response)
        if (!response.success || !response.products || !response.products.length) {
          search_status.removeClass('d-none')
          search_status.html('Unable to find product\'s matching your requests.')
        }
        response.products.forEach(product => {
          search_products_container_html += `
            <div class="col-md-4">
              <a class="product-container" href="${product.product_link}#related">
                <img src="${product.thumbnail_url}" alt="${product.name}" class="w-100"/>
                <p class="text-center text-dark mb-0">${product.name}</p>
              </a>
            </div>`
        })
        $search_products_container.find('.placeholder-item-wrapper').remove()
        $search_products_container.append(search_products_container_html)
        ProductReviewModel.continuouslyScroll = ((ProductReviewModel.currentPage * ProductReviewModel.perPage) < response.total)
        ProductReviewModel.currentPage++
    }).catch(() => {
      search_status.removeClass('d-none')
      search_status.html('Error. Please try again.')
    })
  },
  performNewSearch: function (newSearch) {
    this.continuouslyScroll = true
    this.currentPage = 1
    $('.review-search-products-container').scrollTop(0)
    this.performSearch(newSearch)
  },
}
