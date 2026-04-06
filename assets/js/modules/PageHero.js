
const PageHero = {
  onLoad: function() {
    if (!$('.hero_image-right').length) {
      return
    }
    // TODO: This should be in a layout VideoModal.js file so can be used everywhere
    if ($('#heroVideoModal').length) {
      this.onVideoModalShow()
      this.onVideoModalHide()
    }
  },
  onVideoModalShow: function() {
    $('.modal_video').on('show.bs.modal', function (e) {
      // then make video autoplay
      const modal_video_iframe = $('#' + e.target.id).find('iframe')
      let video_src = modal_video_iframe.attr('data-src')
      video_src += '?rel=0&autoplay=1'
      modal_video_iframe.attr('src', video_src)
    })
  },
  onVideoModalHide: function() {
    $('.modal_video').on('hide.bs.modal', function (e) {
      // then pause video
      const modal_video_iframe = $('#' + e.target.id).find('iframe')
      modal_video_iframe.attr('src', '')
    })
  },
}
