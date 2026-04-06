<?php
/**
 * Payment methods
 *
 * Shows customer payment methods on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/payment-methods.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

defined( 'ABSPATH' ) || exit;

$current_user_id = get_current_user_id();
$saved_methods = wc_get_customer_saved_methods_list( $current_user_id );
$has_methods = (bool) $saved_methods;
$types = wc_get_account_payment_methods_types();
$site_url = get_site_url();
do_action( 'woocommerce_before_account_payment_methods', $has_methods );

?>
<div class="d-flex justify-content-between mb-3">
  <h2 class="m-0">Payment Methods</h2>
  <div class="d-flex" style="column-gap: 0.5rem;">
    <?php if (WC()->payment_gateways->get_available_payment_gateways()) : ?>
      <a class="btn btn-outline-secondary text-capitalize" href="<?php echo esc_url( wc_get_endpoint_url( 'add-payment-method' ) ); ?>"><?php esc_html_e( 'Add payment method', 'woocommerce' ); ?></a>
    <?php endif; ?>
    <?php if ($has_methods && get_field('payment_methods_enable_delete_all_payment_methods', 'option')) : ?>
      <?php
      function radical_delete_all_payment_methods($user_id = false) {
        if (!$user_id) {
          return false;
        }
        if (!class_exists('WC_Payment_Tokens')) {
          return false;
        }
        // Clean up payment tokens.
        $payment_tokens = WC_Payment_Tokens::get_customer_tokens( $user_id );
        foreach ( $payment_tokens as $payment_token ) {
          if (!$payment_token->is_default()) {
            $payment_token->delete();
          }
        }
        return true;
      }
      if (isset($_POST['action']) && $_POST['action'] === 'radical_delete_all_payment_methods') {
        radical_delete_all_payment_methods($current_user_id);
      }
      ?>
      <form method="POST">
        <button type="submit" class="btn btn-danger text-capitalize" href="<?php echo esc_url( wc_get_endpoint_url( 'add-payment-method' ) ); ?>" name="action" value="radical_delete_all_payment_methods"><?php esc_html_e( 'Delete All Payment Methods', 'woocommerce' ); ?></button>
      </form>
    <?php endif; ?>
  </div>
</div>
<?php if ($has_methods) : ?>
  <?php
  $payment_methods_names = json_decode(get_user_meta($current_user_id, 'payment_methods_names', true));
  ?>
  <div id="account-payment-methods" class="row">
    <?php foreach ($saved_methods as $methods) : ?>
      <?php foreach ($methods as $method) : ?>
        <div class="col-lg-6 d-flex">
          <?php
          set_query_var('method', $method);
          set_query_var('payment_methods_names', $payment_methods_names);
          get_template_part('template-parts/account/payment/method-card');
          ?>
        </div>
      <?php endforeach; ?>
    <?php endforeach; ?>
  </div>
<?php else : ?>
	<p class="woocommerce-Message woocommerce-Message--info woocommerce-info text-capitalize"><?php esc_html_e( 'No saved methods found.', 'woocommerce' ); ?></p>
<?php endif; ?>
<?php do_action( 'woocommerce_after_account_payment_methods', $has_methods ); ?>
<?php get_template_part('template-parts/modal/payment-method-edit-name'); ?>
