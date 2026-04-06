
<?php
$site_url = get_site_url();
$uri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$uri = str_replace($site_url, '', $uri);
?>
<section id="account-cta">
  <div class="container bg-lightestgray py-5 rounded">
    <div class="row justify-content-center">
      <div class="col-lg-6 text-center">
        <?php if (str_contains($uri, '/loyalty_reward')) : ?>
          <a href="<?php echo get_site_url(); ?>/rewards" class="btn btn-darkergray">Learn More</a>
        <?php else : ?>
          <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="btn btn-darkergray">Shop Products</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
