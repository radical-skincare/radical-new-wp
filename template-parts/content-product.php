<?php
global $post;
$product = $args['product'] ?? $post;
$wc_product = wc_get_product( $product->ID );
if (!$wc_product) {
  error_log('General Product not found: '.$product->ID);
  error_log($_SERVER['REQUEST_URI']);
  return;
}
$product_sku = $wc_product->get_sku();
$product_feat_img = wp_get_attachment_url( $wc_product->get_image_id() );
$permalink = get_permalink( $product->ID );
$title = $wc_product->get_title();
$_wcsatt_schemes = get_post_meta( $product->ID, '_wcsatt_schemes', true );
$stock_status = get_post_meta($product->ID, '_stock_status', true);
$cannot_access_bp_product = radical_cannot_access_brand_partner_product($product->ID);
$disable_add_to_cart = false;
$visibly_sold_out = get_field('visibly_sold_out', $product->ID);
$visibly_sold_out = is_null($visibly_sold_out) ? false : $visibly_sold_out;

$_price = $wc_product->get_price();
$_regular_price = $wc_product->get_regular_price();
$_sale_price = $wc_product->get_sale_price();
$_is_on_sale = $wc_product->is_on_sale();
$_is_variable = $wc_product->is_type('variable');
if ($_is_variable) {
  $_price = $wc_product->get_variation_price();
  $_regular_price = $wc_product->get_variation_regular_price();
  $_sale_price = $wc_product->get_variation_sale_price();
}
$average = $wc_product->get_average_rating();
?>
<div class="card card-product">
  <div class="product-loader">
    <div class="spinner-border" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  </div>
  <div class="card-body">
    <a href="<?php echo esc_url($permalink); ?>" class="d-block relative text-darkergray">
      <?php if ($stock_status === 'outofstock' || $visibly_sold_out) : ?>
        <span class="card-product_badge badge badge-sold-out">Sold Out</span>
      <?php elseif ($badge_text = get_field('badge_text', $product->ID)) : ?>
        <span class="card-product_badge badge badge-new"><?php echo $badge_text; ?></span>
      <?php elseif ($_is_on_sale) : ?>
        <?php if ($on_sale_label = get_field('on_sale_label', $product->ID)) : ?>
          <span class="card-product_badge badge badge-sale"><?php echo $on_sale_label; ?></span>
        <?php else : ?>
         <span class="card-product_badge badge badge-sale">On Sale</span>
        <?php endif; ?>
      <?php else : ?>
        <span class="card-product_badge badge badge-rating">
          <div class="star-rating">
            <span style="width:<?php echo ( ( $average / 5 ) * 100 ); ?>%;"></span>
          </div>
        </span>
      <?php endif; ?>
      <div class="card-product_price">
        <?php
        echo $wc_product->get_price_html();
        ?>
      </div>
      <div class="card-product_image">
        <img src="<?php echo esc_url($product_feat_img); ?>" alt="<?php echo esc_attr($title); ?>"/>
      </div>
      <h4 class="card-product_title"><?php echo $title; ?></h4>
    </a>
    <?php ?>
    <div class="d-none" id="product_description_<?php echo esc_attr($product->ID); ?>">
      <?php echo get_the_excerpt( $product->ID ); ?>
    </div>
    <div class="card-product_actions">
      <div class="row justify-content-center w-100 m-0">
        <?php if ($stock_status !== 'outofstock' && !$visibly_sold_out) : ?>
          <div class="col-6">
            <button type="button" class="btn text-darkergray mx-auto d-block" data-toggle="modal" data-target="#product-quickview-modal"
              data-permalink="<?php echo esc_url($permalink); ?>"
              data-thumbnail="<?php echo esc_url($product_feat_img); ?>"
              data-title="<?php echo esc_attr($title); ?>"
              data-id="<?php echo esc_attr($product->ID); ?>"
              data-sku="<?php echo esc_attr($product_sku); ?>"
              data-is_purchasable="<?php echo esc_attr($wc_product->is_purchasable()); ?>"
              data-price="<?php echo esc_attr($_price); ?>"
              data-regular_price="<?php echo esc_attr($_regular_price); ?>"
              data-sale_price="<?php echo esc_attr($_sale_price); ?>"
              data-is_on_sale="<?php echo esc_attr($_is_on_sale); ?>"
              <?php if ($_wcsatt_schemes) : ?>
                data-wcsatt_schemes="<?php echo esc_attr(json_encode($_wcsatt_schemes)); ?>"
              <?php endif; ?>
              data-is_variable="<?php echo esc_attr($_is_variable); ?>"
              data-cannot-access-brand-partner-product="<?php echo $cannot_access_bp_product ? 'true' : 'false'; ?>"
              data-disable_add_to_cart="<?php echo $disable_add_to_cart ? 'true' : 'false'; ?>">
              Quick View
            </button>
          </div>
        <?php endif; ?>
        <?php if (!$disable_add_to_cart && $_wcsatt_schemes && (!$cannot_access_bp_product)) : ?>
          <div class="col-6">
            <?php if ($stock_status !== 'outofstock' && !$visibly_sold_out) : ?>
              <div class="dropdown card-overlay-add-to-cart single-add-to-cart-button-dropdown">
                <button class="add_to_cart_button btn text-darkergray dropdown-toggle" type="button"
                  id="single_add_to_cart_button_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                  data-product_id="<?php echo esc_attr($product->ID); ?>"
                  data-product_sku="<?php echo esc_attr($product_sku); ?>"
                  aria-label="" rel="nofollow">
                  <span class="added_to_cart_label font-weight-normal">Added To Cart</span>
                  <span class="add_to_cart_label font-weight-normal">Add To Cart</span>
                </button>
                <div class="dropdown-menu" aria-labelledby="single_add_to_cart_button_dropdown_quick_view" style="margin-top: -16px; margin-left: 4px;">
                  <a class="dropdown-item one-time-purchase-action" href="javascript:void(0);">One Time Purchase</a>
                  <?php foreach ($_wcsatt_schemes as $_wcsatt_scheme) : ?>
                    <?php
                    $subscription_period_interval = (int)$_wcsatt_scheme['subscription_period_interval'];
                    $subscription_period = $_wcsatt_scheme['subscription_period'];
                    $interval_text = 'Every';
                    if ($subscription_period_interval != 1) {
                      $interval_text .= ' ' . $subscription_period_interval;
                    }
                    $period_text = $subscription_period_interval != 1 ? ucfirst($subscription_period) . 's' : ucfirst($subscription_period);
                    $formatted_text = 'Refill ' . $interval_text . ' ' . $period_text . ' 10% Off';
                    ?>
                    <a class="dropdown-item refill-action-option text-capitalize" href="javascript:void(0)"
                      subscription_period_interval="<?php echo esc_attr($subscription_period_interval); ?>" subscription_period="<?php echo esc_attr($subscription_period); ?>">
                      <?php echo esc_html($formatted_text); ?>
                    </a>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php else : ?>
              <a href="<?php echo esc_url($wc_product->get_permalink()); ?>" class="btn text-darkergray mx-auto d-block" aria-label="<?php echo esc_attr($title); ?>">
                View More
              </a>
            <?php endif; ?>
          </div>
        <?php elseif(!$disable_add_to_cart && !$_wcsatt_schemes && (!$wc_product->is_type('variable') && !$cannot_access_bp_product)) : ?>
          <div class="col-6">
            <?php if ($stock_status !== 'outofstock' && !$visibly_sold_out) : ?>
              <a href="<?php echo esc_url($wc_product->add_to_cart_url()); ?>" value="<?php echo esc_attr($wc_product->get_id()); ?>" class="ajax_add_to_cart add_to_cart_button btn text-darkergray mx-auto d-block" data-product_id="<?php echo esc_attr($wc_product->get_id()); ?>" data-product_sku="<?php echo esc_attr($product_sku); ?>" aria-label="Add &ldquo;<?php echo esc_attr($title); ?>&rdquo; to your cart">
                Add to Cart
              </a>
            <?php else : ?>
              <a href="<?php echo esc_url($wc_product->get_permalink()); ?>" class="btn text-darkergray mx-auto d-block" aria-label="<?php echo esc_attr($title); ?>">
                View More
              </a>
            <?php endif; ?>
          </div>
        <?php else : ?>
          <div class="col-6">
            <a href="<?php echo esc_url($wc_product->get_permalink()); ?>" class="btn text-darkergray mx-auto d-block" aria-label="<?php echo esc_attr($title); ?>">
              View More
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
