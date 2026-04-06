<?php
/**
 * Coupon Card partial for dashboard
 */
?>
<div class="card border-0 bg-light h-100" <?php echo $coupon['meta']['expired'] ? 'style="opacity: 0.75;"' : ''; ?>>
  <div class="card-body d-flex flex-column justify-content-around">
    <h3 class="mb-0">
      <?php echo $coupon['meta']['coupon_amount'][0] . $coupon['meta']['amount_symbol']; ?>
    </h3>
    <h4 class="card-title mb-1"><?php echo esc_html($coupon['post_title']); ?></h4>
    <ul class="ml-0 pl-0 mb-0" style="list-style-type: none;">
      <?php if (isset($coupon['meta']['date_expires']) && $coupon['meta']['date_expires'][0]) : ?>
        <li>Valid Till : <?php echo esc_html(date('m/d/y', $coupon['meta']['date_expires'][0])); ?></li>
      <?php endif; ?>
      <?php if (isset($coupon['meta']['usage_limit']) && $coupon['meta']['usage_limit'][0]) : ?>
        <li>Usage Limit : <?php echo esc_html($coupon['meta']['usage_limit'][0]); ?> </li>
      <?php endif; ?>
    </ul>
    <div>
      <?php if (!$coupon['meta']['expired']) : ?>
        <button class="link-underline link-underline_darker-gray copy-coupon-code" tabindex="0" title="Copy Code" trigger="manual" data-coupon="<?php echo esc_attr($coupon['post_title']); ?>">Copy Code</button>
      <?php endif; ?>
    </div>
  </div>
</div>
