<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' ); ?>

<nav class="woocommerce-MyAccount-navigation mb-5 mb-lg-0">
  <a href="javascript:void(0);" id="account-toggle-menu" class="account-toggle-menu btn-collapse collapsed fs-1.5x" title="Toggle Account Menu">
    <i class="fa fa-bars" aria-hidden="true"></i>
    <i class="fa fa-close" aria-hidden="true"></i>
    <span class="account-toggle-menu_inner-text">Account Menu</span>
  </a>
  <ul>
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
      <?php
      $account_menu_link = $endpoint === 'subscriptions' ? get_site_url() . '/account/subscriptions' : esc_url( wc_get_account_endpoint_url( $endpoint ) );
      ?>
			<li class="<?php echo esc_attr(wc_get_account_menu_item_classes( $endpoint )); ?>">
				<a href="<?php echo esc_url($account_menu_link); ?>">
          <?php echo $label; ?>
        </a>
			</li>
    <?php endforeach; ?>
	</ul>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
