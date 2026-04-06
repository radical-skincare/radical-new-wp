
const BrandPartner = {
  onLoad: function() {
    this.onAgreementScroll()
    this.onRegisterSubmit()
    this.onLoginSubmit()
    // this.onClickDashboardLink()
  },
	onAgreementScroll: function() {
		$('#brand-partner-agreement').on('scroll', function() {
			const scrollFromTop = $(this).scrollTop()
			const scrollHeight = $(this)[0].scrollHeight - $(this).height()
			if ( scrollFromTop > (scrollHeight - 100) ) {
				$('label[for="brand-partner-terms"]').removeClass('form-check-label-disabled')
				$('#brand-partner-terms').prop('disabled', false).prop('checked', true).removeAttr('disabled')
				$('#brand-partner-terms-description').removeClass('text-danger').addClass('text-success')
				$('#brand-partner-terms-description').parent().removeClass('alert-danger').addClass('alert-success')
			}
		});
	},
  onRegisterSubmit: function() {
		$('#register').on('submit', function(e) {
      e.preventDefault()
			const $register_form = $(this)
      const $register_password = $('#register_password', $register_form)
      const register_password = $register_password.val()
      const $confirm_password = $('#register_confirm_password', $register_form)
      $confirm_password[0].setCustomValidity('')
      if (register_password !== $confirm_password.val()) {
        $confirm_password[0].setCustomValidity('Please provide a matching password')
        $register_form.get(0).reportValidity()
        $register_password.on('keyup', function () {
          $confirm_password[0].setCustomValidity('')
        })
        $confirm_password.on('keyup', function () {
          $confirm_password[0].setCustomValidity('')
        })
        return false
      }
      const $bp_terms_cehckbox = $('#brand-partner-terms')
      if (!$bp_terms_cehckbox.is(':checked') ){
        return false
      }
      const $register_login_form_loader = $('#register-login-form-loader')
      $register_login_form_loader.css('display', 'flex')
      $.ajax({
          type: 'POST',
          url: $register_form.attr('action'),
          data: $register_form.serialize(),
          success: function(data) {
            $register_login_form_loader.css('display','none')
            data = JSON.parse(data)
            if (data.success && data.result) {
              window.location.href = $('#redirect_to', $register_form).val()
              return
            }
            $('.form-register-alert').html('<p class="mb-0">' + data.err_msg + '</p>').show()
          },
      });
		});
	},
  onLoginSubmit: function() {
		$('#enrollment_login').on('submit', function(e) {
      e.preventDefault()
			const $register_form = $(this)
      $.ajax({
        type: 'POST',
        url: $register_form.attr('action'),
        data: $register_form.serialize(),
        success: function(data) {
          data = JSON.parse(data)
          if (data.success && data.result) {
            window.location.href = $('#redirect_to', $register_form).val()
            return
          } else {
            if (data.result && data.result.errors) {
              let error_msg = ''
              for (let i = 0, errors = Object.values(data.result.errors); i < errors.length; i++) {
                error_msg += errors[i] + ' '
              }
              $('.form-register-alert').html('<p class="mb-0">' + error_msg + '</p>').show()
            }
          }
        },
      })
		})
	},
  /*
  onClickDashboardLink: function() {
    $('a[href*="/account/brand-partner-dashboard"]').on('click', function() {
      $('#on-click-gigfiliate-dashboard-link').addClass('loading').css('display', 'flex')
      // let counter = 0
      let progress_bar_css_width = 0
      const clear_interval_after_milliseconds = 10000 // seconds
      const interval_milliseconds = 250 // seconds
      const css_width_per_interval = 100/(clear_interval_after_milliseconds/interval_milliseconds)
      const intervalId = setInterval(() => {
        progress_bar_css_width += css_width_per_interval
        // console.log(progress_bar_css_width, counter)
        $('#on-click-gigfiliate-dashboard-link.loading .progress-bar').css('width', `${progress_bar_css_width}%`)
        // counter++
      }, interval_milliseconds)
      setTimeout(() => {
        clearInterval(intervalId)
      }, clear_interval_after_milliseconds)
    })
  },
  */
}
