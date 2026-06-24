
const Form = {
  onLoad: function() {
    setTimeout(Form.load, 100)
  },
  load: function() {
    window.$ = jQuery;
    Form.addFloating($('.form-outline input, .form-outline textarea'))
    Form.addFloating($('.nf-form-outline input, .nf-form-outline textarea'))
    if ($('.template-account #password').length) {
      Form.onTogglePassVisibility('password');
    }
    if ($('.template-account #reg_password').length) {
      Form.onTogglePassVisibility('reg_password');
    }
  },
  onTogglePassVisibility: function( input_id ) {
    $(`#${input_id}`).on('input, change', function() {
      if ($(this).val() === '') {
        $('[toggle="#' + input_id + '"]').hide()
      } else {
        $('[toggle="#' + input_id + '"]').show()
      }
    })
    $('[toggle="#' + input_id + '"]').on('click', function() {
      let toggle_password = $(this)
      if (toggle_password.hasClass('invisible-p')) {
        toggle_password.removeClass('invisible-p').addClass('visible-p')
        $(toggle_password.attr('toggle')).attr('type','text')
      } else {
        toggle_password.addClass('invisible-p').removeClass('visible-p')
        $(toggle_password.attr('toggle')).attr('type','password')
      }
    })
  },
  addFloating: function($fields) {
    $fields.on('focus', function() {
      $(this).parent().find('label').addClass('focused')
    })
    $fields.on('blur', function() {
      const $this = $(this)
      if ($this.val() == null || $this.val() == '') {
        $this.parent().find('label').removeClass('focused')
      }
    })
    $fields.map(function(i,e){
      const $this = $(e)
      if ($this.val() != null && $this.val() != '') {
        $this.parent().find('label').addClass('focused')
      }
    })
  },
}
