(function($) {
const Checkout  = {
  $currentScreenActivePrimaryBtn: null,
  // currentScreenActivePrimaryBtnOriginalText: '',
  onInit: function() {
    // if (Cookie.read('Brand_Partner_Address')) {
    //   Cookie.erase('Brand_Partner_Address');
    // }
    Checkout.showSummary()
    Checkout.init()
    setTimeout(function() {
      Checkout.stPatricksDayNotice()
      if ($('select#billing_country').length) {
        Checkout.checkCartItems( $('select#billing_country'), 'Billing' );
      } else if ($('select#shipping_country').length) {
        Checkout.checkCartItems( $('select#shipping_country'), 'Shipping' );
      }
    }, 1500)
    // Checkout.cartItemListener()
    Checkout.onCFW()
  },
  // cyberMondayNotice: function() {
  //   jQuery('#cfw-cart').before('<div class="checkoutwc-black-friday" style="background-color: #b21837; color: white; padding: 1rem; text-align: center; margin-bottom: 1rem;">Cyber Monday Sale 20% OFF everything! <br>Sale Ends 11.30 <br><small>Discount already automatically applied, <br>except to excluded items.</small></div>')
  // },
  stPatricksDayNotice: function() {
    jQuery('#cfw-cart').before('<div class="checkoutwc-black-friday" style="background-color: green; color: white; padding: 1rem; text-align: center; margin-bottom: 1rem;">15% OFF St Patrick\'s Day! <br><small>Discount already automatically applied, <br>except to excluded items and collections.</small></div>')
  },
  showSummary: function() {
    if ( $(window).width() <= 991 ) {
      $('#cfw-cart-details').addClass('active')
      $('#cfw-cart-details-collapse-wrap').show()
    }
  },
  init: function() {
    if ( $('#checkout').hasClass('wc-custom-init') ) {
      return
    }
    // console.log('wc-custom-init');
    Checkout.onCountryChange()
    Checkout.onClickGoGreen()
    $(".cfw-continue-to-payment-btn").on('click',function() {
      setTimeout(function() {
        $('#mailchimp_woocommerce_newsletter').attr('checked', 'checked').click()
      }, 1000)
    })
    setTimeout(function() {
      $('#mailchimp_woocommerce_newsletter').attr('checked', 'checked').click()
    }, 1000)
    // hide saved payment options
    // setTimeout(function() {
    //   Checkout.hideSavedPaymentOptions();
    // }, 250)
    $('#checkout').addClass('wc-custom-init')
  },
  cartItemListener: function() {
    setTimeout(function() {
      Checkout.storeWideSaleCarItems()
    }, 500)
    // Select the target node
    var target = document.querySelector('#cfw-cart-summary-content')
    // Create an observer instance
    var observer = new MutationObserver(function(mutations) {
      mutations.forEach(function(mutation) {
        if (mutation.type === 'childList') {
          console.log('Child list changed')
          // Do something here, such as updating the UI or triggering an event
          setTimeout(function() {
            Checkout.storeWideSaleCarItems()
          }, 500)
        }
      })
    })
    // Configuration of the observer:
    var config = { childList: true }
    // Pass in the target node, as well as the observer options
    observer.observe(target, config)
  },
  onCFW: function() {
    $(document.body).on( 'cfw_updated_checkout', function() {
      console.log('onCFW')
      Checkout.init()
      if ($('select#billing_country').length) {
        Checkout.checkCartItems( $('select#billing_country'), 'Billing' )
      } else if ($('select#shipping_country').length) {
        Checkout.checkCartItems( $('select#shipping_country'), 'Shipping' )
      }
    })
  },
  setCurrentScreenActivePrimaryBtn: function() {
    $('.cfw-panel.active a.cfw-primary-btn').each(function() {
      if ($(this).css('display') === 'block') {
        Checkout.$currentScreenActivePrimaryBtn = $(this)
        // Checkout.currentScreenActivePrimaryBtnOriginalText = $(this).text()
      }
    })
  },
  onCountryChange: function() {
    $('select#billing_country').on( 'change', function () {
      Checkout.checkCartItems( $(this), 'Billing' );
      Checkout.specificCountryNotice( $(this) );
    });
    $('select#shipping_country').on( 'change', function () {
      Checkout.checkCartItems( $(this), 'Shipping' );
      Checkout.specificCountryNotice( $(this) );
    });
  },
  checkCartItems: function( $country, type ) {
    // console.log('checkCartItems');
    Checkout.setCurrentScreenActivePrimaryBtn();
    let disable_next_step = false;
    let have_holiday_product = false;
    const country_value = $country.val();
    let invalid_products = []
    console.log({country_value})
    if ( country_value && country_value !== 'US') {
      $('.cfw-cart-item-title').each(function() {
        const this_text = $(this).text().toLowerCase().trim();
        if (this_text.indexOf('essentials collection plus free dual ended') !== -1) {
          have_holiday_product = true;
          invalid_products.push("Essentials Collection")
        }
        if (this_text.indexOf('fresh face collection featuring ice facial') !== -1) {
          have_holiday_product = true;
          invalid_products.push("Fresh Face Collection")
        }
        if (this_text.indexOf('ended beauty roller') !== -1) {
          have_holiday_product = true;
          invalid_products.push("Ended Beauty Roller")
        }
        if (this_text.indexOf('ice facial roller') !== -1) {
          have_holiday_product = true;
          invalid_products.push("Ice Facial Roller")
        }
        if (this_text.indexOf('re-usable eye repair patches') !== -1) {
          have_holiday_product = true;
          invalid_products.push("Re-Usable Eye Repair Patches")
        }
      })
    } else {
      invalid_products = []
    }
    if (invalid_products.length >= 1) {
      Checkout.$currentScreenActivePrimaryBtn.attr('disabled', true).text('Either remove ('+(invalid_products.join(','))+') from your cart or change your "' + type + ' Country. Because these products are only available in US."').css('background-color', 'gray').css('cursor', 'default');
    } else {
      Checkout.$currentScreenActivePrimaryBtn.attr('disabled', false).removeAttr('disabled').text('Continue').css('background-color', '#b20839').css('cursor', 'pointer');
    }
    if (disable_next_step) {
      if ( !$('#checkout').hasClass('already-shown-cbd-alert') ) {
        alert('One of product(s) in your cart is only available in United States, United Kingdom and Canada. Please either remove the CBD products from your cart or change your "' + type + ' Country".');
      }
      Checkout.$currentScreenActivePrimaryBtn.attr('disabled', true).text('Remove CBD items from your cart or change your "' + type + ' Country"').css('background-color', 'gray').css('cursor', 'default');
      $('#checkout').addClass('already-shown-cbd-alert');
    } else {
      if (Checkout.$currentScreenActivePrimaryBt) {
        Checkout.$currentScreenActivePrimaryBtn.attr('disabled', false).removeAttr('disabled').text('Continue').css('background-color', '#b20839').css('cursor', 'pointer');
        $('#checkout').removeClass('already-shown-cbd-alert');
      }
    }
  },
  /*
  * Checkout - Display a notice for certain countries
  * Austria, Germany, Switzerland
  */
  specificCountryNotice: function( $country ) {
    const country_value = $country.val();
    if ( country_value === "AT" || country_value === "DE" || country_value === "CH" ) {
      alert("Important Notice! We don't currently ship product to Austria, Germany, or Switzerland at this time. Check your local retailer or email customercare@radicalskincare.com.");
    }
  },
  /*
    * TODO: For CBD produt can only ship to US & UK
    */
  onClickGoGreen: function() {
    if ( ! $('#go-green-opt-out-packaging').hasClass('init') ) {
      $('a[title="Go-Green"]').on('click', function() {
        alert('The bottles with leaflet is great for me! I also acknowledge that I can get all of the ingredients from Radical Skincare website if necessary.');
      });
      $('#go-green-opt-out-packaging').addClass('init');
    }
  },
  populateFields: function () {
    let Brand_Partner_Address = Cookie.read('Brand_Partner_Address');
    Brand_Partner_Address = JSON.parse(Brand_Partner_Address);
    setTimeout(function() {
      if ($('input#shipping_first_name').val() === '' && Brand_Partner_Address.first_name) {
        $('input#shipping_first_name').val(Brand_Partner_Address.first_name).trigger('change');
      }
      if ($('input#shipping_last_name').val() === '' && Brand_Partner_Address.last_name) {
        $('input#shipping_last_name').val(Brand_Partner_Address.last_name).trigger('change');
      }
      if ($('input#shipping_address_1').val() === '' && Brand_Partner_Address.address_line_1) {
        $('input#shipping_address_1').val(Brand_Partner_Address.address_line_1).trigger('change');
      }
      if ($('input#shipping_address_2').val() === '' && Brand_Partner_Address.address_line_2) {
        $('input#shipping_address_2').val(Brand_Partner_Address.address_line_2).trigger('change');
      }
      if ($('input#shipping_city').val() === '' && Brand_Partner_Address.city) {
        $('input#shipping_city').val(Brand_Partner_Address.city);
      }
      if ($('input#shipping_state').val() === 'AL' && Brand_Partner_Address.state) {
        $('select#shipping_state').val(Brand_Partner_Address.state);
      }
      if ($('input#shipping_postcode').val() === '' && Brand_Partner_Address.zip_code) {
        $('input#shipping_postcode').val(Brand_Partner_Address.zip_code).trigger('change');
      }
    }, 500);
  },
  hideSavedPaymentOptions: function() {
    if ($('input#wc-elavon-converge-credit-card-use-new-payment-method').length) {
      $('input#wc-elavon-converge-credit-card-use-new-payment-method').prop('checked', true);
      $('[name="wc-elavon-converge-credit-card-payment-token"]').trigger('change');
      $('#wc-elavon-converge-credit-card-use-new-payment-method:checked').parent().hide();
    }
  },
  storeWideSaleCarItems: function() {
    if ($('#cfw-cart').hasClass('discount-applied')) {
      return
    }
    $('#cfw-cart .cart-item-row').each(function() {
      const $this = $(this)
      const sale_discount = 0.15
      if (!$this.find('del[aria-hidden="true"]').length) {
        const original_price = parseFloat($this.find('.cfw-cart-item-subtotal bdi').text().trim().replace('$', ''))
        const new_price = (original_price - (original_price * sale_discount)).toFixed(2)
        const $original_price_wrap = $this.find('.woocommerce-Price-amount')
        $original_price_wrap.css('text-decoration', 'line-through').css('display', 'block')
        $original_price_wrap.after(`<ins style="display: block;"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>${new_price}</bdi></span></ins>`)
      } else {
        const $sale_price_wrap = $this.find('ins')
        const sale_price = parseFloat($sale_price_wrap.text().trim().replace('$', ''))
        const new_price = (sale_price - (sale_price * sale_discount)).toFixed(2)
        $sale_price_wrap.remove()
        $this.find('del[aria-hidden="true"]').after(`<ins style="display: block;"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>${new_price}</bdi></span></ins>`)
      }
    })
    $('#cfw-cart').addClass('discount-applied')
  },
}

const Cookie = {
  read: function(name) {
    const nameEQ = name + '=';
    var ca = document.cookie.split(';');
    for (var i=0;i < ca.length;i++) {
      var c = ca[i];
      while (c.charAt(0)==' ') c = c.substring(1,c.length);
      if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
  },
  create: function(name, value, days) {
    let expires = '';
    if (days) {
      var date = new Date();
      date.setTime(date.getTime()+(days*24*60*60*1000));
      expires = '; expires=' + date.toGMTString();
    } else {
      expires = '';
    }
    document.cookie = name + '=' + value + expires + '; path=/';
  },
  erase: function(name) {
    Cookie.create(name, '', -1);
  },
}

$(document).ready(function() {
  setTimeout(function() {
    Checkout.onInit()
  }, 1000)
})
})(jQuery)
