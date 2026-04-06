const Search = {
  currentPage: 1,
  perPage: 6,
  continuouslyScroll: true,
  onLoad: function() {
    if (!$('#searchModal').length) {
      return
    }
    this.onShow()
    this.onSearch()
    this.onClearSearch()
    this.onSearchClick()
    const $search_products_container = $('.search-products-container')
    $search_products_container.on('scroll', Search.loadMore)
  },
  loadMore: function(e) {
    if (!Search.continuouslyScroll) return
    const $currentElm = $(e.currentTarget);
    if (Math.round(($currentElm[0].scrollHeight - $currentElm.scrollTop())) == Math.round($currentElm.outerHeight())) {
      Search.performSearch($('#search-bar').val())
    }
  },
  onShow: function() {
    $('#searchModal').on('shown.bs.modal', function () {
      $('#search-bar').focus()
    })
  },
  onSearch: function() {
    const $search_bar = $('#search-bar')
    const $perform_search = $('#perform_search')
    let timeout
    $search_bar.on('input, keyup', function() {
      const newSearch = $(this).val()
      if (newSearch !== '') {
        $('.clear-search').removeClass('d-none')
        $perform_search.removeAttr('disabled')
        if (timeout) {
          clearTimeout(timeout)
        }
        timeout = setTimeout(function() {
          $('.search-products-container').html('')
          Search.performNewSearch(newSearch)
        }, 1000)
      } else {
        $('.clear-search').addClass('d-none')
        $perform_search.attr('disabled', 'disabled')
      }
    })
  },
  onClearSearch: function() {
    const $clear_search = $('.clear-search')
    const $search_products_container = $('.search-products-container')
    const search_status = $('#search_status')
    $clear_search.on('click', function() {
      Search.continuouslyScroll = true
      Search.currentPage = 1
      $('#search-bar').val('')
      $(this).addClass('d-none')
      search_status.addClass('d-none')
      $search_products_container.scrollTop(0)
      $search_products_container.html('')
    })
  },
  performSearch: function(search) {
    /* global ThemeSettings */
    const $search_products_container = $('.search-products-container')
    const search_status = $('#search_status')
    search_status.addClass('d-none')
    let search_products_container_html = ''
    for (let i = 0; i < 6; i++) {
      search_products_container_html += '<div class="col-sm-6 placeholder-item-wrapper"><div class="placeholder-item"></div></div>'
    }
    $search_products_container.append(search_products_container_html)
    Search.continuouslyScroll = false
    $.post(ThemeSettings.admin_ajax_url, {action: 'search_product', search, page: Search.currentPage, nonce: ThemeSettings.radical_nonce}, function(response) {
      search_products_container_html = ''
      response = JSON.parse(response)
      if (!response.success || !response.products || !response.products.length) {
        search_status.removeClass('d-none')
        search_status.html('Unable to find product\'s matching your requests.')
      }
      response.products.forEach(product => {
        search_products_container_html += `
          <div class="col-sm-6">
            <a class="product-container" href="${product.product_link}">
              <img src="${product.thumbnail_url}" alt="${product.name}" class="w-100"/>
              <p class="text-center">${product.name}</p>
            </a>
          </div>`
      })
      $search_products_container.find('.placeholder-item-wrapper').remove()
      $search_products_container.append(search_products_container_html)
      Search.continuouslyScroll = ((Search.currentPage * Search.perPage) < response.total)
      Search.currentPage++
    }).catch(() => {
      search_status.removeClass('d-none')
      search_status.html('Error. Please try again.')
    })
  },
  onSearchClick: function () {
    const $search_bar = $('#search-bar')
    const $perform_search = $('#perform_search')
    $perform_search.on('click', () => {
      const newSearch = $search_bar.val()
      if (!newSearch) return
      Search.performNewSearch(newSearch)
    })
  },
  performNewSearch: function (newSearch) {
    this.continuouslyScroll = true
    this.currentPage = 1
    $('.search-products-container').scrollTop(0)
    this.performSearch(newSearch)
  },
}
