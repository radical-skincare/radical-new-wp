/**
 * Radical Skincare — Main JS
 * Plain jQuery, no bundler. All modules loaded via wp_enqueue_script.
 */
jQuery(document).ready(function($) {

  // Smooth scroll
  if (typeof SmoothScroll !== 'undefined') {
    new SmoothScroll('a[href*="#"]', {
      header: '#main-header',
      offset: function() { return 64; }
    });
  }

  // Initialize all modules
  if (typeof Global !== 'undefined')                Global.onLoad();
  if (typeof Header !== 'undefined')                Header.onLoad();
  if (typeof Product !== 'undefined')               Product.onLoad();
  if (typeof Search !== 'undefined')                Search.onLoad();
  if (typeof Favorites !== 'undefined')             Favorites.onLoad();
  if (typeof Login !== 'undefined')                 Login.onLoad();
  if (typeof PageHero !== 'undefined')              PageHero.onLoad();
  if (typeof Sale !== 'undefined')                  Sale.onLoad();
  if (typeof SkinCareAddition !== 'undefined')      SkinCareAddition.onLoad();
  if (typeof RefillAddToCart !== 'undefined')        RefillAddToCart.onLoad();
  if (typeof BrandPartner !== 'undefined')          BrandPartner.onLoad();
  if (typeof AmbassadorEnrollment !== 'undefined')  AmbassadorEnrollment.onLoad();
  if (typeof ArchivePodcasts !== 'undefined')       ArchivePodcasts.onLoad();
  if (typeof Giving !== 'undefined')                Giving.onLoad();
  if (typeof TemplateHome !== 'undefined')          TemplateHome.onLoad();
  if (typeof TemplatePress !== 'undefined')         TemplatePress.onLoad();
  if (typeof TemplateFAQ !== 'undefined')           TemplateFAQ.onLoad();
  if (typeof MyAccount !== 'undefined')             MyAccount.onLoad();
  if (typeof Form !== 'undefined')                  Form.onLoad();
  if (typeof ArchiveProducts !== 'undefined')       ArchiveProducts.onLoad();
  if (typeof TemplateTrylacel !== 'undefined')      TemplateTrylacel.onLoad();
  if (typeof EmailSubscribe !== 'undefined')        EmailSubscribe.onLoad();
  if (typeof WoocommerceSubscription !== 'undefined') WoocommerceSubscription.onLoad();
  if (typeof ProductReviewModel !== 'undefined')    ProductReviewModel.onLoad();
  if (typeof ProductPurchaseOptions !== 'undefined') ProductPurchaseOptions.onLoad();

});
