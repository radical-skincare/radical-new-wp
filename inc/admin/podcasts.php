<?php

/**
 * Custom Post Type for Podcasts
 */
add_action( 'init', function () {
  $labels = array(
    'name'                  => _x( 'Podcasts', 'Post Type General Name', 'radical' ),
    'singular_name'         => _x( 'Podcast', 'Post Type Singular Name', 'radical' ),
    'menu_name'             => __( 'Podcasts', 'radical' ),
    'name_admin_bar'        => __( 'Podcast', 'radical' ),
    'archives'              => __( 'Item Archives', 'radical' ),
    'attributes'            => __( 'Item Attributes', 'radical' ),
    'parent_item_colon'     => __( 'Parent Item:', 'radical' ),
    'all_items'             => __( 'All Items', 'radical' ),
    'add_new_item'          => __( 'Add New Item', 'radical' ),
    'add_new'               => __( 'Add New', 'radical' ),
    'new_item'              => __( 'New Item', 'radical' ),
    'edit_item'             => __( 'Edit Item', 'radical' ),
    'update_item'           => __( 'Update Item', 'radical' ),
    'view_item'             => __( 'View Item', 'radical' ),
    'view_items'            => __( 'View Items', 'radical' ),
    'search_items'          => __( 'Search Item', 'radical' ),
    'not_found'             => __( 'Not found', 'radical' ),
    'not_found_in_trash'    => __( 'Not found in Trash', 'radical' ),
    'featured_image'        => __( 'Featured Image', 'radical' ),
    'set_featured_image'    => __( 'Set featured image', 'radical' ),
    'remove_featured_image' => __( 'Remove featured image', 'radical' ),
    'use_featured_image'    => __( 'Use as featured image', 'radical' ),
    'insert_into_item'      => __( 'Insert into item', 'radical' ),
    'uploaded_to_this_item' => __( 'Uploaded to this item', 'radical' ),
    'items_list'            => __( 'Items list', 'radical' ),
    'items_list_navigation' => __( 'Items list navigation', 'radical' ),
    'filter_items_list'     => __( 'Filter items list', 'radical' ),
  );
  $args = array(
    'label'                 => __( 'Podcast', 'radical' ),
    'description'           => __( 'Podcast Description', 'radical' ),
    'labels'                => $labels,
    'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
    'taxonomies'            => array(
      // 'category', 'post_tag'
    ),
    'hierarchical'          => false,
    'public'                => true,
    'rewrite' => array(
      'slug' => 'podcasts',
      'with_front' => false,
    ),
    'show_ui'               => true,
    'show_in_menu'          => true,
    'menu_position'         => 5,
    'menu_icon'             => 'dashicons-microphone',
    'show_in_admin_bar'     => true,
    'show_in_nav_menus'     => true,
    'can_export'            => true,
    'has_archive'           => 'podcasts',
    // 'with_front'            => false,
    'exclude_from_search'   => false,
    'publicly_queryable'    => true,
    'capability_type'       => 'post',
    'show_in_rest'          => true,
    'query_var'             => true,
    'archive'               => true
  );
  register_post_type( 'podcasts', $args );
}, 0);

/**
 * Disable Gutenberg for CPT podcasts
 */
add_filter('use_block_editor_for_post_type', function ($current_status, $post_type) {
  if ($post_type === 'podcasts') return false;
  return $current_status;
}, 10, 2);

/**
 * Get Podcasts
 *
 * Standalone function ported from ArchivePodcasts Controller.
 */
function radical_get_podcasts($listing_type = false, $posts_per_page = false, $offset = false) {
  $args = [
    'post_type' => 'podcasts',
    'order' => 'DESC',
    'meta_key' => 'start_date',
    'orderby' => 'meta_value',
    'post_status' => array('publish'),
  ];
  if ($posts_per_page) {
    $args['posts_per_page'] = $posts_per_page;
  }
  if ($offset) {
    $args['offset'] = $offset;
  }
  if ($listing_type) {
    if ($listing_type == 'upcoming') {
      $args['meta_query'] = [
        [
          'key' => 'start_date',
          'value' => date('Y-m-d'),
          'compare' => '>=',
          'type' => 'DATE'
        ]
      ];
      $args['order'] = 'ASC';
    } else if ($listing_type == 'past') {
      $args['meta_query'] = [
        [
          'key' => 'end_date',
          'value' => date('Y-m-d'),
          'compare' => '<',
          'type' => 'DATE'
        ]
      ];
    } else if ($listing_type == 'all') {
      $args['meta_key'] = 'start_date';
      $args['orderby'] = 'meta_value_num';
      // $args['order'] = 'ASC';
    }
  }
  $podcasts_query = new WP_Query( $args );
  $podcasts = $podcasts_query->posts;
  if (!empty($podcasts)) {
    foreach ($podcasts as $podcast) {
      $podcast->feat_img_url = wp_get_attachment_url(get_post_thumbnail_id( $podcast->ID ));
      $podcast->start_date = get_field('start_date', $podcast->ID);
    }
  }
  return ['podcasts' => $podcasts, 'total_podcasts' => $podcasts_query->found_posts];
}

/**
 * AJAX: Get Podcasts (list)
 */
function radical_ajax_get_podcasts() {
  if (!isset($_POST['action']) || $_POST['action'] !== 'get_podcasts') {
    return;
  }
  $res = array( 'success' => false );
  $listing_type = (isset($_POST['listing_type'])) ? $_POST['listing_type'] : false;
  $posts_per_page = (isset($_POST['posts_per_page'])) ? $_POST['posts_per_page'] : false;
  $offset = (isset($_POST['offset'])) ? $_POST['offset'] : false;
  $res = array_merge($res, radical_get_podcasts($listing_type, $posts_per_page, $offset));
  $res['success'] = true;
  exit(json_encode($res));
}
add_action( 'wp_ajax_get_podcasts', 'radical_ajax_get_podcasts' );
add_action( 'wp_ajax_nopriv_get_podcasts', 'radical_ajax_get_podcasts' );

/**
 * AJAX: Get single Podcast
 */
function radical_ajax_get_podcast() {
  if (!isset($_POST['action']) || $_POST['action'] !== 'get_podcast') {
    return;
  }
  $res = array( 'success' => false );
  $podcast = get_post($_POST['id']);
  if (!empty($podcast)) {
    $podcast->feat_img_url = wp_get_attachment_url(get_post_thumbnail_id( $podcast->ID ));
    $podcast->meta = get_post_meta($podcast->ID);
    $podcast->meta['formatted_start_date'] = get_field('start_date', $podcast->ID);
    $podcast->meta['formatted_end_date'] = get_field('end_date', $podcast->ID);
    $podcast->meta['formatted_start_time'] = get_field('start_time', $podcast->ID);
    $podcast->meta['formatted_end_time'] = get_field('end_time', $podcast->ID);
    $podcast->meta['add_to_calendar'] = get_field('add_to_calendar', $podcast->ID);
  }
  $res['podcast'] = $podcast;
  $res['success'] = true;
  exit(json_encode($res));
}
add_action( 'wp_ajax_get_podcast', 'radical_ajax_get_podcast' );
add_action( 'wp_ajax_nopriv_get_podcast', 'radical_ajax_get_podcast' );
