<?php
$site_url = get_site_url();
$product_category = '';
function radical_getSelectedLink($prefix, $slug) {
  $url = $_SERVER['REQUEST_URI'];
  $index = strpos($url, $prefix) + strlen($prefix);
  $result = substr($url, $index);
  $result = strtok($result, '/');
  return $result;
}
$terms = get_terms( array('product_cat') );
if ($terms) {
  foreach ($terms as $category) {
    if (!$category->count) {
      continue;
    }
    $prefix = "/product-category/";
    $classes = 'text-dark mb-2';
    $cat_link = trailingslashit($site_url . $prefix . $category->slug);
    if (radical_getSelectedLink($prefix, $category->slug) === $category->slug) {
      $classes .= ' selected-accordion-link font-weight-bolder';
      $cat_link = 'javascript:void(0)';
    }
    if ($category->slug === 'brand-partner-exclusive') {
      if (is_user_logged_in() && get_user_meta(get_current_user_id(), 'v_affiliate_status', true) === 'active') {
        $product_category = $product_category . "<a class='$classes' href='$cat_link' title='$category->name' style='display: block;'>$category->name</a>";
      }
    } else if ($category->slug !== 'ambassador' && $category->slug !== 'books') {
      $product_category = $product_category . "<a class='$classes' href='$cat_link' title='$category->name' style='display: block;'>$category->name</a>";
    }
  }
}

$product_tags = '';
$terms = get_terms( array('product_tag') );
if ($terms) {
  foreach($terms as $tags) {
    if (!$tags->count) {
      continue;
    }
    $prefix = '/product-tag/';
    $classes = 'text-dark mb-2';
    $tag_link = trailingslashit($site_url . $prefix . $tags->slug);
    if (radical_getSelectedLink($prefix, $tags->slug) === $tags->slug) {
      $classes .= ' selected-accordion-link font-weight-bolder';
      $tag_link = 'javascript:void(0)';
    }
    $product_tags = $product_tags . "<a class='$classes' href='$tag_link' title='$tags->name' style='display: block;'>$tags->name</a>";
  }
}
?>
<?php
get_template_part('template-parts/modules/flex/accordion-list', null, [
  'id' => 'shop-sidebar',
  'items' => [
    [
      'title' => 'Shop by Skin Concern',
      'content' => $product_tags
    ],
    [
      'title' => 'Shop by Product Category',
      'content' => $product_category
    ]
  ],
]);
?>
