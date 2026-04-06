
/* global ThemeSettings */
const Sale = {
  settings: false,
  onLoad: function() {
    if (!$('#saleModal').length) {
      return
    }
    if (!ThemeSettings.sitewide_discount.enable) {
      return
    }
    if (!ThemeSettings.sitewide_discount.sale_popup_modal.enable) {
      return
    }
    if (Cookie.read('SALE_MODAL_SHOWN')) {
      return
    }
    this.load()
  },
  load: function() {
    this.settings = ThemeSettings.sitewide_discount.sale_popup_modal
    setTimeout(() => {
      Sale.showModal()
    }, parseInt(Sale.settings.popup_delay_seconds) * 1000)
  },
  showModal: function() {
    $('#saleModal').modal('show')
    // Dont show sale modal for 1 day
    Cookie.create('SALE_MODAL_SHOWN', 1, 1)
  },
}
