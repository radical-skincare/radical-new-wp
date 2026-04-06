
const TemplateFAQ = {
  onLoad: function() {
    if (!$('.template-faq').length) {
      return
    }
    const $clear_input = $('#clear-input')
    const $results_faq = $('#results-faq')
    this.radical_Search_FAQ( $('#search-faq').val() )
    $clear_input.on('click', function() {
      TemplateFAQ.radical_Clear_Search_FAQ()
    });
    $('#search-faq').on('keyup', function() {
      if ( TemplateFAQ.radical_Search_FAQ($(this).val()) ) {
        $clear_input.show()
        $results_faq.hide()
      } else {
        if ( $(this).val() ) {
          $clear_input.show()
          $results_faq.show()
        } else {
          $clear_input.hide()
          $results_faq.hide()
        }
      }
    });
  },
  radical_Search_FAQ: ( searchVal ) => {
    if (!searchVal) {
      // searchVal is empty
      $('#clear-input').show()
      $('#faq-accordion .accordion-item').show()
      $('#results-faq').hide()
      return false;
    }
    let matchFound = false
    $('#faq-accordion .accordion-item').each(function() {
      const itemTitle = $(this).find('.accordion-item_title').text()
      const itemBody = $(this).find('.accordion-item_body').text().toLowerCase()
      if (itemTitle.toLowerCase().indexOf(searchVal.toLowerCase()) > -1 || itemBody.indexOf(searchVal) > -1) {
        $(this).find('.accordion-item_title').html(TemplateFAQ.highlight_Match(searchVal, itemTitle))
        $(this).show()
        matchFound = true
      } else {
        $(this).hide()
      }
    })
    return matchFound
  },
  highlight_Match: ( needle, haystack ) => {
    const reg = new RegExp('(' + needle + ')', 'gi')
    return haystack.replace(reg, '<span class="highlight">$1</span>')
  },
  radical_Clear_Search_FAQ: () => {
    $('#search-faq').val('')
    $('#results-faq, #clear-input').hide()
    $('#faq-accordion .accordion-item').show()
    $('#search-faq').parent().find('label').removeClass('focused')
  },
}
