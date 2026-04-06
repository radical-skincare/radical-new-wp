
const ArchiveProducts = {
  onLoad: function() {
    if (!$('.woocommerce.archive').length) {
      return
    }
    this.onOrderByDropdownClick()
    this.collapseAccordionItem()
  },
  onOrderByDropdownClick: function() {
    $('.btn-group-orderby .dropdown-item').on('click', function() {
      let text = $(this).text()
      text = text.replace('Sort by ', '')
      const value = $(this).attr('value')
      $('[name="orderby"]').val(value).trigger('change')
      $('.btn-group-orderby .dropdown-toggle').text(text)
    })
  },
  collapseAccordionItem: function() {
    const window_width = $(window).width()
    if (window_width <= 991) {
      $('#shop-sidebarheading0 button').trigger('click')
    }
  },
}
