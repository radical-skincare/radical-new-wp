<?php if ($intro = get_field('intro')) : ?>
  <?php
  $desc = $intro['description'];
  $video_id = $intro['video_id'];
  ?>
  <section class="intro py-5">
    <div class="container position-relative bg-lightestgray py-5 rounded mt-4">
      <div class="d-block border border-1 border-dark my-4 mr-auto ml-auto vertical-line"></div>
      <div class="row d-flex justify-content-center">
        <div class="col-lg-8 py-4 text-center">
          <?php if ($heading = $intro['heading']) : ?>
            <p class="ff-orpheus fs-1.5x <?php echo !$desc ? 'mb-0' : ''; ?>">
              <?php echo $heading; ?>
            </p>
          <?php endif; ?>
          <?php if ($desc) : ?>
            <p class="mb-<?php echo $video_id ? '3' : '0'; ?>">
              <?php echo $desc; ?>
            </p>
          <?php endif; ?>
          <?php if ($video_id) : ?>
            <button type="button" class="link-underline link-underline_darker-gray" data-toggle="modal" data-target="#playVideoModal">
              <i class="fa fa-play" aria-hidden="true"></i> Learn More
            </button>
            <div class="modal fade" id="playVideoModal" tabindex="-1" role="dialog" aria-labelledby="playVideoModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-body">
                    <div class="embed-responsive embed-responsive-16by9">
                      <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo esc_attr($video_id); ?>" allowfullscreen></iframe>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <script>
            (function($) {
            const original_video_src = $('#playVideoModal .embed-responsive-item').attr('src')
            $('#playVideoModal').on('show.bs.modal', function (e) {
              $('#playVideoModal .embed-responsive-item').attr('src', original_video_src + '?rel=0&autoplay=1')
            })
            $('#playVideoModal').on('hide.bs.modal', function (e) {
              $('#playVideoModal .embed-responsive-item').attr('src', original_video_src)
            })
            })(jQuery)
            </script>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>
