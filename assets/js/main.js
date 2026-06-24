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

  // Initialize all modules. Each call is wrapped in its own try/catch so that
  // one module throwing an error can't prevent every module after it in this
  // list from ever initializing (there's no bundler/module isolation here —
  // all modules share this one sequential dispatch, and module objects are
  // declared with `const`, so they can't be looked up via window[name]).
  function safeInit(name, module) {
    if (typeof module === 'undefined') {
      return;
    }
    try {
      module.onLoad();
    } catch (e) {
      console.error('[main.js] ' + name + '.onLoad() threw an error:', e);
    }
  }

  if (typeof Global !== 'undefined') safeInit('Global', Global);
  if (typeof Header !== 'undefined') safeInit('Header', Header);
  if (typeof Product !== 'undefined') safeInit('Product', Product);
  if (typeof Search !== 'undefined') safeInit('Search', Search);
  if (typeof Favorites !== 'undefined') safeInit('Favorites', Favorites);
  if (typeof Login !== 'undefined') safeInit('Login', Login);
  if (typeof PageHero !== 'undefined') safeInit('PageHero', PageHero);
  if (typeof Sale !== 'undefined') safeInit('Sale', Sale);
  if (typeof SkinCareAddition !== 'undefined') safeInit('SkinCareAddition', SkinCareAddition);
  if (typeof RefillAddToCart !== 'undefined') safeInit('RefillAddToCart', RefillAddToCart);
  if (typeof BrandPartner !== 'undefined') safeInit('BrandPartner', BrandPartner);
  if (typeof AmbassadorEnrollment !== 'undefined') safeInit('AmbassadorEnrollment', AmbassadorEnrollment);
  if (typeof ArchivePodcasts !== 'undefined') safeInit('ArchivePodcasts', ArchivePodcasts);
  if (typeof Giving !== 'undefined') safeInit('Giving', Giving);
  if (typeof TemplateHome !== 'undefined') safeInit('TemplateHome', TemplateHome);
  if (typeof TemplatePress !== 'undefined') safeInit('TemplatePress', TemplatePress);
  if (typeof TemplateFAQ !== 'undefined') safeInit('TemplateFAQ', TemplateFAQ);
  if (typeof MyAccount !== 'undefined') safeInit('MyAccount', MyAccount);
  if (typeof Form !== 'undefined') safeInit('Form', Form);
  if (typeof ArchiveProducts !== 'undefined') safeInit('ArchiveProducts', ArchiveProducts);
  if (typeof TemplateTrylacel !== 'undefined') safeInit('TemplateTrylacel', TemplateTrylacel);
  if (typeof EmailSubscribe !== 'undefined') safeInit('EmailSubscribe', EmailSubscribe);
  if (typeof WoocommerceSubscription !== 'undefined') safeInit('WoocommerceSubscription', WoocommerceSubscription);
  if (typeof ProductReviewModel !== 'undefined') safeInit('ProductReviewModel', ProductReviewModel);
  if (typeof ProductPurchaseOptions !== 'undefined') safeInit('ProductPurchaseOptions', ProductPurchaseOptions);

});
