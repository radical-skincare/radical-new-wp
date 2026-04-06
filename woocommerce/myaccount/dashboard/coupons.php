<?php
$current_user = wp_get_current_user();

function radical_get_user_coupons($current_user = null, $is_active_affiliate = false) {
  $user_email = $current_user->user_email;
  $coupon_query_args = [
    'post_type' => 'shop_coupon',
    'post_status'   => 'publish',
  ];
  if ($v_affiliate_id = get_user_meta($current_user->ID, 'v_affiliate_id', true)) {
    $coupon_query_args['meta_query'] = [
      'relation' => 'OR',
      [
        'key' => 'v_discount_affiliate_id',
        'value' => $v_affiliate_id,
        'compare' => '='
      ],
      [
        'key' => 'customer_email',
        'value' => $user_email,
        'compare' => 'LIKE',
      ]
    ];
  } else {
    $coupon_query_args['meta_key'] = 'customer_email';
    $coupon_query_args['meta_value'] = $user_email;
    $coupon_query_args['meta_compare'] = 'LIKE';
  }
  $coupons_query = new WP_Query($coupon_query_args);
  $coupons = [];
  $amount_symbol = get_woocommerce_currency_symbol();
  if ($coupons_query->have_posts()) {
    foreach ($coupons_query->get_posts() as $value) {
      $coupon = (array)$value;
      $meta = get_post_meta($value->ID);
      $discount_type = $meta['discount_type'][0];
      $date_expires = isset($meta['date_expires']) ? $meta['date_expires'][0] : null;
      $usage_limit = isset($meta['usage_limit']) ? $meta['usage_limit'][0] : null;
      $usage_count = isset($meta['usage_count']) ? $meta['usage_count'][0] : null;
      $meta['amount_symbol'] = $amount_symbol;
      $meta['expired'] = false;
      if ($usage_limit && $usage_limit <= $usage_count) {
        $meta['expired'] = true;
      }
      if ($date_expires && date('m/d/y', $date_expires) <= date('m/d/y')) {
        $meta['expired'] = true;
      }
      if ($discount_type == 'percent_andor_recurring_percent' || $discount_type == 'recurring_percent' || $discount_type == 'percent' || $discount_type == 'sign_up_fee_percent') {
        $meta['amount_symbol'] = '%';
      }
      $coupon['meta'] = $meta;
      $coupons[] = $coupon;
    }
  }
  wp_reset_postdata();
  $all_coupons = [
    'active' => [],
    'expired' => [],
  ];
  foreach ($coupons as $key => $coupon) {
    $do_not_show_in_account_area = get_field('do_not_show_in_account_area', $coupon['ID']);
    if ($do_not_show_in_account_area) {
      continue;
    }
    if ($coupon['meta']['expired']) {
      $all_coupons['expired'][] = $coupon;
    } else {
      $all_coupons['active'][] = $coupon;
    }
  }
  if (!$is_active_affiliate) {
    $ref_affiliate_id = get_user_meta($current_user->ID, 'v_ref_affiliate_id', true);
    if ($ref_affiliate_id) {
      $ref_user_id = gigfiliate_get_user_id_by_affiliate_id($ref_affiliate_id);
      $ref_primary_affiliate_coupon_code = get_user_meta($ref_user_id, 'primary_affiliate_coupon_code', true);
      $wc_coupon = new WC_Coupon($ref_primary_affiliate_coupon_code);
      $new_coupon = (array)$wc_coupon;
      $meta = get_post_meta($wc_coupon->get_id());
      $discount_type = $meta['discount_type'][0];
      $date_expires = isset($meta['date_expires']) ? $meta['date_expires'][0] : null;
      $usage_limit = isset($meta['usage_limit']) ? $meta['usage_limit'][0] : null;
      $usage_count = isset($meta['usage_count']) ? $meta['usage_count'][0] : null;
      $meta['amount_symbol'] = $amount_symbol;
      $meta['expired'] = false;
      if ($usage_limit && $usage_limit <= $usage_count) {
        $meta['expired'] = true;
      }
      if ($date_expires && date('m/d/y', $date_expires) <= date('m/d/y')) {
        $meta['expired'] = true;
      }
      if ($discount_type == 'percent_andor_recurring_percent' || $discount_type == 'recurring_percent' || $discount_type == 'percent' || $discount_type == 'sign_up_fee_percent') {
        $meta['amount_symbol'] = '%';
      }
      $new_coupon['meta'] = $meta;
      $new_coupon['post_title'] = $wc_coupon->get_code();
      $all_coupons['active'][] = $new_coupon;
    }
  }
  return $all_coupons;
}
$all_coupons = radical_get_user_coupons($current_user, $is_active_affiliate);
?>
<?php if ($all_coupons['active'] || $all_coupons['expired']) : ?>
  <section id="dashboard-coupons" class="dashboard-coupons mb-3">
    <?php if ($active_coupons = $all_coupons['active']) : ?>
      <h3 class="mb-2">Available Coupons</h3>
      <div class="row justify-content-left align-items-stretch" style="row-gap: 1rem;">
        <?php foreach ($active_coupons as $coupon) : ?>
          <div class="col-sm-6 col-lg-4">
            <?php include get_template_directory() . '/woocommerce/myaccount/dashboard/coupon-card.php'; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <?php if ($expired_coupons = $all_coupons['expired']) : ?>
      <h3 class="mt-3 mb-2">Used / Expired Coupons</h3>
      <div class="row justify-content-left align-items-stretch" style="row-gap: 1rem;">
        <?php foreach ($expired_coupons as $coupon) : ?>
          <div class="col-sm-6 col-lg-4">
            <?php include get_template_directory() . '/woocommerce/myaccount/dashboard/coupon-card.php'; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
<?php endif; ?>
