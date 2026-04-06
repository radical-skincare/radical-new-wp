
const Login = {
  onLoad: function() {
    if ($('#login_modal').length) {
      this.init( 'login_modal' )
    }
    if ($('#enrollment_login').length) {
      this.init( 'enrollment_login' )
    }
    if ($('#register').length) {
      this.init( 'register' )
    }
  },
  init: function( form_prefix ) {
    $(`#${form_prefix} input`).on('change', function() {
      if ($(this).hasClass('invalid')) {
        $(this).removeClass('invalid')
        $(this).parent().find('.invalid-feedback').hide()
      }
    })
    Form.onTogglePassVisibility( form_prefix + '_password' )
    Form.onTogglePassVisibility( form_prefix + '_confirm_password' )
    this.onSubmit( form_prefix )
  },
  onSubmit: function( form_prefix ) {
    $(`#${form_prefix}`).on('submit', function(e) {
      e.preventDefault()
      // if website contains value exit! // this is spam honeypot
      if ( $('#' + form_prefix + ' #website').val() !== '' ) {
        return
      }
      $('#' + form_prefix + '_loader').show()
      $('#' + form_prefix + ' .form-control').removeClass('invalid')
      $('#' + form_prefix + ' button[type="submit"]').prop('disabled', true)
      Login.login( form_prefix ).then(function(res) {
        $('#' + form_prefix + ' #ip-blocker-invalid-feedback').hide()
        res = JSON.parse(res)
        if (res.success) {
          const $redirect_to = $('#redirect_to',$(e))
          if (!$redirect_to.length || $redirect_to.val() == null) {
            location.reload()
          } else {
            window.location.href = $redirect_to.val()
          }
        } else {
          console.error(res)
          if (res.result.errors) {
            if (res.result.errors.invalid_email || res.result.errors.invalid_username) {
              $('#' + form_prefix + '_user_login').addClass('invalid')
              const feedback = (res.result.errors.invalid_email) ? res.result.errors.invalid_email[0] : res.result.errors.invalid_username[0]
              $('#' + form_prefix + '_user_login').parent().find('.invalid-feedback').html(feedback).show()
            } else if (res.result.errors.incorrect_password) {
              $('#' + form_prefix + '_password').addClass('invalid')
              $('#' + form_prefix + '_password').parent().find('.invalid-feedback').html(res.result.errors.incorrect_password[0]).show()
            } else if (res.result.errors.ip_blocked) {
              $('#' + form_prefix + ' #ip-blocker-invalid-feedback').html(res.result.errors.ip_blocked.join(',')).show()
            }
          }
          $('#' + form_prefix + '_loader').hide()
          $('#' + form_prefix + ' button[type="submit"]').prop('disabled', false).removeAttr('disabled')
        }
      }).catch(function(err) {
        console.error(err)
      })
    })
  },
  login: function( form_prefix ) {
    return new Promise( (resolve, reject) => {
      $.ajax({
        url: $('#' + form_prefix).attr('action'),
        data: $('#' + form_prefix).serialize(),
        type: 'POST',
        config: { headers: {'Content-Type': 'multipart/form-data' }},
      }).done(function(res) {
        resolve(res)
      }).fail(function(err) {
        reject(err)
      })
    })
  },
}
