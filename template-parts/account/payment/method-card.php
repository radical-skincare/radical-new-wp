
<?php
$method_id = $method['method']['last4'] . '_' . str_replace('/', '-', $method['expires']);
?>
<div id="method_<?php echo esc_html($method_id); ?>" class="card card-method mb-3 w-100">
  <div class="card-body d-flex justify-content-center">
    <div class="row align-items-center">
      <div class="col-2 pr-0">
        <img src="<?php echo get_template_directory_uri() . '/assets/images/cards/' . str_replace(' ', '-', strtolower($method['method']['brand'])) . '.svg'; ?>" alt="<?php echo esc_html($method['method']['brand']); ?>" class="w-100"/>
      </div>
      <div class="card-method_details <?php echo (count($method['actions']) > 1 || $method['actions'][0]['name'] == 'Delete') ? 'col-5' : 'col-auto'; ?>">
        <?php if ($payment_methods_names) : ?>
          <?php if ($method_name = radical_get_payment_method_name_by_id($method_id, $current_user_id, $payment_methods_names)) : ?>
            <h3 class="method-name mb-1"><?php echo $method_name; ?></h3>
          <?php endif; ?>
        <?php endif; ?>
        <?php if (!empty( $method['method']['last4'])) : ?>
          <p class="mb-1"><strong><?php echo esc_html($method['method']['brand']); ?> ****</strong> <?php echo esc_html($method['method']['last4']); ?></p>
        <?php else : ?>
          <?php echo esc_html( wc_get_credit_card_type_label( $method['method']['brand'] ) ); ?>
        <?php endif; ?>
        <?php if (!empty($method['expires'])) : ?>
          <p class="mb-0">Expires <?php echo esc_html($method['expires']); ?></p>
        <?php endif; ?>
      </div>
      <?php if (count($method['actions']) > 1 || (isset($method['actions'][0]) && $method['actions'][0]['name'] == 'Delete')) : ?>
        <div class="col-5 d-flex justify-content-end pl-0" style="column-gap: 0.5rem;">
          <?php foreach (array_reverse($method['actions']) as $action) : ?>
            <?php
            $classes = ($action['name'] == 'Delete') ? 'link-underline link-underline_danger btn-action-delete' : 'link-underline link-underline_darker-gray';
            ?>
            <a href="<?php echo $action['url']; ?>" class="<?php echo $classes; ?>">
              <?php echo esc_html($action['name']); ?>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <button type="button" id="btn-payment-method_edit<?php echo esc_html($method_id); ?>" class="btn-payment-method_edit" data-method-id="<?php echo esc_html($method_id); ?>" data-toggle="modal" data-target="#paymentMethodEditNameModal">
    <span class="sr-only">Edit Name</span>
    <i class="fa fa-pencil" aria-hidden="true"></i>
  </button>
</div>
