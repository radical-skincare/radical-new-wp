<?php
// Variables passed from header.php context
if (!isset($site_url)) $site_url = get_site_url();
if (!isset($is_user_logged_in)) $is_user_logged_in = is_user_logged_in();
if (!isset($current_user)) $current_user = ($is_user_logged_in) ? wp_get_current_user() : false;
if (!isset($is_active_affiliate)) $is_active_affiliate = ($is_user_logged_in && function_exists('gigfiliate_is_active_affiliate')) ? gigfiliate_is_active_affiliate(get_current_user_id()) : false;
?>
<style>
#collapseAccount0 .collapse-account-inner {
  flex-direction: column;
  align-items: start;
}
#collapseAccount0 .collapse-account-inner a {
  display: block;
  text-align: left;
}
</style>
<div id="leftSidebarModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="leftSidebarModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div id="leftSidebarMenu">
          <?php if ( ! is_search() ) : ?>
            <div id="headerMobileSearchAccordion" class="accordion">
              <div class="accordion-item">
                <div id="headerMobileSearch" class="accordion-item_header">
                  <a href="javascript:void(0)" class="btn accordion-item_btn" data-toggle="modal" data-target="#searchModal" style="justify-content: start; column-gap: 0.5rem;">
                    <i class="fa fa-search" aria-hidden="true"></i>
                    Search
                  </a>
                </div>
              </div>
            </div>
          <?php endif; ?>
          <?php if ($is_user_logged_in) : ?>
            <div id="headerMobileFavoritesAccordion" class="accordion">
              <div class="accordion-item">
                <div id="headerMobileFavorites" class="accordion-item_header">
                  <a href="javascript:void(0)" class="btn accordion-item_btn" data-toggle="modal" data-target="#favoritesModal" style="justify-content: start; column-gap: 0.5rem;">
                    <i class="fa fa-heart-o" aria-hidden="true"></i>
                    Favorites
                  </a>
                </div>
              </div>
            </div>
          <?php endif; ?>
          <?php if ( has_nav_menu( 'navbar' ) ) : ?>
            <?php
            $menu_locations = get_nav_menu_locations();
            $menu_id = $menu_locations['mobile-navbar'];
            $mobile_nav = wp_get_nav_menu_items($menu_id);
            if (function_exists('radical_nest_menu')) {
              $mobile_nav = radical_nest_menu($mobile_nav);
            }
            ?>
            <?php if ($mobile_nav) : ?>
              <div id="headerMobileMenuAccordion" class="accordion">
                <?php foreach ( $mobile_nav as $key => $menu_item) : ?>
                  <div class="accordion-item">
                    <?php if (!empty($menu_item['children'])) : ?>
                      <div id="heading<?php echo esc_attr($key); ?>" class="accordion-item_header">
                        <button class="btn accordion-item_btn <?php echo ($key === 0) ? '' : 'collapsed'; ?>" data-toggle="collapse" data-target="#collapse<?php echo esc_attr($key); ?>" aria-expanded="<?php echo ($key === 0) ? 'true' : 'false'; ?>" aria-controls="collapse<?php echo esc_attr($key); ?>">
                          <?php echo $menu_item['title']; ?>
                          <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/angle-down.svg'); ?>" alt="Bottom Angle" class="accordion-item_icon-angle-down"/>
                        </button>
                      </div>
                      <div id="collapse<?php echo esc_attr($key); ?>" class="collapse <?php echo ($key === 0) ? 'show' : ''; ?>" aria-labelledby="heading<?php echo esc_attr($key); ?>" data-parent="#headerMobileMenuAccordion">
                        <div id="headerMobileMenuChildAccordion" class="accordion">
                          <?php foreach($menu_item['children'] as $this_key => $child_menu_item) : ?>
                            <?php if (!empty($child_menu_item['children'])) : ?>
                              <div id="headingChild<?php echo esc_attr($this_key); ?>" class="accordion-item_header">
                                <button class="btn accordion-item_btn <?php echo ($this_key === 0) ? '' : 'collapsed'; ?> pl-3" data-toggle="collapse" data-target="#collapseChild<?php echo esc_attr($this_key); ?>" aria-expanded="<?php echo ($this_key === 0) ? 'true' : 'false'; ?>" aria-controls="collapseChild<?php echo esc_attr($this_key); ?>">
                                  <?php echo $child_menu_item['title']; ?>
                                  <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/angle-down.svg'); ?>" alt="Bottom Angle" class="accordion-item_icon-angle-down"/>
                                </button>
                              </div>
                              <div id="collapseChild<?php echo esc_attr($this_key); ?>" class="collapse <?php echo ($this_key === 0) ? 'show' : ''; ?>" aria-labelledby="headingChild<?php echo esc_attr($this_key); ?>" data-parent="#headerMobileMenuChildAccordion">
                                <div class="pl-3">
                                  <ul class="pl-3 my-3" style="list-style: none;">
                                    <?php foreach($child_menu_item['children'] as $child_child_menu_item) : ?>
                                      <li class="pl-3">
                                        <a href="<?php echo esc_url($child_child_menu_item['url']); ?>"><?php echo $child_child_menu_item['title']; ?></a>
                                      </li>
                                    <?php endforeach; ?>
                                  </ul>
                                </div>
                              </div>
                            <?php else : ?>
                              <div id="headingChild<?php echo esc_attr($this_key); ?>" class="accordion-item_header">
                                <div class="btn accordion-item_btn pl-3" data-toggle="collapse" data-target="#collapseChild<?php echo esc_attr($this_key); ?>" aria-expanded="true" aria-controls="collapseChild<?php echo esc_attr($this_key); ?>">
                                  <a href="<?php echo esc_url($child_menu_item['url']); ?>">
                                    <?php echo $child_menu_item['title']; ?>
                                  </a>
                                </div>
                              </div>
                            <?php endif; ?>
                          <?php endforeach; ?>
                        </div>
                      </div>
                    <?php else : ?>
                      <div id="heading<?php echo esc_attr($key); ?>" class="card-header">
                        <div href="<?php echo esc_url($menu_item['url']); ?>" class="btn btn-link" data-toggle="collapse" data-target="#collapse<?php echo esc_attr($key); ?>" aria-expanded="true" aria-controls="collapse<?php echo esc_attr($key); ?>">
                          <a href="<?php echo esc_url($menu_item['url']); ?>">
                            <?php echo $menu_item['title']; ?>
                          </a>
                        </div>
                      </div>
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          <?php else : ?>
            Please assign Navbar Menu in Wordpress Admin -> Appearance -> Menus -> Manage Locations
          <?php endif; ?>
          <div id="headerMobileAccountAccordion" class="accordion">
            <div class="accordion-item">
              <div id="headerMobileAccount0" class="accordion-item_header">
                <?php if ($is_user_logged_in) : ?>
                  <button class="btn accordion-item_btn collapsed" data-toggle="collapse" data-target="#collapseAccount0" aria-expanded="false" aria-controls="headerMobileAccount0">
                    <span style="font-weight: 400;">
                      <i class="fa fa-user mr-2" aria-hidden="true"></i>
                      Account
                    </span>
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/angle-down.svg'); ?>" alt="Bottom Angle" class="accordion-item_icon-angle-down"/>
                  </button>
                <?php else : ?>
                  <a href="javascript:void(0)" data-toggle="modal" data-target="#loginModal" title="Login" class="btn accordion-item_btn" style="justify-content: start; column-gap: 0.5rem;">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    Login
                  </a>
                <?php endif; ?>
              </div>
              <?php if ($is_user_logged_in) : ?>
                <div id="collapseAccount0" class="collapse" aria-labelledby="headerMobileAccount0" data-parent="#headerMobileMenuAccordion">
                  <div class="pl-3">
                    <div class="btn accordion-item_btn collapse-account-inner pl-3">
                      <?php if ($is_active_affiliate) : ?>
                        <a href="<?php echo esc_url($site_url); ?>/account/brand-partner-dashboard">
                          BP Dashboard
                        </a>
                        <a href="<?php echo esc_url($site_url); ?>/account/brand-partner-resources">
                          BP Resources
                        </a>
                        <a href="<?php echo esc_url($site_url); ?>/account/brand-partner-customers">
                          BP Customers
                        </a>
                      <?php endif; ?>
                      <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>">
                        My Orders
                      </a>
                      <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>subscriptions">
                        My Subscriptions
                      </a>
                      <?php if (class_exists('\Wlr\App\Router')) : ?>
                        <a href="<?php echo esc_url($site_url); ?>/account/loyalty_reward/">
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
                      <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>">
                        Account Details
                      </a>
                      <a href="<?php echo esc_url(wc_get_account_endpoint_url('customer-logout')); ?>" class="d-block text-left">
                        Logout <i class="fa fa-sign-out" aria-hidden="true"></i>
                      </a>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
          <div id="headerMobileJoinAccordion" class="accordion mb-4">
            <div class="accordion-item">
              <div id="headerMobileJoin" class="accordion-item_header">
                <a ref="<?php echo esc_url($site_url); ?>/brand-partner/" class="btn accordion-item_btn">
                  Join Brand Partners
                </a>
              </div>
            </div>
          </div>
          <nav class="nav flex-column px-3 mb-3">
            <li class="nav-item dropdown">
              <button class="btn nav-link_currency d-flex align-items-center px-0" type="button" id="navbarCurrencyDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: transparent; width: fit-content;">
                <div class="dropdown-item p-0" currency="USD" value="US">
                  <div class="border border-dark d-inline p-2 mr-1">
                    <i class="fa fa-dollar mr-1" aria-hidden="true"></i> US Dollar
                  </div>
                </div>
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/angle-down.svg'); ?>" alt="Bottom Angle" class="nav-link_icon"/>
              </button>
              <div id="currency-items" class="dropdown-menu" aria-labelledby="navbarCurrencyDropdown">
                <a class="dropdown-item" href="javascript:void(0);" currency="USD" value="US">
                  <i class="fa fa-dollar mr-1" aria-hidden="true"></i> US Dollar
                </a>
                <a class="dropdown-item" href="javascript:void(0);" currency="EUR" value="AD">
                  <i class="fa fa-euro mr-1" aria-hidden="true"></i> Euro
                </a>
                <a class="dropdown-item" href="javascript:void(0);" currency="GBP" value="GB">
                  <i class="fa fa-gbp mr-1" aria-hidden="true"></i> British Pound
                </a>
              </div>
            </li>
          </nav>
          <div class="d-flex justify-content-start align-content-center px-3 mb-4">
            <a href="https://www.facebook.com/radicalskincare/" class="social-link m-2 rounded-circle bg-dark text-white" target="_blank">
              <i class="fa fa-facebook" aria-hidden="true"></i>
              <span class="sr-only">Facebook</span>
            </a>
            <a href="https://www.instagram.com/radicalskincare/" class="social-link m-2 rounded-circle bg-dark text-white" target="_blank">
              <i class="fa fa-instagram" aria-hidden="true"></i>
              <span class="sr-only">Instagram</span>
            </a>
            <a href="https://www.youtube.com/channel/UC3ox2qF9xqJ6NJMIOTAqKFw" class="social-link m-2 rounded-circle bg-dark text-white" target="_blank">
              <i class="fa fa-youtube" aria-hidden="true"></i>
              <span class="sr-only">YouTube</span>
            </a>
            <a href="https://www.tiktok.com/@radicalskincare" class="social-link m-2 rounded-circle bg-dark text-white" target="_blank">
              <svg fill="#fff" width="18" height="13" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" xml:space="preserve">
                <path d="M19.589 6.686a4.793 4.793 0 0 1-3.77-4.245V2h-3.445v13.672a2.896 2.896 0 0 1-5.201 1.743l-.002-.001.002.001a2.895 2.895 0 0 1 3.183-4.51v-3.5a6.329 6.329 0 0 0-5.394 10.692 6.33 6.33 0 0 0 10.857-4.424V8.687a8.182 8.182 0 0 0 4.773 1.526V6.79a4.831 4.831 0 0 1-1.003-.104z"/>
              </svg>
              <span class="sr-only">TikTok</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
