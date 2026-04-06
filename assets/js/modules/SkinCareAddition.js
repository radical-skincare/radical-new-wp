
const SkinCareAddition = {
  onLoad: function() {
    if (!$('.template-radical-skincare-addition').length) {
      return
    }
    this.load()
  },
  load: function() {
    this.carouselFuncinality();
  },
  carouselFuncinality: function() {
    $('.hero-slider').slick({
      lazyLoad: 'ondemand',
      dots: true,
      adaptiveHeight: false,
      prevArrow: $('.left-slider-action'),
      nextArrow: $('.right-slider-action'),
    });
  },
}
