<?php
global $template;
$site_url = get_site_url();
$template_directory_uri = get_template_directory_uri();
$site_name = get_bloginfo('name', 'display');
$cart_count = ( function_exists( 'WC' ) && WC()->cart != null ) ? WC()->cart->get_cart_contents_count() : false;
$is_user_logged_in = is_user_logged_in();
$current_user_id = ($is_user_logged_in) ? get_current_user_id() : false;
$current_user = ($is_user_logged_in) ? wp_get_current_user() : false;
$is_active_affiliate = ($is_user_logged_in && function_exists('gigfiliate_is_active_affiliate')) ? gigfiliate_is_active_affiliate($current_user_id) : false;
?>
<header class="main-header" id="main-header">
  <?php get_template_part('template-parts/header/announcements'); ?>
  <?php /* get_template_part('template-parts/header/countdown'); */ ?>
  <div class="main-header_navbar">
    <div class="container">
      <div class="row justify-content-center">
        <?php
        $slug = get_post_field('post_name', get_the_ID());
        ?>
        <?php if ($slug == '5-reasons-women-swear-by-radicals-age-defying-exfoliating-pads' || $slug == '5-reasons-women-swear-by-radicals-age-defying-exfoliating-pads-bogo') : ?>
          <div class="col-6 text-center">
            <a class="main-header_brand" href="https://radicalskincare.com/products/age-defying-exfoliating-pads-ad/?applycoupon=save20">
              <img src="<?php echo esc_url($template_directory_uri . '/assets/images/logo/radicalskincare-logo.svg'); ?>" alt="<?php echo esc_attr($site_name); ?>" class="brand_logo"/>
              <span class="sr-only"><?php echo esc_html($site_name); ?></span>
            </a>
          </div>
          <!-- <div class="col-6 text-right">
            <a href="https://radicalskincare.com/products/age-defying-exfoliating-pads-ad/?applycoupon=save20" class="btn btn-primary">SHOP NOW</a>
          </div> -->
        <?php else : ?>
          <div class="col d-flex align-items-center d-lg-none">
            <button class="navbar-toggler collapsed" type="button" data-toggle="modal" data-target="#leftSidebarModal">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          </div>
          <div class="d-none d-lg-block col-lg-5">
            <?php if ( has_nav_menu( 'navbar' ) ) : ?>
              <?php echo wp_nav_menu([
                'menu'              => 'navbar',
                'theme_location'    => 'navbar',
                'depth'             => 2,
                'menu_class'        => 'navbar-nav mr-auto',
                'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
                'container'         => false,
                'walker'            => new WP_Bootstrap_Navwalker()
              ]); ?>
            <?php else : ?>
              Please assign Navbar Menu in Wordpress Admin -> Appearance -> Menus -> Manage Locations
            <?php endif; ?>
          </div>
          <div class="col-auto col-lg-2 text-center px-0">
            <a class="main-header_brand" href="<?php echo esc_url(home_url('/')); ?>">
              <img src="<?php echo esc_url($template_directory_uri . '/assets/images/logo/radicalskincare-logo.svg'); ?>" alt="<?php echo esc_attr($site_name); ?>" class="brand_logo"/>
              <span class="sr-only"><?php echo esc_html($site_name); ?></span>
            </a>
          </div>
          <div class="main-header_navbar_col-cart col d-lg-none">
            <button type="button" class="col-cart_btn xoo-wsc-cart-trigger cfw-side-cart-open-trigger">
              <?php if ($cart_count) : ?>
                <div class="col-cart_btn_count"><?php echo esc_html($cart_count); ?></div>
              <?php endif; ?>
              <img src="<?php echo esc_url($template_directory_uri . '/assets/images/cart.svg'); ?>" alt="Cart" style="height: 28px;"/>
            </button>
          </div>
          <div class="d-none d-lg-flex col-lg-5">
            <ul class="navbar-nav ml-auto align-items-center">
              <?php if ( ! is_search() ) : ?>
                <li class="nav-item">
                  <a href="javascript:void(0);" class="nav-link" data-toggle="modal" data-target="#searchModal">
                    <i class="fa fa-search" aria-hidden="true"></i>
                    <span class="sr-only">Search</span>
                  </a>
                </li>
              <?php endif; ?>
              <?php if ($is_user_logged_in) : ?>
                <li class="nav-item">
                  <a href="javascript:void(0);" class="nav-link" data-toggle="modal" data-target="#favoritesModal">
                    <i class="fa fa-heart-o" aria-hidden="true"></i>
                    <span class="sr-only">Favorites</span>
                  </a>
                </li>
              <?php endif; ?>
              <li class="nav-item dropdown">
                <button class="nav-link nav-link_currency" type="button" id="navbarCurrencyDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/dollar.svg'); ?>" alt="Dollar" class="nav-link_USD nav-link_img"/>
                  <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/euro.svg'); ?>" alt="Euro" class="nav-link_EUR nav-link_img"/>
                  <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/pound.svg'); ?>" alt="Pound" class="nav-link_GBP nav-link_img"/>
                  <span class="sr-only">Currency</span>
                  <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/angle-down.svg'); ?>" alt="Bottom Angle" class="nav-link_icon"/>
                </button>
                <div id="currency-items" class="dropdown-menu" aria-labelledby="navbarCurrencyDropdown">
                  <a class="dropdown-item" href="javascript:void(0)" currency="USD" value="US">
                    <i class="fa fa-dollar mr-1" aria-hidden="true"></i> US Dollar
                  </a>
                  <a class="dropdown-item" href="javascript:void(0)" currency="EUR" value="AD">
                    <i class="fa fa-euro mr-1" aria-hidden="true"></i> Euro
                  </a>
                  <a class="dropdown-item" href="javascript:void(0)" currency="GBP" value="GB">
                    <i class="fa fa-gbp mr-1" aria-hidden="true"></i> British Pound
                  </a>
                </div>
              </li>
              <?php /* Temporarily hidden — restore later
              <li class="nav-item">
                <a href="<?php echo esc_url($site_url); ?>/brand-partner/" class="nav-link">Join Brand Partners</a>
              </li>
              */ ?>
              <?php if ($is_user_logged_in) : ?>
                <?php
                $most_recent_subscription_id = get_posts([
                  'post_type' => 'shop_subscription',
                  'numberposts' => 1,
                  'meta_key' => '_customer_user',
                  'meta_value' => $current_user_id,
                  'post_status' => 'wc-active',
                  'fields' => 'ids',
                ]);
                ?>
                <li class="nav-item dropdown">
                  <?php if ($current_user_id && ($most_recent_subscription_id || get_user_meta($current_user_id, 'vip_customer', true))) : ?>
                    <div class="badge badge-primary" style="position: absolute; top: -24px; right: 0; object-fit: contain; font-size: 12px;"><i class="fa fa-star" aria-hidden="true"></i> VIP</div>
                  <?php endif; ?>
                  <button class="nav-link nav-link_account" type="button" id="navbarAccountDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    Account
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/angle-down.svg'); ?>" alt="Bottom Angle" class="nav-link_icon"/>
                  </button>
                  <div id="account-items" class="dropdown-menu <?php echo $most_recent_subscription_id ? 'has-a-subscription py-0' : ''; ?>" aria-labelledby="navbarAccountDropdown">
                    <div class="row">
                      <?php if ($most_recent_subscription_id) : ?>
                        <?php
                        $most_recent_subscription_id = $most_recent_subscription_id[0];
                        $wc_most_recent_subscription = wcs_get_subscription($most_recent_subscription_id);
                        $status = $wc_most_recent_subscription->get_status();
                        $skippable_items = get_post_meta($wc_most_recent_subscription->get_ID(), 'one_time_skippable_item', true);
                        $global_skippable_products = get_field('subscription_global_skipped_products', 'option');
                        if ($skippable_items) {
                          $skippable_items = json_decode($skippable_items, true);
                        } else {
                          $skippable_items = [];
                        }
                        if ($global_skippable_products) {
                          $global_skippable_products = explode(',', $global_skippable_products);
                        } else {
                          $global_skippable_products = [];
                        }
                        ?>
                        <div class="d-none d-lg-flex col-lg-7 bg-lightestgray align-items-center">
                          <div id="main-header_most-recent-subscription" class="py-3">
                            <h3 class="fs-1.25x mb-3">Most Recent Subscription</h3>
                            <p class="mb-0">Status <span class="badge badge-<?php echo esc_attr($status); ?>"><?php echo esc_html($status); ?></span></p>
                            <p class="mb-0">Next Order <strong><?php echo $wc_most_recent_subscription->get_date_to_display('next_payment'); ?></strong></p>
                            <?php if ($items = $wc_most_recent_subscription->get_items()) : ?>
                              <table class="main-header_most-recent-subscription_items mb-3">
                                <tbody>
                                  <?php foreach ($items as $key => $item) : ?>
                                    <?php
                                    $product = $item->get_product();
                                    $product_id = $product->get_id();
                                    $image_src = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'single-post-thumbnail');
                                    $is_product_skipped = in_array($product_id, $skippable_items);
                                    if (!$is_product_skipped) {
                                      $is_product_skipped = in_array($product_id, $global_skippable_products);
                                    }
                                    ?>
                                    <tr>
                                      <td>
                                        <?php if ($image_src) : ?>
                                          <img src="<?php echo esc_url($image_src[0]); ?>" alt="<?php echo esc_attr($image_src[1]); ?>"/>
                                        <?php endif; ?>
                                      </td>
                                      <td class="pl-2">
                                        <?php echo esc_html($product->get_name()); ?> <b>* <?php echo esc_html($item->get_quantity()); ?></b>
                                        <?php if ($is_product_skipped) : ?>
                                          <span class="badge badge-secondary">Skipped</span>
                                        <?php endif; ?>
                                      </td>
                                    </tr>
                                  <?php endforeach; ?>
                                </tbody>
                              </table>
                            <?php endif; ?>
                            <?php
                            $actions = wcs_get_all_user_actions_for_subscription( $wc_most_recent_subscription, $current_user_id );
                            ?>
                            <?php if (isset($actions['subscription_renewal_early'])) : ?>
                              <a href="<?php echo esc_url($actions['subscription_renewal_early']['url']); ?>" class="btn btn-darkergray mr-3">
                                Buy Now
                              </a>
                            <?php endif; ?>
                            <a href="<?php echo esc_url($site_url); ?>/account/view-subscription/<?php echo esc_html($most_recent_subscription_id); ?>" class="link-underline link-underline_darker-gray">
                              <!-- <i class="fa fa-cogs" aria-hidden="true"></i> -->Manage
                            </a>
                          </div>
                        </div>
                      <?php endif; ?>
                      <div class="<?php echo $most_recent_subscription_id ? 'col-lg-5 py-3' : 'col'; ?>">
                        <a class="dropdown-item" href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>">
                          Account
                        </a>
                        <?php if ($is_active_affiliate) : ?>
                          <a class="dropdown-item" href="<?php echo esc_url($site_url); ?>/account/brand-partner-dashboard">
                            BP Dashboard
                          </a>
                          <a class="dropdown-item" href="<?php echo esc_url($site_url); ?>/account/brand-partner-resources">
                            BP Resources
                          </a>
                          <a class="dropdown-item" href="<?php echo esc_url($site_url); ?>/account/brand-partner-customers">
                            BP Customers
                          </a>
                        <?php endif; ?>
                        <a class="dropdown-item" href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>">
                          My Orders
                        </a>
                        <a class="dropdown-item" href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>subscriptions">
                          My Subscriptions
                        </a>
                        <?php if (class_exists('\Wlr\App\Router')) : ?>
                          <a class="dropdown-item" href="<?php echo esc_url($site_url); ?>/account/loyalty_reward/">
                            Points &amp; Rewards
                            <?php if (class_exists('Wlr\App\Helpers\EarnCampaign')) : ?>
                              <?php
                              $earn_campaign = Wlr\App\Helpers\EarnCampaign::getInstance();
                              $wp_loyalty_points = $earn_campaign->getPointUserByEmail($current_user->user_email);
                              ?>
                              <?php if ($wp_loyalty_points) : ?>
                                <span class="badge badge-points"><?php echo esc_html($wp_loyalty_points->points); ?></span>
                              <?php endif; ?>
                            <?php endif; ?>
                          </a>
                        <?php endif; ?>
                        <a class="dropdown-item" href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>">
                          Account Details
                        </a>
                        <a class="dropdown-item" href="<?php echo esc_url(wc_get_account_endpoint_url('customer-logout')); ?>">
                          Logout <i class="fa fa-sign-out" aria-hidden="true"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                </li>
              <?php else : ?>
                <li class="nav-item">
                  <a href="javascript:void(0);" data-toggle="modal" data-target="#loginModal" title="Login" class="nav-link">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    Login
                  </a>
                </li>
              <?php endif; ?>
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link xoo-wsc-cart-trigger cfw-side-cart-open-trigger">
                  Cart <span class="navbar-nav_cart-count"><?php echo esc_html($cart_count); ?></span>
                </a>
              </li>
            </ul>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php /*
  <div id="on-click-gigfiliate-dashboard-link" class="progress" style="display: none;">
    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
  </div>
  */ ?>
</header>
<?php get_template_part('template-parts/header/mega-menu'); ?>
<?php get_template_part('template-parts/header/left-sidebar'); ?>
<div id="currency-converter-widget">
  <?php dynamic_sidebar('currency-converter-widget'); ?>
</div>
<?php get_template_part('template-parts/header/search'); ?>
<?php get_template_part('template-parts/header/favorites'); ?>
<?php get_template_part('template-parts/modal/login', null, ['is_user_logged_in' => $is_user_logged_in]); ?>
<?php /* get_template_part('template-parts/header/cyber-monday'); */ ?>
