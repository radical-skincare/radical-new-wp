
const WoocommerceSubscriptionSearch = {
  currentPage: 1,
  perPage: 9,
  continuouslyScroll: true,
  onLoad: function() {
    if (!$('#subscriptionAddProductModel').length) {
      return
    }
    this.onShow()
    this.onSearch()
    this.onClearSearch()
    const $subscription_search_products = $('#subscription-search-products')
    $subscription_search_products.on('scroll', WoocommerceSubscriptionSearch.loadMore)
  },
  loadMore: function(e) {
    if (!WoocommerceSubscriptionSearch.continuouslyScroll) return
    const $currentElm = $(e.currentTarget);
    if (Math.round(($currentElm[0].scrollHeight - $currentElm.scrollTop())) == Math.round($currentElm.outerHeight())) {
      WoocommerceSubscriptionSearch.performSearch($('#subscription-search-bar').val())
    }
  },
  onShow: function() {
    $('#subscriptionAddProductModel').on('shown.bs.modal', function () {
      $('#subscription-search-bar').focus()
    })
  },
  onSearch: function() {
    const $search_bar = $('#subscription-search-bar')
    let timeout
    $search_bar.on('input, keyup', function() {
      const newSearch = $(this).val()
      if (newSearch !== '') {
        $('#clear-subscription-search-bar').removeClass('d-none')
        if (timeout) {
          clearTimeout(timeout)
        }
        timeout = setTimeout(function() {
          $('#subscription-search-products').html('')
          WoocommerceSubscriptionSearch.performNewSearch(newSearch)
        }, 1000)
      } else {
        $('#clear-subscription-search-bar').addClass('d-none')
      }
    })
  },
  onClearSearch: function() {
    const $clear_search = $('#clear-subscription-search-bar')
    const $subscription_search_products = $('#subscription-search-products')
    const search_status = $('#search_status')
    $clear_search.on('click', function() {
      WoocommerceSubscriptionSearch.continuouslyScroll = true
      WoocommerceSubscriptionSearch.currentPage = 1
      $('#subscription-search-bar').val('')
      $(this).addClass('d-none')
      search_status.addClass('d-none')
      $subscription_search_products.scrollTop(0)
      $subscription_search_products.html('')
    })
  },
  performSearch: function(search) {
    /* global ThemeSettings */
    const $subscription_search_products = $('#subscription-search-products')
    const search_status = $('#subscription-search-notice')
    search_status.addClass('d-none')
    let search_products_container_html = ''
    for (let i = 0; i < 6; i++) {
      search_products_container_html += '<div class="col-sm-4 placeholder-item-wrapper mb-4"><div class="placeholder-item"></div></div>'
    }
    $subscription_search_products.append(search_products_container_html)
    WoocommerceSubscriptionSearch.continuouslyScroll = false
    $.post(ThemeSettings.admin_ajax_url, {action: 'search_product', search, page: WoocommerceSubscriptionSearch.currentPage, per_page : WoocommerceSubscriptionSearch.perPage, 'only_subscription' : true, nonce: ThemeSettings.radical_nonce}, function(response) {
      search_products_container_html = ''
      response = JSON.parse(response)
      if (!response.success || !response.products || !response.products.length) {
        search_status.removeClass('d-none')
        search_status.html('Sorry, no subscription products were found matching your search.')
      }
      response.products.forEach(product => {
        search_products_container_html += `
          <div class="col-sm-4 mb-4">
            <div class="card card-product">
              <div class="product-loader">
                <div class="spinner-border" role="status">
                  <span class="sr-only">Loading...</span>
                </div>
              </div>
              <div class="card-body">
                <div class="d-block relative text-darkergray">`;
                  if (product.on_sale) {
                    search_products_container_html += '<span class="card-product_badge badge badge-sale">On Sale</span>';
                  }
                  if (!product.is_in_stock || product.visibly_sold_out) {
                    search_products_container_html += '<span class="card-product_badge badge badge-sold-out">Sold Out</span>';
                  }
                  search_products_container_html += `
                  <div class="card-product_price">
                    <span class="woocommerce-Price-amount amount sale-price"><span class="woocommerce-Price-currencySymbol">$</span> ${Utilities.percentageOff(product.price, 10)}</span>
                  </div>
                  <div class="card-product_image">
                    <img alt="${product.name}" src="${product.thumbnail_url}">
                  </div>
                  <h4 class="card-product_title">${product.name}</h4>
                </div>
                <div class="card-product_actions">
                  <div class="row justify-content-center w-100 m-0">`;
                  if (product.type == 'simple' && product.is_in_stock && !product.visibly_sold_out) {
                    search_products_container_html += `
                    <div class="col-6">
                      <button aria-label="${product.name}" class="btn text-darkergray mx-auto d-block add_to_subscription" data-product_id="${product.id}">Add To Subscription</button>
                    </div>`;
                  }
                  search_products_container_html += `
                    <div class="col-6">
                      <a aria-label="${product.name}" class="btn text-darkergray mx-auto d-block" href="${product.product_link}">View More</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>`
      })
      $subscription_search_products.find('.placeholder-item-wrapper').remove()
      $subscription_search_products.append(search_products_container_html)
      WoocommerceSubscriptionSearch.continuouslyScroll = ((WoocommerceSubscriptionSearch.currentPage * WoocommerceSubscriptionSearch.perPage) < response.total)
      WoocommerceSubscriptionSearch.currentPage++
      WoocommerceSubscriptionSearch.onAddToSubscriptionClick()
    }).catch(() => {
      search_status.removeClass('d-none')
      search_status.html('Error. Please try again.')
    })
  },
  performNewSearch: function (newSearch) {
    this.continuouslyScroll = true
    this.currentPage = 1
    $('#subscription-search-products').scrollTop(0)
    this.performSearch(newSearch)
  },
  onAddToSubscriptionClick: function () {
    const $add_to_subscription = $('.add_to_subscription')
    $add_to_subscription.off('click')
    $add_to_subscription.on('click', function () {
      const $subscriptionAddProductModel = $('#subscriptionAddProductModel')
      const $self = $(this)
      const subscription_id = $subscriptionAddProductModel.data('subscription_id')
      const product_id = $self.data('product_id')
      var fd = new FormData()
      fd.append('product_id', product_id)
      fd.append('subscription_id', subscription_id)
      fd.append('action', 'add-product-to-subscription')
      fd.append('nonce', ThemeSettings.radical_nonce)
      $self.parent().parent().parent().parent().parent().find('.product-loader').addClass('d-flex')
      $.ajax({
        url: ThemeSettings.admin_ajax_url,
        data: fd,
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        success: function () {
          $self.parent().parent().parent().parent().parent().find('.product-loader').removeClass('d-flex')
          location.reload()
        },
        error: function () {
          alert('Error!! Please try again.')
          location.reload()
        },
      })
    })
  },
}
