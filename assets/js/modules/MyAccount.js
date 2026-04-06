const MyAccount = {
  onLoad: function() {
    if (!$('body.template-account').length) {
      return
    }
    this.addStyles()
    MDFormInputFocus.onLoad()
    this.onToggleMenu()
    ResourcesTabs.onLoad()
    MyAccount.subscriptionOnPauseClick()
    this.initUserCoupons()
    PaymentMethods.onLoad()
    this.onReOrderItemClick();
    this.onRePurchaseOrder();
  },
  addStyles: function() {
    $('.my-account-wrapper .woocommerce #customer_login').addClass('row').removeClass('u-columns col2-set').css('display', 'flex')
    $('.my-account-wrapper .woocommerce #customer_login .u-column1').addClass('col-12 col-md-6').removeClass('u-column1 col-1')
    $('.my-account-wrapper .woocommerce #customer_login .u-column2').addClass('col-12 col-md-6').removeClass('u-column2 col-2')
    $('.my-account-wrapper .woocommerce-MyAccount-content').addClass('card p-3 my-3')
    $('.my-account-wrapper .woocommerce-form-login').addClass('card')
    $('.my-account-wrapper .woocommerce-form-register').addClass('card')
    $('.my-account-wrapper .woocommerce-form-row').addClass('mt-4 md-form')
    $('.my-account-wrapper .woocommerce-form-row input').addClass('form-control')
    $('.my-account-wrapper #customer_login .form-row button[type="submit"]').addClass('btn btn-pink mr-4')
  },
  onToggleMenu: function() {
    if ($(window).width() >= 768) {
      $('#account-toggle-menu').removeClass('collapsed')
    }
    $('#account-toggle-menu').on('click', function() {
      const $container = $(this).parent().parent()
      const window_width = $(window).width()
      if (!$(this).hasClass('collapsed')) {
        if (window_width >= 768) {
          $container.addClass('collapsed-left-menu')
        } else {
          $('.woocommerce-MyAccount-navigation ul').slideUp()
        }
        $(this).addClass('collapsed')
      } else {
        if (window_width >= 768) {
          $container.removeClass('collapsed-left-menu')
        } else {
          $('.woocommerce-MyAccount-navigation ul').slideDown()
        }
        $(this).removeClass('collapsed')
      }
    })
  },
  subscriptionOnPauseClick: function() {
    const $pause_button = $('#btn-on_pause')
    $pause_button.on('click', function () {
      $pause_button.attr('disabled', 'disabled')
      $.ajax({
        type: 'POST',
        url: ThemeSettings.admin_ajax_url,
        data: {
          'subscription_id': $pause_button.attr('subscription_id'),
          'action': 'shift_order_to_pause',
          'nonce': ThemeSettings.radical_nonce,
        },
      }).done(function () {
        $pause_button.removeAttr('disabled')
        window.location.reload()
      }).fail(function (err) {
        $pause_button.removeAttr('disabled')
        console.error(err)
      })
    })
  },
  initUserCoupons : function() {
    if (!$('.template-account .copy-coupon-code').length) {
      return
    }
    $('.template-account .copy-coupon-code').on('click', function() {
      const e = $(this);
      navigator.clipboard
      .writeText(e.attr('data-coupon'))
      .then(() => {
        e.attr('title', 'Code Copied')
        e.tooltip('show')
        setTimeout(
          function() {
            e.tooltip('dispose')
          }, 1000);
      })
      .catch(() => {
        e.attr('title', 'Error!! Please Try Again')
        e.tooltip('show')
        setTimeout(
          function() {
            e.tooltip('dispose')
          }, 1000);
      });
    })
  },
  onReOrderItemClick: function() {
    const $reorderOrderItem = $('.reorder_order_item')
    if (!$reorderOrderItem.length) {
      return
    }
    $reorderOrderItem.on('click', function() {
      const $self = $(this)
      $self.attr('disabled', 'disabled')
      $self.html('Processing')
      const product_id = $self.data('product_id')
      var fd = new FormData()
      fd.append('product_id', product_id)
      fd.append('product_sku', $self.data('product_sku'))
      fd.append('refill_frequencies', 0)
      fd.append('quantity', 1)
      fd.append('add-to-cart',  product_id)
      fd.append('action', 'cfw_add_to_cart')
      fd.append(`convert_to_sub_${product_id}`, 0)
      $.ajax({
        url: `${ThemeSettings.site_url}/?wc-ajax=cfw_add_to_cart`,
        data: fd,
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        success: function () {
          window.location.replace(`${ThemeSettings.site_url}/checkout`)
        },
        error: function () {
          alert('Error Please Try Again')
        },
      })
    })
  },
  onRePurchaseOrder: function() {
    $('#rePurchaseOrder').on('click', function () {
      const $self = $(this)
      $self.attr('disabled', 'disabled')
      $self.html('Loading...')
      $.ajax({
        url: ThemeSettings.admin_ajax_url,
        data: {
          order_id: $self.data('order_id'),
          action: 're_purchase_order',
          nonce: ThemeSettings.radical_nonce,
        },
        type : 'post',
        dataType : 'json',
        success: function (data) {
         if (!data.success) {
          $self.html('Purchase Again')
          $self.removeAttr('disabled')
          alert('Error!! Please try again')
          return
         }
         window.location.href = `${ThemeSettings.site_url}/checkout`
        },
        error: function (xhr) {
          $self.html('Purchase Again')
          $self.removeAttr('disabled')
          alert('Error!! Please try again')
          console.log(xhr)
        },
      })
    })
  },
};

/*
  * MDFormInputFocus
  * Kinda like a patch to focus on inputs that aren't being listened to by mdb.min.js
  */
const MDFormInputFocus = {
  onLoad: function() {
    if($('body.page-template-template-account.template-account')) {
      $('[for="password"]').insertBefore('#password')
      $('[for="reg_password"]').insertBefore('#reg_password')
    }
    if ($('.woocommerce-form-row .woocommerce-Input--password').length) {
      this.load()
    }
  },
  load: function() {
    $('.woocommerce-form-row .woocommerce-Input--password').each(function() {
      MDFormInputFocus.inputAction($(this))
      MDFormInputFocus.initListeners($(this))
    })
  },
  initListeners: function( $this ) {
    $this.on('focus', function() {
      MDFormInputFocus.inputAction($(this))
    })
    $this.on('input', function() {
      MDFormInputFocus.inputAction($(this))
    })
    $this.on('change', function() {
      MDFormInputFocus.inputAction($(this))
    })
    $this.on('focusout', function() {
      MDFormInputFocus.inputAction($(this))
    })
  },
  inputAction: function( $this ) {
    console.log('isfocused?', $this.is(':focus'))
    const $label = $this.parent().parent().find('label')
    if ($this.val() !== '' || $this.is(':focus')) {
      $label.addClass('active')
    } else {
      $label.removeClass('active')
    }
  },
}

const ResourcesTabs = {
  onLoad: function() {
    if (!$('.woocommerce-MyAccount-content.brand-partner-resources').length) {
      return
    }
    $('#brandPartnerResources a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      const active_tab = $(e.target).attr('href').replace('#', '').replace('-tab', '')
      ResourcesTabs.updateWindowLocation(active_tab)
    })
    this.loadTraining()
  },
  loadTraining: function() {
    this.onSubmitLessonQuiz()
    this.onGoToLesson()
  },
  onSubmitLessonQuiz: function() {
    $('.lesson-quiz').on('submit', function (e) {
      e.preventDefault()
      const $submit_btn = $(this).find('[type="submit"]')
      const lesson_title = $submit_btn.attr('lesson-title')
      const lesson_id = $submit_btn.attr('lesson-id')
      const $selected_answer = $('[name="lesson_' + lesson_id + '"]:checked')
      const is_correct = ($selected_answer.attr('is-correct') === 'true') ? true : false
      if (is_correct) {
        $('#lesson-incorrect-answer_' + lesson_id).hide()
        $('#lesson-completed_' + lesson_id).slideDown()
        $submit_btn.prop('disabled', true)
        $('[data-slide-to="' + lesson_id + '"]').attr('is-completed', '1')
        let data = {
          quiz_title: lesson_title,
          correct: is_correct,
          all_correct: true,
          action: 'bp_training_quiz',
          nonce: ThemeSettings.radical_nonce,
        }
        $('ol#training-videos-links a').map( (k, e) => {
          if (!$(e).attr('is-completed')) {
            data.all_correct = false
          }
        })
        $.ajax({
          type: 'POST',
          url: ThemeSettings.admin_ajax_url,
          data: data,
        }).done(function (res) {
          if (res === 0) {
            $submit_btn.removeAttr('disabled')
            return;
          }
          $('[name="lesson_' + lesson_id + '"]').prop('disabled', true)
          $('#training-videos-links a[data-slide-to="' + lesson_id + '"]').prepend('<i class="fa fa-check" aria-hidden="true"></i>')
        }).fail(function (err) {
          console.error(err)
          $submit_btn.prop('disabled', false).removeAttr('disabled')
        })
      } else {
        $('#lesson-incorrect-answer_' + lesson_id).slideDown()
      }
    })
  },
  onGoToLesson: function() {
    $('[data-target="#training-videos-carousel"]').on('click', function() {
      const slide_to = parseInt($(this).attr('data-slide-to')) + 1
      const $lesson_slide = $('#training-videos-carousel .carousel-item:nth-child(' + slide_to + ')')
      if (!$lesson_slide.hasClass('video-loaded')) {
        const youtube_video_id = $(this).attr('youtube-video-id')
        const video_html = '<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/' + youtube_video_id + '?rel=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
        $lesson_slide.find('.card-video .embed-responsive').html(video_html)
        $lesson_slide.addClass('video-loaded')
      }
    })
  },
  updateWindowLocation: function( active_tab ) {
    const newurl = location.protocol + '//' + location.host + location.pathname + '?tab=' + active_tab
    window.history.pushState({ path: newurl }, 'My Account', newurl)
  },
}

const PaymentMethods = {
  onLoad: function() {
    if (!$('#account-payment-methods').length) {
      return
    }
    this.onShowEditNameModal()
    this.onClickDelete()
  },
  onClickDelete: function() {
    $('#account-payment-methods .btn-action-delete').on('click', function(e) {
      e.preventDefault()
      if (confirm('Are you sure?')) {
        location.href = $(this).attr('href')
      }
    })
  },
  onShowEditNameModal: function() {
    $('#paymentMethodEditNameModal').on('show.bs.modal', function (e) {
      const $triggerEl = $(e.relatedTarget)
      const method_id = $triggerEl.attr('data-method-id')
      $('#paymentMethodEditNameModal').find('[name="cc_id"]').val(method_id)
      const method_name = $(`#method_${method_id}`).find('.method-name').html()
      if (method_name) {
        $('#paymentMethodEditNameModal').find('[name="cc_name"]').val(method_name)
        $('#paymentMethodEditNameModal').find('label[for="cc-name"]').addClass('focused')
      }
    })
    this.onUpdateName()
  },
  onUpdateName: function() {
    $('#form_payment-method-update-name').on('submit', function(e) {
      e.preventDefault()
      const $form = $(this)
      const $submit_btn = $form.find('[type="submit"]').prop('disabled', true)
      $.ajax({
        type: 'POST',
        url: ThemeSettings.admin_ajax_url,
        data: {
          user_id: ThemeSettings.current_user_id,
          cc_id: $('[name="cc_id"]').val(),
          cc_name: $('[name="cc_name"]').val(),
          action: 'payment_methods_change_name',
          nonce: ThemeSettings.radical_nonce,
        },
      }).done(function (res) {
        res = JSON.parse(res)
        if (res.success && res.payment_methods_names) {
          $('#account-payment-methods .card-method').each(function() {
            const id = $(this).attr('id').replace('method_', '')
            res.payment_methods_names.forEach((payment_method) => {
              console.log(payment_method.id, id)
              if (payment_method.id === id) {
                $(this).find('.method-name').remove()
                $(this).find('.card-method_details').prepend(`<h3 class="method-name mb-1">${payment_method.name}</h3>`)
              }
            })
          })
          $('#paymentMethodEditNameModal').modal('hide')
        } else {
          console.error('Something went wrong.', res)
        }
        $submit_btn.prop('disabled', false).removeAttr('disabled')
      }).fail(function (err) {
        console.error(err)
        $submit_btn.prop('disabled', false).removeAttr('disabled')
      })
    })
  },
}
