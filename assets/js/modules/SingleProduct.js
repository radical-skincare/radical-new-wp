
const Product = {
  onLoad: function() {
    if (!$('.single-product').length) {
      return
    }
    this.load()
  },
  load: function() {
    $('[data-toggle="tooltip"]').tooltip()
    this.onChangeQuantity()
    this.carouselFuncinality()
    this.onScheduleRefill()
    this.onClickPlay()
    this.onModalDismiss()
    this.loadVariations()
    this.makeRattingsStarVisible()
    ProductReviews.onLoad()
    Product.descriptionMore()
    SingleProductFavorites.onLoad()
    // window.addEventListener('scroll', () =>{
    //   Product.stickyAddToCart()
    // });
  },
  descriptionMore: function () {
    const $short_description = $('.woocommerce-product-details__short-description')
    if (!$short_description.length) {
      return
    }
    $('#loadMore').on('click', function () {
      const $this = $(this);
      const minimumHeight = 85;
      const currentHeight = $short_description.innerHeight();
      const autoHeight = $short_description.css('height', 'auto').innerHeight();
      $short_description.css('height', currentHeight).animate({
        height: (($short_description.hasClass('expanded')) ? minimumHeight : (autoHeight + 30)),
      })
      if ($short_description.hasClass('expanded')) {
        $short_description.removeClass('expanded')
        $this.html('Show More <i class="fa fa-angle-down"></i>')
      } else {
        $short_description.addClass('expanded')
        $this.html('Show Less <i class="fa fa-angle-up"></i> ')
      }
    })
  },
  makeRattingsStarVisible: function () {
    setInterval(function(){
      $('.star-rating').css('visibility', 'visible')
    }, 1)
  },
  loadVariations: function() {
    if (!$('form.variations_form .variations').length) {
      return
    }
    CustomizeCollection.onLoad()
  },
  carouselFuncinality: function() {
    if ($('.product-gallery-slider .vertical-slider .item').length >= 4) {
      $('.vertical-slider').slick({
        // lazyLoad: 'ondemand',
        vertical: true,
        verticalSwiping: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        centerMode: false,
        infinite: true,
        arrows: true,
        dots: false,
        prevArrow: $('.top-slider-action'),
        nextArrow: $('.bottom-slider-action'),
      })
      $('.horizontal-slider').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        centerMode: false,
        infinite: true,
        arrows: true,
        dots: false,
        prevArrow: $('.left-slider-action'),
        nextArrow: $('.right-slider-action'),
      })
    }
    $('.product-gallery-slider .item img').on('click', function() {
      $('.product-gallery-slider .item img').removeClass('selected')
      $(this).addClass('selected')
      $('#product_main-img').attr('src', $(this).attr('src'))
    })
  },
  onChangeQuantity: function() {
    const $quantity = $('.btn-group_quantity input[type="number"]')
    $('.btn-quantity_minus').on('click', function() {
      const val = parseInt($quantity.val())
      if (val <= 1) {
        return
      }
      const new_val = val - 1
      if (new_val <= 1) {
        $('.btn-quantity_minus').prop('disabled', true)
      }
      $quantity.val(new_val)
    })
    $('.btn-quantity_plus').on('click', function() {
      const val = parseInt($quantity.val())
      if (val >= 1000) {
        return
      }
      $quantity.val(val + 1)
      $('.btn-quantity_minus').removeAttr('disabled').prop('disabled', false)
    })
  },
  onScheduleRefill: function() {
    if (!$('.woo-sub-vars-radio-wrap').length) {
      return
    }
    const $self = this
    const $schedule_opiton = $('.woo-sub-vars-radio-wrap input[type="radio"]')
    const original_bp_price = parseInt($('#bp-price').text())
    $schedule_opiton.on('change', (e) => {
      $('#refill_frequencies').addClass('d-none')
      if ($(e.currentTarget).val() === 'one-time-purchase') {
        $self.onOneTimeRefill()
        $('#bp-price').text(original_bp_price)
      } else {
        $self.onMultipleRefill()
        $('#bp-price').text((original_bp_price - (original_bp_price * 0.10)).toFixed(2))
      }
    })
  },
  onOneTimeRefill: function() {
    $('.wcsatt-options-wrapper .one-time-option input').click()
  },
  onMultipleRefill: function() {
    const $refill_frequencies = $('#refill_frequencies')
    if ($refill_frequencies.hasClass('d-none')) {
      $refill_frequencies.removeClass('d-none')
      $refill_frequencies.on('change', function(e) {
        $('.wcsatt-options-wrapper input[value="'+($(e.currentTarget).val())+'"]').click()
      })
      $('.wcsatt-options-wrapper input[value="'+($('option ', $refill_frequencies).val())+'"]').click()
    } else {
      $refill_frequencies.addClass('d-none')
      $refill_frequencies.off('change')
    }
  },
  onClickPlay: function() {
    $('.inner-wrapper-watch-video-btn').on('click', function() {
      const iframe_src = $(this).attr('data-iframe-src')
      $('#productVideoModal').find('.embed-responsive-item').attr('src', 'https://www.youtube.com/embed/' + iframe_src + '?rel=0&amp;autoplay=1')
      $('#productVideoModal').modal('show')
    })
  },
  onModalDismiss: function() {
    // on modal dismiss pause video
    $('#productVideoModal').on('hide.bs.modal', function() {
      $('#productVideoModal').find('.embed-responsive-item').attr('src', '')
    })
    $('#productVideoModal').on('hidden.bs.modal', function() {
      $('#productVideoModal').find('.embed-responsive-item').attr('src', '')
    })
  },
  stickyAddToCart: function() {
    const addToCartButton = document.querySelector('.single_add_to_cart_button');
    const quantityWrap = document.querySelector('.product_quantity-wrap');
    if (window.innerWidth >= 768) return; // Run only on small screens
    const buttonRect = addToCartButton.getBoundingClientRect();
    const quantityRect = quantityWrap.getBoundingClientRect();
    // Check if user has scrolled past the Add to Cart button
    const hasPassedButton = buttonRect.top < 83;
    // Check if quantity section is visible
    const isQuantityVisible = quantityRect.top >= 0 && quantityRect.bottom <= window.innerHeight;
    if (isQuantityVisible) {
      addToCartButton.style.position = 'fixed';
      addToCartButton.style.bottom = '0';
    } else if (hasPassedButton) {
      addToCartButton.style.position = 'fixed';
      addToCartButton.style.bottom = '0';
    }
  },
}

const ProductReviews = {
  $form: null,
  onLoad: function() {
    this.loadReviews()
    this.loadForm()
  },
  loadReviews: function() {
    if (!$('#comments').length) {
      return
    }
    const totalComments = $('#comments .commentlist .single-comment').length
    $('#comments .commentlist').slick({
      infinite: true,
      slidesToShow: (totalComments > 3 ? 3 : 2),
      slidesToScroll: 1,
      centerMode: true,
      arrows: true,
      dots: false,
      variableWidth: true,
      prevArrow: $('.commentList-wrap_slider-action_left'),
      nextArrow: $('.commentList-wrap_slider-action_right'),
    })
  },
  loadForm: function() {
    if (!$('#review_form').length) {
      return
    }
    this.$form = $('.single-product form#commentform')
    // this.$form.find('input#submit').off().unbind()
    this.$form.find('.comment-form-rating').append('<small class="comment-form-error text-danger" style="display: none;">Your rating is required.</small>');
    this.$form.find('.comment-form-comment').append('<small class="comment-form-error text-danger" style="display: none;">Your review is required.</small>');
    this.$form.find('.comment-form-author').append('<small class="comment-form-error text-danger" style="display: none;">Your full name is required.</small>');
    this.$form.find('.comment-form-email').append('<small class="comment-form-error text-danger" style="display: none;">Your email address is required.</small>');
    this.onSubmitReview()
  },
  onSubmitReview: function() {
    const self = this
    this.$form.on('submit', function(e) {
      if (self.$form.find('textarea#comment').val() === '') {
        self.$form.find('.comment-form-comment .comment-form-error').show()
        e.preventDefault()
      } else {
        self.$form.find('.comment-form-comment .comment-form-error').hide()
      }
      if (self.$form.find('input#author').length && self.$form.find('input#author').val() === '') {
        self.$form.find('.comment-form-author .comment-form-error').show()
        e.preventDefault()
      } else {
        self.$form.find('.comment-form-author .comment-form-error').hide()
      }
      if (self.$form.find('input#email').length && (self.$form.find('input#email').val() === '' || self.$form.find('input#email').val().indexOf('@') === -1)) {
        self.$form.find('.comment-form-email .comment-form-error').show()
        e.preventDefault()
      } else {
        self.$form.find('.comment-form-email .comment-form-error').hide()
      }
    });
  },
}

const CustomizeCollection = {
  variations: [
    {
      'name' : 'Hydrating Cleanser',
      'img' : 'https://radicalskincare.com/wp-content/uploads/2018/03/HydratingCleanser.jpg',
    },
    {
      'name' : 'Age-Defying Exfoliating Pads',
      'img' : 'https://radicalskincare.com/wp-content/uploads/2022/08/age-defying-exfoliating-pads.jpg',
    },
    {
      'name' : 'Youth Infusion Serum',
      'img' : 'https://radicalskincare.com/wp-content/uploads/2018/03/youth-infusion-serum.jpg',
    },
    {
      'name' : 'Advanced Peptide Antioxidant Serum',
      'img' : 'https://radicalskincare.com/wp-content/uploads/2018/03/advanced-peptide-antioxidant-serum.jpg',
    },
    {
      'name' : 'Anti-Aging Restorative Moisture',
      'img' : 'https://radicalskincare.com/wp-content/uploads/2019/10/RestorativeMoisture-1024x951.jpg',
    },
    {
      'name' : 'Extreme Repair',
      'img' : 'https://radicalskincare.com/wp-content/uploads/2018/03/ExtremeRepair-1.jpg',
    },
  ],
  onLoad: function() {
    if (!$('#my-customized-collection').length) {
      return
    }
    this.setupModalBody()
    this.onModalInputChange()
    $('#customizeCollectionModal').on('hidden.bs.modal', function () {
      $('#my-customized-collection').show()
    })
    this.essentialsCollectionContent()
  },
  setupModalBody: function() {
    let html = '<p>Customize your Collection to suit your preferences!</p>'
    let step = 1
    $('form.variations_form .variations select').each( function() {
      const step_id = $(this).attr('id')
      const select_id = `customize_modal_${step_id}`
      html += `
        <div class="step">
          <div class="mb-2">Step ${step}: ${step === 3 || step === 4 ? 'Choose One' : ''}</div>
          <div class="row">`
            let i = 0
            $(this).find('option').each( function() {
              const value = $(this).attr('value')
              if (value !== '') {
                const selected = $(this).is(':selected')
                let found_variation = false
                CustomizeCollection.variations.forEach( function(variation) {
                  if (variation.name === value) {
                    found_variation = variation
                    return false
                  }
                })
                html += `
                  <div class="relative col-lg-6 mb-3 mb-lg-0">
                    <label for="${select_id}_${i}" class="${selected ? 'selected' : ''}">
                      <div class="circle">
                        <i class="fa fa-check" aria-hidden="true"></i>
                        <span class="sr-only">Selected</span>
                      </div>
                      <div class="row">
                        <div class="col-4">
                          <img src="${found_variation.img}" alt="${value}" class="w-100"/>
                        </div>
                        <div class="step_item-details col-8 pl-0">
                          <div>
                            ${value}`
                            if (value === 'Advanced Peptide Antioxidant Serum') {
                              html += ' <br>(+$15)'
                            }
                            html += `
                            <input type="radio" id="${select_id}_${i}" name="${select_id}" value="${value}" ${selected ? 'checked="checked"' : ''} step-id="${step_id}"/>`
                            html += `
                          </div>
                        </div>
                      </div>
                    </label>
                  </div>`
                i++
              }
            })
            html += `
          </div>
        </div>`
      step++
    })
    $('#customizeCollectionModal .modal-body').html(html)
  },
  onModalInputChange: function() {
    $('#customizeCollectionModal input[type="radio"]').on('change', function() {
      const $parent = $(this).parent().parent().parent().parent()
      const checked = $(this).is(':checked')
      const name = $(this).attr('name')
      $(`[name="${name}"]`).each( function() {
        $(this).parent().parent().parent().parent().removeClass('selected')
      })
      if (checked) {
        $parent.addClass('selected')
        const step_id = $(this).attr('step-id')
        const value = $(this).val()
        $(`#${step_id}`).val(value)
        let found_variation = false
        CustomizeCollection.variations.forEach( function(variation) {
          if (variation.name === value) {
            found_variation = variation
            return false
          }
        })
        $(`.selected-variant_${step_id}`).html(`<img src="${found_variation.img}" alt="${found_variation.name}" class="selected-variant_img"/><div class="selected-variant_title">${found_variation.name}</div>`)
      }
      $('form.variations_form .variations tr:last-child select').trigger('change')
      setTimeout(() => {
        const $submit = $('form.variations_form button[type="submit"]')
        if (!$submit.hasClass('disabled') && $('#customizeCollectionModal label.selected').length === 4) {
          $('form.variations_form button[type="submit"]').show()
          $('button[data-target="#customizeCollectionModal"]').hide()
        }
      }, 500)
    })
  },
  essentialsCollectionContent: function() {
    setTimeout(() => {
      $('#essentials-collection-content a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        const $this = $(e.target)
        const product_name = $this.text().trim()
        const id = $this.attr('id')
        if (id.indexOf('3') !== -1) {
          $('.step-3_img').hide()
        } else if (id.indexOf('4') !== -1) {
          $('.step-4_img').hide()
        }
        $(`[alt="${product_name}"]`).show()
      })
    }, 500)
  },
}

const SingleProductFavorites = {
  onLoad: () => {
    SingleProductFavorites.addListeners()
  },
  addListeners: () => {
    $('#add_product_to_favorites').off('click').on('click', SingleProductFavorites.onAddToFavoritesClick)
    $('#remove_product_from_favorites').off('click').on('click', SingleProductFavorites.onRemoveProductFromFavorites)
  },
  onAddToFavoritesClick: (e) => {
    /* global ThemeSettings */
    const $action = $(e.currentTarget);
    const product_id = $action.data('product_id')
    $action.attr('disabled', true)
    $action.addClass('loading')
    $.ajax({
      type: 'POST',
      url: ThemeSettings.admin_ajax_url,
      data: {
        'action': 'add_product_to_favorites',
        product_id,
        nonce: ThemeSettings.radical_nonce,
      },
      success: (data) => {
        if (!data.success) {
          alert('Error!! Unable to add product to favorites.')
          return;
        }
        $action.find('.fa-heart-o').addClass('fa-times').removeClass('fa-heart-o')
        $action.attr('id', 'remove_product_from_favorites')
        $action.find('.favorites_action-text').html('Remove from Favorites')
        SingleProductFavorites.addListeners()
      },
      error: ()=>{
        alert('Error!! Unable to add product to Favorites.')
      },
      complete: () => {
        $action.removeClass('loading')
        $action.removeAttr('disabled')
      },
    })
  },
  onRemoveProductFromFavorites: (e) => {
    /* global ThemeSettings */
    const $action = $(e.currentTarget);
    const product_id = $action.data('product_id')
    $action.attr('disabled', true)
    $action.addClass('loading')
    $.ajax({
      type: 'POST',
      url: ThemeSettings.admin_ajax_url,
      data: {
        'action': 'remove_product_from_favorites',
        product_id,
        nonce: ThemeSettings.radical_nonce,
      },
      success: function(data) {
        if (!data.success) {
          alert('Error!! Unable to add product to favorites.')
          return;
        }
        $action.find('.fa-times').addClass('fa-heart-o').removeClass('fa-times')
        $action.attr('id', 'add_product_to_favorites')
        $action.find('.favorites_action-text').html('Add to Favorites')
        SingleProductFavorites.addListeners()
      },
      error: ()=>{
        alert('Error!! Unable to add product to Favorites.')
      },
      complete: () => {
        $action.removeClass('loading')
        $action.removeAttr('disabled')
      },
    })
  },
}
