
const TemplateHome = {
  onLoad: function() {
    if (!$('.template-home').length) {
      return
    }
    this.hero()
    this.imagesCarousel()
    this.productsTagFilters()
  },
  hero: function () {
    const $home_hero = $('#home-hero');
    if ($('.home-hero_item').length <= 1) {
      return
    }
    $home_hero.slick({
      dots: true,
      fade: true,
      speed: 500,
      infinite: true,
      lazyLoad: 'ondemand',
      prevArrow: $('.home-hero_left-action'),
      nextArrow: $('.home-hero_right-action'),
    })
    $home_hero.on('beforeChange', function(event, slick, currentSlide, nextSlide){
      if ($(slick.$slides[nextSlide]).find('.social-icons-color_black').length) {
        $home_hero.find('.slick-dots').addClass('dark-btn')
      } else {
        $home_hero.find('.slick-dots').removeClass('dark-btn')
      }
    });
    $(window).on('resize', function(){
      TemplateHome.heroResponsive()
    })
    this.heroResponsive()
  },
  heroResponsive: function(){
    $('#home-hero .home-hero_item').map( (_,e) => {
      const $e = $(e)
      const mobileImageSrc = $e.data('mobileImage')
      const desktopImageSrc = $e.data('desktopImage')
      if (mobileImageSrc && desktopImageSrc) {
        if (window.innerWidth > 991) {
          $e.css('background-image', `url("${desktopImageSrc}")`)
        } else {
          $e.css('background-image', `url("${mobileImageSrc}")`)
        }
      }
    })
  },
  imagesCarousel: function() {
    const $image_carousel = $('#image-carousel')
    $image_carousel.find('.slick-carousel').slick({
      infinite: true,
      slidesToShow: 4,
      slidesToScroll: 1,
      prevArrow: `
        <button class="slick-prev slick-arrow" aria-label="Previous" type="button">
          <i class="fa fa-angle-left" aria-hidden="true"></i>
        </button>`,
      nextArrow: `
        <button class="slick-next slick-arrow" aria-label="Next" type="button">
          <i class="fa fa-angle-right" aria-hidden="true"></i>
        </button>`,
      responsive: [
        {
          breakpoint: 992,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2,
            infinite: true,
          },
        },
      ],
    })
    $image_carousel.show()
  },
  productsTagFilters: function() {
    const $tagFilterLabel = $('.products_tag-filters label')
    if (!$tagFilterLabel.length) {
      return
    }
    $('.best-seller-heading').addClass('d-none')
    $('.products_tag-filters .active').map((_,e) => {
      const forAttr = $(e).attr('for')
      if($(`.${forAttr}:not(.no-products)`).length) {
        $('.best-seller-heading').removeClass('d-none')
      }
    })
    $tagFilterLabel.on('click', function(e) {
      if ($(e.currentTarget).hasClass('active')) {
        return
      }
      $tagFilterLabel.removeClass('active')
      $(e.currentTarget).addClass('active')
      $('.products-container').addClass('d-none')
      const forAttr = $(e.currentTarget).attr('for')
      $(`.${forAttr}`).removeClass('d-none')
      if(!$(`.${forAttr}:not(.no-products)`).length) {
        $('.best-seller-heading').addClass('d-none')
      } else {
        $('.best-seller-heading').removeClass('d-none')
      }
    })
  },
}
