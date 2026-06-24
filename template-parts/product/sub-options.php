<?php
if (!defined('ABSPATH')) {
    exit(); // Exit if accessed directly
}
global $product;
$site_url = get_site_url();
$post_id = get_the_ID();
$product_id = $product->get_id();
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
// $is_active_affiliate = true; // Show Brand Partner price to everyone
$product_price = (float) $product->get_price();
$regular_price = (float) $product->get_regular_price();
if (radical_cannot_access_brand_partner_product($post_id) || !$wcsatt_schemes) {
  return;
}
?>
<style>
.row.prices-row.mb-3 {
    display: none;
}
.woocommerce:where(body:not(.woocommerce-uses-block-theme)) div.product p.price, .woocommerce:where(body:not(.woocommerce-uses-block-theme)) div.product span.price {
    color: black;
}
/* Card-like radios */
.woo-sub-options .list-group-item {
  border: 1.5px solid #e1e5ea;
  border-radius: .75rem;
  padding: 1rem 1.25rem;
  cursor: pointer;
  transition: border-color .2s, box-shadow .2s, background-color .2s;
}

.woo-sub-options .list-group-item:hover {
  border-color: #b20839;
}

@supports(selector(:has(*))) {
  .woo-sub-options .list-group-item:has(input[type="radio"]: checked) {
    border-color: #b20839;
    box-shadow: 0 0 0 .15rem #b207383b;
    ;
    background: #fbfffd;
  }
}

.woo-sub-options .list-group-item.option {
  position: relative;
}

.woo-sub-divider {
  position: relative;
}

.woo-sub-divider>hr {
  margin: .75rem 0;
}

.woo-sub-divider .divider-badge {
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
}

.badge.most-popular {
  background-color: #fff;
  color: #0b6445;
  position: absolute;
  top: -14px;
  right: 14px;
  border: 1px solid #0b6445;
}

.badge.save {
  background: #e6f7ef;
  color: #0a6444;
  font-weight: 700;
}

/* Fallback selected styling */
.woo-sub-options .list-group-item.is-selected {
  border-color: #b20839;
  box-shadow: 0 0 0 .15rem rgba(10, 100, 68, .15);
  background: #fbfffd;
}

/* Improve visibility of divider badge */
.woo-sub-divider .divider-badge {
  padding: .25rem .75rem;
  font-weight: 600;
}

input[type="radio"] {
  appearance: none;
  /* removes default radio */
  -webkit-appearance: none;
  -moz-appearance: none;
  width: 20px;
  height: 20px;
  border: 2px solid #b20839;
  border-radius: 50%;
  /* makes it round */
  cursor: pointer;
  position: relative;
}

input[type="radio"]:checked {
  background-color: #b20839;
  box-shadow: inset 0 0 0 4px #fff;
  /* creates inner circle */
}
</style>
<div class="woo-sub-vars-radio-wrap mb-3">
  <div class="list-group woo-sub-options">
    <!-- One-time purchase option -->
    <label class="list-group-item d-flex align-items-center justify-content-between rounded" aria-label="One-time purchase">
      <span class="d-flex align-items-center gap-2">
        <input type="radio" class="form-check-input position-relative m-0 mr-2" name="sub_plan" value="one-time-purchase" aria-describedby="otp-price" />
        <span class="font-weight-bold">One-Time Purchase</span>
      </span>
      <span id="otp-price" class="price fw-semibold">
        <?php if ( $product->is_on_sale() ) : ?>
          <strike class="text-muted text-decoration-line-through"><?php echo wc_price( $regular_price ); ?></strike>
          <span class="fw-bold ms-1"><?php echo wc_price( $product_price ); ?></span>
        <?php else : ?>
          <?php echo wc_price( $regular_price ?: $product_price ); ?>
        <?php endif; ?>
      </span>
    </label>
    <!-- Divider -->
    <div class="woo-sub-divider my-4">
      <hr class="m-0" />
      <span class="divider-badge bg-white">Radical on Repeat</span>
    </div>
    <?php $i = 0; ?>
    <?php foreach ($wcsatt_schemes as $scheme) : ?>
      <?php
      $interval_label = radical_wcsatt_scheme_interval_period_text($scheme);
      $discount_text = radical_wcsatt_scheme_data_discount_text($scheme, $product_price);
      $sub_price = (float) radical_wcsatt_scheme_data_price($scheme, $product_price);
      $is_popular = $i === 0;
      $plan_value = $scheme['subscription_period_interval'] . '_' . $scheme['subscription_period'];
      ?>
      <label class="list-group-item option d-flex align-items-center justify-content-between rounded mb-2" data-interval="<?php echo esc_html($plan_value); ?>" aria-label="<?php echo esc_html($interval_label); ?>" style="column-gap: 8px;">
        <span class="d-flex align-items-center justify-content-between" style="gap:0.25rem">
          <input type="radio" class="form-check-input position-relative m-0 mr-2" name="sub_plan" value="refill" data-plan="<?php echo esc_html($plan_value); ?>" data-sub-price="<?php echo esc_html($sub_price); ?>" aria-describedby="sub-<?php echo $i; ?>-price" />
          <span class="font-weight-bold"><?php echo esc_html($interval_label); ?></span>
          <?php if ($discount_text) : ?>
            <?php if (isset($regular_price) && $regular_price > $sub_price) : ?>
              <span class="badge save ms-2">
                Save <?php echo wc_price($regular_price - $sub_price); ?>
              </span>
            <?php endif; ?>
          <?php endif; ?>
          <?php if ($is_popular) : ?>
            <span class="badge most-popular ms-2">MOST POPULAR</span>
          <?php endif; ?>
        </span>
        <span id="sub-<?php echo $i; ?>-price" style="display: flex ; flex-direction: column;">
          <span class="fw-bold"><?php echo wc_price($sub_price); ?></span>
          <?php if (isset($regular_price) && $regular_price > $sub_price) : ?>
            <strike class="text-muted text-decoration-line-through"><?php echo wc_price($regular_price); ?></strike>
          <?php endif; ?>
        </span>
      </label>
      <?php $i++; ?>
    <?php endforeach; ?>
  </div>
  <?php /* Keep a hidden select if other logic still reads it; we will sync it from JS. */ ?>
  <div class="d-none">
    <select name="convert_to_sub_dropdown<?php echo $product_id; ?>" id="refill_frequencies" class="d-none">
      <?php foreach ($wcsatt_schemes as $scheme) : ?>
        <option value="<?php echo esc_html($scheme['subscription_period_interval'] . '_' . $scheme['subscription_period']); ?>">
          <?php echo esc_html(radical_wcsatt_scheme_interval_period_text($scheme)); ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
</div>
<!-- Hidden fields consumed by add-to-cart logic.
     convert_to_sub has no name attribute on purpose: the WooCommerce All
     Products for Subscriptions plugin renders its own native radio inputs
     named convert_to_sub_<id> (one per scheme + a "0" one-time option,
     hidden visually by CSS). If our field shared that name, the form would
     submit two values for the same key and PHP would only keep whichever
     one came last in the request body — a race that silently dropped the
     user's subscription choice in production. This element is kept only so
     existing JS bookkeeping (.val() calls) has something to write to. -->
<input type="hidden" name="subscribe-to-action-input" value="no" id="subscribe-to-action-input" />
<input type="hidden" value="0" id="convert_to_sub" />
<input type="hidden" name="product_id" value="<?php echo esc_html($product_id); ?>" id="product_id" />
