<?php
$order_id = $args['order_id'];
$have_renewal_gift = get_post_meta($order_id, 'have_renewal_gift', true);
$renewal_gift_product = get_post_meta($order_id, 'product', true);
?>
<?php if ($have_renewal_gift && $renewal_gift_product) : ?>
  <?php if ($product = wc_get_product( $renewal_gift_product )) : ?>
    <section class="card mb-3">
      <div class="card-body">
        <h3 class="card-title mb-0">Thank You For Your Subscription Renewal.</h3>
        <p class="card-text mb-0">You've earned a FREE gift:</p>
        <a href="<?php echo get_permalink( $renewal_gift_product ); ?>" target="_blank"><?php echo esc_html($product->get_title()); ?></a>
      </div>
    </section>
  <?php endif; ?>
<?php endif; ?>
