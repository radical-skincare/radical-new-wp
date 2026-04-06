<table class="w-100 mb-3">
  <tbody class="w-100">
    <?php foreach ($order->get_order_item_totals() as $key => $total) : ?>
      <tr class="d-flex justify-content-between border-bottom mb-3 pb-1">
        <td class="fs-4 title-f"><?php echo esc_html($total['label']); ?></td>
        <td class="fs-4 title-f font-weight-bold"><?php echo wp_kses_post($total['value']); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
