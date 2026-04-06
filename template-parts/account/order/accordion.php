<?php
$subscriptions = wcs_get_subscriptions_for_order( $order, array( 'order_type' => 'any' ) );
$downloads = $order->get_downloadable_items();
$show_downloads = $order->has_downloadable_item() && $order->is_download_permitted();
?>
<div class="accordion" id="orderDetailsAccordion">
  <?php if ($subscriptions) : ?>
    <div class="accordion-item mb-3">
      <div class="accordion-item_header" id="orderRelatedSubscriptionsCollapseHeading" role="tab">
        <button aria-controls="orderRelatedSubscriptionsCollapse" aria-expanded="true" class="btn accordion-item_btn lh-base text-left mb-2" data-target="#orderRelatedSubscriptionsCollapse" data-toggle="collapse">
          <div class="accordion-item_title">
            <h2 class="title title-d mb-0"><?php echo esc_html_e( 'Related Subscription', 'woocommerce-subscriptions' ); ?></h2>
          </div>
          <span aria-hidden="true" class="accordion-item_icon-show">+</span> <span aria-hidden="true" class="accordion-item_icon-hide">-</span>
        </button>
      </div>
      <div aria-labelledby="orderRelatedSubscriptionsCollapseHeading" class="collapse show" data-parent="#orderDetailsAccordion" id="orderRelatedSubscriptionsCollapse">
        <div class="accordion-item_body text-dark-gray p-3">
          <?php get_template_part('template-parts/account/order/related-subscriptions', null, ['subscriptions' => $subscriptions]); ?>
        </div>
      </div>
    </div>
  <?php endif; ?>
  <?php if ( $show_downloads ) : ?>
    <div class="accordion-item mb-3">
      <div class="accordion-item_header" id="orderDownloadsCollapseHeading" role="tab">
        <button aria-controls="orderDownloadsCollapse" aria-expanded="true" class="btn accordion-item_btn lh-base text-left mb-2" data-target="#orderDownloadsCollapse" data-toggle="collapse">
          <div class="accordion-item_title">
            <h2 class="title title-d mb-0"><?php echo esc_html_e( 'Downloads', 'woocommerce-subscriptions' ); ?></h2>
          </div>
          <span aria-hidden="true" class="accordion-item_icon-show">+</span> <span aria-hidden="true" class="accordion-item_icon-hide">-</span>
        </button>
      </div>
      <div aria-labelledby="orderDownloadsCollapseHeading" class="collapse show" data-parent="#orderDetailsAccordion" id="orderDownloadsCollapse">
        <div class="accordion-item_body text-dark-gray p-3">
          <?php
          wc_get_template(
            'order/order-downloads.php',
            array(
              'downloads'  => $downloads,
              'show_title' => false,
            )
          );
          ?>
        </div>
      </div>
    </div>
  <?php endif; ?>
  <?php if ( $notes = $order->get_customer_order_notes() ) : ?>
    <div class="accordion-item mb-3">
      <div class="accordion-item_header" id="orderNotesHeading" role="tab">
        <button aria-controls="orderNotesCollapse" aria-expanded="true" class="btn accordion-item_btn lh-base text-left mb-2 collapsed" data-target="#orderNotesCollapse" data-toggle="collapse">
          <div class="accordion-item_title">
            <h2 class="title title-d mb-0"><?php echo esc_html_e( 'Order Updates', 'woocommerce-subscriptions' ); ?></h2>
          </div>
          <span aria-hidden="true" class="accordion-item_icon-show">+</span> <span aria-hidden="true" class="accordion-item_icon-hide">-</span>
        </button>
      </div>
      <div aria-labelledby="orderNotesHeading" class="collapse" data-parent="#orderDetailsAccordion" id="orderNotesCollapse">
        <div class="accordion-item_body text-dark-gray p-3">
          <div class="list-group">
            <?php foreach ( $notes as $note ) : ?>
              <div class="list-group-item">
                <h5 class="mb-1"><?php echo esc_html(date_i18n( _x( 'l jS \o\f F Y, h:ia', 'date on order updates list. Will be localized', 'woocommerce-subscriptions' ), wcs_date_to_time( $note->comment_date ) )); ?></h5>
                <p class="mb-0"><?php echo $note->comment_content; ?></p>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>
