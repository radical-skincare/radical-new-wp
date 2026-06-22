<?php
/**
 * Add <body> classes
 */
add_filter('body_class', function (array $classes) {
    /** Add page slug if it doesn't exist */
    if (is_single() || is_page() && !is_front_page()) {
        if (!in_array(basename(get_permalink()), $classes)) {
            $classes[] = basename(get_permalink());
        }
    }

    /** Add class if sidebar is active */
    if (display_sidebar()) {
        $classes[] = 'sidebar-primary';
    }

    /** Clean up class names for custom templates */
    $classes = array_map(function ($class) {
        return preg_replace(['/-blade(-php)?$/', '/^page-template-views/'], '', $class);
    }, $classes);

    /**
     * Add a bare "template-{name}" class (e.g. "template-home") matching the
     * flat template filename. The old Sage theme stored templates as
     * "views/template-home.blade.php" and the regex above stripped that down
     * to "template-home" for the JS modules (TemplateHome.js, etc.) to key
     * off of. Flat filenames like "template-home.php" have no "-blade"
     * segment, so that regex never fires here — add the bare class directly.
     */
    $template_slug = get_page_template_slug();
    if ($template_slug) {
        $bare_template_class = basename($template_slug, '.php');
        if (!in_array($bare_template_class, $classes)) {
            $classes[] = $bare_template_class;
        }
    }

    return array_filter($classes);
});

/**
 * Add "... Continued" to the excerpt
 */
add_filter('excerpt_more', function () {
    return ' &hellip; <a href="' . get_permalink() . '" class="text-darker-gray font-weight-bold">' . __('Continued', 'radical') . '</a>';
});
