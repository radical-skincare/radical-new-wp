<?php
/**
 * Theme Setup
 */
add_action('after_setup_theme', function () {
    // Enable features from Soil when plugin is activated
    add_theme_support('soil-clean-up');
    add_theme_support('soil-jquery-cdn');
    add_theme_support('soil-nav-walker');
    add_theme_support('soil-nice-search');
    add_theme_support('soil-relative-urls');

    // Enable plugins to manage the document title
    add_theme_support('title-tag');

    // Register navigation menus
    register_nav_menus([
        'navbar'              => __('Navbar Menu'),
        'primary_navigation'  => __('Primary Navigation', 'radical'),
        'mobile-navbar'       => __('Mobile Navbar Menu'),
    ]);

    // Enable post thumbnails
    add_theme_support('post-thumbnails');

    // Enable HTML5 markup support
    add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

    // Enable selective refresh for widgets in customizer
    add_theme_support('customize-selective-refresh-widgets');

    // Use main stylesheet for visual editor
    add_editor_style('assets/css/main.css');
}, 20);

/**
 * Register sidebars
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<div class="widget %1$s %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="fs-1.25x mb-3">',
        'after_title'   => '</div>',
    ];
    register_sidebar([
        'name' => __('Primary', 'radical'),
        'id'   => 'sidebar-primary',
    ] + $config);
    register_sidebar([
        'name' => __('Footer', 'radical'),
        'id'   => 'sidebar-footer',
    ] + $config);
    register_sidebar([
        'name' => __('Mega Menu', 'radical'),
        'id'   => 'mega-menu',
    ] + $config);
    register_sidebar([
        'name' => 'Currency Converter Widget',
        'id'   => 'currency-converter-widget',
    ] + $config);
});

/**
 * Cache Control
 */
$regex_path_patterns = [
    '/account/brand-partner-customers',
    '/checkout',
];

foreach ($regex_path_patterns as $friendly_path) {
    if (isset($_SERVER['REQUEST_URI']) && preg_match('#^' . $friendly_path . '#', $_SERVER['REQUEST_URI'])) {
        $domain = $_SERVER['HTTP_HOST'];
        setcookie('NO_CACHE', '1', time() + 0, $friendly_path, $domain);
        add_action('send_headers', function () {
            header('Cache-Control: no-cache, must-revalidate, max-age=0');
        }, 15);
    }
}
