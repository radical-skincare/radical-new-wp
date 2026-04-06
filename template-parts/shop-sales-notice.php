<?php
$active_subscriber_discounts = get_field('active_subscriber_discounts', 'option');
$discount_active = false;
$serums_discount_active = (isset($active_subscriber_discounts['serums_discount']) && $active_subscriber_discounts['serums_discount']);
$sales_discount_active = (isset($active_subscriber_discounts['sale_category_discount']) && $active_subscriber_discounts['sale_category_discount']);
if ($serums_discount_active) {
  $discount_active = true;
} else if ($sales_discount_active) {
  $discount_active = true;
}
?>
<?php if ($active_subscriber_discounts && $discount_active) : ?>
  <?php
  // Check if the user is logged in
  global $product;
  // Check if the product exists and in the category
  $is_archive = false;
  $product_has_cat = false;
  $text = '';
  if ($serums_discount_active) {
    $is_archive = (is_product_category('serums'));
    $product_has_cat = $product && has_term('serums', 'product_cat', $product->get_id());
    $serums_promo_discount = get_field('serums_promo_discount', 'option');
    $text = 'Thank you for being a Radical on Repeat subscriber, get ' . $serums_promo_discount . '% OFF all Serums! Discount calculated at checkout.';
  } else if ($sales_discount_active) {
    $is_archive = (is_product_category('sale'));
    $product_has_cat = $product && has_term('sale', 'product_cat', $product->get_id());
    $text = 'Thank you for being a Radical on Repeat subscriber, get 20% OFF all Sale products! Discount calculated at checkout.';
  }
  $not_eligible_text = 'Special discounts to customers who are subscribed to Radical on Repeat!';
  ?>
  <?php if ($is_archive || $product_has_cat) : ?>
    <?php
    $is_user_logged_in = is_user_logged_in();
    $user_has_active_subscription = $is_user_logged_in ? radical_user_has_active_subscription(get_current_user_id()) : false;
    ?>
    <?php if ($is_user_logged_in && $user_has_active_subscription) : ?>
      <div class="alert alert-info mx-auto" role="alert" style="width: fit-content;">
        <i class="fa fa-info-circle mr-2" aria-hidden="true"></i>
        <?php echo esc_html($text); ?>
      </div>
    <?php else : ?>
      <div class="alert alert-info mx-auto" role="alert" style="width: fit-content;">
        <i class="fa fa-info-circle mr-2" aria-hidden="true"></i>
        <?php echo esc_html($not_eligible_text); ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
<?php endif; ?>
