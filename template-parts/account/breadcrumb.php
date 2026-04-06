
<?php
$site_url = get_site_url();
$uri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$uri = str_replace($site_url, '', $uri);
$uri_ar = [];
foreach (explode('/', $uri) as $key => $value) {
  if (!$value) {
    continue;
  }
  if (($have_get_param = strpos($value, "?")) !== false) {
    continue;
  }
  $uri_ar[] = $value;
}
$is_subscription_edit_address = (str_contains($uri, '/edit-address') && isset($_GET['subscription']));
?>
<?php if (is_user_logged_in()) : ?>
  <section id="account-breadcrumb" class="account-breadcrumb bg-lightestgray py-3 mb-3">
    <nav class="woocommerce-breadcrumb container">
      <a href="<?php echo esc_html($site_url); ?>/account">Dashboard</a>
      <?php if (str_contains($uri, '/brand-partner-dashboard')) : ?>
        &nbsp;/&nbsp;Brand Partner
      <?php elseif (str_contains($uri, '/brand-partner-leaderboard')) : ?>
        &nbsp;/&nbsp;<a href="<?php echo esc_html($site_url); ?>/account/brand-partner-dashboard">Brand Partner</a>&nbsp;/&nbsp;Leaderboards
      <?php elseif (str_contains($uri, '/brand-partner-resources')) : ?>
        &nbsp;/&nbsp;<a href="<?php echo esc_html($site_url); ?>/account/brand-partner-dashboard">Brand Partner</a>&nbsp;/&nbsp;Resources
      <?php elseif (str_contains($uri, '/brand-partner-customers')) : ?>
        &nbsp;/&nbsp;<a href="<?php echo esc_html($site_url); ?>/account/brand-partner-dashboard">Brand Partner</a>&nbsp;/&nbsp;Customers
      <?php elseif (str_contains($uri, '/orders')) : ?>
        &nbsp;/&nbsp;My Orders
      <?php elseif (str_contains($uri, '/subscriptions')) : ?>
        &nbsp;/&nbsp;My Subscriptions
      <?php elseif (str_contains($uri, '/downloads')) : ?>
        &nbsp;/&nbsp;Downloads
      <?php elseif (str_contains($uri, '/edit-address') && !$is_subscription_edit_address) : ?>
        &nbsp;/&nbsp;Addresses
      <?php elseif (str_contains($uri, '/payment-methods')) : ?>
        &nbsp;/&nbsp;Payment Methods
      <?php elseif (str_contains($uri, '/edit-account')) : ?>
        &nbsp;/&nbsp;Account Details
      <?php elseif (str_contains($uri, '/add-payment-method')) : ?>
        &nbsp;/&nbsp;<a href="<?php echo esc_html($site_url); ?>/account/payment-methods">Payment methods</a>
      <?php endif; ?>
      <?php if (str_contains($uri, 'view-order')) : ?>
        &nbsp;/&nbsp;<a href="<?php echo esc_html($site_url); ?>/account/orders/">My Orders</a>
        <?php if ($uri_ar) : ?>
          &nbsp;/&nbsp;Order #<?php echo esc_html($uri_ar[count($uri_ar)-1]); ?>
        <?php endif; ?>
      <?php elseif (str_contains($uri, 'view-subscription') || $is_subscription_edit_address) : ?>
        &nbsp;/&nbsp;<a href="<?php echo esc_html($site_url); ?>/account/subscriptions/">My Subscriptions</a>
        <?php if ($is_subscription_edit_address) : ?>
          &nbsp;/&nbsp;<a href="<?php echo esc_html($site_url); ?>/account/view-subscription/<?php echo esc_html($_GET['subscription']); ?>">Subscription #<?php echo esc_html($_GET['subscription']); ?></a>
          &nbsp;/&nbsp;Addresses
        <?php elseif ($uri_ar) : ?>
          &nbsp;/&nbsp;Subscription #<?php echo esc_html($uri_ar[count($uri_ar)-1]); ?>
        <?php endif; ?>
      <?php elseif (str_contains($uri, '/add-payment-method')) : ?>
        <?php if ($uri_ar) : ?>
          &nbsp;/&nbsp;Add Payment Method
        <?php endif; ?>
      <?php endif; ?>
    </nav>
  </section>
<?php endif; ?>
