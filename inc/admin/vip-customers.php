<?php

// Ensure WooCommerce is active
if (!class_exists('WooCommerce')) {
    echo '<div class="notice notice-error"><p>WooCommerce is not active.</p></div>';
    return;
}

// Register the admin page
add_action('admin_menu', function () {
    add_menu_page(
        'VIP Customers',
        'VIP Customers',
        'manage_woocommerce',
        'vip-customers',
        'render_vip_customers_page',
        'dashicons-groups',
        56
    );
});

function render_vip_customers_page() {
    $tab = isset($_GET['tab']) ? $_GET['tab'] : 'automatic';
    $automatic_url = admin_url('admin.php?page=vip-customers&tab=automatic');
    $manual_url = admin_url('admin.php?page=vip-customers&tab=manual');
    $bp_ordered_for_customers_url = admin_url('admin.php?page=vip-customers&tab=bp_ordered_for_customers');

    $per_page = 20;
    $paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $search_query = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

    // Get all active subscriptions
    $args = [
        'post_type' => 'shop_subscription',
        'post_status' => 'wc-active',
        'posts_per_page' => -1,
    ];
    $subscriptions = get_posts($args);
    $unique_customers = [];

    foreach ($subscriptions as $subscription) {
        $order = wcs_get_subscription($subscription->ID);
        if ($order) {
            $user_id = $order->get_user_id();
            if (!isset($unique_customers[$user_id])) {
                $unique_customers[$user_id] = [
                    'email' => $order->get_billing_email(),
                    'subs' => [],
                ];
            }
            $unique_customers[$user_id]['subs'][] = $subscription->ID;
        }
    }

    // Apply search filter
    if ($search_query) {
        $unique_customers = array_filter($unique_customers, function($data) use ($search_query) {
            $user = get_user_by('email', $data['email']);
            if (!$user) return false;
            return stripos($user->display_name, $search_query) !== false || stripos($data['email'], $search_query) !== false;
        });
    }

    // Pagination
    $total_items = count($unique_customers);
    $offset = ($paged - 1) * $per_page;
    $paged_customers = array_slice($unique_customers, $offset, $per_page, true);
    $total_pages = ceil($total_items / $per_page);
    $base_url = admin_url('admin.php?page=vip-customers&s=' . urlencode($search_query));
    ?>
    <div class="wrap">
      <h1>VIP Customers - Active Subscriptions</h1>
      <h2 class="nav-tab-wrapper">
          <a href="<?= esc_url($automatic_url); ?>" class="nav-tab <?= $tab === 'automatic' ? 'nav-tab-active' : '' ?>">Automatically added</a>
          <a href="<?= esc_url($manual_url); ?>" class="nav-tab <?= $tab === 'manual' ? 'nav-tab-active' : '' ?>">Manually added</a>
          <a href="<?= esc_url($bp_ordered_for_customers_url); ?>" class="nav-tab <?= $tab === 'bp_ordered_for_customers' ? 'nav-tab-active' : '' ?>">BP Ordered for Customers</a>
      </h2>
      <?php if ($tab === 'automatic') : ?>
        <p class="description">This page lists all VIP customers with active subscriptions. You can search by customer name or email, and export the list to CSV.</p>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
          <form method="get">
              <input type="hidden" name="page" value="vip-customers" />
              <input type="search" name="s" value="<?php echo esc_attr($search_query); ?>" placeholder="Search by name or email..." />
              <input type="submit" class="button" value="Search" />
          </form>
          <button onclick="exportTableToCSV()">Export to CSV</button>
        </div>
        <table id="vip-customers-table" class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Subscription ID(s)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($paged_customers as $user_id => $data) :
                    $user = get_userdata($user_id);
                    $name = $user ? $user->display_name : '—';
                    $subs = implode(', ', array_map(function($id) {
                        return "<a href='" . get_edit_post_link($id) . "' target='_blank'>{$id}</a>";
                    }, $data['subs']));
                ?>
                <tr>
                    <td>
                        <a href="<?= esc_url(get_edit_user_link($user_id)) ?>" target="_blank"><?= $user_id ?></a>
                    </td>
                    <td><?= esc_html($data['email']) ?></td>
                    <td><?= esc_html($name) ?></td>
                    <td><?= $subs ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if ($total_pages > 1) : ?>
            <div class="tablenav bottom">
                <div class="tablenav-pages">
                    <span class="pagination-links">
                        <?php if ($paged > 1): ?>
                            <a class="prev-page button" href="<?= esc_url($base_url . '&paged=' . ($paged - 1)) ?>">&laquo;</a>
                        <?php else: ?>
                            <span class="tablenav-pages-navspan">&laquo;</span>
                        <?php endif; ?>

                        <span class="paging-input">
                            <span class="current-page"><?php echo $paged; ?></span> of
                            <span class="total-pages"><?php echo $total_pages; ?></span>
                        </span>

                        <?php if ($paged < $total_pages): ?>
                            <a class="next-page button" href="<?= esc_url($base_url . '&paged=' . ($paged + 1)) ?>">&raquo;</a>
                        <?php else: ?>
                            <span class="tablenav-pages-navspan">&raquo;</span>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        <?php endif; ?>
      <?php endif; ?>
      <?php if ($tab === 'manual') : ?>
        <p class="description">This tab is for manually adding VIP customers. Use the form below to add a customer by email.</p>
        <form method="post" action="" style="margin-bottom: 20px;">
            <input type="hidden" name="action" value="add_vip_customer">
            <input type="email" name="vip_email" placeholder="Enter VIP customer email" class="widefat" required />
            <input type="submit" class="button button-primary" value="Add VIP Customer" />
        </form>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_vip_customer') {
            $vip_email = sanitize_email($_POST['vip_email']);
            $user = get_user_by('email', $vip_email);
            if ($user) {
                update_user_meta($user->ID, 'vip_customer', '1');
                echo '<div class="notice notice-success"><p>VIP customer added successfully.</p></div>';
            } else {
                echo '<div class="notice notice-error"><p>User not found.</p></div>';
            }
        } ?>
        <table class="widefat" id="vip-customers-table_manual">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch users with the user meta vip_customer
                $vip_users = get_users(array(
                    'meta_key' => 'vip_customer',
                    'meta_value' => '1',
                    'fields' => array('ID', 'user_email', 'display_name'),
                ));
                foreach ($vip_users as $user) :
                    $user_id = $user->ID;
                    $email = $user->user_email;
                    $name = $user->display_name;
                    ?>
                    <tr>
                        <td>
                            <a href="<?= esc_url(get_edit_user_link($user_id)) ?>" target="_blank"><?= $user_id ?></a>
                        </td>
                        <td><?= esc_html($email) ?></td>
                        <td><?= esc_html($name) ?></td>
                        <td><button class="button-link delete-vip" data-user-id="<?= $user_id ?>">[- remove]</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
      <?php endif; ?>
      <?php if ($tab === 'bp_ordered_for_customers') : ?>
        <!-- Output a list of subscription customers that have the post meta 'gig_ordered_by' -->
        <table class="widefat" id="bp-ordered-for-customers-table">
            <thead>
                <tr>
                    <th>Subscription ID</th>
                    <th>BP Email (Subscription Owner Email)</th>
                    <th>BP Name</th>
                    <th>Customer Email</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch subscriptions with the post meta 'gig_ordered_by'
                $subscriptions = get_posts(array(
                    'post_type' => 'shop_subscription',
                    'post_status' => array('wc-active', 'wc-on-hold', 'wc-pending', 'wc-cancelled'),
                    'posts_per_page' => -1,
                    'meta_query' => array(
                        array(
                            'key' => 'gig_ordered_by',
                            'compare' => 'EXISTS',
                        ),
                    ),
                ));
                foreach ($subscriptions as $subscription) {
                    $subscription_obj = wcs_get_subscription($subscription->ID);
                    if (!$subscription_obj) continue;
                    $user_id = $subscription_obj->get_user_id();
                    $user = get_userdata($user_id);
                    if (!$user) continue;
                    $email = $user->user_email;
                    $name = $user->display_name;
                    ?>
                    <tr>
                        <td><a href="<?= get_edit_post_link($subscription->ID); ?>" target="_blank"><?= $subscription->ID ?></a></td>
                        <td><?= esc_html($email) ?></td>
                        <td><?= esc_html($name) ?></td>
                        <td><?= esc_html($subscription_obj->get_billing_email()) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
      <?php endif; ?>
    </div>
    <script>
    function exportTableToCSV() {
        var csv = [];
        var rows = document.querySelectorAll("#vip-customers-table tr");
        for (var i = 0; i < rows.length; i++) {
            var row = [], cols = rows[i].querySelectorAll("td, th");
            for (var j = 0; j < cols.length; j++) {
                var text = cols[j].innerText.replace(/\n/g, '').trim();
                row.push('"' + text.replace(/"/g, '""') + '"');
            }
            csv.push(row.join(","));
        }
        var csvContent = "data:text/csv;charset=utf-8," + csv.join("\n");
        var link = document.createElement("a");
        link.setAttribute("href", encodeURI(csvContent));
        link.setAttribute("download", "vip_customers.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
      document.querySelectorAll(".delete-vip").forEach(function (btn) {
        btn.addEventListener("click", function () {
          const userId = this.getAttribute("data-user-id");
          if (!confirm("Are you sure you want to remove this VIP customer?")) return;

          const formData = new FormData();
          formData.append("action", "remove_vip_customer");
          formData.append("user_id", userId);

          fetch(ajaxurl, {
            method: "POST",
            body: formData,
          })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              this.closest("tr").remove();
            } else {
              alert("Failed to remove VIP status.");
            }
          });
        });
      });
    });
    </script>
    <?php
}

add_action('wp_ajax_remove_vip_customer', function () {
    $user_id = intval($_POST['user_id']);
    if ($user_id && current_user_can('manage_woocommerce')) {
        delete_user_meta($user_id, 'vip_customer');
        wp_send_json_success();
    } else {
        wp_send_json_error();
    }
});
