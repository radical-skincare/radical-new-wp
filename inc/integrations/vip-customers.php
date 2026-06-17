<?php

/**
 * VIP Customer Role
 * 
 * Creates a VIP Customer user role with the same privileges and permissions as regular WooCommerce customers.
 * This allows for future differentiation and special treatment of VIP customers while maintaining
 * the same base capabilities.
 */

/**
 * Register VIP Customer Role on theme/plugin activation
 * 
 * Creates a new user role called 'vip_customer' with the same capabilities as the standard
 * WooCommerce 'customer' role.
 */
function register_vip_customer_role()
{
  // Check if the role doesn't already exist to avoid re-adding it
  if (! get_role('vip_customer')) {
    // Get the customer role to copy its capabilities
    $customer_role = get_role('customer');

    $capabilities = array();

    // Copy all capabilities from the customer role
    if ($customer_role) {
      $capabilities = $customer_role->capabilities;
    } else {
      // Fallback to default customer capabilities if customer role doesn't exist yet
      $capabilities = array(
        'read' => true,
      );
    }

    // Create the VIP Customer role with the same capabilities as customer
    add_role(
      'vip_customer',
      'VIP Customer',
      $capabilities
    );
  }
}

// Register the role on WordPress init
add_action('init', 'register_vip_customer_role', 5);

/**
 * Sync VIP Customer capabilities with Customer role when capabilities change
 * 
 * If the customer role capabilities are modified, this ensures VIP customers
 * stay in sync with the same permissions.
 */
function sync_vip_customer_capabilities()
{
  $customer_role = get_role('customer');
  $vip_role = get_role('vip_customer');

  if ($customer_role && $vip_role) {
    // Remove all current VIP customer capabilities
    foreach ($vip_role->capabilities as $cap => $grant) {
      $vip_role->remove_cap($cap);
    }

    // Add all customer capabilities to VIP customer
    foreach ($customer_role->capabilities as $cap => $grant) {
      $vip_role->add_cap($cap, $grant);
    }
  }
}

// Hook into WordPress to sync capabilities when admin users/roles are updated
add_action('set_user_role', 'sync_vip_customer_capabilities');
add_filter('editable_roles', 'add_vip_customer_to_editable_roles');

/**
 * Make VIP Customer role visible in the WordPress users interface
 * 
 * @param array $roles The array of editable roles.
 * @return array The filtered array of editable roles.
 */
function add_vip_customer_to_editable_roles($roles)
{
  if (isset($roles['customer']) && ! isset($roles['vip_customer'])) {
    $vip_role = get_role('vip_customer');
    if ($vip_role) {
      $roles['vip_customer'] = translate_user_role($vip_role->name);
    }
  }
  return $roles;
}

/**
 * Promote customer to VIP Customer when they create a subscription
 * 
 * When a subscription is created/activated, the associated customer is automatically
 * promoted to the VIP Customer role.
 * 
 * @param WC_Subscription $subscription The subscription object.
 */
function promote_to_vip_on_subscription($subscription)
{
  if (! $subscription) {
    return;
  }

  $customer_id = $subscription->get_customer_id();

  if (! $customer_id || $customer_id === 0) {
    return;
  }

  $user = get_user_by('ID', $customer_id);

  if (! $user) {
    return;
  }

  // Check if user is not already a VIP customer
  if (! in_array('vip_customer', $user->roles, true)) {
    $user->add_role('vip_customer');
  }
}

// Hook into WooCommerce Subscriptions when subscription is created
add_action('woocommerce_subscription_status_created', 'promote_to_vip_on_subscription');

// Hook into WooCommerce Subscriptions when subscription is activated (for manual subscriptions)
add_action('woocommerce_subscription_status_active', 'promote_to_vip_on_subscription', 10, 1);

/**
 * Promote to VIP Customer when subscription is renewed
 * 
 * Ensures VIP status is maintained/assigned when a subscription renews
 * 
 * @param WC_Subscription $subscription The subscription object.
 */
function promote_to_vip_on_subscription_renewal($subscription)
{
  if (! $subscription) {
    return;
  }

  $customer_id = $subscription->get_customer_id();

  if (! $customer_id || $customer_id === 0) {
    return;
  }

  $user = get_user_by('ID', $customer_id);

  if (! $user) {
    return;
  }

  // Ensure user has VIP customer role after renewal
  if (! in_array('vip_customer', $user->roles, true)) {
    $user->add_role('vip_customer');
  }
}

// Hook into subscription renewal
add_action('woocommerce_subscription_renewal_payment_complete', 'promote_to_vip_on_subscription_renewal');
add_action('woocommerce_subscription_status_updated', 'promote_to_vip_on_subscription_renewal');

/**
 * Promote to VIP Customer via checkout when subscription product is purchased
 * 
 * Handles cases where subscription is purchased during checkout
 * 
 * @param int $order_id The order ID.
 */
function promote_to_vip_on_subscription_checkout($order_id)
{
  if (! $order_id) {
    return;
  }

  $order = wc_get_order($order_id);

  if (! $order) {
    return;
  }

  $customer_id = $order->get_customer_id();

  if (! $customer_id || $customer_id === 0) {
    return;
  }

  // Check if order contains subscription product
  $has_subscription = false;
  if (class_exists('WC_Subscription')) {
    foreach ($order->get_items() as $item) {
      $product_id = $item->get_meta('product_id');
      if (! $product_id) {
        $product_id = $item->get_id();
      }
      $product = wc_get_product($product_id);
      if ($product && $product->is_type('subscription')) {
        $has_subscription = true;
        break;
      }
    }
  }

  // If order has subscription product, promote customer to VIP
  if ($has_subscription) {
    $user = get_user_by('ID', $customer_id);
    if ($user && ! in_array('vip_customer', $user->roles, true)) {
      $user->add_role('vip_customer');
    }
  }
}

// Hook into order completion
add_action('woocommerce_order_status_completed', 'promote_to_vip_on_subscription_checkout');
add_action('woocommerce_payment_complete', 'promote_to_vip_on_subscription_checkout');

/**
 * Demote VIP Customer back to regular customer when subscription becomes inactive
 * 
 * When a subscription changes to any status other than 'active' (e.g., cancelled, paused, expired),
 * check if the customer has any other active subscriptions. If not, remove the VIP Customer role
 * and revert them to regular customer.
 * 
 * @param WC_Subscription $subscription The subscription object.
 */
function demote_from_vip_on_subscription_inactive($subscription)
{
  if (! $subscription) {
    return;
  }

  $customer_id = $subscription->get_customer_id();

  if (! $customer_id || $customer_id === 0) {
    return;
  }

  $user = get_user_by('ID', $customer_id);

  if (! $user) {
    return;
  }

  // Check if user has VIP customer role
  if (! in_array('vip_customer', $user->roles, true)) {
    return;
  }

  // Get all subscriptions for this customer
  $subscriptions = wcs_get_users_subscriptions($customer_id);

  // Check if customer has any active subscriptions
  $has_active_subscription = false;
  foreach ($subscriptions as $sub) {
    // Skip the current subscription
    if ($sub->get_id() === $subscription->get_id()) {
      continue;
    }

    // Check if this subscription is active
    if ($sub->has_status('active')) {
      $has_active_subscription = true;
      break;
    }
  }

  // If no active subscriptions remain, demote user back to customer
  if (! $has_active_subscription) {
    $user->remove_role('vip_customer');
    $user->add_role('customer');
  }
}

// Hook into subscription status changes (except active status)
add_action('woocommerce_subscription_status_cancelled', 'demote_from_vip_on_subscription_inactive');
add_action('woocommerce_subscription_status_expired', 'demote_from_vip_on_subscription_inactive');
add_action('woocommerce_subscription_status_on-hold', 'demote_from_vip_on_subscription_inactive');
add_action('woocommerce_subscription_status_paused', 'demote_from_vip_on_subscription_inactive');
add_action('woocommerce_subscription_status_pending', 'demote_from_vip_on_subscription_inactive');
add_action('woocommerce_subscription_status_pending-cancel', 'demote_from_vip_on_subscription_inactive');

/**
 * Register REST API endpoint for bulk VIP promotion
 * 
 * Registers a REST endpoint that promotes all customers with active subscriptions
 * to the VIP Customer role.
 */
function register_vip_bulk_promotion_endpoint()
{
  register_rest_route(
    'vip-customers/v1',
    '/promote-active-subscribers',
    array(
      'methods' => 'POST',
      'callback' => 'bulk_promote_active_subscribers',
      // Admin-only: this bulk-promotes user roles, so require the same
      // capability as the VIP Customers admin page.
      'permission_callback' => function () {
        return current_user_can('manage_woocommerce');
      },
      'args' => array(
        'dry_run' => array(
          'type' => 'boolean',
          'description' => 'If true, report what would be done without making changes',
          'default' => false,
        ),
      ),
    )
  );
}

add_action('rest_api_init', 'register_vip_bulk_promotion_endpoint');

/**
 * Bulk promote all customers with active subscriptions to VIP Customer role
 * 
 * This REST endpoint callback processes all active subscriptions and promotes
 * their customers to VIP Customer role.
 * 
 * @param WP_REST_Request $request The REST request object.
 * @return WP_REST_Response The response with promotion results.
 */
function bulk_promote_active_subscribers($request)
{
  // Check if WooCommerce Subscriptions is active
  if (! class_exists('WC_Subscription')) {
    return new \WP_REST_Response(
      array(
        'success' => false,
        'message' => 'WooCommerce Subscriptions is not active',
      ),
      400
    );
  }

  $dry_run = $request->get_param('dry_run');
  $promoted_count = 0;
  $skipped_count = 0;
  $promoted_users = array();
  $errors = array();

  // Get all active subscriptions using WCS function
  $active_subscriptions = wcs_get_subscriptions(
    array(
      'status' => 'active',
      'limit' => -1,
    )
  );

  if (empty($active_subscriptions)) {
    return new \WP_REST_Response(
      array(
        'success' => true,
        'message' => 'No active subscriptions found',
        'promoted_count' => 0,
        'skipped_count' => 0,
        'dry_run' => $dry_run,
        'promoted_users' => array(),
      ),
      200
    );
  }

  $processed_customer_ids = array();

  foreach ($active_subscriptions as $subscription) {
    $customer_id = $subscription->get_customer_id();

    // Skip if no customer or already processed
    if (! $customer_id || $customer_id === 0 || isset($processed_customer_ids[$customer_id])) {
      $skipped_count++;
      continue;
    }

    $processed_customer_ids[$customer_id] = true;

    $user = get_user_by('ID', $customer_id);

    if (! $user) {
      $errors[] = sprintf('Customer ID %d does not have a valid user account', $customer_id);
      $skipped_count++;
      continue;
    }

    // Skip if already VIP customer
    if (in_array('vip_customer', $user->roles, true)) {
      $skipped_count++;
      continue;
    }

    if (! $dry_run) {
      $user->add_role('vip_customer');
    }

    $promoted_count++;
    $promoted_users[] = array(
      'user_id' => $customer_id,
      'user_email' => $user->user_email,
      'user_login' => $user->user_login,
    );
  }

  return new \WP_REST_Response(
    array(
      'success' => true,
      'message' => sprintf(
        'Promotion %s complete. %d customers promoted, %d skipped.',
        $dry_run ? '(dry run)' : 'complete',
        $promoted_count,
        $skipped_count
      ),
      'promoted_count' => $promoted_count,
      'skipped_count' => $skipped_count,
      'dry_run' => $dry_run,
      'promoted_users' => $promoted_users,
      'errors' => $errors,
    ),
    200
  );
}
