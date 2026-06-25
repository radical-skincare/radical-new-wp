<?php
/**
 * Template Name: Account
 */

$is_user_logged_in = is_user_logged_in();
$agreed_to_latest_terms = true; // Disable agreeing to policy for now
/*
$agreed_to_latest_terms = false;
if ($is_user_logged_in) {
  $current_user_id = get_current_user_id();
  function radical_account_agree_to_privacy_policy($current_user__id = false) {
    $agreed_to_privacy_policy = false;
    if (isset($_POST['action']) && $_POST['action'] === 'agree_to_privacy_policy') {
      $agreed_to_privacy_policy = date('m/d/Y');
      update_user_meta($current_user__id, 'privacy_policy_last_agreed_date', $agreed_to_privacy_policy);
    }
    return $agreed_to_privacy_policy;
  }
  radical_account_agree_to_privacy_policy($current_user_id);
  $last_agreed_date = get_user_meta($current_user_id, 'privacy_policy_last_agreed_date', true);
  $privacy_policy = get_field('privacy_policy', get_queried_object_id());
  if ($privacy_policy) {
    $agreed_to_latest_terms = $last_agreed_date && (strtotime($last_agreed_date) > strtotime($privacy_policy['last_updated_date']));
  }
}
*/
$site_url = get_site_url();
$uri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$uri = str_replace($site_url, '', $uri);

get_header(); ?>

  <?php if ($is_user_logged_in && $agreed_to_latest_terms) : ?>
    <?php get_template_part('template-parts/account/breadcrumb'); ?>
  <?php endif; ?>
  <div class="container <?php echo !$is_user_logged_in ? 'pt-5' : ''; ?>">
    <?php while (have_posts()) : the_post(); ?>
      <?php if ($is_user_logged_in && !$agreed_to_latest_terms) : ?>
        <?php get_template_part('template-parts/account/privacy-policy'); ?>
      <?php else : ?>
        <?php /* <div class="gig-dashboard-version-switcher mb-3">
          <div class="btn-group" role="group">
            <a href="<?php echo esc_url($site_url); ?>/account/brand-partner-dashboard/" class="btn btn-outline-dark">
              Current (Legacy)
            </a>
            <a href="<?php echo esc_url($site_url); ?>/bp-dashboard-v2" class="btn btn-dark">
              New (Beta)
            </a>
          </div>
        </div> */ ?>
        <?php get_template_part('template-parts/content', 'page'); ?>
        <div class="my-5"></div>
        <?php get_template_part('template-parts/account/cta'); ?>
      <?php endif; ?>
    <?php endwhile; ?>
  </div>
  <?php if (str_contains($uri, '/loyalty_reward')) : ?>
    <div class="modal fade" id="selectProductToReviewModel" tabindex="-1" aria-labelledby="selectProductToReviewModelLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="selectProductToReviewModelLabel">Select Product To Review</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body review-search-products-container-modal-body">
            <div class="select-product-review-search-wrap mb-3">
              <input type="text" id="review-search-bar" class="review-search-bar w-100 py-2 border-top-0 border-right-0 border-left-0" placeholder="Search Products">
              <a href="javascript:void(0)" class="clear-review-search position-absolute float-right d-none" data-dismiss="modal">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/close.svg" alt="Close"/>
                Clear
              </a>
            </div>
            <p class="d-none text-center px-3" id="review_search_status"></p>
            <div class="row review-search-products-container flex-grow-1" style="row-gap: 1rem;"></div>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>

<?php get_footer(); ?>
