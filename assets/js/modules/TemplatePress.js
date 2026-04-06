/* global ThemeSettings */
const TemplatePress = {
  onLoad: function() {
    if (!$('.template-press').length) {
      return
    }
    this.load()
  },
  load: function() {
    const self = this
    $('#load-more').on('click', function() {
      self.onLoadMoreClick()
    })
    $('#press-model').on('show.bs.modal', function (event) {
      self.onPressModelShow(event, $(this))
    })
  },
  onLoadMoreClick: function() {
    this.showLoader(true)
    const $loadMore = $('#load-more')
    const $pressContainer = $('#press-container')
    const per_page = $loadMore.data('post_per_page')
    let page = $loadMore.data('current_page')
    const quote_image_src = $loadMore.data('quote_image_src')
    page++
    $loadMore.attr('disabled', 'disabled')
    $.ajax({
      url: ThemeSettings.site_url + '/wp-json/wp/v2/press_item?per_page=' + per_page + '&status=publish&page=' + page,
    }).done(function( pressItems ) {
      TemplatePress.showLoader(false)
      if (!pressItems) {
        $loadMore.addClass('d-none')
        return
      }
      $loadMore.removeAttr('disabled')
      $loadMore.data('current_page', page)
      pressItems.forEach(pressItem => {
        $pressContainer.append(`
          <div class="col-12 col-lg-4 mb-4">
            <div class="h-100 card border-0">
              <div class="card-body d-flex flex-column justify-content-between">
                <h4 class="card-title">${pressItem.title.rendered}</h4>
                <div>
                  <img src="${quote_image_src}" alt="Quote" class="blockquote_img mb-3" style="height: 28px;"/>
                  <blockquote>${pressItem.content.rendered}</blockquote>
                </div>
                <div>
                  <a type="button" data-toggle="modal" data-target="#press-model" data-image_url="${pressItem.radical_skincare_additional_meta.thumbnail}" data-image_alt="${pressItem.title.rendered}" class="link-underline link-underline_darker-gray">
                    View Press
                  </a>
                </div>
              </div>
            </div>
          </div>`)
      });
    });
  },
  showLoader: function(show) {
    if (show) {
      $('.loader').removeClass('d-none')
    } else {
      $('.loader').addClass('d-none')
    }
  },
  onPressModelShow: function (event, modal) {
    const button = $(event.relatedTarget)
    const image_url = button.data('image_url')
    const image_alt = button.data('image_alt')
    modal.find('img').attr('src', image_url)
    modal.find('img').attr('alt', image_alt)
  },
}
