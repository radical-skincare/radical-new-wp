<?php
/**
 * View Subscription
 *
 * Shows the details of a particular subscription on the account page
 *
 * @author  Prospress
 * @package WooCommerce_Subscription/Templates
 * @version 1.0.0 - Migrated from WooCommerce Subscriptions v2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wc_print_notices();
if (isset($_GET['new_product']) && $_GET['t']) {
  if (strtotime(date('Y-m-d H:i:s')) - strtotime(date("Y-m-d H:i:s", substr($_GET['t'], 0, 10))) <= 50) {
    if ($product = wc_get_product($_GET['new_product'])) {
      wc_print_notice( __( $product->get_name() .' Is added in your subscription.', 'woocommerce' ), 'success' );
    }
  }
}
/**
 * Gets subscription details table template
 * @param WC_Subscription $subscription A subscription object
 * @since 1.0.0 - Migrated from WooCommerce Subscriptions v2.2.19
 */
do_action( 'woocommerce_subscription_details_table', $subscription );
$site_url = get_site_url();
$subscription_id = $subscription->get_ID();
$action_links = [];
$actions = wcs_get_all_user_actions_for_subscription( $subscription, get_current_user_id() );
foreach ($actions as $key => $action) {
  $action['name'] = str_replace(" ", "_", $action['name']);
  $action_links[strtolower($action['name'])] = $action['url'];
}
$interval = (int)$subscription->get_billing_interval();
$period = $subscription->get_billing_period();
$subscription_skip_history = get_post_meta($subscription_id, 'radical_subscription_skip_history', true);
$subscription_skip_history = (($subscription_skip_history) ? json_decode($subscription_skip_history, true) : []);
$number_of_sub_skips_in_this_year = 0;
foreach ($subscription_skip_history as $date) {
  if (date('Y') == date('Y', strtotime($date))) {
    $number_of_sub_skips_in_this_year++;
  }
}

$next_payment_date = $subscription->get_date('next_payment');
$next_formatted_date = date('F j, Y', strtotime($next_payment_date));
$future_date = date('Y-m-d H:i:s', strtotime('+' . $interval . ' ' . $period, strtotime($next_payment_date)));
$future_formatted_date = date('F j, Y', strtotime($future_date));

?>
<div class="row mb-3">
  <div class="col">
    <?php if (isset($_GET['changed-frequency'])) : ?>
      <div class="alert alert-success">
        <i class="fa fa-check" aria-hidden="true"></i> Frequency updated to <b><?php echo esc_html($_GET['changed-frequency'] === '1' ? 'Every Month' : 'Every Two Months'); ?></b>
      </div>
    <?php endif; ?>
    <?php if ($number_of_sub_skips_in_this_year > 0) : ?>
      <div class="alert alert-info">
        <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Skipping a renewal order is limited to 3 times per year, you have skipped <b><?php echo esc_html($number_of_sub_skips_in_this_year); ?></b> times this year.
      </div>
    <?php endif; ?>
    <h3 class="m-0">Subscription Details</h3>
  </div>
</div>
<div class="row">
  <div class="col-lg-8">
    <?php
    /**
     * Gets subscription totals table template
     * @param WC_Subscription $subscription A subscription object
     * @since 1.0.0 - Migrated from WooCommerce Subscriptions v2.2.19
     */
    do_action( 'woocommerce_subscription_totals_table', $subscription );
    do_action( 'woocommerce_subscription_details_after_subscription_table', $subscription );
    ?>
  </div>
  <div class="col-lg-4">
    <?php
    set_query_var('subscription', $subscription);
    set_query_var('action_links', $action_links);
    get_template_part('template-parts/account/subscription/totals-table');
    ?>
    <?php
    set_query_var('subscription', $subscription);
    set_query_var('action_links', $action_links);
    get_template_part('template-parts/account/subscription/payment-card');
    ?>
    <?php
    set_query_var('site_url', $site_url);
    set_query_var('subscription', $subscription);
    set_query_var('type', 'billing');
    get_template_part('template-parts/account/address-card');
    ?>
    <div class="mb-3">
      <?php
      set_query_var('site_url', $site_url);
      set_query_var('subscription', $subscription);
      set_query_var('type', 'shipping');
      get_template_part('template-parts/account/address-card');
      ?>
    </div>
  </div>
</div>

<style>
#subscriptionCancelReason_confirm {
  display: none;
}
</style>
<div class="modal fade" id="subscriptionCancelReason" tabindex="-1" role="dialog" aria-labelledby="subscriptionCancelReasonLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="subscriptionCancelReasonLabel">Are You Sure?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="subscriptionCancelReason_feedback">
          <?php echo get_field('subscription_reason_for_cancellation_text', 'option'); ?>
          <?php if ($nf_id = get_field('subscription_reason_for_cancellation_ninja_form_id', 'option')) : ?>
            <?php echo do_shortcode('[ninja_form id="' . $nf_id . '"]'); ?>
          <?php endif; ?>
        </div>
        <div id="subscriptionCancelReason_confirm">
          <?php echo get_field('subscription_cancellation_warning_text', 'option'); ?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="link-underline link-underline_darker-gray mr-3" data-dismiss="modal">Keep Active Subscription!</button>
        <button id="btn-subscription-confirm-cancel" type="button" class="btn btn-darkergray continue-to-cancel">Continue to Cancel</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="skipWholeOrder" tabindex="-1" role="dialog" aria-labelledby="skipWholeOrderLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="skipWholeOrderLabel">
          <?php if ($number_of_sub_skips_in_this_year >= 3) : ?>
            Sorry, No More Skips Available On This Subscription
          <?php else : ?>
            Confirm Skip Renewal Order
          <?php endif; ?>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php if ($number_of_sub_skips_in_this_year >= 3) : ?>
          <p>You have skipped this subscription <?php echo esc_html($number_of_sub_skips_in_this_year); ?> times this year. Skipping a renewal order is limited to 3 times per year</p>
        <?php else : ?>
          <p>Please confirm that you want to skip all of the products. Your next shipment for all products will be on <b><?php echo esc_html($future_formatted_date); ?></b>. Skipping a renewal order is limited to <b>3</b> times per year, you have skipped <b><?php echo esc_html($number_of_sub_skips_in_this_year); ?></b> times this year.</p>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="link-underline link-underline_darker-gray" data-dismiss="modal">Cancel</button>
        <?php if ($number_of_sub_skips_in_this_year < 3) : ?>
          <button type="button" class="btn btn-darkergray" id="skipWholeOrder_btn" data-subscription_id="<?php echo esc_attr($subscription_id); ?>">Skip</button>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="skipProductInOrder" tabindex="-1" role="dialog" aria-labelledby="skipProductInOrderLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="skipProductInOrderLabel">Confirm Skip Item For Next Renewal Order</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Please confirm that you would like to skip <span class="skipProductInOrder_nameOfProductToSkip"></span> in your next renewal order. Your next shipment for the <span class="skipProductInOrder_nameOfProductToSkip"></span> will be <b><?php echo esc_html($future_formatted_date); ?></b> </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="link-underline link-underline_darker-gray" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-darkergray" id="skipProductInOrder_btn" data-subscription_id="<?php echo esc_attr($subscription_id); ?>" data-product_id="">Skip</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="productQuantityUpdated" tabindex="-1" role="dialog" aria-labelledby="productQuantityUpdatedLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="productQuantityUpdatedLabel">Confirm Update Subscription Product Quantity</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>You are updating the subscription product <b id="productQuantityUpdated_quantityOfProduct"></b> quantity to <b id="productQuantityUpdated_nameOfProduct"></b>. This updated quantity will reflect in your next order which will be delivered on <b><?php echo esc_html(date('F j, Y', strtotime($subscription->get_date('next_payment')))); ?></b>.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="link-underline link-underline_darker-gray" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-darkergray" id="productQuantityUpdated_confirm-btn" data-product_id="">Confirm</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="subscriptionAddProductModel" tabindex="-1" role="dialog" aria-labelledby="subscriptionAddProductModelLabel" aria-hidden="true" data-subscription_id="<?php echo esc_attr($subscription->get_ID()); ?>" data-refill_frequency="<?php echo esc_attr($interval . '_' . $period); ?>">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="subscriptionAddProductModelLabel">Add Product(s) To Subscription</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="position-relative mb-3">
          <div class="form-outline">
            <input type="text" id="subscription-search-bar" name="subscription_search" class="search-bar form-control form-control-lg"/>
            <label for="subscription-search-bar">Search Subscription Products</label>
          </div>
          <a href="javascript:void(0)" id="clear-subscription-search-bar" class="position-absolute d-none" data-dismiss="modal" style="right: 10px;top: 10px;">
            <img src="<?php echo get_template_directory_uri() . '/assets/images/close.svg'; ?>" alt="Close"> Clear
          </a>
        </div>
        <p id="subscription-search-notice" class="text-center"></p>
        <div id="subscription-search-products" class="row align-items-center justify-content-center"></div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="subscriptionChangeFrequencyModel" tabindex="-1" role="dialog" aria-labelledby="subscriptionChangeFrequencyModelLabel" aria-hidden="true" data-subscription_id="<?php echo esc_attr($subscription->get_ID()); ?>" data-billing_interval=<?php echo esc_attr($interval); ?>>
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form id="subscriptionChangeFrequencyForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="subscriptionChangeFrequencyModelLabel">Change Your Subscription Refill Frequency</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <label class="form-check" for="subscriptionChangeFrequencyModel-frequency-one" <?php echo $interval == 1 ? 'disabled' : ''; ?>>
          <input class="form-check-input" type="radio" name="subscriptionChangeFrequencyModel-frequency" id="subscriptionChangeFrequencyModel-frequency-one" value="1" <?php echo $interval == 1 ? 'checked disabled' : ''; ?>> Every Month
        </label>
        <label class="form-check mb-3" for="subscriptionChangeFrequencyModel-frequency-two" <?php echo $interval == 2 ? 'disabled' : ''; ?>>
          <input class="form-check-input" type="radio" name="subscriptionChangeFrequencyModel-frequency" id="subscriptionChangeFrequencyModel-frequency-two" value="2" <?php echo $interval == 2 ? 'checked disabled' : ''; ?>> Every Two Months
        </label>
        <div class="alert alert-danger" id="subscription-change-frequency-double-confirm-alert" style="display: none">
          <label for="subscription-change-frequency-double-confirm" class="form-check pl-0 mb-0">
            <input id="subscription-change-frequency-double-confirm" type="checkbox" required/> This will also change your next shipment date. Your next shipment date will be <b id="subscription-change-frequency-double-confirm-next-shipment-date"></b>
          </label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="link-underline link-underline_darker-gray" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-darkergray" id="subscriptionChangeFrequencyModel_confirm-btn" disabled>Confirm</button>
      </div>
    </form>
  </div>
</div>

<?php if (get_field('subscription_enable_change_next_order_date', 'option')) : ?>
  <div class="modal fade" id="subChangeNextOrderDateModel" tabindex="-1" role="dialog" aria-labelledby="subChangeNextOrderDateModelLabel" aria-hidden="true" data-subscription_id="<?php echo esc_attr($subscription_id); ?>" data-billing_interval="<?php echo esc_attr($interval); ?>">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <form id="subscriptionChangeNextOrderDateForm" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="subChangeNextOrderDateModelLabel">Change Your Subscription Next Order Date</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Select a next order date 60 days from today.</p>
          <?php
          $next_payment_date_str = $subscription->get_date_to_display('next_payment');
          $timestamp = strtotime($next_payment_date_str);
          $next_payment_formatted_date = date('Y-m-d', $timestamp);
          // min date
          $today_date = date('Y-m-d');
          $timestamp = strtotime($today_date);
          $new_timestamp = strtotime('+1 days', $timestamp);
          $min_formatted_date = date('Y-m-d', $new_timestamp);
          // max date
          $new_timestamp = strtotime('+60 days', $timestamp);
          $max_formatted_date = date('Y-m-d', $new_timestamp);
          ?>
          <div class="form-group">
            <input id="input-next-order-date" name="input_next_order_date" class="form-control" type="date" value="<?php echo esc_attr($next_payment_formatted_date); ?>" min="<?php echo esc_attr($min_formatted_date); ?>" max="<?php echo esc_attr($max_formatted_date); ?>" required/>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="subChangeNextOrderDateModel_confirm-btn" disabled data-subscription_id="<?php echo esc_attr($subscription_id); ?>">Confirm</button>
        </div>
      </form>
    </div>
  </div>
<?php endif; ?>

<div class="clear"></div>
