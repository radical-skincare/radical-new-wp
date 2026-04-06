
const Favorites = {
  onLoad: function() {
    if (!$('#favoritesModal').length) {
      return
    }
    this.onShow()
  },
  onShow: function() {
    $('#favoritesModal').on('shown.bs.modal', function () {
      Favorites.loadFavoritesProducts()
    })
  },
  loadFavoritesProducts: function() {
    /* global ThemeSettings */
    const $favorite_products_container = $('.favorites-products-container')
    $favorite_products_container.html('')
    const $favorite_status = $('#favorites_status')
    $favorite_status.html('')
    $favorite_status.addClass('d-none')
    let favorite_products_container_html = ''
    for (let i = 0; i < 6; i++) {
      favorite_products_container_html += '<div class="col-sm-6 placeholder-item-wrapper"><div class="placeholder-item"></div></div>'
    }
    $favorite_products_container.append(favorite_products_container_html)
    $.post(ThemeSettings.admin_ajax_url, {action: 'favorite_products', nonce: ThemeSettings.radical_nonce}, function(response) {
      favorite_products_container_html = ''
      if (!response.success || !response.products || !response.products.length) {
        $favorite_status.removeClass('d-none')
        $favorite_status.html('You have not saved any favorite products.')
      }
      response.products.forEach(product => {
        favorite_products_container_html += `
          <div class="col-sm-6">
            <a class="product-container" href="${product.product_link}">
              <img src="${product.thumbnail_url}" alt="${product.name}" class="w-100"/>
              <p class="text-center">${product.name}</p>
            </a>
          </div>`
      })
      $favorite_products_container.find('.placeholder-item-wrapper').remove()
      $favorite_products_container.append(favorite_products_container_html)
    }).catch(() => {
      $favorite_status.removeClass('d-none')
      $favorite_status.html('Error. Please try again.')
    })
  },
}
