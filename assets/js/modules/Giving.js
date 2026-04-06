
const Giving = {
  onLoad: function() {
    $('#radical-giving-feat-video').on('click', function() {
      $(this).hide();
      let iframe_src = $('#radical-giving-feat-video-wrap iframe').attr('src');
      iframe_src += '&autoplay=1';
      $('#radical-giving-feat-video-wrap iframe').attr('src', iframe_src).css('visibility', 'hidden');
      $('#radical-giving-feat-video-wrap').show();
      setTimeout(function() {
        $('#radical-giving-feat-video-wrap iframe').css('visibility', 'visible');
      }, 250);
    });
  },
};
