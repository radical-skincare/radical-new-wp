<?php
$product = $args['product'] ?? null;
if (!$product) {
  return;
}
$wc_product = wc_get_product($product->ID);
if (!$wc_product) {
  error_log('BP Product not found: ' . $product->ID);
  error_log($_SERVER['REQUEST_URI']);
  return;
}
$stock_status = get_post_meta($product->ID, '_stock_status', true);
$admin_ajax_url = admin_url('admin-ajax.php');
$product_feat_img = wp_get_attachment_url($wc_product->get_image_id());
$permalink = get_permalink($product->ID);
$title = $wc_product->get_title();
?>
<div class="card card-product">
	<div class="product-loader">
		<div class="spinner-border" role="status">
			<span class="sr-only">Loading...</span>
		</div>
	</div>
	<div class="card-body">
		<a href="<?php echo esc_url($permalink); ?>" class="d-block relative text-darkergray">
			<?php if ($stock_status === 'outofstock') : ?>
				<span class="badge badge-sold-out">Sold Out</span>
      <?php elseif ($badge_text = get_field('badge_text', $product->ID)) : ?>
        <span class="card-product_badge badge-<?php echo esc_attr(str_replace('-', ' ', strtolower($badge_text))); ?>"><?php echo $badge_text; ?></span>
      <?php endif; ?>
			<div class="card-product_price">
				<?php if ($wc_product->is_on_sale()) : ?>
					<span class="woocommerce-Price-amount amount sale-price mr-2">
						<span class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol(); ?></span>
						<?php echo str_replace('.00', '', number_format((float) $wc_product->get_price(), 2, '.', '')); ?>
					</span>
					<strike class="woocommerce-Price-amount amount regular-price">
						<span class="woocommerce-Price-currencySymbol">
							<?php echo get_woocommerce_currency_symbol(); ?>
						</span><?php echo str_replace('.00', '', $wc_product->get_regular_price()); ?>
					</strike>
				<?php else : ?>
					<span class="woocommerce-Price-amount amount sale-price">
						<span class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol(); ?></span>
						<?php if ($wc_product->is_type('variable')) : ?>
							<?php echo str_replace('.00', '', $wc_product->get_variation_regular_price('min', true)); ?> - <span class="woocommerce-Price-currencySymbol mr-1"><?php echo get_woocommerce_currency_symbol(); ?></span> <?php echo str_replace('.00', '', $wc_product->get_variation_regular_price('max', true)); ?>
						<?php else : ?>
							<?php echo str_replace('.00', '', number_format((float) $wc_product->get_price(), 2, '.', '')); ?>
						<?php endif; ?>
					</span>
				<?php endif; ?>
			</div>
			<div class="card-product_image">
				<img src="<?php echo esc_url($product_feat_img); ?>" alt="<?php echo esc_attr(get_the_title($product->ID)); ?>" />
			</div>
			<h4 class="card-product_title"><?php echo $title; ?></h4>
		</a>
		<div class="d-none" id="product_description_<?php echo (int) $product->ID; ?>">
			<?php echo get_the_excerpt($product->ID); ?>
		</div>
		<div class="card-product_actions">
      <?php if ($stock_status !== 'outofstock') : ?>
        <div class="row justify-content-center m-0 align-items-end w-100">
          <div class="col-12">
            <div class="enrollment-typing-loading-dots typing-loading-dots">Adding...</div>
            <form class="enrollment-add-product-form special-add-to-cart <?php echo radical_Is_In_Cart($product->ID) ? 'added-to-cart' : 'd-block'; ?>" action="<?php echo esc_url($admin_ajax_url); ?>" method="POST">
              <input type="hidden" name="product_id" value="<?php echo (int) $product->ID; ?>" />
              <input type="hidden" name="action" value="radical_add_collection_to_cart" />
              <?php wp_nonce_field('radical_ajax_nonce', 'nonce', true, true); ?>
              <button type="submit" class="add_to_cart_button btn text-darkergray mx-auto d-block">Add To Cart</button>
            </form>
            <form class="remove-special w-100 text-center <?php echo !radical_Is_In_Cart($product->ID) ? 'd-none' : ''; ?>" action="<?php echo esc_url($admin_ajax_url); ?>" method="POST">
              <input type="hidden" name="product_id" value="<?php echo (int) $product->ID; ?>" />
              <input type="hidden" name="action" value="radical_remove_collection_from_cart" />
              <?php wp_nonce_field('radical_ajax_nonce', 'nonce', true, true); ?>
              <button type="submit" class="link-underline link-underline_darker-gray">
                <i class="fa fa-times" aria-hidden="true"></i> Remove From Cart
              </button>
            </form>
          </div>
        </div>
      <?php endif; ?>
		</div>
	</div>
</div>
