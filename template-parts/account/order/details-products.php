<?php
do_action( 'woocommerce_order_details_before_order_table_items', $order );
$have_renewal_gift = get_post_meta($order->get_ID(), 'have_renewal_gift', true);
$gift_product = get_post_meta($order->get_ID(), 'product', true);
?>
<div class="order-details-products list-group mb-3">
<?php foreach ( $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) ) as $item_id => $item ) : ?>
  <?php
  $product = $item->get_product();
  ?>
  <div class="order-product-card list-group-item p-3">
    <?php if ($have_renewal_gift && $gift_product && $gift_product == $product->get_ID()) : ?>
      <div class="badge badge-primary position-absolute" style="z-index:1">Gift</div>
    <?php endif; ?>
    <div class="row">
      <?php if ($image = wp_get_attachment_image_src(get_post_thumbnail_id($item['product_id']), 'single-post-thumbnail')) : ?>
        <div class="col-sm-2 d-flex align-items-center">
          <img src="<?php echo esc_html($image[0]); ?>" class="d-block rounded" alt="<?php echo esc_html($item['alt']); ?>"/>
        </div>
      <?php endif; ?>
      <div class="col-sm-10 d-flex flex-column align-items-start justify-content-between">
        <div class="w-100 mb-3">
          <?php if ($product && !$product->is_visible()) : ?>
            <h5 class="title-e m-0"><?php echo wp_kses_post(apply_filters('woocommerce_order_item_name', $item['name'], $item, false)); ?></h5>
          <?php else : ?>
            <?php echo wp_kses_post(apply_filters('woocommerce_order_item_name', sprintf('<a href="%s" class="title-d mb-0">%s</a>', get_permalink($item['product_id']), $item['name']), $item, false)); ?>
          <?php endif; ?>
        </div>
        <div class="row align-items-center flex-wrap w-100 mb-3">
          <div class="col-auto">
            <label class="m-0">Price</label>
            <div class="price fs-1.25x">
              <span class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol(); ?></span>
              <?php echo esc_html($order->get_item_subtotal( $item, false, true )); ?>
            </div>
          </div>
          <div class="col-auto text-center">
            <label class="mb-0">Quantity</label><br>
            <span class="fs-1.25x"><?php echo esc_html($item['qty']); ?></span>
          </div>
          <div class="col-auto">
            <label class="mb-0">Total</label><br>
            <div class="price">
              <span class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol(); ?></span>
              <span class="fs-1.25x"><?php echo esc_html($item['line_total']); ?></span>
            </div>
          </div>
          <?php foreach ($item->get_formatted_meta_data() as $item_meta) : ?>
            <div class="col-auto">
              <label class="mb-0"><?php echo esc_html($item_meta->display_key); ?></label><br>
              <?php echo strip_tags($item_meta->display_value); ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>
<?php
do_action( 'woocommerce_order_details_after_order_table_items', $order );
?>
