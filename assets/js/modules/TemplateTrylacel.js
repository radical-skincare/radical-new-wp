const TemplateTrylacel = {
  onLoad: function() {
    if (!$('.template-trylacel').length) {
      return
    }
    // Handled by PageHero.js now - justin@justinestrada.com
    // this.onClickPlay()
    // this.onModalDismiss()
  },
  /*
  onClickPlay: function() {
    $('.learn-more-btn').on('click', function() {
      $('#trylacelModal').find('.embed-responsive-item').attr('src', 'https://www.youtube.com/embed/rVhRDFcS2u4?rel=0&amp;autoplay=1')
      jQuery('#trylacelModal').modal('show')
    });
  },
  onModalDismiss: function() {
    // on modal dismiss pause video
    $('#trylacelModal').on('hide.bs.modal', function() {
      $('#trylacelModal').find('.embed-responsive-item').attr('src', '')
    })
    $('#trylacelModal').on('hidden.bs.modal', function() {
      $('#trylacelModal').find('.embed-responsive-item').attr('src', '')
    })
  },
  */
}
