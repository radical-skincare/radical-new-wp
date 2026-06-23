
<?php
$site_url = get_site_url();
$type = $args['type'];
$subscription = $args['subscription'] ?? null;
$order = $args['order'] ?? null;
$is_subscription = isset($subscription);
$wc_object = $is_subscription ? $subscription : $order;
$subscription_id = $is_subscription ? $subscription->get_ID() : false;
?>
<?php if ($type === 'billing') : ?>
  <div class="card mb-3 w-100">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h4 class="cart-title mb-0">Billing Address</h4>
        <?php if ($is_subscription) : ?>
          <a href="<?php echo esc_html($site_url); ?>/account/edit-address/billing?subscription=<?php echo esc_html($subscription_id); ?>" title="Change Address" class="text-darker-gray">
            <span class="sr-only">Change Address</span>
            <i class="fa fa-cogs" aria-hidden="true"></i>
          </a>
        <?php endif; ?>
      </div>
      <strong class="mb-0"><?php echo esc_html(radical_formatted_billing_name($wc_object)); ?></strong>
      <address class="card-text"><?php echo radical_formatted_billing_address($wc_object); ?></address>
      <?php if ($wc_object->get_billing_email()) : ?>
        <a href="mailto:<?php echo esc_html($wc_object->get_billing_email()); ?>" class="d-block text-dark-gray" title="Email"><i class="fa fa-envelope mr-2" aria-hidden="true"></i> <?php echo esc_html($wc_object->get_billing_email()); ?></a>
      <?php endif; ?>
      <?php if ($wc_object->get_billing_phone()) : ?>
        <a href="tel:<?php echo esc_html($wc_object->get_billing_phone()); ?>" class="d-block text-dark-gray" title="Call"><i class="fa fa-phone mr-2" aria-hidden="true"></i> <?php echo esc_html($wc_object->get_billing_phone()); ?></a>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>
<?php if ($type === 'shipping') : ?>
  <div class="card mb-3 w-100">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h4 class="cart-title mb-0">Shipping Address</h4>
        <?php if ($is_subscription) : ?>
          <a href="<?php echo esc_html($site_url); ?>/account/edit-address/shipping?subscription=<?php echo esc_html($subscription_id); ?>" title="Change Address" class="text-darker-gray">
            <span class="sr-only">Change Address</span>
            <i class="fa fa-cogs" aria-hidden="true"></i>
          </a>
        <?php endif; ?>
      </div>
      <strong class="mb-0"><?php echo esc_html(radical_formatted_shipping_name($wc_object)); ?></strong>
      <address class="card-text mb-0"><?php echo radical_formatted_shipping_address($wc_object); ?></address>
      <?php if ($wc_object->get_shipping_phone()) : ?>
        <a href="tel:<?php echo esc_html($wc_object->get_shipping_phone()); ?>" class="d-block text-dark-gray" title="Call"><i class="fa fa-phone mr-2" aria-hidden="true"></i> <?php echo esc_html($wc_object->get_shipping_phone()); ?></a>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>
