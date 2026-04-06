<?php

if( function_exists('acf_add_options_page') ) {
    acf_add_options_page( array(
        'page_title' => 'Brand Partner Settings',
        'menu_title' => 'Brand Partner Settings',
        'menu_slug' => 'brand-partner-settings',
    ));
}

/**
 * ACF Local JSON
 */
add_filter('acf/settings/save_json', function ( $path ) {
    // update path
    $path = get_template_directory() . '/acf-json';
    // return
    return $path;
});

/**
 * ACF Local JSON - Load Point
 */
add_filter('acf/settings/load_json', function ( $paths ) {
    // remove original path (optional)
    unset($paths[0]);
    // append path
    $paths[] = get_template_directory() . '/acf-json';
    // return
    return $paths;
});

/**
 * ACF Options Page
 */
if( function_exists('acf_add_options_page') ) {
    acf_add_options_page( array(
		'page_title' 	=> 'Theme Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
}
