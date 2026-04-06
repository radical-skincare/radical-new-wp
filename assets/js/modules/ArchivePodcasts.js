
const ArchivePodcasts = {
  onLoad: function () {
    if (!$('.post-type-archive-podcasts').length) {
      return
    }
    this.onWindowResize()
    this.initFeaturedPodcasts()
    this.initPodcastFilter()
    this.onClickPodcastItem()
    this.setupAddToCalDropdown()
    this.selectMainPodcastCard()
    this.setUpPagination()
  },
  onWindowResize: function() {
    $(window).on('resize', function() {
      setTimeout( () => {
        $('#featured-podcasts-carousel-wrap').css('visibility', 'hidden')
        $('#featured-podcasts-carousel').owlCarousel('destroy')
        ArchivePodcasts.initFeaturedPodcasts()
      }, 500)
    })
  },
  initFeaturedPodcasts: function () {
    $('#featured-podcasts-carousel').owlCarousel({
      responsiveClass: true,
      nav: true,
      navText: ['<i class="feature-slider-button fa fa-angle-left"></i>', '<i class="feature-slider-button fa fa-angle-right"></i>'],
      responsive: {
        0: {
          items: 1.5,
          dots: true,
        },
        768: {
          items: 2.5,
          dots: true,
        },
        1200: {
          items: 3.5,
          dots: true,
        },
      },
      dots: true,
      loop: true,
    })
    this.setFeaturedPodcastsPositionLeft()
    $('#featured-podcasts-carousel-wrap').show()
    setTimeout( () => {
      $('#featured-podcasts-carousel-wrap').css('visibility', 'visible')
    }, 500)
  },
  setFeaturedPodcastsPositionLeft: function() {
    const container_width = $('#featured-podcast-section .container').width()
    const window_width = $(window).width()
    $('#featured-podcasts-carousel-wrap').css('left', (window_width - container_width)/2)
  },
  initPodcastFilter: function () {
    $('#podcast-listing-type').on('change', function () {
      $('#podcasts-left-sidebar_loader-wrap').css('display', 'flex')
      $('#podcasts-left-sidebar_listing').attr('offset', 0)
      ArchivePodcasts.onGetPodcasts()
    })
  },
  onGetPodcasts: function (add = false) {
    const $podcasts_left_sidebar_listing = $('#podcasts-left-sidebar_listing')
    const post_per_page = $podcasts_left_sidebar_listing.attr('post-per-page')
    const offset = $podcasts_left_sidebar_listing.attr('offset')
    const listing_type = $('#podcast-listing-type :selected').val()
    this.getPodcasts(post_per_page, offset).then(function(res) {
      res = JSON.parse(res)
      if (res.success) {
        let new_podcasts_html = ''
        if (res.podcasts.length) {
          res.podcasts.forEach( (podcast) => {
            let new_podcast_html = '<div class="d-flex card card-podcast mb-3 card-podcast-item" id="card-podcast" post-id=' + podcast.ID + '>\
              <div class="card-body">\
                <div class="row mx-0">\
                  <div class="col-4 card-podcast_play-col d-flex align-items-center justify-content-center">\
                    <div class="card-podcast_play py-3 mb-0">\
                      <i class="card-podcast_play-icon fa fa-play" aria-hidden="true"></i>\
                      <span class="sr-only">Play</span>\
                    </div>\
                  </div>\
                  <div class="d-flex col-8 card-podcast_title-col align-items-center">\
                    <div>\
                      <h3 class="card-podcast-title mb-1">\
                        ' + podcast.post_title + '\
                      </h3>\
                      <div class="card-podcast_excerpt">' + podcast.post_excerpt + '</div>\
                    </div>\
                  </div>\
                </div>\
              </div>\
            </div>'
            new_podcasts_html += new_podcast_html
          })
          if (add) {
            $podcasts_left_sidebar_listing.append(new_podcasts_html)
          } else {
            $podcasts_left_sidebar_listing.html(new_podcasts_html)
          }
          $('#load_more_podcasts').remove()
          if (res.total_podcasts > $('#podcasts-left-sidebar_listing .card-podcast-item').length){
            $podcasts_left_sidebar_listing.append('<a href="javascript:void(0)" id="load_more_podcasts" class="d-block link-underline link-underline_darker-gray mx-auto" style="width: fit-content;">Load More</a>')
            ArchivePodcasts.setUpPagination()
          }
          ArchivePodcasts.selectMainPodcastCard()
        } else {
          $podcasts_left_sidebar_listing.html('\
            <div class="alert alert-info" role="alert">\
              <i class="fa fa-info-circle mr-3" aria-hidden="true"></i>No ' + listing_type + ' podcasts found.\
            </div>')
        }
        $('#podcasts-left-sidebar_loader-wrap').hide()
      } else {
        console.error(res, 'Something went wrong.')
        $podcasts_left_sidebar_listing.html('\
          <div class="alert alert-danger" role="alert">\
            <i class="fa fa-exclamation-triangle mr-3" aria-hidden="true"></i>Something went wrong.\
          </div>')
      }
    }).catch(function(err) {
      console.error(err, 'Something went wrong.')
      $podcasts_left_sidebar_listing.html('\
        <div class="alert alert-danger" role="alert">\
          <i class="fa fa-exclamation-triangle mr-3" aria-hidden="true"></i>Something went wrong.\
        </div>')
    })
  },
  getPodcasts: function (post_per_page = false, offset = false) {
    return new Promise( (resolve, reject) => {
      $.ajax({
        url: ThemeSettings.adminAjax,
        data: {
          'action': 'get_podcasts',
          'listing_type': $('#podcast-listing-type :selected').val(),
          'posts_per_page': post_per_page,
          'offset': offset,
        },
        type: 'POST',
        config: { headers: {'Content-Type': 'multipart/form-data' }},
      }).done(function(res) {
        resolve(res)
      }).fail(function(err) {
        reject(err)
      })
    })
  },
  getPodcast: function (id) {
    return new Promise( (resolve, reject) => {
      $.ajax({
        url: ThemeSettings.adminAjax,
        data: {
          'action': 'get_podcast',
          'id': id,
        },
        type: 'POST',
        config: { headers: {'Content-Type': 'multipart/form-data' }},
      }).done(function(res) {
        resolve(res)
      }).fail(function(err) {
        reject(err)
      })
    })
  },
  onClickPodcastItem: function() {
    const self = this
    $(document).on('click', '.card-podcast-item', function() {
      if ($(this).hasClass('active')) {
        return
      }
      $('.card-podcast-item').removeClass('active')
      $(this).addClass('active')
      self.toggleMainPodcastLoader(true)
      self.getPodcast($(this).attr('post-id')).then(function (podcast) {
        podcast = JSON.parse(podcast)
        podcast = podcast.podcast
        const today = new Date()
        const year = today.getFullYear()
        const month = `${today.getMonth() + 1}`.padStart(2, '0')
        const day = `${today.getDate()}`.padStart(2, '0')
        const stringDate = [year, month, day].join('')
        if (podcast.meta.start_date) {
          podcast.meta.start_date = podcast.meta.start_date[0]
        }
        if (podcast.meta.end_date) {
          podcast.meta.end_date = podcast.meta.end_date[0]
        }
        if (podcast.meta.start_time) {
          podcast.meta.start_time = podcast.meta.start_time[0]
        }
        if (podcast.meta.end_time) {
          podcast.meta.end_time = podcast.meta.end_time[0]
        }
        if (podcast.meta.all_day) {
          podcast.meta.all_day = podcast.meta.all_day[0]
        }
        if (podcast.meta.event_link) {
          podcast.meta.event_link = podcast.meta.event_link[0]
        }
        let main_podcast = ''
        if (podcast.meta.embed_player && podcast.meta.embed_player !== '' && podcast.meta.embed_player[0] !== '') {
          main_podcast += '<div class="embed-player-wrap">' + podcast.meta.embed_player + '</div>'
        } else if (podcast.feat_img_url) {
          main_podcast += '<img src="' + (podcast.feat_img_url) + '" alt="' + (podcast.post_title) + '" class="card-img"/>'
        }
        main_podcast += '\
          <div class="card-body" main-post-id="' + podcast.ID + '">\
            <div class="row align-items-center">\
              <div class="col-sm-8 mb-3 mb-sm-0">\
                <h3 class="mb-0">\
                ' + (podcast.post_title) + '\
                </h3>\
              </div>\
              <div class="col-sm-4 text-sm-right">'
              podcast.meta.enable_button = parseInt(((podcast.meta.button_enabled && podcast.meta.button_enabled.length) ? podcast.meta.button_enabled[0] : 0))
                if((podcast.meta.enable_button || podcast.meta.event_link) && (podcast.meta.start_date <= stringDate)) {
                  main_podcast += '<a href="' + ((podcast.meta.button_link && podcast.meta.enable_button) ? podcast.meta.button_link : podcast.meta.event_link ) + '" class="btn btn-pink" target="_blank">Listen </a>'
                }
              main_podcast += '</div>\
            </div>\
            <hr/>'
            main_podcast += '<div>' + (podcast.post_content) + '</div>\
          </div>'
        $('#main-podcast-content').html(main_podcast)
        self.toggleMainPodcastLoader(false)
        self.setupAddToCalDropdown()
      })
    })
  },
  toggleMainPodcastLoader: function( show = true ) {
    const display = (show) ? 'flex' : 'none'
    $('#main-podcast_loader-wrap').css('display', display)
  },
  setupAddToCalDropdown: function() {
    if (!$('#addToCalDropdown').length){
      return
    }
    const $addToCalDropdown = $('#addToCalDropdown')
    let config = {
      title: $addToCalDropdown.data('title'),
      location: $addToCalDropdown.data('link'),
      description: $addToCalDropdown.data('excerpt'),
      start: new Date($addToCalDropdown.data('start-date')),
    }
    if ($addToCalDropdown.data('end-date')) {
      config.end = new Date($addToCalDropdown.data('end-date'))
    }
    if ($addToCalDropdown.data('start-time')) {
      config.start = new Date($addToCalDropdown.data('start-date')+' '+$addToCalDropdown.data('start-time'))
    }
    if ($addToCalDropdown.data('end-time')) {
      config.end = new Date($addToCalDropdown.data('end-date')+' '+$addToCalDropdown.data('end-time'))
    }
    // Note: GoogleCalendar, OutlookCalendar, YahooCalendar, ICalendar from 'datebook' must be loaded globally
    if (typeof GoogleCalendar !== 'undefined') {
      const googleCalendar = new GoogleCalendar(config)
      const outlookCalendar = new OutlookCalendar(config)
      const yahooCalendar = new YahooCalendar(config)
      const iCalendar = new ICalendar(config)
      $('[aria-labelledby="addToCalDropdown"] .google').attr('href', googleCalendar.render())
      $('[aria-labelledby="addToCalDropdown"] .outlook').attr('href', outlookCalendar.render())
      outlookCalendar.setHost('outlook.office.com')
      $('[aria-labelledby="addToCalDropdown"] .office365').attr('href', outlookCalendar.render())
      $('[aria-labelledby="addToCalDropdown"] .yahoo').attr('href', yahooCalendar.render())
      $('[aria-labelledby="addToCalDropdown"] .apple').on('click', function(){
        iCalendar.download($addToCalDropdown.data('title') + '.ics')
      })
    }
  },
  selectMainPodcastCard: function() {
    const main_post_id = $('#main-podcast-content .card-body').attr('main-post-id')
    if (!main_post_id) {
      return
    }
    $('.card-podcast-item').removeClass('active')
    $('.card-podcast-item[post-id="' + main_post_id + '"]').addClass('active')
  },
  setUpPagination: function() {
    $('#load_more_podcasts').on('click', function() {
      $('#podcasts-left-sidebar_listing').attr('offset', $('#podcasts-left-sidebar_listing .card-podcast-item').length)
      ArchivePodcasts.onGetPodcasts(true)
      $('#podcasts-left-sidebar_loader-wrap').css('display', 'flex')
      $('#load_more_podcasts').html('Loading...')
    })
  },
}
