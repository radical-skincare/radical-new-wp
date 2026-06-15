<?php

add_action('after_setup_theme', function () {
  add_theme_support('woocommerce');
});

/**
 * Enable Product Revisions
 */
add_filter('woocommerce_register_post_type_product', function ($args) {
  $args['supports'][] = 'revisions';
  return $args;
});

// Remove related products output
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

// Remove the product rating display on product loops
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);

add_action('template_redirect', function () {
  if (is_product())
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
});

remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

add_action('woocommerce_single_product_summary', function () {
  woocommerce_template_loop_rating();
}, 1);

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 5);

/*
add_filter('woocommerce_short_description', function ($description) {
  global $post;
  if ($post->post_name == 'age-defying-exfoliating-pads') {
    return $description;
  }
  return empty($description) ? $description :  $description . '<span id="loadMore" class="pl-2">Show More <i class="fa fa-angle-down"></i></span>';
});
*/

function radical_search_products() {
  check_ajax_referer('radical_ajax_nonce', 'nonce');
  $posts_per_page = ((isset($_POST['per_page'])) ? $_POST['per_page'] : 6);
  $res = ['success' => false, 'products' => [], 'total' => 0, 'per_page' => $posts_per_page];
  $args = [
    'post_type' => 'product',
    'posts_per_page' => $posts_per_page,
    'post_status' => ['publish'],
    'tax_query'   => [[
      'taxonomy'  => 'product_visibility',
      'terms'     => ['exclude-from-catalog'],
      'field'     => 'name',
      'operator'  => 'NOT IN',
    ]]
  ];
  if (isset($_POST['search'])) {
    $args['s'] = $_POST['search'];
  }
  if (isset($_POST['page'])) {
    $args['paged'] = $_POST['page'];
  }
  if (isset($_POST['only_subscription'])) {
    $args['meta_key'] = '_wcsatt_schemes';
  }
  $query = (new WP_Query($args));
  $products = $query->posts;
  $res['total'] = $query->found_posts;
  $res['success'] = true;
  if (empty($products)) {
    exit(json_encode($res));
  }
  foreach ($products as $post) {
    $product = wc_get_product($post->ID);
    if (!$product) {
      continue;
    }
    $new_product = [
      'id' => $post->ID,
      'thumbnail_url' => wp_get_attachment_url($product->get_image_id()),
      'name' => $product->get_name(),
      'price' => $product->get_price(),
      'sku' => $product->get_sku(),
      'add_to_cart_url' => $product->add_to_cart_url(),
      'is_in_stock' => $product->is_in_stock(),
      'product_link' => $product->get_permalink(),
      'type' => $product->get_type(),
      'on_sale' => $product->is_on_sale(),
      'visibly_sold_out' => get_field('visibly_sold_out', $post->ID)
    ];
    // if (isset($_POST['only_subscription'])) {
    //   $new_product['add_to_cart_key'] = wp_create_nonce('wcsatt_nonce_'.$post->ID);
    // }
    $res['products'][] = $new_product;
  }
  exit(json_encode($res));
}

add_action('wp_ajax_search_product', 'radical_search_products');
add_action('wp_ajax_nopriv_search_product', 'radical_search_products');

add_filter('woocommerce_before_main_content', function () {
  if (!is_product() && !is_product_category()) {
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
  }
});

add_filter('woocommerce_loop_add_to_cart_link', function ($add_to_cart_html, $product, $args) {
  $product_id = $product->get_id();
  $product_sku = $product->get_sku();
  $_wcsatt_schemes = get_post_meta($product_id, '_wcsatt_schemes', true);
  $before = '<div class="d-none" id="product_description_' . ($product_id) . '">' . get_the_excerpt($product_id) . '</div>';
  $before .= ' <div class="product-actions-container"> <div class="d-flex align-items-center justify-content-around mt-4">';
  $before .= "<button type='button' class='btn bg-white text-darkergray' data-toggle='modal' data-target='#product-quickview-model' data-permalink='" . get_permalink($product_id) . "' data-thumbnail='" . wp_get_attachment_url($product->get_image_id()) . "' data-title='" . ($product->get_title()) . "' data-id='" . $product_id . "' data-sku='" . $product_sku . "' data-is_purchasable='" . $product->is_purchasable() . "' data-price='" . ($product->get_price()) . "' data-wcsatt_schemes='" . json_encode($_wcsatt_schemes) . "'>Quick View</button>";
  if ($_wcsatt_schemes) {
    $before .= '
    <div class="dropdown card-overlay-add-to-cart single-add-to-cart-button-dropdown">
      <button class="add_to_cart_button btn bg-white text-darkergray px-2 dropdown-toggle waves-effect waves-light" type="button" id="single_add_to_cart_button_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-product_id="' . $product_id . '" data-product_sku="' . $product_sku . '" aria-label="" rel="nofollow">
        <span class="added_to_cart_label">Added to Cart</span>
        <span class="add_to_cart_label">Add to Cart</span>
      </button>
      <div class="dropdown-menu" aria-labelledby="single_add_to_cart_button_dropdown_quick_view" style="margin-top: -16px; margin-left: 4px;">
        <a class="dropdown-item one-time-purchase-action" href="javascript:void(0);">One Time Purchase</a>
        <a class="dropdown-item refill-action" href="javascript:void(0);">Refill 10% Off</a>
        <a class="dropdown-item back-action d-none" href="javascript:void(0);">
          <i class="fa fa-angle-left" aria-hidden="true"></i> Back
        </a>';
    foreach ($_wcsatt_schemes as $_wcsatt_scheme) {
      $subscription_period_interval = $_wcsatt_scheme['subscription_period_interval'];
      $subscription_period = $_wcsatt_scheme['subscription_period'];
      $before .= "<a class='dropdown-item refill-action-option d-none text-capitalize' href='javascript:void(0)' subscription_period_interval='$subscription_period_interval' subscription_period='$subscription_period'>Every $subscription_period_interval $subscription_period</a>";
    }
    $before .= '
        </div>
      </div>
    </div>';
    return $before;
  }
  return $before . $add_to_cart_html . '</div></div>';
}, 10, 3);

add_filter('woocommerce_product_single_add_to_cart_text', function ($text = '', $post = '') {
  global $product;
  // if ( $product->get_type() == 'variable-subscription' ) {
  $text = 'Add To Cart';
  // }
  return $text;
}, 2, 10);

add_shortcode('woocommerce_currency_symbol', function () {
  return get_woocommerce_currency_symbol();
});


/**
 * Add a discount to an Orders programmatically
 * (Using the FEE API - A negative fee)
 *
 * @since  3.2.0
 * @param  int     $order_id  The order ID. Required.
 * @param  string  $title  The label name for the discount. Required.
 * @param  mixed   $amount  Fixed amount (float) or percentage based on the subtotal. Required.
 */
function wc_order_add_discount($order_id, $title, $amount) {
  $order = wc_get_order($order_id);
  $subtotal = $order->get_subtotal();
  $item = new WC_Order_Item_Fee();

  if (strpos($amount, '%') !== false) {
    $percentage = (float) str_replace(array('%', ' '), array('', ''), $amount);
    $percentage = $percentage > 100 ? -100 : -$percentage;
    $discount   = $percentage * $subtotal / 100;
  } else {
    $discount = (float) str_replace(' ', '', $amount);
    $discount = $discount > $subtotal ? -$subtotal : -$discount;
  }

  $item->set_name($title);
  $item->set_amount($discount);
  $item->set_total($discount);
  $item->save();

  $order->add_item($item);
  $order->calculate_totals(true);
  $order->save();
}


/*
 * Fix the subscriptions not calculating shipping fees + Adding note Radical On Repeat Gift
 */
add_action('woocommerce_subscription_renewal_payment_complete', function ($subscription, $order) {
  $order->calculate_totals();
  $subscription->calculate_totals();
}, 10, 2);

add_action('woocommerce_checkout_subscription_created', function ($subscription) {
  $subscription->calculate_totals();
});

add_action('woocommerce_email_order_details', function ($order, $sent_to_admin, $plain_text, $email) {
  if (!wcs_order_contains_subscription($order)) {
    return;
  }
  echo get_field('radical_email_header', 'option');
}, 10, 4);


add_action('woocommerce_email_customer_details', function ($order, $sent_to_admin, $plain_text, $email) {
  if (!wcs_order_contains_subscription($order)) {
    return;
  }
  echo '<div style="text-align:center;"><p>If you would like to make changes to your Radical on Repeat shipments, please </br> click the link below. You still have time to edit your subscription.</p> <a href="' . get_site_url() . '/account/" style="padding:10px 15px;display:block;margin:5px auto;background-color:#b20839;color:#fff;text-decoration:none; width:150px;">View My Account</a></div>';
  echo '<div style="padding: 1px;background-color: #dcdcdc;margin: 30px;"></div>';
}, 30, 4);


add_action('woocommerce_email_footer', 'radical_email_footer');
add_action('gigfiliate_wp_email_footer', 'gigfiliate_radical_footer', 10, 2);

function gigfiliate_radical_footer($notification, $template_tags)
{
  echo radical_email_footer();
}

function radical_email_footer()
{
  $radical_email_footer = get_field('radical_email_footer', 'option');
  if ($radical_email_footer && isset($radical_email_footer['radical_email_footer_content'])) {
    $content = '<div>';
    $content .= $radical_email_footer['radical_email_footer_content'];
    $content .= '
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>';
    foreach ($radical_email_footer['radical_email_footer_icons'] as $icon) {
      $content .= '
                <td align="center" style="padding:12px">
                  <img src="' . $icon['icon']['url'] . '" style="width: 100%; height: 64px; object-fit: contain; margin: 0 auto;"/>
                  <p style="font-size: 12px;margin: 0;">' . $icon['label'] . '</p>
                </td>';
    }
    $content .= '
          </tr>
        </table>
      </div>';
    echo $content;
  }
}


add_filter('woocommerce_get_availability', function ($availability, $_product) {
  // Static variable to track if we've already displayed the waitlist message
  static $waitlist_displayed = false;
  
  // Change Out of Stock Text
  $visibly_sold_out = get_field('visibly_sold_out', get_the_ID());
  if (!$_product->is_in_stock() || $visibly_sold_out) {
    // $availability['availability'] = __('Sold Out', 'woocommerce');
    $availability['availability'] = __('', 'woocommerce');
    
    // Only display the waitlist message once per page load
    // For variable products, this prevents duplicate output when checking multiple variations
    if (!$waitlist_displayed) {
      if (function_exists('radical_render_sold_out_waitlist_form')) {
        radical_render_sold_out_waitlist_form($_product);
      }
      $waitlist_displayed = true;
    }
  } else {
    // Add urgency messaging for in-stock products that manage stock
    if ($_product->get_manage_stock() && $_product->is_in_stock()) {
      $stock_quantity = $_product->get_stock_quantity();
      if ($stock_quantity !== null && $stock_quantity > 0) {
        $availability['availability'] = sprintf(__('Hurry, there are only %d left in stock.', 'woocommerce'), $stock_quantity);
        // Add custom class for styling - red text if 5 or less
        if ($stock_quantity <= 5) {
          $availability['class'] = 'in-stock urgency-low-stock text-danger';
        } else {
          $availability['class'] = 'in-stock urgency-stock';
        }
      }
    }
  }
  return $availability;
}, 1, 2);

/**
 * Hide shipping rates when free shipping is available.
 * Updated to support WooCommerce 2.6 Shipping Zones.
 *
 * @param array $rates Array of rates found for the package.
 * @return array
 */
add_filter('woocommerce_package_rates', function ($rates, $package) {
  $hide_shipping_rates = get_field('hide_shipping_rates_when_free_shipping_is_available', 'option');
  if (!isset($hide_shipping_rates) || !$hide_shipping_rates) {
    return $rates;
  }
  $free = array();
  foreach ($rates as $rate_id => $rate) {
    if ('free_shipping' === $rate->method_id) {
      $free[$rate_id] = $rate;
      break;
    }
  }
  return !empty($free) ? $free : $rates;
}, 9999, 2);

/*
 * Checkout - Display a notice for certain countries
 * Austria, Germany, Switzerland
function radical_skincare_display_notice_for_certain_countries() {
  if ( ! is_checkout() ) return; ?>
  <script>
  jQuery( function($) {
  $(document).ready(function() {
    setTimeout(function() {
      check_Country_Value( $('select#billing_country').val() );
      check_Country_Value( $('select#shipping_country').val() );
    }, 1500); // 1.5 sec
  });
  $('select#billing_country, select#shipping_country').on( 'change', function () {
      check_Country_Value( $(this).val() );
  });
  function check_Country_Value( country_value ) {
    if ( country_value === "AT" || country_value === "DE" || country_value === "CH" ) {
      jQuery("#checkoutNoticeModal").modal("show");            
    }
  }
  });
  </script>
  <?php
}
add_action('wp_footer', 'radical_skincare_display_notice_for_certain_countries', 50);
*/


/**
 * Remove password strength check.
 */
add_action('wp_print_scripts', function () {
  wp_dequeue_script('wc-password-strength-meter');
}, 10);


/**
 * Change Subscription 'Cancel' Button Action Text
 */
add_filter('wcs_view_subscription_actions', function ($actions, $subscription) {
  if (isset($actions['cancel'])) {
    $actions['cancel']['name'] = 'Pause';
  }
  return $actions;
}, 100, 2);


/**
 * Add checkbox field to the checkout
 **/
add_action('woocommerce_after_order_notes', function ($checkout) {
  if (isset($_COOKIE['Ambassador_Enrollment_InProgress'])) {
    return;
  }

  $enable_shipment_message_notification = get_field('enable_shipment_message_notification', 'option');
  if ($enable_shipment_message_notification) {
    ?>
    <div class="fields">
      <p id="receive-shipment-update-using-sms" class="form-check" style="margin-bottom: 1rem;margin-top: 1rem;">
        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox form-check-label" for="receive-shipment-update-using-sms-checkbox">
          <input id="receive-shipment-update-using-sms-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox form-check-input" type="checkbox" name="receive-shipment-update-using-sms" <?php echo (isset($_POST['receive-shipment-update-using-sms'])) ? 'checked' : ''; ?> value="1" />
          Get order shipment updates via SMS (Optional) (Only Available For US Orders)
        </label>
      </p>
    </div>
  <?php
  }

  $checkout = get_field('checkout', 'option');
  if (!$checkout['enable_go-green']) {
    return;
  }
  ?>
  <style>
    .popover-header::after {
      content: "×";
      position: absolute;
      top: -2px;
      right: 0;
      padding: 9px 10px;
      cursor: pointer;
    }
  </style>
  <div class="fields">
    <p id="go-green-opt-out-packaging" class="form-check">
      <input id="go-green-opt-out-packaging-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox form-check-input" type="checkbox" name="go_green_checkbox" <?php echo (isset($_POST['go_green_checkbox'])) ? 'checked' : ''; ?> value="1" />
      <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox form-check-label" for="go-green-opt-out-packaging-checkbox">
        <span>Yes, I want to <strong style="font-weight: bold; color: green;">Go-Green</strong> and not have Radical Skincare retail boxes included with my shipment.</span>
        <a href="javascript:void(0);" data-toggle="popover" data-placement="top" title="Go-Green" class="show-sub-popover grey-text">
          <i class="fa fa-question-circle" aria-hidden="true"></i>
          <span class="sr-only">More Go-Green Info</span>
        </a>
      </label>
    </p>
  </div>
<?php
});

/**
 * Process the checkout
 **/
add_action('woocommerce_checkout_process', function () {
  global $woocommerce;
  // $woocommerce->add_error( __('test') );
  if (isset($_POST['go_green_checkbox'])) {
    $_POST['order_comments'] .= " This is a Go-Green order.";
  }
});

/**
 * Update the order meta with field value
 **/
add_action('woocommerce_checkout_update_order_meta', function ($order_id) {
  if (isset($_POST['go_green_checkbox'])) {
    update_post_meta($order_id, 'go_green_checkbox', esc_attr($_POST['go_green_checkbox']));
  }
  if (isset($_POST['receive-shipment-update-using-sms']) && $_POST['shipping_country'] == 'US') {
    update_post_meta($order_id, 'radical_receive_shipment_update_sms', esc_attr($_POST['receive-shipment-update-using-sms']));
  }
});

/**
 * Display field value on the order edit page
 */
add_action('woocommerce_admin_order_data_after_billing_address', function ($order) {
  $go_green_checkbox = get_post_meta($order->get_id(), 'go_green_checkbox', true); ?>
  <p>
    <strong>Is this a "Go-Green" order?</strong><br>
    <?php echo ($go_green_checkbox) ? '<span>Yes</span>' : 'No' ?>
  </p>
<?php
}, 10, 1);

/**
 * Exclude products from 'brand-partner-exclusive' product category on the shop page
 */
add_action('woocommerce_product_query', function ($query) {
  // if active brand partner exit
  if (is_user_logged_in() && get_user_meta(get_current_user_id(), 'v_affiliate_status', true) === 'active') {
    return;
  }
  // if not shop exit
  if (!is_shop()) {
    return;
  }
  $tax_query = (array) $query->get('tax_query');
  $tax_query[] = array(
    'taxonomy' => 'product_cat',
    'field' => 'slug',
    'terms' => array('brand-partner-exclusive'), // Don't display products in the 'brand-partner-exclusive' category on the shop page.
    'operator' => 'NOT IN'
  );
  $query->set('tax_query', $tax_query);
});


// Terms Checked by Default
add_filter('woocommerce_terms_is_checked_default', '__return_true');

/*
 * Checkout Disable Auto-fill/Auto-populate checkout fields
 */
add_filter('woocommerce_checkout_get_value', function ($value, $input) {
  if (isset($_COOKIE['wordpress_gigfiliate_placing_order_for_customer']) && $_COOKIE['wordpress_gigfiliate_placing_order_for_customer']) {
    return $value;
  }
  $checkout_disable_autopopulate_fields = get_field('checkout_disable_autopopulate_fields', 'option');
  if ($checkout_disable_autopopulate_fields) {
    $item_to_set_null = array(
      'billing_first_name',
      'billing_last_name',
      'billing_company',
      'billing_address_1',
      'billing_address_2',
      'billing_city',
      'billing_postcode',
      'billing_country',
      'billing_state',
      'billing_email',
      'billing_phone',
      'shipping_first_name',
      'shipping_last_name',
      'shipping_company',
      'shipping_address_1',
      'shipping_address_2',
      'shipping_city',
      'shipping_postcode',
      // 'shipping_country',
      'shipping_state',
      'shipping_phone',
    ); // All the fields in this array will be set as empty string, add or remove as required.
    if (in_array($input, $item_to_set_null)) {
      $value = '';
    }
  }
  return $value;
}, 10, 2);

/*
 * change default checkout country
add_filter( 'default_checkout_shipping_country', function ( $country ) {
  // If the user already exists, don't override country
  // TODO: Fix Error "Uncaught Error: Call to a member function get_is_paying_customer() on null"
  if ( WC()->customer->get_is_paying_customer() ) {
    return $country;
  }
  return 'US'; 
}, 10, 1 );
 */

// Checkout - Product price - sale price (if any) show the checkout cart items
add_filter('woocommerce_cart_item_subtotal', function ($subtotal, $cart_item, $cart_item_key) {
  $product = $cart_item['data'];
  $quantity = $cart_item['quantity'];
  // check if the object exists
  if (!$product) {
    return $subtotal;
  }
  if ($product->is_on_sale() && !empty($product->get_sale_price())) {
    // shows sale price and regular price       
    $price = wc_format_sale_price(
      // regular price
      wc_get_price_to_display(
        $product,
        array(
          'price' => $product->get_regular_price(),
          'qty' => $quantity
        )
      ),
      // sale price
      wc_get_price_to_display(
        $product,
        array(
          'price' => $product->get_sale_price(),
          'qty' => $quantity
        )
      )
    ) . $product->get_price_suffix();
  } else {
    // shows regular price
    $price = wc_price(
      // regular price
      wc_get_price_to_display(
        $product,
        array(
          'price' => $product->get_regular_price(),
          'qty' => $quantity
        )
      )
    ) . $product->get_price_suffix();
  }
  return $price;
}, 10, 3);

/**
 * Trim zeros in price decimals
 **/
add_filter('woocommerce_price_trim_zeros', '__return_true');

/**
 * Temporary Notice For Hydrating Cleanser. Only valid till sept 12th
add_action('cfw_before_customer_info_tab_login', function() {
  if (WC()->cart->is_empty()) {
    return;
  }
  $is_in_cart = false;
  $product_id_to_search = 2513; //Update product ID
  foreach (WC()->cart->get_cart() as $cart_item ) {
    if ($cart_item['product_id'] == $product_id_to_search) {
      $is_in_cart = true;
    }
  }
  if (!$is_in_cart) {
    return;
  }
  echo '<div class="alert alert-info">If you are ordering the Hydrating Cleanser or have an order containing the Hydrating Cleanser, your order will not ship until Sept. 12th. Thank you for your patience!</div>';
}, 10);
 **/

/**
 * Travel Kit Discount
 */
$conditional_product_sale = get_field('conditional_product_sale', 'option');
if (isset($conditional_product_sale['enable']) && $conditional_product_sale['enable']) {
  require_once(get_template_directory() . '/inc/integrations/woocommerce/conditional-product-sale.php');
}

/**
 * Log Invalid Recurring Shipping Method Error
 */
add_filter('woocommerce_add_error', function ($message) {
  if (strpos($message, 'Invalid recurring shipping method') !== false) {
    $message .= ' Please order your subscribtion products individually.';
    error_log('Invalid recurring shipping method');
    error_log('Current User Id ' . get_current_user_id());
    error_log('shipping_methods ' . WC()->checkout()->shipping_methods);
    error_log(json_encode(WC()->cart));
    error_log(json_encode(WC()->cart->get_customer()));
  }
  return $message;
});

// Send order invoice to "Ordered By" user too.
add_filter('woocommerce_email_recipient_customer_renewal_invoice', function ($recipient, $order) {
  $subscription = wcs_get_subscriptions_for_order($order, array('order_type' => array('parent', 'renewal')));
  if ($subscription && array_values($subscription)[0]) {
    $subscription_id = array_values($subscription)[0]->id;
    if ($gig_ordered_by = get_post_meta($subscription_id, 'gig_ordered_by', true)) {
      $recipient .= ', ' . $gig_ordered_by;
    }
  }
  return $recipient;
}, 10, 2);

// Remove Cross-Sells @ WooCommerce Cart Page
remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');

// Subscription Features
require_once(get_template_directory() . '/inc/integrations/woocommerce/subscription-features.php');

// Renewal Gift For ROR
require_once(get_template_directory() . '/inc/integrations/woocommerce/renewal-gift.php');

// Subscription Reminder Order Email
require_once(get_template_directory() . '/inc/integrations/woocommerce/subscription-reminder-email.php');

// Payment Methods
require_once(get_template_directory() . '/inc/integrations/woocommerce/payment-methods.php');

add_action('wp_ajax_re_purchase_order', function () {
  check_ajax_referer('radical_ajax_nonce', 'nonce');
  $res = ['success' => false];
  if (!isset($_POST['order_id'])) {
    die(json_encode($res));
  }
  $order = wc_get_order($_POST['order_id']);
  global $woocommerce;
  $woocommerce->cart->empty_cart();
  foreach ($order->get_items() as $key => $item) {
    $is_gift = array_filter($item->get_meta_data(), function ($item) {
      return ($item->key === '_fgf_gift_product');
    });
    if ($is_gift) {
      continue;
    }
    $product = $item->get_product();
    $variation_id = null;
    if ($product->get_type() === 'variation') {
      $variation_id = $item->get_variation_id();
    }
    $woocommerce->cart->add_to_cart($item['product_id'], $item['qty'], $variation_id);
  }
  $woocommerce->cart->calculate_totals();
  foreach ($order->get_coupon_codes() as $coupon_code) {
    $coupon = new \WC_Coupon($coupon_code);
    $discounts = new \WC_Discounts(WC()->cart);
    $response = $discounts->is_coupon_valid($coupon);
    if (!is_wp_error($response)) {
      $woocommerce->cart->add_discount($coupon_code);
    }
  }
  $woocommerce->cart->calculate_totals();
  $res['success'] = true;
  die(json_encode($res));
});

add_filter('woocommerce_email_recipient_cancelled_subscription', function ($recipient, $order) {
  // Get any subscriptions related to the order
  $order = wc_get_order($order);
  if ($order) {
    $recipients = explode(',', $recipient);
    if ($billing_email = $order->get_billing_email()) {
      $recipients[] = trim(strip_tags($billing_email));
    }
    $recipients = array_unique($recipients);
    $recipient = implode(',', $recipients);
  }
  return $recipient;
}, 10, 2);

/**
 * My Account My Orders Posts Per Page
 */
add_filter('woocommerce_my_account_my_orders_query', function ($args) {
  $args['posts_per_page'] = 10;
  return $args;
}, 10, 1);

/**
 * This will cancel the subscription as soon as user click cancel instead of pending cancellation
 */
add_filter('woocommerce_subscription_use_pending_cancel', function ($args) {
  $args = false;
  return $args;
}, 10, 1);

add_filter('woocommerce_product_get_rating_html', function($html, $rating, $count) {
  return '<div class="write_a_review_container">' . $html . '<a href="#reviews" class="write_a_review_container-click">Write a Review</a></div>';
}, 10, 3);

/**
 * Restrict product reviews to verified buyers and enforce a 100-character minimum.
 * Mirrors the template gate so direct POSTs to wp-comments-post.php cannot bypass it.
 */
define('RADICAL_REVIEW_MIN_CHARS', 100);

add_filter('preprocess_comment', function ($commentdata) {
  if (empty($commentdata['comment_post_ID']) || get_post_type($commentdata['comment_post_ID']) !== 'product') {
    return $commentdata;
  }
  $user_id = get_current_user_id();
  if (!$user_id) {
    wp_die(
      esc_html__('You must be logged in and have purchased this product to leave a review.', 'sage'),
      esc_html__('Review not allowed', 'sage'),
      ['response' => 403, 'back_link' => true]
    );
  }
  $user = get_userdata($user_id);
  $email = $user ? $user->user_email : '';
  if (!wc_customer_bought_product($email, $user_id, (int)$commentdata['comment_post_ID'])) {
    wp_die(
      esc_html__('Only verified buyers of this product may leave a review.', 'sage'),
      esc_html__('Review not allowed', 'sage'),
      ['response' => 403, 'back_link' => true]
    );
  }
  $comment_length = function_exists('mb_strlen')
    ? mb_strlen(trim(wp_strip_all_tags($commentdata['comment_content'] ?? '')))
    : strlen(trim(wp_strip_all_tags($commentdata['comment_content'] ?? '')));
  if ($comment_length < RADICAL_REVIEW_MIN_CHARS) {
    wp_die(
      sprintf(
        esc_html__('Your review must be at least %d characters long.', 'sage'),
        RADICAL_REVIEW_MIN_CHARS
      ),
      esc_html__('Review too short', 'sage'),
      ['response' => 400, 'back_link' => true]
    );
  }
  return $commentdata;
}, 10, 1);

/**
 * Cart - Limit Specific Product to 1 Cart Item Quantity
add_action('wp_head', function() {
  $limit_product_to_one_cart_quantity = get_field('limit_product_to_one_cart_quantity', 'option');
  if (!isset($limit_product_to_one_cart_quantity) || !$limit_product_to_one_cart_quantity) {
    return;
  }
  $limit_product_to_one_cart_quantity = (int)$limit_product_to_one_cart_quantity; // The product ID
  $cart = WC()->cart; // The WC_Cart Object
  if (is_null($cart)) {
    return;
  }
  // When cart is not empty 
  if ( $cart->is_empty() ) {
    return;
  }
  // Loop through cart items
  foreach( $cart->get_cart() as $cart_item_key => $cart_item ) {
    // If the cart item is not the current defined product ID
    if ( $limit_product_to_one_cart_quantity != $cart_item['product_id'] ) {
      $cart->remove_cart_item( $cart_item_key ); // remove it from cart
    } 
    // If the cart item is the current defined product ID and quantity is more than 1
    else if( $cart_item['quantity'] > 1 ) {
      $cart->set_quantity( $cart_item_key, 1 ); // Set the quantity to 1
    }
  }
});
 */

//  Add custom meta data from product to order product item
add_action('woocommerce_checkout_update_order_meta', function($order_id, $data) {
  $order = wc_get_order($order_id);
  foreach ( $order->get_items() as $item_id => $item ) {
    $product_id = $item->get_product_id();
    $order_metadata = get_field('order_metadata', $product_id);
    if ($order_metadata) {
      foreach ($order_metadata as $data) {
        wc_update_order_item_meta($item_id, $data['label'], $data['value']);
      }
    }
  }
}, 10, 2);

// Coupons
require_once(get_template_directory() . '/inc/integrations/woocommerce/coupons.php');

// Active Subscriber Discounts
require_once(get_template_directory() . '/inc/integrations/woocommerce/active-subscriber-discounts.php');

function send_ga4_purchase_event($order_id) {
  if (!$order_id) return;

  $order = wc_get_order($order_id);

  if (!$order) return;

  // Avoid duplicate tracking (e.g., only for first-time view)
  if (get_post_meta($order_id, '_ga4_purchase_tracked', true)) {
      return;
  }

  // Mark order as tracked
  update_post_meta($order_id, '_ga4_purchase_tracked', true);

  $transaction_id = $order->get_order_number();
  $total = $order->get_total();
  $currency = get_woocommerce_currency();
  $tax = $order->get_total_tax();
  $shipping = $order->get_shipping_total();

  $items = [];
  foreach ($order->get_items() as $item_id => $item) {
      $product = $item->get_product();
      $items[] = [
          'item_name' => $item->get_name(),
          'item_id'   => $product ? $product->get_sku() : '',
          'price'     => $item->get_total() / $item->get_quantity(),
          'quantity'  => $item->get_quantity(),
      ];
  }

  ?>
  <script>
      window.dataLayer = window.dataLayer || [];
      window.dataLayer.push({
          event: "purchase",
          ecommerce: {
              transaction_id: "<?php echo esc_js($transaction_id); ?>",
              value: <?php echo esc_js($total); ?>,
              currency: "<?php echo esc_js($currency); ?>",
              tax: <?php echo esc_js($tax); ?>,
              shipping: <?php echo esc_js($shipping); ?>,
              items: <?php echo json_encode($items); ?>
          }
      });
  </script>
  <?php
}

add_action('woocommerce_thankyou', 'send_ga4_purchase_event');

//To record what script is creating coupons
add_action('save_post_shop_coupon', function ($post_id, $post, $update) {
  // Only proceed if it's a new coupon (not an update)
  if ($update) return;

  // Get coupon code
  $coupon_code = get_post_field('post_title', $post_id);

  // Prepare stack trace
  ob_start();
  debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 20);
  $trace = ob_get_clean();

  // Log the data
  $log_entry = "=== Coupon Created: {$coupon_code} ===\n";
  $log_entry .= "Time: " . current_time('mysql') . "\n";
  $log_entry .= "Backtrace:\n$trace\n\n";

  // Write to log file
  $log_file = WP_CONTENT_DIR . '/coupon_debug.log';
  file_put_contents($log_file, $log_entry, FILE_APPEND);
}, 10, 3);