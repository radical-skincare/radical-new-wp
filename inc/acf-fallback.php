<?php
/**
 * ACF (Advanced Custom Fields) fallback shims.
 *
 * The theme uses ACF functions (get_field, have_rows, etc.) in ~230 places,
 * including core templates like header.php and footer.php. If the ACF plugin
 * is not active these functions are undefined, and any call — even one at file
 * load time — throws a fatal error that takes down the whole site.
 *
 * When ACF is missing we define safe no-op stubs that return ACF's own
 * "empty" values so templates render (without ACF content) instead of
 * crashing, and we show an admin notice so the dependency is obvious.
 *
 * These are only declared when the real ACF functions are absent, so they
 * never override the plugin when it is active. Must be loaded before any other
 * include that calls ACF functions.
 */

if (!defined('ABSPATH')) {
    exit;
}

// This file is only included by functions.php when ACF is NOT active
// (gated with class_exists('ACF')), so it simply declares safe stubs.

if (!function_exists('get_field')) {
    function get_field($selector = '', $post_id = false, $format_value = true)
    {
        return null;
    }
}

if (!function_exists('the_field')) {
    function the_field($selector = '', $post_id = false, $format_value = true)
    {
        // Nothing to echo without ACF.
    }
}

if (!function_exists('get_fields')) {
    function get_fields($post_id = false, $format_value = true)
    {
        return false;
    }
}

if (!function_exists('get_field_object')) {
    function get_field_object($selector = '', $post_id = false, $format_value = true, $load_value = true)
    {
        return false;
    }
}

if (!function_exists('have_rows')) {
    function have_rows($selector = '', $post_id = false)
    {
        // Returning false keeps `while (have_rows(...))` loops from running.
        return false;
    }
}

if (!function_exists('the_row')) {
    function the_row($format_values = false)
    {
        return false;
    }
}

if (!function_exists('get_row')) {
    function get_row($format_values = false)
    {
        return false;
    }
}

if (!function_exists('get_row_layout')) {
    function get_row_layout()
    {
        return false;
    }
}

if (!function_exists('get_sub_field')) {
    function get_sub_field($selector = '', $format_value = true)
    {
        return null;
    }
}

if (!function_exists('the_sub_field')) {
    function the_sub_field($selector = '', $format_value = true)
    {
        // Nothing to echo without ACF.
    }
}

if (!function_exists('get_sub_fields')) {
    function get_sub_fields($selector = '')
    {
        return false;
    }
}

if (!function_exists('reset_rows')) {
    function reset_rows()
    {
        return false;
    }
}

if (!function_exists('update_field')) {
    function update_field($selector = '', $value = null, $post_id = false)
    {
        return false;
    }
}

if (!function_exists('update_sub_field')) {
    function update_sub_field($selector = '', $value = null, $post_id = false)
    {
        return false;
    }
}

if (!function_exists('delete_field')) {
    function delete_field($selector = '', $post_id = false)
    {
        return false;
    }
}

if (!function_exists('acf_add_options_page')) {
    function acf_add_options_page($page = '')
    {
        return false;
    }
}

if (!function_exists('acf_add_local_field_group')) {
    function acf_add_local_field_group($field_group = array())
    {
        // No-op: field groups cannot register without ACF.
    }
}

// Tell admins why ACF-driven content is missing.
add_action('admin_notices', function () {
    if (!current_user_can('activate_plugins')) {
        return;
    }
    $theme = wp_get_theme();
    printf(
        '<div class="notice notice-warning"><p><strong>%1$s:</strong> %2$s</p></div>',
        esc_html($theme->get('Name')),
        esc_html__(
            'Advanced Custom Fields (ACF) is not active. Custom field content across the site will be empty until you install and activate ACF.',
            'radical'
        )
    );
});
