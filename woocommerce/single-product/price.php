<?php
/**
 * Single Product Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

if (!defined('ABSPATH')) {
    exit(); // Exit if accessed directly
}

global $product;
$site_url = get_site_url();
$post_id = get_the_ID();
$currency_symbol = get_woocommerce_currency_symbol();
$wcsatt_schemes = get_post_meta($post_id, '_wcsatt_schemes', true);
$is_user_logged_in = is_user_logged_in();
$current_user_id = $is_user_logged_in ? get_current_user_id() : false;
$is_active_affiliate = false;
$is_affiliate_customer = false;
if ($is_user_logged_in) {
    $is_active_affiliate = function_exists('gigfiliate_is_active_affiliate') ? gigfiliate_is_active_affiliate($current_user_id) : false;
    $is_affiliate_customer = (int) get_user_meta($current_user_id, 'v_ref_affiliate_id', true);
}
if (!$is_affiliate_customer) {
    $is_affiliate_customer = isset($_GET['ref']) || isset($_COOKIE['gigfiliatewp_ref']);
}
$product_price = (float) $product->get_price();
$regular_price = (float) $product->get_regular_price();

if (radical_cannot_access_brand_partner_product($post_id)) {
    return;
}
$product_slug = $product->get_slug();
?>
<div class="row prices-row mb-3">
	<div class="col">
		<span>Retail Price</span>
		<div class="<?php echo esc_attr(apply_filters('woocommerce_product_price_class', 'price')); ?>">
			<?php echo $product->get_price_html(); ?>
		</div>
	</div>
	<?php if ($wcsatt_schemes) : ?>
		<?php
			$ror_discount = 10;
			$ror_price = str_replace('.00', '', number_format((float) ($product_price * ((100 - $ror_discount) / 100)), 2, '.', ''));
		?>
		<div class="col font-weight-light" <?php echo !$is_active_affiliate ? 'style="border-right: none;"' : ''; ?>>
			<span>
				Radical on Repeat
				<a href="<?php echo esc_url($site_url); ?>/radical-on-repeat/" title="Learn More" target="_blank" class="text-darkergray">
					<i class="fa fa-info-circle" aria-hidden="true"></i>
					<span class="sr-only">Learn More</span>
				</a>
			</span>
			<div class="<?php echo esc_attr(apply_filters('woocommerce_product_price_class', 'price')); ?>">
				<span class="woocommerce-Price-currencySymbol"><?php echo $currency_symbol; ?></span>
				<?php echo esc_html($ror_price); ?>
			</div>
		</div>
	<?php endif; ?>
	<?php if ($is_active_affiliate || $is_affiliate_customer) : ?>
		<?php
			$bp_discount = 20;
			$bp_price = str_replace('.00', '', number_format((float) ($product_price * ((100 - $bp_discount) / 100)), 2, '.', ''));
		?>
		<div class="col font-weight-light">
			<span>
				Brand Partner <?php echo $is_affiliate_customer ? 'Customer ' : ''; ?>Price
				<a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="With applied coupon" class="text-darkergray">
					<i class="fa fa-info-circle" aria-hidden="true"></i>
					<span class="sr-only">Learn More</span>
				</a>
			</span>
			<div class="<?php echo esc_attr(apply_filters('woocommerce_product_price_class', 'price')); ?>">
				<span class="woocommerce-Price-currencySymbol"><?php echo $currency_symbol; ?></span>
				<span id="bp-price"><?php echo esc_html($bp_price); ?></span>
			</div>
		</div>
	<?php endif; ?>
</div>
<?php echo do_shortcode('[afterpay_paragraph]'); ?>
<?php if ($product_slug === 'age-defying-exfoliating-pads' || $product_slug === 'age-defying-exfoliating-pads-ad' || $product_slug === 'age-defying-exfoliating-pads-ad-bogo') : ?>
	<p class="try-risk-free"><strong>Try Risk free: 60 day bottom of the jar Guarantee.</strong></p>
<?php endif; ?>
