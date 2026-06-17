<?php
$site_url = get_site_url();
$template_directory_uri = get_template_directory_uri();
$site_name = get_bloginfo('name');
?>
<style>
.main-footer .footer_brand-logo {
  height: 4rem;
  object-fit: contain;
  margin-bottom: 1rem;
}
</style>
<footer class="main-footer py-5">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 sidebar-footer-container d-flex justify-content-between">
        <?php dynamic_sidebar('sidebar-footer'); ?>
      </div>
      <div class="col-lg-5 offset-lg-1 text-center text-lg-right text-dark-gray">
        <a href="<?php echo esc_url($site_url); ?>">
          <img src="<?php echo esc_url($template_directory_uri . '/assets/images/logo/radicalskincare-logo.svg'); ?>" alt="<?php echo esc_attr($site_name); ?>" class="footer_brand-logo"/>
        </a>
        <hr class="d-lg-none mb-4"/>
        <p class="mb-1">Radical Newsletter Subscription</p>
        <p class="fs-0.75x mb-3 mb-lg-1">Get instant updates about our new products and special offers.</p>
        <?php get_template_part('template-parts/footer/klaviyo'); ?>
      </div>
    </div>
    <hr class="d-none d-lg-block mb-4">
    <div class="row">
      <div class=" d-flex justify-content-center justify-content-lg-start align-content-center col-lg-4 main-footer_social-icons mb-4 mb-lg-0">
        <a href="https://www.facebook.com/radicalskincare/" class="social-link" target="_blank">
          <i class="fa fa-facebook" aria-hidden="true"></i>
          <span class="sr-only">Facebook</span>
        </a>
        <a href="https://www.instagram.com/radicalskincare/" class="social-link" target="_blank">
          <i class="fa fa-instagram" aria-hidden="true"></i>
          <span class="sr-only">Instagram</span>
        </a>
        <a href="https://www.youtube.com/channel/UC3ox2qF9xqJ6NJMIOTAqKFw" class="social-link" target="_blank">
          <i class="fa fa-youtube" aria-hidden="true"></i>
          <span class="sr-only">YouTube</span>
        </a>
        <a href="https://www.tiktok.com/@radicalskincare" class="social-link" target="_blank">
          <svg fill="#fff" width="18" height="13" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" xml:space="preserve">
            <path d="M19.589 6.686a4.793 4.793 0 0 1-3.77-4.245V2h-3.445v13.672a2.896 2.896 0 0 1-5.201 1.743l-.002-.001.002.001a2.895 2.895 0 0 1 3.183-4.51v-3.5a6.329 6.329 0 0 0-5.394 10.692 6.33 6.33 0 0 0 10.857-4.424V8.687a8.182 8.182 0 0 0 4.773 1.526V6.79a4.831 4.831 0 0 1-1.003-.104z"/>
          </svg>
          <span class="sr-only">TikTok</span>
        </a>
      </div>
      <div class="col-lg-4 order-3 order-lg-2 align-items-center text-center mb-4 mb-lg-0">
        <small class="text-gray">
          &copy; <?php echo date('Y'); ?> Radical Skincare | All Rights reserved
        </small>
      </div>
      <div class="col-lg-4 order-2 mb-4 mb-lg-0">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/payment-icons.svg'); ?>" alt="Payment Icons" style="height: 22px;" class="d-block mr-auto mr-lg-0 ml-auto"/>
      </div>
    </div>
  </div>
</footer>
