<?php
$site_url = get_site_url();
?>
<style>
.woo-sub-vars-radio-wrap {
  display: none;
}
</style>
<div class="row">
  <div class="col">
    <h3 class="d-block h1-responsive pink-text mb-2">VIP Active Subscriber Exclusive</h3>
    <div class="alert alert-info">Currently, only logged in VIP Active Subscribers can buy this product. A VIP Active Subscriber is a customer who has a Radial Refill active subscription. You must be logged into your customer account to verify that you have an active subscription.</div>
    <a href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>" class="d-inline-block link-underline link-underline_darker-gray mr-3">Return To Shop</a>
  </div>
</div>
