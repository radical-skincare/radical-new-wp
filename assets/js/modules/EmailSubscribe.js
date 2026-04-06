
/* global ThemeSettings */
const EmailSubscribe = {
  onLoad: function() {
    if (!$('#emailSubscribeModal').length) {
      return
    }
    // if ref exists exit
    if (Utilities.getAllUrlParams().ref !== undefined) {
      return
    }
    if (Cookie.read('EMAIL_SUBSCRIBE_MODAL_SHOWN')) {
      return
    }
    this.load()
  },
  load: function() {
    setTimeout(() => {
      EmailSubscribe.showModal()
    }, parseInt(ThemeSettings.email_signup_modal.popup_delay_seconds) * 1000)
  },
  showModal: function() {
    $('#emailSubscribeModal').modal('show')
    // Dont show sale modal for 3 days
    Cookie.create('EMAIL_SUBSCRIBE_MODAL_SHOWN', 1, 3)
  },
}
