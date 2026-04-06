<?php
/**
 * Accordion List Module
 *
 * Expected variables: $id, $items
 */
if (!isset($id) || !isset($items)) {
    return;
}
?>
<div id="<?php echo esc_attr($id); ?>" class="accordion">
  <?php foreach ($items as $key => $item) : ?>
    <div class="accordion-item mb-3">
      <div id="<?php echo esc_attr($id); ?>heading<?php echo esc_attr($key); ?>" class="accordion-item_header">
        <button class="btn accordion-item_btn lh-base text-left <?php echo ($key === 0) ? '' : 'collapsed'; ?> mb-2" type="button" data-toggle="collapse" data-target="#<?php echo esc_attr($id); ?>collapse<?php echo esc_attr($key); ?>" aria-expanded="<?php echo ($key === 0) ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr($id); ?>collapse<?php echo esc_attr($key); ?>">
          <div class="accordion-item_title title title-d">
            <?php echo $item['title']; ?>
          </div>
          <span class="accordion-item_icon-show" aria-hidden="true">&plus;</span>
          <span class="accordion-item_icon-hide" aria-hidden="true">&#45;</span>
        </button>
      </div>
      <div id="<?php echo esc_attr($id); ?>collapse<?php echo esc_attr($key); ?>" class="collapse <?php echo ($key === 0) ? 'show' : ''; ?>" aria-labelledby="<?php echo esc_attr($id); ?>heading<?php echo esc_attr($key); ?>" data-parent="#<?php echo esc_attr($id); ?>">
        <div class="accordion-item_body text-dark-gray pt-2">
          <?php echo $item['content']; ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
