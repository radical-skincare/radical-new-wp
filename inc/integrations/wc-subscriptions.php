<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * A custom Subscription Order WooCommerce Email class
 *
 * @since 0.1
 * @extends \WC_Email
 */
class WC_Subscription_Reminder_Order_Email extends WC_Email
{
  public $skippable_products;
  /**
   * Set email defaults
   *
   * @since 0.1
   */
  public function __construct()
  {

    // set ID, this simply needs to be a unique name
    $this->id = 'wc_Subscription_reminder_order';

    // this is the title in WooCommerce Email settings
    $this->title = 'Renewal Order Reminder';

    // this is the description in WooCommerce email settings
    $this->description = 'Renewal Order Reminder emails are sent to the customer 7 days before next payment date.';

    // these are the default heading and subject lines that can be overridden using the settings
    $this->heading = 'Renewal Order Reminder';
    $this->subject = 'Renewal Order Reminder';

    // these define the locations of the templates that this email should use, we'll just use the new order template since this email is similar
    $this->template_html  = 'emails/subscribtion-reminder.php';
    $this->template_plain = 'emails/plain/admin-new-order.php';

    $this->customer_email = true;

    // Trigger
    add_action('radical_subscription_reminder_notification', array($this, 'trigger'));

    // Call parent constructor to load any other defaults not explicity defined here
    parent::__construct();

    // this sets the recipient to the settings defined below in init_form_fields()
    $this->recipient = 'Customer';
  }


  /**
   * Determine if the email should actually be sent and setup email merge variables
   *
   * @since 0.1
   * @param int $order_id
   */
  public function trigger($order_id)
  {
    // bail if no order ID is present
    if (!$order_id) {
      return;
    }
    // setup order object
    $this->object = new WC_Order($order_id);
    $next_payment = wcs_get_subscription($order_id)->get_time('next_payment');
    // bail if no next payment is present
    if (!$next_payment) {
      return;
    }
    $this->next_payment = $next_payment;
    $this->recipient = wcs_get_objects_property($this->object, 'billing_email');

    // replace variables in the subject/headings
    $this->placeholders = array_merge(
      array(
        '{order_date}' => date_i18n(wc_date_format(), strtotime($this->object->get_date_created())),
        '{order_number}' => $this->object->get_order_number(),
      ),
      $this->placeholders
    );

    if (!$this->is_enabled() || !$this->get_recipient()) {
      return;
    }
    $this->skippable_products = [];
    $skippable_items = get_post_meta($this->object->get_ID(), 'one_time_skippable_item', true);
    if ($skippable_items) {
      $skippable_items = json_decode($skippable_items, true);
    } else {
      $skippable_items = [];
    }
    $skip_total_amount = 0;
    foreach ($this->object->get_items() as $item_id => $item) {
      $is_product_skipped = in_array($item['product_id'], $skippable_items);
      if ($is_product_skipped) {
        $this->skippable_products[] = $item;
        $this->object->remove_item($item_id);
        $skip_total_amount += $item['line_total'];
      }
    }
    if ($skip_total_amount) {
      $total = $this->object->get_total();
      $new_total = $total - $skip_total_amount;
      if ($new_total < 0) {
        $new_total = $skip_total_amount - $total;
      }
      $this->object->set_total($new_total);
    }

    if (count($this->skippable_products) > 0) {
      $total_discount = $this->object->get_discount_total();
      $old_subtotal = $this->object->get_subtotal();
      foreach ($this->skippable_products as $item) {
        $total_discount -= $item->get_subtotal() - $item->get_total();
        $old_subtotal -= $item->get_total();
      }
      $this->object->set_discount_total($total_discount);
    }

    $this->send($this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments());
  }

  /**
   * get_content_html function.
   *
   * @since 0.1
   * @return string
   */
  public function get_content_html()
  {
    ob_start();
    wc_get_template($this->template_html, array(
      'order' => $this->object,
      'email_heading' => $this->get_heading(),
      'sent_to_admin' => false,
      'plain_text' => false,
      'email' => 'email',
      'additional_content' => '',
      'next_payment' => $this->next_payment,
      'skippable_products' => $this->skippable_products
    ));
    return ob_get_clean();
  }

  /**
   * get_content_plain function.
   *
   * @since 0.1
   * @return string
   */
  public function get_content_plain()
  {
    ob_start();
    wc_get_template($this->template_plain, array(
      'order' => $this->object,
      'email_heading' => $this->get_heading(),
      'sent_to_admin' => false,
      'plain_text' => true,
      'email' => 'email',
      'additional_content' => '',
      'next_payment' => $this->next_payment
    ));
    return ob_get_clean();
  }


  /**
   * Initialize Settings Form Fields
   *
   * @since 2.0
   */
  public function init_form_fields()
  {
    $this->form_fields = array(
      'enabled'    => array(
        'title'   => 'Enable/Disable',
        'type'    => 'checkbox',
        'label'   => 'Enable this email notification',
        'default' => 'yes'
      ),
      'subject'    => array(
        'title'       => 'Subject',
        'type'        => 'text',
        'description' => sprintf('This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', $this->subject),
        'placeholder' => '',
        'default'     => 'Renewal Order Reminder'
      ),
      'heading'    => array(
        'title'       => 'Email Heading',
        'type'        => 'text',
        'description' => sprintf(__('This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.'), $this->heading),
        'placeholder' => '',
        'default'     => 'Renewal Order Reminder'
      ),
      'email_type' => array(
        'title'       => 'Email type',
        'type'        => 'select',
        'description' => 'Choose which format of email to send.',
        'default'     => 'html',
        'class'       => 'email_type',
        'options'     => array(
          'plain'      => __('Plain text', 'woocommerce'),
          'html'       => __('HTML', 'woocommerce'),
          'multipart' => __('Multipart', 'woocommerce'),
        )
      )
    );
  }
} // end \WC_Subscription_Reminder_Order_Email class
