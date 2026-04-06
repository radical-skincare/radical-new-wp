
const WoocommerceSubscription = {
  onLoad: function() {
    if ($('.wcsatt-add-to-subscription-options').length) {
      $('.wcsatt-add-to-subscription-options').on('DOMSubtreeModified', function() {
        WoocommerceSubscription.addProductToSubscription()
        WoocommerceSubscription.onSubscriptionQuickViewClick()
      })
    }
    if (!$('body.woocommerce-view-subscription').length) {
      return
    }
    $('.subscription-details [data-toggle="tooltip"]').tooltip()
    this.onSubscriptionRemoveProduct()
    this.onSkipProductClick()
    this.unSkipProductClick()
    this.onNextPaymentChange()
    this.onCancelSubscriptionClick()
    this.onSkipWholeOrder()
    this.onProductQuantityUpdate()
    this.onChangeQuantity()
    $('#productQuantityUpdated').on('hidden.bs.modal', this.onProductQuantityUpdatedHide)
    this.movePayNowOnTop()
    // this.formatEndDate()
    WoocommerceSubscriptionSearch.onLoad()
    this.onSkipWholeOrderBtnClick()
    this.onChangeFrequencyBtnClick()
    this.onChangeNextOrderDate()
  },
  onSkipWholeOrderBtnClick: function () {
    $('#skip_whole_order_btn').on('click', function (e) {
      const $self = $(e.target)
      $('#skipWholeOrder').modal('show')
      $('#skipWholeOrder_btn').data('subscription_id', $self.data('subscription_id'))

    })
  },
  addProductToSubscription: function () {
    const $addProductToSubscription = $('.add_product_to_subscription')
    if (!$addProductToSubscription.length) {
      return
    }
    $addProductToSubscription.off('click')
    $addProductToSubscription.on('click', function (e) {
      const $self = $(e.target)
      const productId = $('.wcsatt-add-to-subscription-wrapper').attr('data-product_id')
      const subscription_id = $self.attr('subscription_id')
      var fd = new FormData()
      fd.append('quantity', 1)
      fd.append('add-to-subscription-input', 'yes')
      fd.append('add-to-subscription', $self.attr('subscription_id'))
      fd.append('add-product-to-subscription', productId)
      fd.append(`convert_to_sub_${productId}`,  $('[name="refill_frequencies"]').val())
      fd.append(`wcsatt_nonce_${productId}`, $(`#wcsatt_nonce_${productId}`).val())
      fd.append('_wp_http_referer', 'radical/?wc-ajax=wcsatt_load_subscriptions_matching_product')
      $self.attr('disabled', 'disabled')
      $self.html('Adding...')
      $.ajax({
        url: document.URL,
        data: fd,
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        success: function () {
          window.location.replace(`${ThemeSettings.site_url}/account/view-subscription/${subscription_id}?new_product=${productId}&t=${Date.now()}`)
        },
        error: function () {
          alert('Error Please Try Again')
          $self.removeAttr('disabled')
          $self.html('Add to subscription')
        },
      })
    })
  },
  onSubscriptionRemoveProduct: function() {
    $('.subscription-product-remove').on('click', function() {
      const $this = $(this)
      const text = 'Are you sure you want remove this item from your subscription?'
      if (confirm(text)) {
        const href = $this.attr('data-href')
        window.location.href = href
      }
    })
  },
  onSkipProductClick: function () {
    $('.skip_shop_subscription_item_once').off('click', '**')
    $('.skip_shop_subscription_item_once').on('click', function () {
      const $current_button = $(this)
      const subscription_product_id = $current_button.attr('data-subscription-product-id')
      const $subscription_product = $(`#subscription-product-card_${subscription_product_id}`)
      const product_id = $current_button.attr('data-product_id')
      const product_name = $current_button.attr('data-product_name')
      const subscription_id = $current_button.attr('data-subscription_id')
      if ($current_button.hasClass('unskip_shop_subscription_item_once')) {
        return
      }
      if ($('.skip_shop_subscription_item_once').length <= 1) {
        $('#skipWholeOrder').modal('show')
        $('#skipWholeOrder_btn').data('subscription_id', subscription_id)
        return
      }
      $current_button.attr('disabled', 'disabled')
      $current_button.html('Loading...')
      const $skipProductInOrder_btn = $('#skipProductInOrder_btn')
      $skipProductInOrder_btn.data('product_id', product_id)
      $skipProductInOrder_btn.data('subscription_id', subscription_id)
      $('.skipProductInOrder_nameOfProductToSkip').html(`(<strong>${product_name}</strong>)`)
      $('#skipProductInOrder').modal('show')
      WoocommerceSubscription.onConfirmSkipProduct($current_button, $subscription_product)
      $('#skipProductInOrder').on('hidden.bs.modal', function () {
        if ($current_button.attr('disabled')) {
          $current_button.html('Skip Once')
          $current_button.removeAttr('disabled')
        }
        if ($('.skip_shop_subscription_item_once').length == 1) {
          $('.skip_shop_subscription_item_once').parent().find('.subscription-product-remove').hide()
        }
      })
    })
  },
  onConfirmSkipProduct: function($initiateButton, $subscription_product) {
    const $skipProductInOrder_btn = $('#skipProductInOrder_btn')
    $skipProductInOrder_btn.on('click', function () {
      $skipProductInOrder_btn.html('Loading')
      $skipProductInOrder_btn.attr('disabled', 'disabled')
      WoocommerceSubscription.initiateSkipProduct($skipProductInOrder_btn.data('subscription_id'), $skipProductInOrder_btn.data('product_id')).then((res) => {
        $initiateButton.html('Add It Back')
        $initiateButton.removeAttr('disabled')
        $initiateButton.removeClass('skip_shop_subscription_item_once')
        $initiateButton.addClass('unskip_shop_subscription_item_once')
        $subscription_product.addClass('subscription-product-card_is-skipped')
        WoocommerceSubscription.unSkipProductClick()
        WoocommerceSubscription.onSkipProductClick()
        $skipProductInOrder_btn.html('Yes, Skip')
        $skipProductInOrder_btn.removeAttr('disabled')
        $('#skipProductInOrder').modal('hide')
        $subscription_product.prepend('<div class="badge badge-skipped">Skipped</div>')
        $subscription_product.find('.product_quantity-wrap, .subscription-product-remove').hide()
        $subscription_product.find('.product_quantity-skipped').show()
        WoocommerceSubscription.prependSubscriptionUpdate(res.note)
      }).catch(() => {
        $skipProductInOrder_btn.html('Yes, Skip')
        $skipProductInOrder_btn.removeAttr('disabled')
        alert('Error Please Try Again')
        $('#skipProductInOrder').modal('hide')
      })
    })
  },
  initiateSkipProduct: function(subscription_id, product_id) {
    return new Promise((resolve, reject) => {
      const $skipProductInOrderNotification = $('#skipProductInOrderNotification')
      const initiateSkipProductObject = {
        product_id,
        subscription_id,
        send_email_notification: false,
        action: 'wc_skip_subscription_product_once',
        nonce: ThemeSettings.radical_nonce,
      }

      if ($skipProductInOrderNotification.length && $skipProductInOrderNotification.is(':checked')) {
        initiateSkipProductObject.send_email_notification = true
      }

      $.ajax({
        url: ThemeSettings.admin_ajax_url,
        data: initiateSkipProductObject,
        type : 'post',
        dataType : 'json',
        success: function (data) {
          if (data.success) {
            resolve(data)
            return
          }
          reject(data)
        },
        error: function (xhr) {
          reject(xhr)
        },
      })
    })
  },
  unSkipProductClick: function () {
    $('.unskip_shop_subscription_item_once').off('click', '**')
    $('.unskip_shop_subscription_item_once').on('click', function () {
      if (!confirm('Are you sure?')) {
        return
      }
      const $current_button = $(this)
      const subscription_product_id = $current_button.attr('data-subscription-product-id')
      const $subscription_product = $(`#subscription-product-card_${subscription_product_id}`)
      if ($current_button.hasClass('skip_shop_subscription_item_once')) {
        return
      }
      const product_id = $current_button.attr('data-product_id')
      const subscription_id = $current_button.attr('data-subscription_id')
      $current_button.attr('disabled', 'true')
      $current_button.html('Loading...')
      $.ajax({
        url: ThemeSettings.admin_ajax_url,
        data: {
          product_id,
          subscription_id,
          send_email_notification: $('#skipProductInOrderNotification').is(':checked'),
          action: 'wc_unskip_subscription_product_once',
          nonce: ThemeSettings.radical_nonce,
        },
        type : 'post',
        dataType : 'json',
        success: function (res) {
          if (res.success) {
            $current_button.html('Skip Once')
            $current_button.removeAttr('disabled')
            $current_button.removeClass('unskip_shop_subscription_item_once')
            $current_button.addClass('skip_shop_subscription_item_once')
            $subscription_product.removeClass('subscription-product-card_is-skipped')
            WoocommerceSubscription.onSkipProductClick()
            WoocommerceSubscription.prependSubscriptionUpdate(res.note)
            $subscription_product.find('.badge-skipped').remove()
            $subscription_product.find('.product_quantity-skipped').hide()
            $subscription_product.find('.product_quantity-wrap').show()
            WoocommerceSubscription.showAllNonSkipSubscriptionProducts()
          }
        },
      })
    })
  },
  onNextPaymentChange: function () {
    const $nextPaymentInput = $('#next_payment_input')
    if (!$nextPaymentInput.length) {
      return
    }
    $nextPaymentInput.on('change', function () {
      const $self = $(this)
      const subscription_id = $self.attr('data-subscription_id')
      const $next_payment_loader = $('#next_payment_loader')
      $next_payment_loader.removeClass('d-none')
      $self.attr('disabled', true)
      $.ajax({
        url: ThemeSettings.admin_ajax_url,
        data: {
          subscription_id,
          new_date: $self.val(),
          action: 'wc_update_subscription_next_payment',
          nonce: ThemeSettings.radical_nonce,
        },
        type : 'post',
        dataType : 'json',
        success: function (res) {
          if (res.success) {
            $next_payment_loader.addClass('d-none')
            $self.removeAttr('disabled')
            WoocommerceSubscription.prependSubscriptionUpdate(res.note)
          } else {
            console.error(res)
          }
        },
      })
    })
  },
  onSkipWholeOrder: function () {
    $('#skipWholeOrder_btn').on('click', function(){
      const $current_button = $(this)
      $current_button.attr('disabled', 'true')
      $current_button.html('Loading...')
      const $skipWholeOrderSendEmailNotification = $('#skipWholeOrderSendEmailNotification')
      const skipOrderObject = {
        subscription_id: $current_button.data('subscription_id'),
        send_email_notification: false,
        action: 'wc_skip_subscription_once',
        nonce: ThemeSettings.radical_nonce,
      }
      if ($skipWholeOrderSendEmailNotification.length && $skipWholeOrderSendEmailNotification.is(':checked')) {
        skipOrderObject.send_email_notification = true
      }
      $.ajax({
        url: ThemeSettings.admin_ajax_url,
        data: skipOrderObject,
        type : 'post',
        dataType : 'json',
        success: function (data) {
          if(data.success) {
            location.reload()
          } else {
            $current_button.html('Yes')
            $current_button.removeAttr('disabled')
            alert(data.msg)
          }
        },
      })
    })
  },
  onSubscriptionQuickViewClick: function () {
    const $subscriptionQuickView = $('.subscription_quick_view')
    $subscriptionQuickView.off('click')
    $subscriptionQuickView.on('click', function() {
      const $self = $(this)
      $('#subscriptionQuickViewModel-subscription-no').html('Subscription No' + $self.data('id'))
      $('#subscriptionQuickViewModel-payment-method').html($self.data('payment'))
      $('#subscriptionQuickViewModel-start-date').html($self.data('start_date'))
      $('#subscriptionQuickViewModel-last-order-date').html($self.data('last_order_date'))
      $('#subscriptionQuickViewModel-next-order-date').html($self.data('next_order_date'))
      $('#subscriptionQuickViewModel-billing-address-name').html($self.data('billing_name'))
      $('#subscriptionQuickViewModel-billing-address').html($self.data('billing_address'))
      $('#subscriptionQuickViewModel-billing-email').html($self.data('billing_email'))
      $('#subscriptionQuickViewModel-shipping-address-name').html($self.data('shipping_name'))
      $('#subscriptionQuickViewModel-shipping-address').html($self.data('shipping_address'))
      $('#subscriptionQuickViewModel-shipping-email').html($self.data('shipping_email'))
      $('#subscriptionQuickViewModel-view_more').attr('href', $self.data('view_more'))
      const $ofcBadge = $('.subscriptionQuickViewModel_order-for-customer');
      $ofcBadge.addClass('d-none')
      if ($self.data('order_for_customer')) {
        $ofcBadge.removeClass('d-none')
      }
      const $productsList = $('#subscriptionQuickViewModel_productsList')
      $productsList.empty()
      $self.data('items').forEach((item) => {
        const skipped_text = (item['is_skipped'] == true) ? '<span class="badge badge-skipped">Skipped</span>' : ''
        $productsList.append(`
          <div class="list-group-item">
            <div class="row align-items-center">
              <div class="col-sm-3">
                <img src="${item['image']}" class="d-block mx-auto" style="height: 5rem;"/>
              </div>
              <div class="col-sm-9">
                <h4 class="mb-3 d-inline">${item['name']}</h4>
                ${skipped_text}
                <div class="row align-items-center w-100">
                  <div class="col-auto">
                    <label class="m-0">Cost</label>
                    <div class="price">
                      <span class="woocommerce-Price-currencySymbol">$</span>
                      ${item['item_subtotal']}
                    </div>
                  </div>
                  <div class="col-auto">
                    <label class="m-0">Quantity</label><br/>
                    ${item['qty']}
                  </div>
                  <div class="col">
                    <label class="m-0">Total</label>
                    <div class="price">
                      <span class="woocommerce-Price-currencySymbol">$</span>
                      ${item['line_total']}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>`)
      });
    })
  },
  onCancelSubscriptionClick: function () {
    $('#btn-subscription-confirm-cancel').on('click', function () {
      if ($(this).hasClass('continue-to-cancel')) {
        $('#subscriptionCancelReason_feedback').hide()
        $('#subscriptionCancelReason_confirm').show()
        $(this).text('Confirm Cancellation')
        $(this).removeClass('continue-to-cancel')
      } else {
        if (confirm('Are you sure? This action is irreversible.')) {
          window.location.href = $('#btn-show-subscription-cancel-modal').data('cancel_link')
        }
      }
    })
    $('#subscriptionCancelReason').on('hide.bs.modal', function (e) {
      console.log(e)
      const $confirm_cancel_btn = $('#btn-subscription-confirm-cancel')
      if (!$confirm_cancel_btn.hasClass('continue-to-cancel')) {
        $('#subscriptionCancelReason_feedback').show()
        $('#subscriptionCancelReason_confirm').hide()
        $confirm_cancel_btn.text('Continue to Cancel')
        $confirm_cancel_btn.addClass('continue-to-cancel')
      }
    })
  },
  onProductQuantityUpdate: function () {
    let timeout
    $('.subscription-details-product-quantity').on('change', function () {
      const $self = $(this)
      if (timeout) {
        clearTimeout(timeout)
      }
      timeout = setTimeout(function() {
        $('#productQuantityUpdated_confirm-btn').attr('data-product_id', $self.data('product_id'))
        $('#productQuantityUpdated_nameOfProduct').html($self.data('product_name'))
        $('#productQuantityUpdated_quantityOfProduct').html($self.val())
        $('#productQuantityUpdated').modal('show')
        WoocommerceSubscription.onProductQuantityUpdateConfirmClick()
      }, 1000)
    })
  },
  onProductQuantityUpdateConfirmClick: function () {
    $('#productQuantityUpdated_confirm-btn').off('click')
    $('#productQuantityUpdated_confirm-btn').on('click', () => {
      const $self = $('#productQuantityUpdated_confirm-btn')
      $self.addClass('updating')
      const $quantityInput = $('.subscription-details-product-quantity.quantity-for_' + $self.attr('data-product_id'))
      $quantityInput.parent().parent().find('button, input').attr('disabled', true).addClass('loading')
      $('#productQuantityUpdated').modal('hide')
      WoocommerceSubscription.initiateProductQuantityUpdate($quantityInput.data('subscription_id'), $quantityInput.data('product_id'), $quantityInput.val()).then((res) => {
        const $totals_table = $('#totals_table')
        const $value_for_total_savings = $('.value-for-total_savings', $totals_table)
        if ($value_for_total_savings.length) {
          $value_for_total_savings.html(res.savings)
        }
        for (const property in res.totals) {
          $('.value-for-' + property, $totals_table).html(res.totals[property]['value'])
        }
        $('#total-for_'+$self.attr('data-product_id')).html(res.item_total)
        WoocommerceSubscription.prependSubscriptionUpdate(res.note)
      }).catch((e) => {
        console.log(e)
        alert('Unable to update quantity')
      }).finally(() => {
        $quantityInput.attr('data-old_value', $quantityInput.val())
        $quantityInput.parent().parent().find('button, input').removeAttr('disabled').removeClass('loading')
        if (parseInt($quantityInput.val()) === 1) {
          $quantityInput.parent().parent().find('button.btn-quantity_minus').attr('disabled', true)
        }
      })
    })
  },
  initiateProductQuantityUpdate: function (subscriptionId, productId, quantity) {
    return new Promise((resolve, reject) => {
      const $productQuantityUpdatedNotification = $('#productQuantityUpdatedNotification')
      const initiateProductQuantityUpdateObject = {
        product_id: productId,
        subscription_id: subscriptionId,
        quantity: quantity,
        send_email_notification: false,
        action: 'wc_update_subscription_product_quantity',
        nonce: ThemeSettings.radical_nonce,
      }
      if ($productQuantityUpdatedNotification.length && $productQuantityUpdatedNotification.is(':checked')) {
        initiateProductQuantityUpdateObject.send_email_notification = true
      }
      $.ajax({
        url: ThemeSettings.admin_ajax_url,
        data: initiateProductQuantityUpdateObject,
        type : 'post',
        dataType : 'json',
        success: function (data) {
          if (!data.success) {
            reject(data)
            return
          }
          resolve(data)
        },
        error: function (xhr) {
          reject(xhr)
          return
        },
      })
    })
  },
  onChangeQuantity: function() {
    $('.btn-group_quantity').each(function() {
      const $this = $(this)
      const $quantity = $this.find('input[type="number"]')
      const $btn_quantity_minus = $this.find('.btn-quantity_minus')
      $btn_quantity_minus.on('click', function() {
        const val = parseInt($quantity.val())
        if (val <= 1) {
          return
        }
        const new_val = val - 1
        if (new_val <= 1) {
          $btn_quantity_minus.prop('disabled', true)
        }
        $quantity.val(new_val).trigger('change')
      })
      $this.find('.btn-quantity_plus').on('click', function() {
        const val = parseInt($quantity.val())
        if (val >= 1000) {
          return
        }
        $quantity.val(val + 1).trigger('change')
        $btn_quantity_minus.removeAttr('disabled').prop('disabled', false)
      })
    })
  },
  onProductQuantityUpdatedHide: function () {
    const $self = $('#productQuantityUpdated_confirm-btn')
    if ($self.hasClass('updating')) {
      $self.removeClass('updating')
      return
    }
    const $quantityInput = $('.subscription-details-product-quantity.quantity-for_' + $self.attr('data-product_id'))
    $quantityInput.val($quantityInput.attr('data-old_value'))
  },
  movePayNowOnTop: function () {
    const $payButton = $('.woocommerce-orders-table__cell-order-actions .pay', '#subscriptionDetailsAccordion')
    if (!$payButton.length) {
      return
    }
    const payLink = $payButton.attr('href')
    $('#subscription_action').append(`<a class="btn btn-darkergray ml-3" href="${payLink}">Pay Now</a>`)
  },
  /*
  formatEndDate: function () {
    const $subEndDateFormatted = $('#sub_end_date_formatted')
    if (!$subEndDateFormatted.length) {
      return
    }
    $subEndDateFormatted.html(Utilities.daysUntil($subEndDateFormatted.text()) + ' days')
  },
  */
  prependSubscriptionUpdate: function(text = '') {
    if ($('.no-subscription-updates').length) {
      $('#subscription-updates').empty()
    }
    $('#subscription-updates').prepend(`<div class="list-group-item">${text}</div>`)
  },
  showAllNonSkipSubscriptionProducts: function() {
    $('.subscription-product-card:not(.subscription-product-card_is-skipped) .subscription-product-remove').each(function() {
      $(this).show()
    })
  },
  onChangeFrequencyBtnClick: function() {
    $('[name="subscriptionChangeFrequencyModel-frequency"]').on('change', function() {
      $('#subscriptionChangeFrequencyModel_confirm-btn').prop('disabled', false).removeAttr('disabled')
      const value = parseInt($(this).val())
      const current_date = new Date()
      current_date.setMonth(current_date.getMonth() + value)
      $('#subscription-change-frequency-double-confirm-alert').show()
      $('#subscription-change-frequency-double-confirm-next-shipment-date').html(current_date.toDateString())
    })
    $('#subscriptionChangeFrequencyForm').on('submit', function(e) {
      e.preventDefault()
      const $current_btn = $('#subscriptionChangeFrequencyModel_confirm-btn')
      const $subscriptionChangeFrequencyModel = $('#subscriptionChangeFrequencyModel')
      const newSubscriptionFrequency = $('[name="subscriptionChangeFrequencyModel-frequency"]:checked').val()
      const subscriptionID = $subscriptionChangeFrequencyModel.data('subscription_id')
      $current_btn.attr('disabled', 'disabled')
      $current_btn.html('Loading')
      const new_url = Utilities.updateOrAppendQueryParameter(window.location.href, 'changed-frequency', newSubscriptionFrequency)
      const $skipChangeFrequencySendEmailNotification = $('#skipChangeFrequencySendEmailNotification')
      const updateSubscriptionFrequencyObject = {
        new_frequency: newSubscriptionFrequency,
        subscription_id: subscriptionID,
        skip_change_frequency_send_email_notif: false,
        action: 'radical_wc_update_subscription_frequency',
        nonce: ThemeSettings.radical_nonce,
      }
      if ($skipChangeFrequencySendEmailNotification.length && $skipChangeFrequencySendEmailNotification.is(':checked')) {
        updateSubscriptionFrequencyObject.skip_change_frequency_send_email_notif = true
      }

      $.ajax({
        url: ThemeSettings.admin_ajax_url,
        data: updateSubscriptionFrequencyObject,
        type : 'post',
        dataType : 'json',
        success: function (data) {
          if (!data.success) {
            alert('Unable to update frequency. Please Try Again')
            window.location.href = new_url
            return
          }
          window.location.href = new_url
        },
        error: function () {
          alert('Unable to update frequency. Please Try Again')
          window.location.href = new_url
        },
      })
    })
  },
  onChangeNextOrderDate: function() {
    const $input_date = $('#subChangeNextOrderDateModel [name="input_next_order_date"]')
    if (!$input_date.length) {
      return
    }
    const original_val = $input_date.val()
    const min = $input_date.attr('min')
    const max = $input_date.attr('max')
    const $submit = $('#subChangeNextOrderDateModel_confirm-btn')
    $input_date.on('change', function() {
      const new_val = $(this).val()
      if (original_val === new_val) {
        $submit.prop('disabled', true)
        return
      }
      $submit.prop('disabled', false).removeAttr('disabled')
      if (new_val < min) {
        $(this).val(min)
        return
      }
      if (new_val > max) {
        $(this).val(max)
        return
      }
    })
    const $form = $('#subscriptionChangeNextOrderDateForm')
    $form.off('submit', WoocommerceSubscription.onChangeNextOrderDateFormSubmit)
    $form.on('submit', WoocommerceSubscription.onChangeNextOrderDateFormSubmit)
  },
  onChangeNextOrderDateFormSubmit: function(e) {
    e.preventDefault()
    if (!confirm('Are you sure?')) {
      return
    }
    const $confirmBtn = $('#subChangeNextOrderDateModel_confirm-btn')
    $confirmBtn.attr('disabled', true)
    $confirmBtn.html('Loading')
    const $input_date = $('#subChangeNextOrderDateModel [name="input_next_order_date"]')
    $.post(ThemeSettings.admin_ajax_url, {
      action: 'radical_wc_update_subscription_new_order_date',
      next_order_date: $input_date.val(),
      subscription_id: $confirmBtn.data('subscription_id'),
      nonce: ThemeSettings.radical_nonce,
    }, function(data) {
      $confirmBtn.removeAttr('disabled')
      $confirmBtn.html('Confirm')
      data = JSON.parse(data)
      if (data.error) {
        alert(data.error)
        return
      }
      if (!data.success) {
        alert('Unable to update next order date, please try again.')
        return
      }
      location.reload()
    }).catch(() => {
      $confirmBtn.removeAttr('disabled')
      alert('Error, unable to update subscription.')
    })
  },
}
