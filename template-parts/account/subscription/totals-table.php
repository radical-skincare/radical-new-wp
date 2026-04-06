<?php
$totals = $subscription->get_order_item_totals();
unset( $totals['payment_method'] );
$total_savings = radical_get_subscription_savings($subscription);
$interval = (int)$subscription->get_billing_interval();
$period = $subscription->get_billing_period();
$total_savings += $subscription->get_discount_total();
?>
<table class="w-100 mb-3" id='totals_table'>
  <tbody class="w-100">
    <?php foreach ($totals as $key => $total) : ?>
      <tr class="d-flex justify-content-between border-bottom mb-3 pb-1">
        <td class="fs-4 title-f"><?php echo esc_html($total['label']); ?></td>
        <td class="fs-4 title-f font-weight-bold value-for-<?php echo $key; ?>"><?php echo wp_kses_post($total['value']); ?></td>
      </tr>
    <?php endforeach; ?>
    <?php if ($total_savings) : ?>
      <tr class="d-flex justify-content-between border-bottom mb-3 pb-1">
        <td class="fs-4 title-f">Total Savings:</td>
        <td class="fs-4 title-f font-weight-bold">
          <span class="woocommerce-Price-amount amount text-success">
            <span class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol(); ?></span><span class="value-for-total_savings"><?php echo round($total_savings); ?></span></span> / <?php echo $interval > 1 ? $interval : ''; ?><?php echo $period; ?>
          </td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>
