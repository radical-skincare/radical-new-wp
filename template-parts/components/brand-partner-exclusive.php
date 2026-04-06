<?php
$site_url = get_site_url();
?>
<div class="row">
  <div class="col">
    <h2 class="d-block h1-responsive pink-text mb-2">Brand Partner Exclusive</h2>
    <p>Sorry only Brand Partners can buy this product.</p>
    <a href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>" class="d-inline-block link-underline link-underline_darker-gray mr-3">Return To Shop</a>
    <a href="<?php echo esc_html($site_url); ?>/brand-partner/enrollment/" class="d-inline-block btn btn-darkergray">Become A Brand Partner</a>
  </div>
</div>
