<section class="container py-5">
  <div class="row justify-content-center mb-5 overflow-hidden">
    <div class="col-auto">
      <div class="text-separator" style="width: 330px;">
        <div class="text-separator_line"></div>
        <h2 class="text-separator_inner-text fs-1x text-uppercase">Radical On Repeat FAQ's</h2>
        <div class="text-separator_line"></div>
      </div>
    </div>
  </div>
  <?php
  $items = [
    [
      'title' => ' How do I join the Radical on Repeat program? ',
      'content' => 'Joining Radical on Repeat is so simple. Simply hover over the Add to Cart button on the product page and select \'Refill 10% off\'.'
    ],
    [
      'title' => ' How do I view my future Radical on Repeat orders? ',
      'content' => 'Your next scheduled order will be listed in the \'My Orders\' section of your account and view future orders.'
    ],
    [
      'title' => ' Can I pause or cancel subscription? ',
      'content' => 'Absolutely! Simply log in to your account, click on Manage My Account, and adjust your settings as desired.'
    ],
    [
      'title' => ' Am I locked into Radical on Repeat for a certain length of time? ',
      'content' => 'Absolutely not! You have the Radical freedom to pause or cancel your Radical on Repeat subscription at any time.'
    ],
    [
      'title' => ' What are the order frequency options? ',
      'content' => 'Radical on Repeat orders ship every month or every two months.'
    ],
    [
      'title' => ' What are the benefits of shopping with a Brand Partner coupon code? ',
      'content' => 'Customers shopping with a Brand Partner receive a 10% savings on Radical on Repeat orders for a total of 20% savings in total.'
    ],
    [
      'title' => ' Can I add additional products to my existing Radical on Repeat order? ',
      'content' => 'Yes! To do so, you must be logged into your account, go to the product you desire, and add to your cart. When you click this button, you will have the option to add the item as one time only or as a Radical on Repeat item.'
    ],
    [
      'title' => ' How do I speak with a Radical team member with any questions? ',
      'content' => 'We love hearing from our Sister\'s on a Mission, send us a note at customercare@radicalskincare.com.'
    ],
    [
      'title' => ' Can different products have different subscription preferences? ',
      'content' => 'Absolutely! In order to have two different product shipping frequencies (some items shipped monthly, others every two months), you will need to have two subscriptions: one for monthly orders and one for every two months.'
    ],
    [
      'title' => ' Radical on Repeat Terms + Conditions ',
      'content' => 'Radical on Repeat items will ship automatically until you cancel. By placing this order, you are authorizing Radical Skincare to charge your credit card for future orders at the frequency and quantity you have selected in your account. The actual cost of each shipment may vary depending on the quantity and frequency chosen. Remember: all orders over <span class="woocommerce-Price-currencySymbol">'. get_woocommerce_currency_symbol() .'</span>85 include free shipping! Yay! For any customer service questions, you can email us at <a href="mailto:customercare@radicalskincare.com" target="_blank" rel="noopener">customercare@radicalskincare.com</a>.'
    ],
  ];
  get_template_part('template-parts/modules/flex/accordion-list', null, [
    'id' => 'radical-on-repeat-faq',
    'items' => $items,
  ]);
  ?>
</section>
