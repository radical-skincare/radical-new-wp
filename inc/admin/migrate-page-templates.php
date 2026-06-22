<?php
/**
 * One-time migration tool: fixes _wp_page_template postmeta left over from the
 * old Sage/Blade theme (radical-wp), e.g. "views/template-contact.blade.php",
 * which doesn't match any file in this theme and silently falls back to the
 * generic page.php. Rewrites those values to this theme's flat filenames,
 * e.g. "template-contact.php".
 *
 * Safe to run repeatedly: only rows still matching the old "views/*.blade.php"
 * pattern are touched, and rows whose target file doesn't exist in this theme
 * (legacy/orphaned assignments predating even the old theme) are left alone.
 */

function radical_migrate_page_templates_scan() {
    global $wpdb;

    $rows = $wpdb->get_results(
        "SELECT post_id, meta_value FROM {$wpdb->postmeta}
         WHERE meta_key = '_wp_page_template'
         AND meta_value LIKE 'views/%.blade.php'"
    );

    $results = [];

    foreach ($rows as $row) {
        $post = get_post($row->post_id);
        if (!$post) {
            continue;
        }

        $new_value = preg_replace('#^views/#', '', $row->meta_value);
        $new_value = preg_replace('/\.blade\.php$/', '.php', $new_value);
        $file_exists = file_exists(get_template_directory() . '/' . $new_value);

        $results[] = [
            'post_id'     => $post->ID,
            'post_title'  => $post->post_title,
            'old_value'   => $row->meta_value,
            'new_value'   => $new_value,
            'file_exists' => $file_exists,
            'action'      => $file_exists ? 'migrate' : 'skip-no-file',
        ];
    }

    return $results;
}

function radical_migrate_page_templates_apply($rows) {
    $migrated = 0;

    foreach ($rows as $row) {
        if ($row['action'] !== 'migrate') {
            continue;
        }
        update_post_meta($row['post_id'], '_wp_page_template', $row['new_value']);
        $migrated++;
    }

    return $migrated;
}

add_action('admin_menu', function () {
    add_management_page(
        'Migrate Page Templates',
        'Migrate Page Templates',
        'manage_options',
        'radical-migrate-page-templates',
        'radical_render_migrate_page_templates_page'
    );
});

function radical_render_migrate_page_templates_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $migrated_count = null;

    if (
        isset($_POST['radical_migrate_run'])
        && check_admin_referer('radical_migrate_page_templates')
    ) {
        $rows = radical_migrate_page_templates_scan();
        $migrated_count = radical_migrate_page_templates_apply($rows);
    }

    $rows = radical_migrate_page_templates_scan();
    ?>
    <div class="wrap">
        <h1>Migrate Page Templates</h1>
        <p>
            Finds pages still assigned an old Sage-theme template path
            (<code>views/template-x.blade.php</code>) and rewrites it to this
            theme's flat filename (<code>template-x.php</code>) so the correct
            template actually loads.
        </p>

        <?php if ($migrated_count !== null) : ?>
            <div class="notice notice-success">
                <p><strong><?php echo esc_html($migrated_count); ?></strong> page(s) migrated.</p>
            </div>
        <?php endif; ?>

        <?php if (empty($rows)) : ?>
            <p>No pages found with an old-style page template assignment. Nothing to do.</p>
        <?php else : ?>
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th>Page</th>
                        <th>Old value</th>
                        <th>New value</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row) : ?>
                        <tr>
                            <td>
                                <a href="<?php echo esc_url(get_edit_post_link($row['post_id'])); ?>">
                                    <?php echo esc_html($row['post_title']); ?>
                                </a>
                                (#<?php echo esc_html($row['post_id']); ?>)
                            </td>
                            <td><code><?php echo esc_html($row['old_value']); ?></code></td>
                            <td><code><?php echo esc_html($row['new_value']); ?></code></td>
                            <td>
                                <?php if ($row['action'] === 'migrate') : ?>
                                    <span style="color:#2271b1;">Will migrate</span>
                                <?php else : ?>
                                    <span style="color:#646970;">Skipped &mdash; target file not found in theme</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <form method="post" style="margin-top:1em;">
                <?php wp_nonce_field('radical_migrate_page_templates'); ?>
                <input type="hidden" name="radical_migrate_run" value="1">
                <?php submit_button('Run Migration'); ?>
            </form>
        <?php endif; ?>
    </div>
    <?php
}
