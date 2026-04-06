<?php
/* ============================================================
Pagination
 ============================================================ */
add_filter('next_posts_link_attributes', 'posts_link_attributes');
add_filter('previous_posts_link_attributes', 'posts_link_attributes');

function posts_link_attributes() {
  return 'class="page-link"';
}

function radical_skincare_pagination( $query = NULL, $paged = NULL ) {

	if ( empty( $query ) ) {
		global $wp_query;
	} else {
		$wp_query = $query;
	}

	/** Stop execution if there's only 1 page */
	if( $wp_query->max_num_pages <= 1 ) {
		return;
	}

	if ( empty( $paged ) ) {
		$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	}

	$max   = intval( $wp_query->max_num_pages );

	/**	Add current page to the array */
	if ( $paged >= 1 ) {
		$links[] = $paged;
	}

	/**	Add the pages around the current page to the array */
	if ( $paged >= 3 ) {
		$links[] = $paged - 1;
		$links[] = $paged - 2;
	}

	if ( ( $paged + 2 ) <= $max ) {
		$links[] = $paged + 2;
		$links[] = $paged + 1;
	}

	echo '<div class="d-flex justify-content-center align-items-center mb-5"><ul class="pagination mb-0">' . "\n";

	/**	Previous Post Link */
	if ( get_previous_posts_link() ) {
		printf( '<li>%s</li>' . "\n", get_previous_posts_link() );
	}

	/**	Link to first page, plus ellipses if necessary */
	if ( ! in_array( 1, $links ) ) {
		$class = 1 == $paged ? ' class="active page-item"' : ' class="page-item"';

		printf( '<li%s><a href="%s" class="page-link">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

		if ( ! in_array( 2, $links ) )
			echo '<li>…</li>';
	}

	/**	Link to current page, plus 2 pages in either direction if necessary */
	sort( $links );
	foreach ( (array) $links as $link ) {
		$class = $paged == $link ? ' class="active page-item"' : ' class="page-item"';
		printf( '<li%s><a href="%s" class="page-link">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
	}

	/**	Link to last page, plus ellipses if necessary */
	if ( ! in_array( $max, $links ) ) {
		if ( ! in_array( $max - 1, $links ) ) {
			echo '<li class="page-item"><a href="javascript:void(0)" class="page-link" disabled>…</a></li>' . "\n";
		}

		$class = $paged == $max ? ' class="active page-item"' : ' class="page-item"';
		printf( '<li%s><a href="%s" class="page-link">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
	}

	/**	Next Post Link */
	if ( get_next_posts_link() ) {
		printf( '<li class="page-item">%s</li>' . "\n", get_next_posts_link() );
	}

	echo '</ul></div>' . "\n";

}

function add_class_to_non_top_level_menu_anchors( $atts, $item, $args, $depth ) {
  $enable_mega_menu = get_post_meta( $item->ID, 'enable_mega_menu', true );
  if ($enable_mega_menu && isset($atts['class'])) {
    $atts['class'] =  $atts['class'] . ' open-mega-menu';
  }
  return $atts;
}
add_filter( 'nav_menu_link_attributes', 'add_class_to_non_top_level_menu_anchors', 10, 4 );

/*
* Show Breadbrumb on website
*/
function radical_get_breadcrumb($classes = '') {
  $to_return = '<nav class="my-breadcrumb ' . $classes . '">';
  if (is_category() || is_single()) {
    $site_url = get_site_url();
    global $post;
    $post_type = get_post_type($post->ID);
    if ($post->post_type === 'post') {
      $to_return .= '<a href="' . $site_url . '/blog">Blog</a>';
    } else if ($post->post_type === 'press_item') {
      $to_return .= '<a href="' . $site_url . '/press">Press</a>';
    } else if ($post->post_type === 'podcasts') {
      $to_return .= '<a href="' . $site_url . '/podcasts">Podcasts</a>';
    }
    $categories = get_the_category_list(' &bull; ');
    if ($categories) {
      $to_return .= "&nbsp;&nbsp;/&nbsp;&nbsp;" . $categories;      
    }
    if (is_single()) {
      $to_return .= "&nbsp;&nbsp;/&nbsp;&nbsp;";
      $to_return .= get_the_title();
    }
  } else if (is_page()) {
    $to_return .= "&nbsp;&nbsp;/&nbsp;&nbsp;";
    $to_return .= get_the_title();
  } else if (is_search()) {
    $to_return .= "&nbsp;&nbsp;/&nbsp;&nbsp;Search Results for... ";
    $to_return .= '"<em>';
    $to_return .= the_search_query();
    $to_return .= '</em>"';
  }
  $to_return .= '</nav>';
  return $to_return;
}

function radical_get_user_most_purchased_product($user_id){
  $limit = 1;
  global $wpdb;
  $limit_clause = $limit > 0 ? "LIMIT $limit" : "";
  $products_ids = $wpdb->get_col("
  SELECT DISTINCT products.ID
  FROM {$wpdb->prefix}posts as products
  JOIN {$wpdb->prefix}postmeta as product_meta
      ON products.ID = product_meta.post_id
  JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_itemmeta
      ON products.ID = order_itemmeta.meta_value
  JOIN {$wpdb->prefix}woocommerce_order_items as order_items
      ON order_itemmeta.order_item_id = order_items.order_item_id
  JOIN {$wpdb->prefix}posts as orders
      ON order_items.order_id = orders.ID
  JOIN {$wpdb->prefix}postmeta as order_meta
      ON orders.ID = order_meta.post_id
  WHERE orders.post_type = 'shop_order'
  AND orders.post_status IN ('wc-completed','wc-processing')
  AND order_meta.meta_key = '_customer_user'
  AND order_meta.meta_value = '$user_id'
  AND order_itemmeta.meta_key = '_product_id'
  AND products.post_type = 'product'
  AND products.post_status = 'publish'
  AND product_meta.meta_key = 'total_sales'
  ORDER BY cast(product_meta.meta_value as unsigned) DESC  $limit_clause
  ");
  return $products_ids;
}

function radical_get_clean_excerpt($excerpt, $wordLimit  = 50) {
  $excerpt = preg_replace(" ([.*?])",'',$excerpt);
  $excerpt = strip_shortcodes($excerpt);
  $excerpt = strip_tags($excerpt);
  $excerpt = substr($excerpt, 0, $wordLimit);
  $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
  $excerpt = trim(preg_replace( '/\s+/', ' ', $excerpt));
  return $excerpt;
}

function radical_paginate_search() {
  global $wp_query;
  $searchPages = $wp_query->max_num_pages;
  $theBig = 999999999;
  $paginateSearchArgs = [
    'base' => str_replace($theBig, '%#%', esc_url(get_pagenum_link($theBig))),
    'format' => '?page = %#%',
    'current' =>  max(1, get_query_var('paged')),
    'total' => $searchPages
  ];
  return paginate_links($paginateSearchArgs);
}

