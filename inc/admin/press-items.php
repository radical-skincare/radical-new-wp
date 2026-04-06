<?php

add_action( 'init', function () {
    register_post_type( 'press_item', [
            "label" => __( "Press Items", "radical" ),
            "labels" => [
            "name" => __( "Press Items", "radical" ),
            "singular_name" => __( "Press", "radical" ),
        ],
        "description" => "",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "",
        "rest_controller_class" => "WP_REST_Posts_Controller",
        "has_archive" => false,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "delete_with_user" => false,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "can_export" => true,
        "rewrite" => [ "slug" => "press_item", "with_front" => true ],
        "query_var" => true,
        "supports" => [ "title", "editor", "thumbnail", "excerpt", "comments", "author" ],
        "taxonomies" => [ "category" ],
        "show_in_graphql" => false,
    ]);
});
