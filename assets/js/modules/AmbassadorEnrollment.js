/* eslint-disable */

const AmbassadorEnrollment = {
  onLoad: function() {
    if ( !$('.template-brand-partner-enrollment').length ) {
      return
    }
    on_Tabs_Change_Set_Next()
    init_Tabs( $('#ambassadorEnrollmentTabs a.active').attr('href') );
    if ( $('.woocommerce-table__product-name').length ) {
      if ($('.woocommerce-table__product-name').text().indexOf('Welcome') !== -1) {
        Cookie.erase('Ambassador_Enrollment_InProgress')
      }
    }
  },
}

/* Ambassador Multi Step Tabs
-------------------------------------------------------------- */
function on_Tabs_Change_Set_Next() {
  $('#ambassadorEnrollmentTabs a[data-toggle="tab"]').on('show.bs.tab', function(e) {
    e.target // newly activated tab
    e.relatedTarget // previous active tab
    $('#ambassadorEnrollmentTabs .nav-item').removeClass("next")
    $(e.target).parent().next().addClass("next")
    init_Tabs( $(e.target).attr("href") )
  })
}

function init_Tabs( active_tab ) {
  if ( active_tab === undefined ) {
    return false;
  }
  if ( active_tab === '#account' ) {
    /* Account Tab
    -------------------------------------------------------------- */
    init_Tab_Account();
    $('#collections-progress').hide()
  } else if ( active_tab === '#coach' ) {
    /* Coach Tab
    -------------------------------------------------------------- */
    init_Tab_Coach();
    $('#collections-progress').hide()
  } else if ( active_tab === '#kit' ) {
    /* Collections Tab
    -------------------------------------------------------------- */
    init_Tab_Collections()
  } else if ( active_tab === '#specials' ) {
    /* Special Tab
    -------------------------------------------------------------- */
    init_Tab_Special()
  } else if ( active_tab === '#activation' ) {
    /* Activation Tab
    -------------------------------------------------------------- */
    init_Tab_Activation()
  } else if ( active_tab === '#checkout' ) {
    Cookie.erase('Ambassador_Enrollment_InProgress')
  }
  if ( active_tab === '#kit' || active_tab === '#specials' ) {
    CollectionsProgress.onLoad()
    AreYouSureModal.onLoad()
  }
}

/* Account Tab
-------------------------------------------------------------- */

function init_Tab_Account() {
    init_Tab_Account_Listeners(); // Only ever loads once
    // if account tab complete
    if ( $('#account-tab.complete').length ) {
        $('.affiliate-step-complete-checkmark-wrap').show();
        setTimeout(function() {
            affiliate_Step_Checkmark();
        }, 1000);
    } else {
        Cookie.create('Ambassador_Enrollment_InProgress', '{ "step":"account" }', 7);
    }
    init_Tab_Account_Listeners();
}

function init_Tab_Account_Listeners() {
  if ( $('#account.tab-pane').hasClass('listening') ) {
    return;
  }
  on_Account_Tel_Change();
  on_Add_Account_Info();
  on_Edit_Account_Info();
  $('#account.tab-pane').addClass('listening');
}

function on_Account_Tel_Change() {
  $('#account.tab-pane input[type="tel"]').on('change', function() {
    Utilities.phoneMask( $(this) );
  });
}

/* Account Tab > On Add Affiliate
-------------------------------------------------------------- */
function on_Add_Account_Info() {
  $('#add-affiliate').on('submit', function(e) {
    e.preventDefault();
    let $form = $(this);
    $('.card-affiliate').hide();
    $('.affiliate-step-complete-checkmark-wrap').show();
    $.post($form.attr('action'), $form.serialize(), function(res) {
      if ( res.success ) {
        // Create Affiliate Address Cooie
        // create_Affiliate_Address_Cookie();
        $('#account-tab').addClass('complete');
        // toastr.success( 'Account Info set!' ); // toastr undefined
        affiliate_Step_Checkmark();
        setTimeout(function() {
          $('#edit-account-info').show();
        }, 500);
        // Go To 'Referred By' Tab
        $('#ambassadorEnrollmentTabs a[href="#coach"]').removeClass('disabled');
        $('#ambassadorEnrollmentTabs a[href="#coach"]').tab('show');
      } else {
        $('.card-affiliate').show();
        $('.affiliate-step-complete-checkmark-wrap').hide();
        if (res.msg.indexOf('State') !== -1) {
          $('#account-state-select-wrap [type="text"]').addClass('invalid');
          $('#account-state-select-wrap .description').removeClass('d-none').addClass('d-block');
        }
        console.error(res);
      }
    }, 'json');
  });
}

function on_Edit_Account_Info() {
    $('#edit-account-info').on('click', function() {
        $('.affiliate-step-complete-checkmark-wrap').hide();
        $('#edit-account-info').hide();
        $('#enrollment-account-info').show();
    });
}

/* Create Affiliate Address Cooie
 * This is used at checkout to prepopulate the checkout fields
--------------------------------------------------------------
function create_Affiliate_Address_Cookie() {
  const data = {
    // 'first_name': $('#enrollment-account-info [name="first_name"]').val(),
    // 'last_name': $('#enrollment-account-info [name="last_name"]').val(),
    'first_name': $('.enrollment-first-name').text(),
    'last_name': $('.enrollment-last-name').text(),
    'address_line_1': $('#enrollment-account-info [name="address_line_1"]').val(),
    'address_line_2': $('#enrollment-account-info [name="address_line_2"]').val(),
    'city': $('#enrollment-account-info [name="city"]').val(),
    'state': $('#enrollment-account-info [name="state"]').val(),
    'zip_code': $('#enrollment-account-info [name="zip_code"]').val(),
    'country': 'US',
  };
  Cookie.create('Brand_Partner_Address', JSON.stringify(data), 1);
}
 */

/* Affiliate Tab > Checkmark
-------------------------------------------------------------- */
function affiliate_Step_Checkmark() {
    if ( ! $('.affiliate-step-complete-checkmark-wrap .circle-loader').hasClass('load-complete') ) {
        $('.affiliate-step-complete-checkmark-wrap .circle-loader').toggleClass('load-complete');
        $('.affiliate-step-complete-checkmark-wrap .checkmark').toggle();
        $('#enrollment-account-info').hide();
    }
}

/* Coach Tab
-------------------------------------------------------------- */
let allCoaches

function get_All_Users() {
  const ASYNC_TIMEOUT = 25000 // 25 secs
  let dfd = jQuery.Deferred()
  if (allCoaches) {
      dfd.resolve( "success" )
  } else {
    let options = {}
    if (ThemeSettings.affiliate_plugin === 'affiliate-wp') {
      options = {
        type: "GET",
        url:  ThemeSettings.site_url + "/wp-json/affservices/v1/ambassadors", // /wp-json/affwp/v1/affiliates"
        cache: false,
        timeout: ASYNC_TIMEOUT, // 10 secs
      }
    } else {
      options = {
        type: "POST",
        data: {
          'action': 'gigfiliate_get_affiliates_from_local'
        },
        url: ThemeSettings.admin_ajax_url,
        cache: false,
        timeout: ASYNC_TIMEOUT, // 10 secs
      }
    }
    $.ajax( options ).done(function( data ) {
      if (ThemeSettings.affiliate_plugin === 'affiliate-wp') {
        allCoaches = data
        dfd.resolve( 'success' )
      } else {
        const json_data = JSON.parse(data)
        if (json_data.success) {
          allCoaches = json_data.users
          dfd.resolve( 'success' )
        } else {
          console.error(data)
          dfd.reject( 'failed' )
        }
      }
    }).fail(function(error) {
      console.error(error)
      dfd.reject( 'failed' )
    })
  }
  return dfd.promise()
}

/* Coach Tab > Init
-------------------------------------------------------------- */
function init_Tab_Coach() {
    if ( $('#coach-tab.complete').length ) {
        $('.part-coach-title-row, .find-coach-row, #skip-coach-row').hide();
        $('.coach-step-complete-row').show();
        setTimeout(function() {
            coach_Step_Checkmark();
        }, 1000);
    } else {
        // Load All Users
        $.when( get_All_Users() ).then( function( status ) {
            // console.log('allCoaches', allCoaches);
            allCoaches.forEach( function(coach) {
                // const this_coach = coach;
                if ( ThemeSettings.affiliate_plugin === 'affiliate-wp' ) {
                    if (coach.user_id != ThemeSettings.current_user_id ) {
                        let li = $('<li class="coach" >');
                            li.attr( 'coach_user_id', coach.user_id );
                            li.attr( 'coach_affiliate_id', coach.affiliate_id );
                            let li_html = '<div class="row">';
                                    li_html += '<div class="col-auto pl-3">';
                                        li_html += '<img src="' + coach.profile_pic_url + '" class="coach-avatar img-fluid w-100" alt="' + coach.name + '" />';
                                    li_html += '</div>';
                                    li_html += '<div class="col">';
                                        li_html += '<h3 class="coach-name" >' + coach.name + '</h3>';
                                        li_html += '<p class="mb-0" >';
                                            li_html += ( coach.city ) ? '<span class="coach-city">' + coach.city + '</span>, ': '';
                                            li_html += ( coach.state ) ? '<span class="coach-state">' + coach.state + '</span> ': '';
                                            li_html += ( coach.zip ) ? '<span class="coach-zip">' + coach.zip + '</span>': '';
                                        li_html += '</p>';
                                    li_html += '</div>';
                                li_html += '</div>';
                            li.html(li_html);
                        $('.coaches-listing').append(li);
                    }
                } else {
                    // else ThemeSettings.affiliate_plugin === 'vialibis'
                    // console.log('coach', coach, this_coach);
                    const coach_html = '<li class="coach" coach_user_id="' + coach.ID + '" coach_affiliate_id="' + coach.data.affiliate_id + '">\
                            <div class="row">\
                                <div class="col-auto pl-3">\
                                    <img src="' + coach.data.profile_pic_url + '" class="coach-avatar img-fluid w-100" alt="' + coach.data.display_name + '" />\
                                </div>\
                                <div class="col">\
                                    <h3 class="coach-name">' + coach.data.first_name + ' ' + coach.data.last_name + '</h3>\
                                    <p class="mb-0" >\
                                        <span class="coach-city">' + coach.data.city + '</span> <span class="coach-state">' + coach.data.state + '</span>, <span class="coach-zip">' + coach.data.zip + '</span>\
                                    </p>\
                                </div>\
                            </div>\
                        </li>';
                    $('.coaches-listing').append(coach_html);
                }
            });
            // $('.coaches-listing-row').show();
            // Select the Coach (Ambassador) Who referred this affiliate
            selected_Coach_Who_Referred();
            // listen to click selecting coach
            on_Select_Coach_Listeners();
        }).fail( function(error) {
            console.error(error);
            alert(error.status);
        });
        $('#find-coach').on('submit', function(e) {
            e.preventDefault();
        });
        $('#find-coach input#full_name').on('input', function() {
            // console.log( $(this).val() );
            on_Input_Filter_Users( $(this).val(), 'name' )
        });
        $('#find-coach input#city_state_zip').on('input', function() {
            on_Input_Filter_Users( $(this).val(), 'location' )
        });
        // on_Set_Coach_Default();
        on_Skip_Referred_By();
        Cookie.create('Ambassador_Enrollment_InProgress', '{ "step":"coach" }', 7);
    }
    on_Edit_Referred_By();
}

/*
 * I guess there is no #btn-skip-coach anymore ?
function on_Set_Coach_Default() {
  $('#btn-skip-coach').on('click', function() {
    let default_coach_affiliate_id = ThemeSettings.default_parent_affiliate_id;
    $('.find-coach-inner-wrap').hide();
    // set elements
    $('.coach-selected-wrap .selected-coach-name').text( 'Radical Skincare' );
    $('.coach-selected-wrap .selected-coach-city').text( 'Malibu' );
    $('.coach-selected-wrap .selected-coach-state').text( 'CA' );
    $('#selected-coach-avatar').css( 'background-image', "url('https://radicalskincare.com/wp-content/uploads/2019/06/radical-skincare-logo.png')" );
    $('#set-coach input[name="affiliate_parent_id"]').val(default_coach_affiliate_id);
    $('.coach-selected-wrap').show();
    $(this).hide();
    unset_Referral_Cookie_Tracking(); // unset tracking cookie so that it doesnt fire a CUSTOMER_VOLUME for the referring affiliate
    // set_Coach_Referral_Cookie_Tracking(default_coach_affiliate_id);
    BP_Tracking.onRemoveShoppingUnderneath();
  });
}
 */

function on_Skip_Referred_By() {
  $('#btn-skip-referred-by').on('click', function() {
    $('#skipReferredByConfirmModal').modal('show')
    // if (confirm('Are you sure you were not referred by a Brand Partner and want to skip this step?')) {
    // }
  })
  $('#skipReferredByConfirmModalButton').on('click', function() {
    $('.part-coach-title-row, .find-coach-row, #skip-coach-row, #skip-referred-by').hide()
    $('.coach-step-complete-row').show()
    process_Set_Enrollment_No_Referrer_Chosen().then( function (res) {
      const res_json = JSON.parse(res)
      if (res_json.success) {
        $('#coach-tab').addClass('complete')
        $('.stored-coach-wrap').html('A Brand Partner referrer was purposely not chosen.')
        coach_Step_Checkmark()
        setTimeout(function() {
          // show Kit tab
          $('#ambassadorEnrollmentTabs a[href="#specials"]').removeClass('disabled').tab('show')
        }, 1000)
        BP_Tracking.onRemoveShoppingUnderneath()
      } else {
        console.error('Something went wrong.', res_json)
      }
    })
  })
}

function process_Set_Enrollment_No_Referrer_Chosen() {
  return new Promise( (resolve, reject) => {
    $.ajax({
      method: 'POST',
      data: {
        'action': 'process_set_enrollment_no_referrer_chosen',
        'nonce': ThemeSettings.radical_nonce,
      },
      url: ThemeSettings.admin_ajax_url,
    }).done(function(res) {
      resolve(res)
    }).fail(function(err) {
      reject(err)
    })
  })
}

/* Coach Tab > On Filter Affiliates
* parameters
* * value
* * type 'name' or 'location' (invalid anything else)
-------------------------------------------------------------- */
function on_Input_Filter_Users( value, type ) {
    let matchFound = false;
    let filter;
    let coachName;
    let coachCity;
    let coachState;
    let coachZip;
    if ( value ) {
        $('.coaches-listing .coach').each(function() {
            if ( type == 'name' ) {
                coachName = $(this).find('.coach-name').text().toLowerCase();
                filter = coachName;
            } else if ( type == 'location' ) {
                coachCity = $(this).find('.coach-city').text().toLowerCase();
                coachState = $(this).find('.coach-state').text().toLowerCase();
                coachZip = $(this).find('.coach-zip').text().toLowerCase();
                filter = coachCity + ', ' +  coachState + ' ' + coachZip;
            }
            if ( filter.indexOf( value.toLowerCase() ) > -1 ) {
                $(this).show();
                matchFound = true;
            } else {
                $(this).hide();
            }
        });
        $('.coaches-listing-row').show();
        $('.coaches-filter-result-row').hide();
        if ( ! matchFound ) {
            $('.coaches-listing-row').hide();
            $('.coaches-filter-result-row').show();
        }
    } else {
        $('.coaches-listing-row').hide();
        $('.coaches-filter-result-row').hide();
    }
}

function selected_Coach_Who_Referred() {
    // read cookie affwp_ref
    let affwp_ref = ( ThemeSettings.affiliate_plugin === 'affiliate-wp' ) ? Cookie.read('affwp_ref') :  Cookie.read('gigfiliatewp_ref');
    // console.log('affwp_ref cookie: ', affwp_ref);
    if (affwp_ref) {
        $('.coaches-listing .coach').each( function() {
            let coach_affiliate_id = $(this).attr('coach_affiliate_id');
            if ( coach_affiliate_id === affwp_ref) {
                // console.log('Select this Coach!', coach_affiliate_id);
                select_Coach($(this));
            }
        });
    }
}

/* Coach Tab > On Select Coach
-------------------------------------------------------------- */
function on_Select_Coach_Listeners() {
  $('ul.coaches-listing li.coach').on('click', function() {
    select_Coach($(this));
  })
  $('form#set-coach').on('submit', function(e) {
    e.preventDefault()
    submit_Selected_Coach($(this))
  })
  $('.coach-selected-wrap .btn-un-select-coach').on('click', function() {
    cancel_Selected_Coach()
  })
}

function select_Coach( $this ) {
  // get values
  let coach_affiliate_id = $this.attr('coach_affiliate_id');
  let coach_name = $this.find('.coach-name').text();
  let coach_city = $this.find('.coach-city').text();
  let coach_state = $this.find('.coach-state').text();
  let coach_avatar = $this.find('.coach-avatar').attr('src');
  $('.find-coach-inner-wrap, #skip-referred-by').hide();
  // set elements
  $('#set-coach input[name="affiliate_parent_id"]').val(coach_affiliate_id);
  $('.coach-selected-wrap .selected-coach-name').text(coach_name);
  $('.coach-selected-wrap .selected-coach-city').text(coach_city);
  $('.coach-selected-wrap .selected-coach-state').text(coach_state);
  $('#selected-coach-avatar').css('background-image', `url('${coach_avatar}')`);
  $('.coach-selected-wrap').show();
  unset_Referral_Cookie_Tracking(); // Unset the tracking cookie
  // set_Coach_Referral_Cookie_Tracking(coach_affiliate_id); // see *note
  BP_Tracking.onRemoveShoppingUnderneath(); // Remove this after you create a VitalibisWP Activation Product Setting - and you update the vitalibis public woocommerce processing function to exit if there is an Auto Activation Product purchased
}

/*
 * We unset the referral tracking cookie because we don't record Enrollment purchases as customer volume
 * Which actually is a problem that well need to remedy, because if a customer abondons enrollment then purchases a product that purchase wont be acredited to referring affiliate
 * Proposed TODO solution is: We only exclude Customer Volume if the Welcome product is in cart at checkout.
 */
function unset_Referral_Cookie_Tracking() {
  Cookie.erase('gigfiliatewp_ref');
}

const BP_Tracking = {
  onRemoveShoppingUnderneath: function( ) {
    this.removeShoppingUnderneath().then( function(res) {
      res = JSON.parse(res);
      if (res.success) {
      } else {
        console.error(res);
      }
    }).catch(function(err) {
      console.error(err);
    });
  },
  removeShoppingUnderneath: function() {
		return new Promise( (resolve, reject) => {
      $.ajax({
        url: ThemeSettings.admin_ajax_url,
        data: {
          'action': 'process_reset_affiliate_referrer',
          'nonce': ThemeSettings.radical_nonce,
        },
        type: 'POST',
        config: { headers: {'Content-Type': 'multipart/form-data' }},
      }).done(function(res) {
        resolve(res);
      }).fail(function(err) {
        reject(err);
      });
		});
  },
};

function submit_Selected_Coach( $this ) {
  const $form = $this;
  $('.part-coach-title-row, .find-coach-row, #skip-coach-row, #skip-referred-by').hide();
  $('.coach-step-complete-row').show();
  $.post($form.attr('action'), $form.serialize(), function(data) {
    if ( data.success ) {
        $('#coach-tab').addClass('complete');
        let selected_coach_name = $('.coach-selected-wrap .selected-coach-name').text();
        $('.stored-coach-name').text( selected_coach_name );
        // toastr.success('Congratulations, your coach is:' + selected_coach_name); // toastr undefined
        coach_Step_Checkmark();
        setTimeout(function() {
            // show Kit tab
            $('#ambassadorEnrollmentTabs a[href="#specials"]').removeClass("disabled").tab("show");
        }, 1000);
    } else {
        $('.part-coach-title-row, .find-coach-row, #skip-coach-row, #skip-referred-by').show();
        $('.coach-step-complete-row').hide();
        console.error( data );
    }
  }, 'json');
}

function coach_Step_Checkmark() {
  if ( ! $('.coach-step-complete-row .circle-loader').hasClass('load-complete') ) {
    $('.coach-step-complete-row .circle-loader').toggleClass('load-complete');
    $('.coach-step-complete-row .checkmark').toggle();
    setTimeout(function() {
      $('#edit-referred-by').show();
    }, 500);
  }
}

function on_Edit_Referred_By() {
  $('#edit-referred-by').on('click', function() {
    $('#coach-tab').removeClass('complete').removeClass('completed')
    $('#search-referred-by-heading, .coach-step-complete-row').hide()
    $('.part-coach-title-row, .find-coach-row, #skip-referred-by, #search-referred-by-heading').show()
    $('.coach-step-complete-row .circle-loader').toggleClass('load-complete')
    $('.coach-step-complete-row .checkmark').toggle()
    allCoaches = null
    $('#edit-referred-by').off('click')
    BP_Tracking.removeShoppingUnderneath()
    unset_Referral_Cookie_Tracking()
    init_Tab_Coach()
  });
}

function cancel_Selected_Coach() {
  $('.coach-selected-wrap').hide();
  $('.find-coach-inner-wrap, #skip-referred-by').show();
}

/* Collections: Welcome & Enrollment
-------------------------------------------------------------- */
function init_Tab_Collections() {
  // if kits tab complete
  if ( $('#kit-tab.complete').length ) {
    $('.welcome-collection-wrap_details').hide()
    // $('#enrollment-collections-wrap').show()
    setTimeout(function() {
      radical_Collections_Step_Checkmark()
    }, 1000)
  } else {
    Cookie.create('Ambassador_Enrollment_InProgress', '{ "step":"kits" }', 7)
  }
  CollectionsProgress.show()
  // if welcome kit added to cart
  if ( $('.welcome-collection-in-cart').length ) {
    // CollectionsProgress.message('Select your Brand Partner Enrollment Collection(s)!');
    CollectionsProgress.nextStep('specials') // Next step is specials because Enrollment Collections are optional
    CollectionsProgress.enableNextStepButton()
  }
  on_Add_Welcome_Collection_To_Basket()
  // on_Add_Enrollment_Collection_To_Basket()
  on_Remove_Product_From_Basket()
}

// Welcome Collection
function on_Add_Welcome_Collection_To_Basket() {
  $('form#add-welcome-collection').on('submit', function(e) {
    e.preventDefault()
    const $form = $(this)
    const enrollment_loading_dots = $form.parent().find('.enrollment-typing-loading-dots')
    enrollment_loading_dots.show()
    $form.find('button[type="submit"]').prop('disabled', true)
    $.post( $form.attr('action'), $form.serialize(), function(res) {
      if (res.success) {
        enrollment_loading_dots.hide()
        $form.hide()
        $form.next().show()
        CollectionsProgress.enableNextStepButton()
        $('.welcome-collection-wrap_details').hide()
        // CollectionsProgress.message("🎉 Congratulations! Welcome Collection added to your basket.<br/>Continue to step two and pick your Enrollment Collection(s)!")
        CollectionsProgress.message('🎉 Congratulations! Welcome Collection added to your basket.')
        CollectionsProgress.nextStep('specials')
        $('#kit-tab').addClass('complete')
        radical_Collections_Step_Checkmark()
        $('#ambassadorEnrollmentTabs a[href="#specials"]').removeClass('disabled')
        $('#ambassadorEnrollmentTabs a[href="#specials"]').tab('show')
      } else {
        $('.enrollment-add-to-cart-wrap').after('<div class="alert alert-warning"><p class="text-warning mb-0"><span class="fa fa-exclamation-circle"></span> Something went wrong.</p></div>')
        console.error('Something went wrong', res)
      }
    }, 'json')
  })
}

const CollectionsProgress = {
  onLoad: function() {
    this.show()
    this.onClickNextStepButton()
  },
  show: function() {
    $('#collections-progress').show()
  },
  hide: function() {
    $('#collections-progress').hide()
  },
  onClickNextStepButton: function() {
    const self = this
    const $button_next_step = $('#collections-progress .btn-kits-next-step')
    if ($button_next_step.hasClass('listening')) {
      return
    }
    $button_next_step.on('click', function() {
      $(this).prop('disabled', true);
      const next_step = $(this).attr('next_step')
      if ( next_step === 'enrollment_collections' ) {
        $('#welcome-collection-wrap').hide()
        $('#enrollment-collections-wrap').show()
        self.message('Select your Brand Partner Enrollment Collection(s)!')
        self.nextStep('specials')
      } else if ( next_step === 'specials' ) {
        // if Enrollment Collection Not In Cart
        // if (!is_An_Enrollment_Collection_In_Cart()) {
        //   AreYouSureModal.toggle('show');
        // } else {
          $('#ambassadorEnrollmentTabs a[href="#specials"]').removeClass('disabled')
          $('#ambassadorEnrollmentTabs a[href="#specials"]').tab('show')
        // }
      } else if (next_step === 'activation') {
        $('#ambassadorEnrollmentTabs a[href="#activation"]').removeClass('disabled')
        $('#ambassadorEnrollmentTabs a[href="#activation"]').tab('show')
      } else if (next_step === 'checkout') {
        if ( $('.specials-section-products .added-to-cart').length ) {
          go_To_Checkout()
        } else {
          location.href = ThemeSettings.site_url + '/account/brand-partner-dashboard/'
        }
      }
      setTimeout(function() {
        $button_next_step.prop('disabled', false).removeAttr('disabled')
      }, 500)
    })
    $button_next_step.addClass('listening')
  },
  enableNextStepButton: function() {
    $('#collections-progress .btn-kits-next-step').prop('disabled', false).removeAttr('disabled')
  },
  message: function (msg) {
    $('#collections-progress .kits-instruction').html(msg)
  },
  nextStep: function( next_step ) {
    $('#collections-progress .btn-kits-next-step').attr('next_step', next_step)
  },
}

function radical_Collections_Step_Checkmark() {
  if (!$('#welcome-collection-wrap .circle-loader').hasClass('load-complete')) {
    $('#welcome-collection-wrap .affiliate-step-complete-checkmark-wrap').show()
    $('#welcome-collection-wrap .circle-loader').toggleClass('load-complete')
    $('#welcome-collection-wrap .checkmark').toggle()
  }
}

// Silver, Gold, Platinum Collection
function on_Add_Enrollment_Collection_To_Basket() {
  $('#add-silver-collection, #add-gold-collection, #add-platinum-collection').on('submit', function(e) {
    e.preventDefault()
    let $form = $(this)
    const enrollment_loading_dots = $form.parent().find('.enrollment-typing-loading-dots')
    enrollment_loading_dots.show()
    $form.find('button[type="submit"]').prop('disabled', true)
    $.post( $form.attr('action'), $form.serialize(), function(res) {
      if (res.success) {
        enrollment_loading_dots.hide()
        $form.hide()
        $form.addClass('added-to-cart')
        // Show Added & Remove button
        $form.next().show()
        // TODO: Mark Collections tab complete!
        // Collections Progress - Continue to Specials
        CollectionsProgress.message("👍 Great Choice! Enrollment Collection added to your basket.");
        CollectionsProgress.nextStep('specials')
        $('#kit-tab').addClass('complete')
      } else {
        console.error('Something went wrong.', res)
      }
    }, 'json')
  })
}

function on_Remove_Product_From_Basket() {
  $('#remove-welcome-collection, #remove-silver-collection, #remove-gold-collection, #remove-platinum-collection').on('submit', function(e) {
    e.preventDefault();
    const $form = $(this);
    const $form_submit = $form.find('button[type="submit"]');
    $form_submit.prop('disabled', true).html('Removing...');
    $.post( $form.attr('action'), $form.serialize(), function(res) {
      // console.log(res)
      if (res.success) {
        $form.parent().hide();
        $form.parent().parent().find('.enrollment-add-product-form').removeClass('added-to-cart').show();
        $form.parent().parent().find('.enrollment-add-product-form [type="submit"]').prop('disabled', false).removeAttr('disabled');
        $form_submit.prop('disabled', false).removeAttr('disabled').html('<i class="fa fa-times" aria-hidden="true"></i>Remove');
      } else {
        console.error(res)
      }
    }, 'json');
  });
}

function is_An_Enrollment_Collection_In_Cart() {
  return ($('#add-silver-collection.added-to-cart').length || $('#add-gold-collection.added-to-cart').length || $('#add-platinum-collection.added-to-cart').length);
}

function init_Tab_Activation() {
  CollectionsProgress.hide()
  // Run activation sequence when tab becomes visible
  if (typeof window.AffiliateAutoApproveSequence !== 'undefined' && window.AffiliateAutoApproveSequence.onLoad) {
    window.AffiliateAutoApproveSequence.onLoad()
  }
  window.addEventListener('gigfiliate_affiliate_activated', function() {
    if ( $('.specials-section-products .added-to-cart').length ) {
      CollectionsProgress.show()
      CollectionsProgress.message('Congratulations! Your account is activated. You have selected a Special product. Please proceed to checkout to complete your purchase.');
      CollectionsProgress.nextStep('checkout');
      CollectionsProgress.enableNextStepButton();
    }
  });
}

function init_Tab_Special() {
  const products_text = $('.specials-section-products .card-product').length === 1 ? 'product' : 'products'
  CollectionsProgress.message(`Select your Special ${products_text}! These are only available during enrollment.`);
  CollectionsProgress.nextStep('activation');
  CollectionsProgress.enableNextStepButton();
  setTimeout(function() {
    AreYouSureModal.message('specials');
  }, 500);
  $(".special-add-to-cart").submit(function(e) {
    e.preventDefault();
    const $form = $(this);
    var actionUrl = $form.attr('action');
    const product_loader = $form.parent().parent().parent().parent().parent().find('.product-loader')
    product_loader.addClass('d-flex')
    $.ajax({
      type: "POST",
      url: actionUrl,
      data: $form.serialize(),
      success: function(data)
      {
        product_loader.removeClass('d-flex')
        CollectionsProgress.message("👍 Great Choice! Special product added to your cart.");
        CollectionsProgress.nextStep('activation');
        $('#special-tab').addClass('complete');
        $form.removeClass('d-block').addClass('d-none').addClass('added-to-cart')
        $form.parent().find('.remove-special').addClass('d-block').removeClass('d-none')
      }
    });
  });

  $(".remove-special").submit(function(e) {
    e.preventDefault();
    const $form = $(this);
    var actionUrl = $form.attr('action');
    const product_loader = $form.parent().parent().parent().parent().parent().find('.product-loader')
    product_loader.addClass('d-flex')
    $.ajax({
      type: "POST",
      url: actionUrl,
      data: $form.serialize(),
      success: function(data)
      {
        product_loader.removeClass('d-flex')
        CollectionsProgress.nextStep('activation');
        $form.removeClass('d-block').addClass('d-none')
        $form.parent().find('.special-add-to-cart').removeClass('d-none').addClass('d-block').removeClass('added-to-cart')
      }
    });
  });
}

const AreYouSureModal = {
  onLoad: function() {
    this.onNextButtonClick();
  },
  toggle: function( toggle = 'show' ) {
    jQuery('#areYouSureModal').modal(toggle);
  },
  message: function( active_tab ) {
    if (active_tab === 'specials') {
      $("#areYouSureModalLabel").html('Are you sure you want to continue to checkout without adding a Special product?')
      $("#are-you-sure-btn-next").removeClass('next_slide_button').attr('href', ThemeSettings.site_url + '/checkout').html("Yes, Take Me To Checkout");
      $("#are-you-sure-btn-dismiss").html('No, I Want to Add A Special Product');
    }
  },
  onNextButtonClick: function() {
    const $are_you_sure_btn_next = $('#are-you-sure-btn-next');
    if ($are_you_sure_btn_next.hasClass('listening')) {
      return;
    }
    $are_you_sure_btn_next.on('click', function() {
      const go_to_tab = $(this).attr('go-to-tab');
      $('#ambassadorEnrollmentTabs a[href="#' + go_to_tab + '"]').removeClass('disabled');
      $('#ambassadorEnrollmentTabs a[href="#' + go_to_tab + '"]').tab('show');
      AreYouSureModal.toggle('hide');
    });
    $are_you_sure_btn_next.addClass('listening');
  },
};

function go_To_Checkout() {
  location.href = ThemeSettings.site_url + '/checkout'
}
