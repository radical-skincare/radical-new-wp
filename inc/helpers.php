<?php
/**
 * Determine whether to show the sidebar
 * @return bool
 */
function display_sidebar()
{
    static $display;
    isset($display) || $display = apply_filters('sage/display_sidebar', false);
    return $display;
}

/**
 * Page Title
 */
function radical_page_title() {
    if (is_singular()) {
        the_title('<h1 class="mb-0">', '</h1>');
    } elseif (is_archive('product')) {
        if (is_tax('product_cat') || is_tax('product_tag')) {
            $category = get_queried_object();
            echo '<h1 class="mb-0">' . esc_html($category->name) . '</h1>';
        } else {
            echo '<h1 class="mb-0">Best Skincare Products</h1>';
        }
    } elseif (is_archive()) {
        if (is_category()) {
            $category = get_queried_object();
            echo '<h1 class="mb-0">' . esc_html($category->name) . '</h1>';
        } else {
            the_archive_title('<h1 class="mb-0">', '</h1>');
        }
    } else {
        the_title('<h1 class="mb-0">', '</h1>');
    }
}

/**
 * Nest a WordPress Menu
 * @return array
 */
if (! function_exists('nest_menu')) {
    function nest_menu($current_menu) {
        if (!$current_menu) return;
        $nested_menu = [];
        foreach ($current_menu as $current_item) {
            $new_menu_item = array(
                'id' => $current_item->ID,
                'title' => $current_item->title,
                'url' => $current_item->url,
                'children' => array(),
                'description' => $current_item->post_content,
            );
            if ( ! $current_item->menu_item_parent ){
                $nested_menu[] = $new_menu_item;
            }
            else {
                foreach($nested_menu as $key => $this_item) {
                    if ($this_item['id'] === (int)$current_item->menu_item_parent) {
                        $nested_menu[$key]['children'][] = $new_menu_item;
                    } else {
                        foreach($this_item['children'] as $this_key => $this_this_item) {
                            if ($this_this_item['id'] === (int)$current_item->menu_item_parent) {
                                $nested_menu[$key]['children'][$this_key]['children'][] = $new_menu_item;
                            }
                        }
                    }
                }
            }
        }
        return $nested_menu;
    }
}

/**
 * Social share URLs
 */
function social_shares($social, $post) {
    $post_url = urlencode(get_permalink($post->ID));
    $post_title = urlencode(str_replace(' ', '%20', $post->post_title));
    $site_title = urlencode(str_replace(' ', '%20', get_bloginfo('name')));
    switch ($social) {
        case 'linkedin':
            return 'https://www.linkedin.com/shareArticle?mini=true&url=' . $post_url . '&amp;title=' . $post_title;
            break;
        case 'twitter':
            $twitter_url = function_exists('get_field') ? get_field('social', 'option')['twitter'] : '';
            $twitter_handle = ($twitter_url) ? urlencode( str_replace('https://twitter.com/', '', $twitter_url) ) : false;
            $twitter_url = 'https://twitter.com/intent/tweet?text=' . $post_title . '&amp;url=' . $post_url . '&amp;via=';
            $twitter_url .= ($twitter_handle) ? $twitter_handle : $site_title;
            return $twitter_url;
            break;
        case 'facebook':
            return 'https://www.facebook.com/sharer/sharer.php?u='. $post_url;
            break;
        case 'rss':
            return 'https://feed.tubularinsights.com/';
            break;
        case 'email':
            return 'mailto:example@email.com?subject=' . $post_title . '&body=' . $post_url;
            break;
    }
}

/**
 * Get US States
 */
function radical_get_states() {
    $states = array(
        'AL'=>'Alabama',
        'AK'=>'Alaska',
        'AZ'=>'Arizona',
        'AR'=>'Arkansas',
        'CA'=>'California',
        'CO'=>'Colorado',
        'CT'=>'Connecticut',
        'DE'=>'Delaware',
        'DC'=>'District of Columbia',
        'FL'=>'Florida',
        'GA'=>'Georgia',
        'HI'=>'Hawaii',
        'ID'=>'Idaho',
        'IL'=>'Illinois',
        'IN'=>'Indiana',
        'IA'=>'Iowa',
        'KS'=>'Kansas',
        'KY'=>'Kentucky',
        'LA'=>'Louisiana',
        'ME'=>'Maine',
        'MD'=>'Maryland',
        'MA'=>'Massachusetts',
        'MI'=>'Michigan',
        'MN'=>'Minnesota',
        'MS'=>'Mississippi',
        'MO'=>'Missouri',
        'MT'=>'Montana',
        'NE'=>'Nebraska',
        'NV'=>'Nevada',
        'NH'=>'New Hampshire',
        'NJ'=>'New Jersey',
        'NM'=>'New Mexico',
        'NY'=>'New York',
        'NC'=>'North Carolina',
        'ND'=>'North Dakota',
        'OH'=>'Ohio',
        'OK'=>'Oklahoma',
        'OR'=>'Oregon',
        'PA'=>'Pennsylvania',
        'RI'=>'Rhode Island',
        'SC'=>'South Carolina',
        'SD'=>'South Dakota',
        'TN'=>'Tennessee',
        'TX'=>'Texas',
        'UT'=>'Utah',
        'VT'=>'Vermont',
        'VA'=>'Virginia',
        'WA'=>'Washington',
        'WV'=>'West Virginia',
        'WI'=>'Wisconsin',
        'WY'=>'Wyoming',
    );
    return $states;
}

/**
 * Cannot Access Brand Partner Product Category
 */
function cannot_Access_Brand_Partner_Product_Category( $get_queried_object ) {
    $cannot_access_products = false;
    if ( property_exists($get_queried_object, 'slug') && $get_queried_object->slug === 'brand-partner-exclusive') {
        if (!is_user_logged_in()) {
            $cannot_access_products = true;
        } else {
            $current_user_id = get_current_user_id();
            $affiliate_status = get_user_meta($current_user_id, 'v_affiliate_status', true);
            $cannot_access_products = ($affiliate_status !== 'active') ? true : false;
        }
    }
    return $cannot_access_products;
}

/**
 * Cannot Access Brand Partner Product
 */
function cannot_Access_Brand_Partner_Product( $post_id ) {
    $cannot_access_products = false;
    if ( has_term( array( 'brand-partner-exclusive' ), 'product_cat', $post_id ) ) {
        if (!is_user_logged_in()) {
            $cannot_access_products = true;
        } else {
            $current_user_id = get_current_user_id();
            $affiliate_status = get_user_meta($current_user_id, 'v_affiliate_status', true);
            $cannot_access_products = ($affiliate_status !== 'active') ? true : false;
        }
    }
    return $cannot_access_products;
}

/**
 * OrderBy Dropdown Text
 */
function orderby_Dropdown_Text( $default_text = 'Top Sellers' ) {
    $orderby_text = $default_text;
    if (isset($_GET['orderby'])) {
        $GET_orderby = $_GET['orderby'];
        if ($GET_orderby === 'popularity') {
            return 'Top Sellers';
        }
        if ($GET_orderby === 'rating') {
            return 'Average Rating';
        }
        if ($GET_orderby === 'date') {
            return 'Latest';
        }
        if ($GET_orderby === 'price') {
            return 'Price: Low to High';
        }
        if ($GET_orderby === 'price-desc') {
            return 'Price: High to Low';
        }
        return $GET_orderby;
    }
    return $orderby_text;
}

/**
 * Convert Number to Word
 */
function convert_number_to_word($number = 0) {
    $words = ['One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten'];
    return $words[$number];
}

/**
 * Get Subscription Interval Period Text
 */
function get_subscription_interval_period_text($subscription = false) {
    if (!$subscription) {
        return '';
    }
    $interval = (int)$subscription->get_billing_interval();
    $period = $subscription->get_billing_period();
    if ($interval === 1 && $period === 'month') {
        return 'Every Month';
    } else if ($interval === 2 && $period === 'month') {
        return 'Every Two Months';
    }
    return $interval . ' ' . $period;
}

function get_wcsatt_scheme_interval_period_text($_wcsatt_scheme = []) {
    $subscription_period_interval = (int)$_wcsatt_scheme['subscription_period_interval'];
    $subscription_period = $_wcsatt_scheme['subscription_period'];
    $subscription_period = ucfirst($subscription_period);
    $to_return = 'Every ';
    if ($subscription_period_interval != 1) {
        $to_return .= convert_number_to_word($subscription_period_interval-1);
        return $to_return .= ' ' . $subscription_period . 's';
    }
    $to_return .= $subscription_period;
    return $to_return;
}

function formatted_billing_address($order) {
    $address = $order->get_billing_address_1() . '<br/>';
    if ($order->get_billing_address_2()) {
        $address .= $order->get_billing_address_2() . '<br/>';
    }
    $address .= $order->get_billing_city() . ', ' . $order->get_billing_state() . ' ' . $order->get_billing_postcode();
    return $address;
}

function formatted_shipping_address($order) {
    $address = $order->get_shipping_address_1() . '<br/>';
    if ($order->get_shipping_address_2()) {
        $address .= $order->get_shipping_address_2() . '<br/>';
    }
    $address .= $order->get_shipping_city() . ', ' . $order->get_shipping_state() . ' ' . $order->get_shipping_postcode();
    return $address;
}

function formatted_billing_name($order) {
    return $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
}

function formatted_shipping_name($order) {
    return $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name();
}

function radical_get_payment_method_name_by_id($cc_id = false, $user_id = null, $payment_methods_names = []) {
    $found_name = '';
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    if (empty($payment_methods_names)) {
        $payment_methods_names = json_decode(get_user_meta($user_id, 'payment_methods_names', true));
    }
    if (empty($payment_methods_names)) {
        return $found_name;
    }
    foreach ($payment_methods_names as $method) {
        if ($method->id === $cc_id) {
            $found_name = $method->name;
        }
    }
    return $found_name;
}

function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

function get_subscription_savings($subscription) {
    $total_savings = 0.0;
    foreach ($subscription->get_items() as $item_id => $item) {
        if (!apply_filters('woocommerce_order_item_visible', true, $item)) {
            continue;
        }
        $product = wc_get_product($item->get_product());
        $saved_amount = $product->get_price() - $subscription->get_item_subtotal( $item, false, true );
        if ($saved_amount > 0) {
            $total_savings += ($saved_amount * $item['qty']);
        }
    }
    return $total_savings;
}

function ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13)) {
        return $number . 'th';
    }
    return $number . $ends[$number % 10];
}

function radical_wcsatt_scheme_data_discount_text($wcsatt_scheme, $product_price) {
    $interval_text = ($wcsatt_scheme['subscription_period_interval'] === '1' && $wcsatt_scheme['subscription_period'] === 'month') ? 'Monthly ' : '';
    if (isset($wcsatt_scheme['subscription_regular_price']) && $wcsatt_scheme['subscription_regular_price'] > 0) {
        $discounted_price = $wcsatt_scheme['subscription_regular_price'] / $product_price;
        return 'Subscribe to Save ' . $discounted_price = 100 - $discounted_price * 100  . '%';
    } elseif (isset($wcsatt_scheme['subscription_discount']) && $wcsatt_scheme['subscription_discount'] > 0) {
        return 'Subscribe to Save ' . $wcsatt_scheme['subscription_discount'] . '%';
    }
    return 'Subscribe';
}

function radical_wcsatt_scheme_data_price($wcsatt_scheme, $product_price) {
    if (isset($wcsatt_scheme['subscription_regular_price']) && $wcsatt_scheme['subscription_regular_price'] > 0) {
        $discounted_price = $wcsatt_scheme['subscription_regular_price'] / $product_price;
        return $discounted_price = 100 - $discounted_price * 100  . '%';
    } elseif (isset($wcsatt_scheme['subscription_discount']) && $wcsatt_scheme['subscription_discount'] > 0) {
        $unrounded_price = (float)$product_price - ($product_price * ($wcsatt_scheme['subscription_discount'] * 0.01));
        return ceil($unrounded_price * 100) / 100;
    }
    return $product_price;
}

function radical_convert_number_to_word($number = 0) {
    $words = ['One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten'];
    return isset($words[$number]) ? $words[$number] : ($number + 1);
}

function radical_wcsatt_scheme_interval_period_text($_wcsatt_scheme = []) {
    $subscription_period_interval = (int)$_wcsatt_scheme['subscription_period_interval'];
    $subscription_period = $_wcsatt_scheme['subscription_period'];
    $subscription_period = ucfirst($subscription_period);
    $to_return = 'Every ';
    if ($subscription_period_interval != 1) {
        $to_return .= radical_convert_number_to_word($subscription_period_interval-1);
        return $to_return .= ' ' . $subscription_period . 's';
    }
    $to_return .= $subscription_period;
    return $to_return;
}
