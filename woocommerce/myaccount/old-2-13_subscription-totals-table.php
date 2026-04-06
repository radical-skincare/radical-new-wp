<?php
/**
 * Subscription details table
 *
 * @author  Prospress
 * @package WooCommerce_Subscription/Templates
 * @since 1.0.0 - Migrated from WooCommerce Subscriptions v2.6.0
 * @version 1.0.0 - Migrated from WooCommerce Subscriptions v2.6.0
 */

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly
}
$status = wcs_get_subscription_status_name($subscription->get_status());
$subscription_id = $subscription->get_ID();
$skippable_items = get_post_meta($subscription_id, 'one_time_skippable_item', true);
if ($skippable_items) {
  $skippable_items = json_decode($skippable_items, true);
} else {
  $skippable_items = [];
}
$new_product = null;
if (isset($_GET['new_product']) && $_GET['t']) {
  if (strtotime(date('Y-m-d H:i:s')) - strtotime(date('Y-m-d H:i:s', substr($_GET['t'], 0, 10))) <= 50) {
    $new_product = $_GET['new_product'];
  }
}

function radical_currency_formatter($number) {
  return number_format((float)$number, 2, '.', '');
}
?>
<div class="subscription-details-products list-group mb-3">
  <?php foreach ($subscription->get_items() as $item_id => $item) : ?>
    <?php
    if (!apply_filters('woocommerce_order_item_visible', true, $item)) {
      continue;
    }
    $_product = apply_filters('woocommerce_subscriptions_order_item_product', $item->get_product(), $item);
    $is_product_skipped = in_array($item['product_id'], $skippable_items);
    $product = wc_get_product($item->get_product());
    $product_price = $product->get_price();
    $saved_amount = $product->get_price() - $subscription->get_item_subtotal($item, false, true);
    $disable_this_item_remove = count($subscription->get_items()) - 1 <= count($skippable_items) && !in_array($item['product_id'], $skippable_items);
    ?>
    <div id="subscription-product-card_<?php echo esc_attr($item_id); ?>" class="subscription-product-card list-group-item p-3 <?php echo $is_product_skipped ? 'subscription-product-card_is-skipped' : ''; ?>">
      <?php if ($new_product == $product->get_ID()) : ?>
        <div class="badge badge-primary">New</div>
      <?php elseif ($is_product_skipped) : ?>
        <div class="badge badge-skipped">Skipped</div>
      <?php endif; ?>
      <div class="row">
        <?php if ($image = wp_get_attachment_image_src(get_post_thumbnail_id($item['product_id']), 'single-post-thumbnail')) : ?>
          <div class="col-md-2 d-flex align-items-center">
            <img src="<?php echo esc_url($image[0]); ?>" class="d-block rounded" alt="<?php echo esc_attr($item['alt']); ?>" />
          </div>
        <?php endif; ?>
        <div class="col-md-10 d-flex flex-column align-items-start justify-content-between">
          <div class="w-100 mb-3">
            <?php if ($_product && !$_product->is_visible()) : ?>
              <h5 class="title-e m-0"><?php echo wp_kses_post(apply_filters('woocommerce_order_item_name', $item['name'], $item, false)); ?></h5>
            <?php else : ?>
              <?php echo wp_kses_post(apply_filters('woocommerce_order_item_name', sprintf('<a href="%s" class="title-d mb-0">%s</a>', get_permalink($item['product_id']), $item['name']), $item, false)); ?>
            <?php endif; ?>
          </div>
          <div class="subscription-product-card_cost-details row align-items-center w-100 mb-3">
            <div class="col-8 col-sm-auto text-center">
              <label>Cost</label>
              <div class="price fs-1.25x">
                <?php if ($saved_amount > 0) : ?>
                  <del>
                    <span class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol(); ?></span>
                    <?php echo esc_html($product_price); ?>
                  </del>
                <?php endif; ?>
                <span class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol(); ?></span>
                <span id="price-for_<?php echo esc_attr($item['product_id']); ?>"><?php echo esc_html($subscription->get_item_subtotal($item, false, true)); ?></span>
              </div>
            </div>
            <?php if ($saved_amount > 0) : ?>
              <div class="col-4 col-sm-auto text-center">
                <label>Savings</label>
                <div class="text-success fs-1.25x">
                  <?php echo esc_html(round(($saved_amount / $product_price) * 100)); ?>%
                </div>
              </div>
            <?php endif; ?>
            <div class="col-8 col-sm-auto text-center">
              <label class="mb-0">Quantity</label>
              <?php if ($status === 'Active') : ?>
                <div class="product_quantity-wrap quantity w-100" style="<?php echo $is_product_skipped ? 'display: none;' : ''; ?>">
                  <div class="btn-group_quantity" role="group" aria-label="Quantity">
                    <button type="button" class="btn btn-quantity_minus" <?php echo $item['qty'] === 1 ? 'disabled' : ''; ?>>
                      <span class="content">
                        <i class="fa fa-minus" aria-hidden="true"></i>
                        <span class="sr-only">Minus</span>
                      </span>
                      <span class="loader">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Loading...</span>
                      </span>
                    </button>
                    <button type="button" class="btn p-0">
                      <input type="number" id="quantity" class="input-text qty text subscription-details-product-quantity quantity-for_<?php echo esc_attr($item['product_id']); ?> fs-1.25x" min="1" name="quantity" data-old_value="<?php echo esc_attr($item['qty']); ?>" value="<?php echo esc_attr($item['qty']); ?>" title="Qty" inputmode="numeric" data-subscription_id="<?php echo esc_attr($subscription_id); ?>" data-product_id='<?php echo esc_attr($item['product_id']); ?>' data-product_name='<?php echo esc_attr($item['name']); ?>'/>
                    </button>
                    <button type="button" class="btn btn-quantity_plus">
                      <span class="content">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        <span class="sr-only">Plus</span>
                      </span>
                      <span class="loader">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="sr-only">Loading...</span>
                      </span>
                    </button>
                  </div>
                </div>
              <?php endif; ?>
              <div class="product_quantity-skipped fs-1.25x mt-2" style="<?php echo $status === 'Active' && !$is_product_skipped ? 'display: none;' : ''; ?>"><?php echo esc_html($item['qty']); ?></div>
            </div>
            <div class="col-4 col-sm-auto text-center">
              <label>Total</label>
              <div class="price fs-1.25x">
                <span class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol(); ?></span>
                <span id="total-for_<?php echo esc_attr($item['product_id']); ?>"><?php echo esc_html(radical_currency_formatter($item['line_total'])); ?>
              </div>
              <small>Discount: <span class="woocommerce-Price-currencySymbol">$</span><?php echo esc_html(radical_currency_formatter(($product_price * $item['qty']) - $item['line_total'])); ?></small>
            </div>
            <?php if ($this_meta_data = $item->get_formatted_meta_data()) : ?>
              <?php foreach ($this_meta_data as $item_meta) : ?>
                <div class="col-auto">
                  <label><?php echo esc_html($item_meta->display_key); ?></label><br>
                  <?php echo esc_html(strip_tags($item_meta->display_value)); ?>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
          <?php if ($status === 'Active') : ?>
            <div>
              <?php if ($allow_item_removal && !$disable_this_item_remove) : ?>
                <?php if (wcs_can_item_be_removed($item, $subscription)) : ?>
                  <a href="javascript:void(0)" data-href="<?php echo esc_url(WCS_Remove_Item::get_remove_url($subscription_id, $item_id)); ?>" class="subscription-product-remove link-underline link-underline_primary mr-3" style="<?php echo $is_product_skipped ? 'display: none;' : ''; ?>">Remove</a>
                <?php endif; ?>
              <?php endif; ?>
              <?php if ($subscription->get_status() == 'active') : ?>
                <?php if (in_array($item['product_id'], $skippable_items)) : ?>
                  <button class="link-underline link-underline_darker-gray unskip_shop_subscription_item_once" data-subscription-product-id="<?php echo esc_attr($item_id); ?>" data-product_id='<?php echo esc_attr($item['product_id']); ?>' data-product_name='<?php echo esc_attr($item['name']); ?>' data-subscription_id='<?php echo esc_attr($subscription_id); ?>'>Add It Back</button>
                <?php else : ?>
                  <button class="link-underline link-underline_darker-gray skip_shop_subscription_item_once" data-subscription-product-id="<?php echo esc_attr($item_id); ?>" data-product_id='<?php echo esc_attr($item['product_id']); ?>' data-product_name='<?php echo esc_attr($item['name']); ?>' data-subscription_id='<?php echo esc_attr($subscription_id); ?>'>Skip Once</button>
                <?php endif; ?>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php if ($subscription->has_status(['completed', 'processing']) && ($purchase_note = get_post_meta($_product->id, '_purchase_note', true))) : ?>
      <div class="product-purchase-note">
        <div colspan="3"><?php echo esc_html(wp_kses_post(wpautop(do_shortcode($purchase_note)))); ?></div>
      </div>
    <?php endif; ?>
  <?php endforeach; ?>
  <?php if ($status === 'Active') : ?>
    <button type="button" id="subscription-details_add-product" class="subscription-product-card list-group-item p-3" data-toggle="modal" data-target="#subscriptionAddProductModel">
      <div class="row">
        <div class="col text-center">
          <i class="fa fa-plus" aria-hidden="true"></i> Add Product(s) To Subscription
        </div>
      </div>
    </button>
  <?php endif; ?>
</div>
