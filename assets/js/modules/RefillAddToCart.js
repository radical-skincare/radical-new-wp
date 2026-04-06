const RefillAddToCart = {
  onLoad: function () {
    if (!$('.single-add-to-cart-button-dropdown')) {
      return
    }
    RefillAddToCart.init()
    $('#product-quickview-modal').on('show.bs.modal', function () {
      setTimeout(function() {
        RefillAddToCart.reset()
      }, 500)
    })
  },
  init: function () {
    $('.single-add-to-cart-button-dropdown .one-time-purchase-action').on(
      'click',
      function () {
        const closeste = $(this).parent().parent().find('.add_to_cart_button').first()
        const productId = closeste.attr('data-product_id')
        const sku = closeste.attr('data-product_sku')
        RefillAddToCart.ajaxAddToCart(
          productId,
          sku,
          0,
          closeste
        )
      }
    )
    $('.single-add-to-cart-button-dropdown .refill-action').on(
      'click',
      function (e) {
        e.stopPropagation()
        $(
          '.single-add-to-cart-button-dropdown .one-time-purchase-action, .single-add-to-cart-button-dropdown .refill-action'
        ).addClass('d-none')
        $(
          '.single-add-to-cart-button-dropdown .refill-action-option, .single-add-to-cart-button-dropdown .back-action'
        ).removeClass('d-none')
      }
    )
    $('.single-add-to-cart-button-dropdown .back-action').on(
      'click',
      function (e) {
        e.stopPropagation()
        $(
          '.single-add-to-cart-button-dropdown .one-time-purchase-action, .single-add-to-cart-button-dropdown .refill-action'
        ).removeClass('d-none')
        $(
          '.single-add-to-cart-button-dropdown .refill-action-option, .single-add-to-cart-button-dropdown .back-action'
        ).addClass('d-none')
      }
    )
    $('.single-add-to-cart-button-dropdown .refill-action-option').on(
      'click',
      function () {
        const closeste = $(this).parent().parent().find('.add_to_cart_button').first()
        const productId = closeste.attr('data-product_id')
        const sku = closeste.attr('data-product_sku')
        const subscription_period = $(this).attr('subscription_period')
        const subscription_period_interval = $(this).attr(
          'subscription_period_interval')
        RefillAddToCart.ajaxAddToCart(
          productId,
          sku,
          subscription_period_interval+ '_' + subscription_period,
          closeste
        )
      }
    )
  },
  reset: function () {
    $('.single-add-to-cart-button-dropdown .one-time-purchase-action').off('click')
    $('.single-add-to-cart-button-dropdown .refill-action').off('click')
    $('.single-add-to-cart-button-dropdown .back-action').off('click')
    $('.single-add-to-cart-button-dropdown .refill-action-option').off('click')
    RefillAddToCart.init()
  },
  ajaxAddToCart: function (productId, sku, refill = '', closest) {
    const refilId = 'convert_to_sub_'+productId
    const $card_product = closest.parents().closest('.card-product')
    let $product_loader = $card_product.find('.product-loader')
    if (!$card_product.length) {
      $product_loader = closest.parents().closest('.modal-content').find('.product-loader')
    }
    $product_loader.addClass('d-flex')
    const formData = {
      'product_id': productId,
      'product_sku': sku,
      'refill_frequencies': refill,
      'quantity': 1,
      'add-to-cart': productId,
      // 'action': 'cfw_add_to_cart', // ((cfw_add_to_cart) is for side cart) (for checkout wc (cfw_add_to_cart))
      'action': 'cfw_add_to_cart',
    }
    formData[refilId] = refill
    $.ajax({
      url: ThemeSettings.site_url+'/?wc-ajax=cfw_add_to_cart',
      type: 'POST',
      data: formData,
      dataType: 'json',
      encode: true,
    }).done(function (response) {
      $product_loader.removeClass('d-flex')
      $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $(closest)])
      $( document.body ).trigger( 'wc_cart_button_updated', [ $(closest) ] );
      $( document.body ).trigger( 'cart_page_refreshed' );
      $( document.body ).trigger( 'cart_totals_refreshed' );
      $( document.body ).trigger( 'wc_fragments_loaded' );
      $(closest).parent().addClass('added')

      // CheckoutWC: Refresh side cart if function exists
      if (typeof window.cfw_refresh_side_cart === 'function') {
        window.cfw_refresh_side_cart();
      }

      // WooCommerce: Force fragment refresh for compatibility
      $(document.body).trigger('wc_fragment_refresh');
    })
  },
}
