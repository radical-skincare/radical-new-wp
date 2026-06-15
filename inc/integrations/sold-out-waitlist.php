<?php

/**
 * Product Sold Out Waitlist
 *
 * Captures customer emails on the front-end "sold out" notice and stores them
 * in a custom WordPress table for later notification when stock returns.
 *
 * Replaces the prior Mailchimp embedded form.
 */

if (!defined('ABSPATH')) {
  exit;
}

define('RADICAL_SOLD_OUT_WAITLIST_TABLE', 'radical_sold_out_waitlist');
define('RADICAL_SOLD_OUT_WAITLIST_DB_VERSION', '1.0.0');
define('RADICAL_SOLD_OUT_WAITLIST_NONCE_ACTION', 'radical_sold_out_waitlist');

/**
 * Return the fully-qualified table name (with WP prefix).
 */
function radical_sold_out_waitlist_table_name() {
  global $wpdb;
  return $wpdb->prefix . RADICAL_SOLD_OUT_WAITLIST_TABLE;
}

/**
 * Create / upgrade the waitlist table via dbDelta.
 */
function radical_sold_out_waitlist_install_schema() {
  global $wpdb;

  $table_name      = radical_sold_out_waitlist_table_name();
  $charset_collate = $wpdb->get_charset_collate();

  // dbDelta is whitespace sensitive: keep two spaces before PRIMARY KEY etc.
  $sql = "CREATE TABLE {$table_name} (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    product_id BIGINT(20) UNSIGNED NOT NULL,
    product_title VARCHAR(255) NOT NULL DEFAULT '',
    email VARCHAR(190) NOT NULL,
    name VARCHAR(190) NOT NULL DEFAULT '',
    user_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
    ip_address VARCHAR(45) NOT NULL DEFAULT '',
    user_agent VARCHAR(255) NOT NULL DEFAULT '',
    referer VARCHAR(255) NOT NULL DEFAULT '',
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    notified_at DATETIME NULL DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY  (id),
    KEY product_id (product_id),
    KEY email (email),
    KEY status (status),
    UNIQUE KEY product_email (product_id, email)
  ) {$charset_collate};";

  require_once ABSPATH . 'wp-admin/includes/upgrade.php';
  dbDelta($sql);
}

/**
 * Run the schema installer when the stored DB version is out of date.
 * Fires on `init` so it survives theme switches without requiring activation hooks.
 */
add_action('init', function () {
  if (get_option('radical_sold_out_waitlist_db_version') !== RADICAL_SOLD_OUT_WAITLIST_DB_VERSION) {
    radical_sold_out_waitlist_install_schema();
    update_option('radical_sold_out_waitlist_db_version', RADICAL_SOLD_OUT_WAITLIST_DB_VERSION);
  }
});

/**
 * Render the front-end waitlist form for a sold-out product.
 * Called from inc/integrations/woocommerce.php in the `woocommerce_get_availability` filter.
 */
function radical_render_sold_out_waitlist_form($product) {
  if (!$product || !is_a($product, 'WC_Product')) {
    return;
  }

  $product_id = (int) $product->get_id();
  $form_id    = 'radical-sold-out-waitlist-' . $product_id;
  $nonce      = wp_create_nonce(RADICAL_SOLD_OUT_WAITLIST_NONCE_ACTION);
  $ajax_url   = admin_url('admin-ajax.php');

  $current_user_email = '';
  $current_user_name  = '';
  if (is_user_logged_in()) {
    $user                = wp_get_current_user();
    $current_user_email  = $user->user_email;
    $current_user_name   = trim($user->first_name . ' ' . $user->last_name);
    if (empty($current_user_name)) {
      $current_user_name = $user->display_name;
    }
  }
  ?>
  <div class="alert alert-danger mr-3">
    <span class="fa fa-exclamation-triangle"></span> <strong>Sold Out.</strong> Get notified when we're back in stock.
  </div>
  <form id="<?php echo esc_attr($form_id); ?>" class="radical-sold-out-waitlist-form" novalidate>
    <input type="hidden" name="action" value="radical_join_waitlist" />
    <input type="hidden" name="product_id" value="<?php echo esc_attr($product_id); ?>" />
    <input type="hidden" name="nonce" value="<?php echo esc_attr($nonce); ?>" />
    <div class="row mr-0 mb-3">
      <div class="col-12 col-md">
        <div class="form-outline">
          <label for="<?php echo esc_attr($form_id); ?>-name">Your Name</label>
          <input id="<?php echo esc_attr($form_id); ?>-name" type="text" name="name" class="form-control" value="<?php echo esc_attr($current_user_name); ?>" />
        </div>
      </div>
      <div class="col-12 col-md">
        <div class="form-outline">
          <label for="<?php echo esc_attr($form_id); ?>-email">Email Address</label>
          <input id="<?php echo esc_attr($form_id); ?>-email" type="email" name="email" class="form-control" value="<?php echo esc_attr($current_user_email); ?>" required />
        </div>
      </div>
      <div class="col-12 col-md-auto d-flex align-items-end">
        <button type="submit" class="btn btn-darkergray m-0 radical-sold-out-waitlist-form_submit">Join Waitlist</button>
      </div>
      <div aria-hidden="true" style="position: absolute; left: -5000px;">
        <label>Leave this field empty <input type="text" name="website" tabindex="-1" autocomplete="off" /></label>
      </div>
    </div>
    <div class="radical-sold-out-waitlist-form_feedback" role="status" aria-live="polite"></div>
  </form>
  <script>
  (function () {
    var form = document.getElementById(<?php echo wp_json_encode($form_id); ?>);
    if (!form || form.dataset.bound === '1') { return; }
    form.dataset.bound = '1';
    var feedback = form.querySelector('.radical-sold-out-waitlist-form_feedback');
    var button   = form.querySelector('.radical-sold-out-waitlist-form_submit');
    var ajaxUrl  = <?php echo wp_json_encode($ajax_url); ?>;

    form.addEventListener('submit', function (event) {
      event.preventDefault();
      feedback.textContent = '';
      button.disabled = true;

      var formData = new FormData(form);
      fetch(ajaxUrl, { method: 'POST', credentials: 'same-origin', body: formData })
        .then(function (response) { return response.json(); })
        .then(function (result) {
          if (result && result.success) {
            feedback.textContent = result.data && result.data.message
              ? result.data.message
              : "Thanks! We'll email you when this product is back in stock.";
            form.reset();
          } else {
            feedback.textContent = result && result.data && result.data.message
              ? result.data.message
              : 'Something went wrong. Please try again.';
          }
        })
        .catch(function () {
          feedback.textContent = 'Something went wrong. Please try again.';
        })
        .then(function () { button.disabled = false; });
    });
  })();
  </script>
  <?php
}

/**
 * AJAX handler — insert a waitlist submission.
 */
function radical_sold_out_waitlist_handle_submission() {
  if (!check_ajax_referer(RADICAL_SOLD_OUT_WAITLIST_NONCE_ACTION, 'nonce', false)) {
    wp_send_json_error(['message' => 'Security check failed. Please refresh and try again.'], 403);
  }

  // Honeypot — silently succeed for bots.
  if (!empty($_POST['website'])) {
    wp_send_json_success(['message' => "Thanks! We'll email you when this product is back in stock."]);
  }

  $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
  $email      = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
  $name       = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';

  if (!$product_id || get_post_type($product_id) !== 'product') {
    wp_send_json_error(['message' => 'That product could not be found.'], 400);
  }
  if (!is_email($email)) {
    wp_send_json_error(['message' => 'Please enter a valid email address.'], 400);
  }

  global $wpdb;
  $table   = radical_sold_out_waitlist_table_name();
  $product = function_exists('wc_get_product') ? wc_get_product($product_id) : null;
  $title   = $product ? $product->get_name() : get_the_title($product_id);

  // Detect existing submission so we report a friendly message instead of a DB error.
  $existing_id = (int) $wpdb->get_var($wpdb->prepare(
    "SELECT id FROM {$table} WHERE product_id = %d AND email = %s LIMIT 1",
    $product_id,
    $email
  ));
  if ($existing_id) {
    wp_send_json_success(['message' => "You're already on the waitlist for this product."]);
  }

  $inserted = $wpdb->insert(
    $table,
    [
      'product_id'    => $product_id,
      'product_title' => $title ?: '',
      'email'         => $email,
      'name'          => $name,
      'user_id'       => get_current_user_id(),
      'ip_address'    => radical_sold_out_waitlist_client_ip(),
      'user_agent'    => isset($_SERVER['HTTP_USER_AGENT']) ? substr(sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'])), 0, 255) : '',
      'referer'       => isset($_SERVER['HTTP_REFERER']) ? esc_url_raw(wp_unslash($_SERVER['HTTP_REFERER'])) : '',
      'status'        => 'pending',
      'created_at'    => current_time('mysql'),
    ],
    ['%d', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s']
  );

  if (false === $inserted) {
    wp_send_json_error(['message' => 'We could not save your request. Please try again later.'], 500);
  }

  do_action('radical_sold_out_waitlist_submission_added', (int) $wpdb->insert_id, [
    'product_id' => $product_id,
    'email'      => $email,
    'name'       => $name,
  ]);

  wp_send_json_success(['message' => "Thanks! We'll email you when this product is back in stock."]);
}
add_action('wp_ajax_radical_join_waitlist', 'radical_sold_out_waitlist_handle_submission');
add_action('wp_ajax_nopriv_radical_join_waitlist', 'radical_sold_out_waitlist_handle_submission');

/**
 * Best-effort client IP. Trusts only REMOTE_ADDR by default;
 * fronting reverse proxies should set their own header if needed.
 */
function radical_sold_out_waitlist_client_ip() {
  $ip = isset($_SERVER['REMOTE_ADDR']) ? wp_unslash($_SERVER['REMOTE_ADDR']) : '';
  return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '';
}
