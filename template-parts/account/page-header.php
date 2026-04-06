
<?php
$site_url = get_site_url();
$uri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$uri = str_replace($site_url, '', $uri);
?>
<?php if (str_contains($uri, '/loyalty_reward')) : ?>
  <section id="account-page-header" class="page-header mb-4">
    <div class="container">
      <div class="row">
        <div class="col px-0">
          <img src="https://radicalskincare.com/wp-content/uploads/2024/01/Screenshot-2024-01-18-at-9.51.56-AM.png" alt="Account Page Header" class="w-100"/>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>
