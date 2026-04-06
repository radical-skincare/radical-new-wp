
const Global = {
  onLoad: function() {
    this.scrollToTop()
    this.beforeAfterSlider()
    this.productQuickView()
    this.expandAccordionWithSelectedText()
    this.ajaxAddToCart()
    this.cartPageFunctionality()
  },
  scrollToTop: function() {
    const $scroll_to_top = $('#scroll-to-top')
    if (!$scroll_to_top.length) return
    const $window = $(window)
    $window.on('scroll', function () {
      if (window.scrollY > $window.height()) {
        $scroll_to_top.fadeIn().addClass('visible')
      } else {
        $scroll_to_top.fadeOut().removeClass('visible')
      }
    })
  },
  beforeAfterSlider: function() {
    $('#before-after-slider').on('input change', (e) => {
      const sliderPos = e.target.value;
      $('.foreground-img').css('width', `${sliderPos}%`)
      $('.slider-button').css('left', `calc(${sliderPos}% - 18px)`)
    })
  },
  productQuickView: function() {
    const $product_quickview_modal = $('#product-quickview-modal')
    if (!$product_quickview_modal.length) return
    $product_quickview_modal.on('show.bs.modal', function (event) {
      const $action_btn = $(event.relatedTarget)
      $product_quickview_modal.find('#product-quickview-modal_name').html($action_btn.data('title'))
      let price = '';
      if ($action_btn.data('is_on_sale')) {
        price += '<strike>$' + $action_btn.data('regular_price') + '</strike> '
      }
      price += '$' + $action_btn.data('price');
      $product_quickview_modal.find('#product-quickview-modal_price').html(price)
      $product_quickview_modal.find('#product-quickview-modal_description').html($('#product_description_'+$action_btn.data('id')).html())
      $product_quickview_modal.find('.product-quickview-modal_thumbnail').attr('src', $action_btn.data('thumbnail'))
      $product_quickview_modal.find('#product-quickview-modal_action_view_more').attr('href', $action_btn.data('permalink'))
      const wcsatt_schemes = $action_btn.data('wcsatt_schemes')
      const is_variable = $action_btn.data('is_variable')
      const cannot_access_brand_partner_product = $action_btn.data('cannot-access-brand-partner-product')
      const disable_add_to_cart = $action_btn.data('disable_add_to_cart')
      if (cannot_access_brand_partner_product || disable_add_to_cart) {
        $product_quickview_modal.find('.add_to_cart_button').hide()
      } else {
        $product_quickview_modal.find('.add_to_cart_button').css('display', 'inline-block')
        const $modal_action_add_to_cart = $product_quickview_modal.find('#product-quickview-modal_action_add_to_cart')
        $modal_action_add_to_cart.removeAttr('disabled')
        $modal_action_add_to_cart.removeClass('d-none')
        const $modal_action_dropdown_quick_view = $product_quickview_modal.find('#product-quickview-modal_action_dropdown_quick_view')
        $modal_action_dropdown_quick_view.removeAttr('disabled')
        $modal_action_dropdown_quick_view.addClass('d-none')
        if (wcsatt_schemes && wcsatt_schemes !== '""' && wcsatt_schemes !== null) {
          $modal_action_add_to_cart.addClass('d-none')
          $modal_action_dropdown_quick_view.removeClass('d-none')
          const $dropdown_menu = $product_quickview_modal.find('.dropdown-menu')
          $dropdown_menu.find('.refill-action-option').remove()
          wcsatt_schemes.forEach(wcsatt_scheme => {
            const subscription_period_interval = wcsatt_scheme['subscription_period_interval']
            const subscription_period = wcsatt_scheme['subscription_period']
            // Format text: "Refill Every X Month(s) 10% Off"
            let intervalText = 'Every'
            if (subscription_period_interval != 1) {
              intervalText += ' ' + subscription_period_interval
            }
            const periodText = subscription_period_interval != 1 ? subscription_period + 's' : subscription_period
            const formattedText = 'Refill ' + intervalText + ' ' + periodText.charAt(0).toUpperCase() + periodText.slice(1) + ' 10% Off'
            $dropdown_menu.append(`
              <a class="dropdown-item refill-action-option text-capitalize" href="javascript:void(0)" subscription_period_interval="${subscription_period_interval}" subscription_period="${subscription_period}">
                ${formattedText}
              </a>`)
          })
        }
        const $modal_aciton = (wcsatt_schemes && wcsatt_schemes !== '""' && wcsatt_schemes !== null) ? $modal_action_dropdown_quick_view : $modal_action_add_to_cart
        $modal_aciton.attr('href', '?add-to-cart=' + $action_btn.data('id'))
        $modal_aciton.attr('data-product_id', $action_btn.data('id'))
        $modal_aciton.attr('data-product_sku', $action_btn.data('sku'))
        $modal_aciton.attr('aria-label', 'Add '+$action_btn.data('title') + ' to your cart')
        if (!$action_btn.data('is_purchasable') || $action_btn.parent().parent().parent().hasClass('outofstock')) {
          $modal_aciton.attr('href', 'javascript:void(0)')
          $modal_aciton.removeClass('ajax_add_to_cart')
        }
        if (is_variable == '1') {
          $modal_aciton.addClass('d-none')
        }
      }
    })
  },
  expandAccordionWithSelectedText: () => {
    $('.selected-accordion-link', '.accordion').closest('.collapse').collapse('show')
  },
  ajaxAddToCart: () => {
    $('.ajax_add_to_cart.add_to_cart_button', '.card-product').on('click', function () {
      const $product_loader = $(this).parent().parent().parent().parent().parent().find('.product-loader')
      $product_loader.addClass('d-flex')
      $('body').on('added_to_cart',function() {
        $product_loader.removeClass('d-flex')
      })
    })
  },
  cartPageFunctionality: () => {
    if (!$('body.template-cart').length) {
      return
    }
    const $btn_group_quantity = $('.btn-group_quantity')
    if (!$btn_group_quantity.length) {
      return
    }
    const $update_cart_btn = $('button[name="update_cart"]')
    $('.btn-quantity_minus', $btn_group_quantity).removeAttr('disabled')
    $('.btn-quantity_minus', $btn_group_quantity).on('click', (e) => {
      const $qty = $(e.currentTarget).parent().find('.qty')
      const currentVal = $qty.val()
      const min = $qty.attr('min') ? $qty.attr('min') : 0
      if (currentVal <= min) {
        return
      }
      $qty.val(parseInt(currentVal)-1)
      $qty.trigger('change')
      $update_cart_btn.removeAttr('disabled')
    })
    $('.btn-quantity_plus', $btn_group_quantity).on('click', (e) => {
      const $qty = $(e.currentTarget).parent().find('.qty')
      const currentVal = $qty.val()
      const max = $qty.attr('max') ? $qty.attr('max') : null
      if (max != null && currentVal >= max) {
        return
      }
      $qty.val(parseInt(currentVal) + 1)
      $qty.trigger('change')
      $update_cart_btn.removeAttr('disabled')
      $(e.currentTarget).parent().find('.btn-quantity_minus').removeAttr('disabled')
    })
  },
}
