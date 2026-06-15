<?php

/**
 * Admin UI for the Product Sold Out Waitlist.
 *
 * Lives at: WP Admin → Sold Out Waitlist
 */

if (!defined('ABSPATH')) {
  exit;
}

add_action('admin_menu', function () {
  add_menu_page(
    'Product Sold Out Waitlist',
    'Sold Out Waitlist',
    'manage_woocommerce',
    'radical-sold-out-waitlist',
    'radical_sold_out_waitlist_render_admin_page',
    'dashicons-email-alt',
    57
  );
});

/**
 * Handle CSV export and row deletion before headers are sent.
 */
add_action('admin_init', function () {
  if (!isset($_GET['page']) || $_GET['page'] !== 'radical-sold-out-waitlist') {
    return;
  }
  if (!current_user_can('manage_woocommerce')) {
    return;
  }

  // Delete single row.
  if (isset($_GET['action'], $_GET['id'], $_GET['_wpnonce']) && $_GET['action'] === 'delete') {
    $id = absint($_GET['id']);
    if ($id && wp_verify_nonce($_GET['_wpnonce'], 'radical_sold_out_waitlist_delete_' . $id)) {
      global $wpdb;
      $wpdb->delete(radical_sold_out_waitlist_table_name(), ['id' => $id], ['%d']);
      wp_safe_redirect(add_query_arg(['page' => 'radical-sold-out-waitlist', 'deleted' => 1], admin_url('admin.php')));
      exit;
    }
  }

  // CSV export.
  if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    check_admin_referer('radical_sold_out_waitlist_export');
    radical_sold_out_waitlist_export_csv();
  }
});

function radical_sold_out_waitlist_export_csv() {
  global $wpdb;
  $rows = $wpdb->get_results('SELECT * FROM ' . radical_sold_out_waitlist_table_name() . ' ORDER BY created_at DESC', ARRAY_A);

  nocache_headers();
  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Disposition: attachment; filename="sold-out-waitlist-' . gmdate('Y-m-d') . '.csv"');

  $out = fopen('php://output', 'w');
  fputcsv($out, ['ID', 'Product ID', 'Product', 'Name', 'Email', 'User ID', 'IP', 'Referer', 'Status', 'Notified At', 'Created At']);
  foreach ($rows as $row) {
    fputcsv($out, [
      $row['id'],
      $row['product_id'],
      $row['product_title'],
      $row['name'],
      $row['email'],
      $row['user_id'],
      $row['ip_address'],
      $row['referer'],
      $row['status'],
      $row['notified_at'],
      $row['created_at'],
    ]);
  }
  fclose($out);
  exit;
}

function radical_sold_out_waitlist_render_admin_page() {
  if (!current_user_can('manage_woocommerce')) {
    wp_die(esc_html__('You do not have permission to view this page.', 'sage'));
  }

  global $wpdb;
  $table = radical_sold_out_waitlist_table_name();

  $per_page = 25;
  $paged    = isset($_GET['paged']) ? max(1, absint($_GET['paged'])) : 1;
  $offset   = ($paged - 1) * $per_page;
  $search   = isset($_GET['s']) ? sanitize_text_field(wp_unslash($_GET['s'])) : '';
  $product  = isset($_GET['product_id']) ? absint($_GET['product_id']) : 0;

  $where  = 'WHERE 1=1';
  $params = [];
  if ($search !== '') {
    $where   .= ' AND (email LIKE %s OR name LIKE %s OR product_title LIKE %s)';
    $like     = '%' . $wpdb->esc_like($search) . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
  }
  if ($product > 0) {
    $where   .= ' AND product_id = %d';
    $params[] = $product;
  }

  $count_sql = "SELECT COUNT(*) FROM {$table} {$where}";
  $rows_sql  = "SELECT * FROM {$table} {$where} ORDER BY created_at DESC LIMIT %d OFFSET %d";

  $count_params = $params;
  $rows_params  = array_merge($params, [$per_page, $offset]);

  $total_items = (int) ($count_params
    ? $wpdb->get_var($wpdb->prepare($count_sql, $count_params))
    : $wpdb->get_var($count_sql));

  $rows = $wpdb->get_results($wpdb->prepare($rows_sql, $rows_params), ARRAY_A);

  $total_pages = max(1, (int) ceil($total_items / $per_page));
  $base_url    = admin_url('admin.php?page=radical-sold-out-waitlist');
  $export_url  = wp_nonce_url(add_query_arg('export', 'csv', $base_url), 'radical_sold_out_waitlist_export');
  ?>
  <div class="wrap">
    <h1 class="wp-heading-inline">Product Sold Out Waitlist</h1>
    <a href="<?php echo esc_url($export_url); ?>" class="page-title-action">Export CSV</a>
    <hr class="wp-header-end" />

    <?php if (!empty($_GET['deleted'])) : ?>
      <div class="notice notice-success is-dismissible"><p>Submission deleted.</p></div>
    <?php endif; ?>

    <p class="description">
      Customer submissions captured when a product is sold out. Total: <strong><?php echo esc_html(number_format_i18n($total_items)); ?></strong>.
    </p>

    <form method="get">
      <input type="hidden" name="page" value="radical-sold-out-waitlist" />
      <?php if ($product > 0) : ?>
        <input type="hidden" name="product_id" value="<?php echo esc_attr($product); ?>" />
      <?php endif; ?>
      <p class="search-box">
        <label class="screen-reader-text" for="waitlist-search">Search:</label>
        <input type="search" id="waitlist-search" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Search email, name, or product" />
        <input type="submit" class="button" value="Search" />
        <?php if ($search !== '' || $product > 0) : ?>
          <a href="<?php echo esc_url($base_url); ?>" class="button-link">Clear</a>
        <?php endif; ?>
      </p>
    </form>

    <table class="wp-list-table widefat fixed striped">
      <thead>
        <tr>
          <th scope="col" style="width:60px;">ID</th>
          <th scope="col">Product</th>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col" style="width:110px;">User</th>
          <th scope="col" style="width:130px;">IP</th>
          <th scope="col" style="width:100px;">Status</th>
          <th scope="col" style="width:160px;">Submitted</th>
          <th scope="col" style="width:80px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($rows)) : ?>
          <tr><td colspan="9">No submissions yet.</td></tr>
        <?php else : ?>
          <?php foreach ($rows as $row) :
            $product_link = get_edit_post_link($row['product_id']);
            $user_link    = $row['user_id'] ? get_edit_user_link($row['user_id']) : '';
            $delete_url   = wp_nonce_url(
              add_query_arg(['action' => 'delete', 'id' => $row['id']], $base_url),
              'radical_sold_out_waitlist_delete_' . $row['id']
            );
          ?>
            <tr>
              <td><?php echo (int) $row['id']; ?></td>
              <td>
                <?php if ($product_link) : ?>
                  <a href="<?php echo esc_url($product_link); ?>"><?php echo esc_html($row['product_title'] ?: ('#' . $row['product_id'])); ?></a>
                <?php else : ?>
                  <?php echo esc_html($row['product_title'] ?: ('#' . $row['product_id'])); ?>
                <?php endif; ?>
                <div class="row-actions">
                  <span class="filter">
                    <a href="<?php echo esc_url(add_query_arg(['product_id' => (int) $row['product_id']], $base_url)); ?>">Filter by this product</a>
                  </span>
                </div>
              </td>
              <td><?php echo esc_html($row['name']); ?></td>
              <td><a href="mailto:<?php echo esc_attr($row['email']); ?>"><?php echo esc_html($row['email']); ?></a></td>
              <td>
                <?php if ($row['user_id'] && $user_link) : ?>
                  <a href="<?php echo esc_url($user_link); ?>">#<?php echo (int) $row['user_id']; ?></a>
                <?php elseif ($row['user_id']) : ?>
                  #<?php echo (int) $row['user_id']; ?>
                <?php else : ?>
                  <span class="description">guest</span>
                <?php endif; ?>
              </td>
              <td><?php echo esc_html($row['ip_address']); ?></td>
              <td><?php echo esc_html($row['status']); ?></td>
              <td><?php echo esc_html(mysql2date(get_option('date_format') . ' ' . get_option('time_format'), $row['created_at'])); ?></td>
              <td>
                <a href="<?php echo esc_url($delete_url); ?>" class="submitdelete" onclick="return confirm('Delete this submission?');">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>

    <?php if ($total_pages > 1) :
      $page_links = paginate_links([
        'base'      => add_query_arg('paged', '%#%'),
        'format'    => '',
        'prev_text' => '&laquo;',
        'next_text' => '&raquo;',
        'total'     => $total_pages,
        'current'   => $paged,
      ]);
      if ($page_links) :
    ?>
      <div class="tablenav"><div class="tablenav-pages"><?php echo wp_kses_post($page_links); ?></div></div>
    <?php endif; endif; ?>
  </div>
  <?php
}
