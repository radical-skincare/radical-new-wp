<?php
$settings_announcements = get_field('announcements', 'option');
$announcements = $settings_announcements ? $settings_announcements : [];
/*
$announcements[] = [
  'text' => 'Free Shipping on Orders Over <span class="woocommerce-Price-currencySymbol">' . get_woocommerce_currency_symbol() . '</span> 85 | <a href="' . get_site_url() . '/frequently-asked-questions/?search=shipping" class="text-white">See Details</a>'
];
*/
?>
<?php if ($announcements) : ?>
  <?php
  $announcements_count = count($announcements);
  ?>
  <div class="main-header_top-navbar">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg text-center">
          <div id="topNavAnnouncements" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
              <?php foreach ($announcements as $key => $announcement) : ?>
                <div class="carousel-item <?php echo !$key ? 'active' : ''; ?>">
                  <?php echo $announcement['text']; ?>
                </div>
              <?php endforeach; ?>
            </div>
            <?php if ($announcements_count > 1) : ?>
              <a class="carousel-control-prev" href="#topNavAnnouncements" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
              </a>
              <a class="carousel-control-next" href="#topNavAnnouncements" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
