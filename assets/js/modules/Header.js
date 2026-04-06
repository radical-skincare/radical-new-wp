
const Header = {
  onLoad: function() {
    this.onToggleLeftSidebar()
    this.setCurrencyToggle()
    this.onToggleCurrency()
    this.onMobileToggleCLick()
    this.onOpenMegaMenu()
  },
  onToggleLeftSidebar: function() {
    $('#leftSidebarModal').on('show.bs.modal', function () {
      $('[data-target="#leftSidebarModal"]').removeClass('collapsed')
    })
    $('#leftSidebarModal').on('hide.bs.modal', function () {
      $('[data-target="#leftSidebarModal"]').addClass('collapsed')
    })
  },
  setCurrencyToggle: function() {
    $('.nav-link_USD, .nav-link_EUR, .nav-link_GBP').hide()
    let value = 'USD'
    value = $('[form="wcpbc-widget-country-switcher-form"]').val()
    if (value === 'AD') {
      value = 'EUR'
    } else if (value === 'GB') {
      value = 'GBP'
    } else {
      value = 'USD'
    }
    $(`.nav-link_${value}`).show()
  },
  onToggleCurrency: function() {
    $('#currency-items a').on('click', function (e) {
      e.preventDefault()
      const value = $(this).attr('value')
      $('[name="wcpbc-manual-country"]').val(value)
      Header.setCurrencyToggle()
      $('#wcpbc-widget-country-switcher-form').submit()
    })
  },
  onMobileToggleCLick: function() {
    $('.vertical-menu a').on('click', function(e) {
      const $currentItem = $(e.currentTarget)
      const $currentItemParent = $currentItem.parent()
      if (!$currentItemParent.find('.sub-menu').length) {
        return true
      }
      e.preventDefault()
      if ($currentItemParent.hasClass('expanded')) {
        $currentItemParent.removeClass('expanded')
      } else {
        $currentItemParent.addClass('expanded')
      }
    })
  },
  onOpenMegaMenu: function() {
    const $mega_menu = $('#mega-menu');
    if (!$mega_menu.length) {
      return
    }
    $('.main-header_navbar .open-mega-menu').on('click', function(e) {
      e.preventDefault()
      if( $mega_menu.hasClass('opened') ){
        $mega_menu.slideUp().removeClass('opened')
      } else {
        $mega_menu.slideDown().addClass('opened')
      }
      $('.dropdown-menu', $(this).parent()).css('display', 'none')
    })
    $('.main-header li a:not(.open-mega-menu) ').on('click', function() {
      if( $mega_menu.hasClass('opened') ){
        $mega_menu.slideUp().removeClass('opened')
      }
    })
    $(document).on('click', function(event){
      if (!$('#product-quickview-modal').is(event.target) && !$mega_menu.is(event.target) && $mega_menu.has(event.target).length === 0) {
        $mega_menu.slideUp().removeClass('opened')
      }
    })
  },
}
