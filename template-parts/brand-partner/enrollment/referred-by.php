<?php
$site_url = get_site_url();
$general_settings = json_decode(get_option('brand_partner_setings'));
$default_parent_affiliate_id = (!empty($general_settings->default_parent_affiliate_id)) ? $general_settings->default_parent_affiliate_id : null;
$user_id = get_current_user_id();

$affiliate_parent_affiliate_id = (int) get_user_meta($user_id, 'v_referrer_id', true);
global $wpdb;
$table = $wpdb->prefix . 'usermeta';
$sql = "SELECT user_id FROM $table WHERE meta_key = 'v_affiliate_id' AND meta_value = $affiliate_parent_affiliate_id";
$affiliate_parent_id = (int) $wpdb->get_var($sql);
$affiliate_parent = get_userdata($affiliate_parent_id);

$no_referrer_chosen = get_user_meta($user_id, 'v_enrollment_no_referrer_chosen', true);
?>
<div class="row justify-content-center coach-step-complete-row" style="display: none;" >
	<div class="col text-center">
		<h3 class="stored-coach-wrap section-title grey-text mb-5">
			<?php if ($no_referrer_chosen) : ?>
				A Brand Partner Referrer Was Purposely Not Chosen
			<?php else : ?>
				Referred By: <span class="stored-coach-name"><?php echo $affiliate_parent ? esc_html($affiliate_parent->display_name) : ''; ?></span>
			<?php endif; ?>
		</h3>
		<div class="coach-step-complete-checkmark-wrap">
			<div class="circle-loader">
			  <div class="checkmark draw"></div>
			</div>
		</div>
		<div class="text-center">
			<button id="edit-referred-by" class="link-underline link-underline_darker-gray" <?php echo $no_referrer_chosen ? 'style="display: none;"' : ''; ?>>Edit Referred By</button>
		</div>
	</div>
</div>
<div id="search-referred-by-heading" class="part-coach-title-row row mb-5">
  <div class="col text-center">
    <h3 class="section-title grey-text">Choose Which Brand Partner Referred You</h3>
  </div>
</div>
<div id="search-referred-by" class="find-coach-row row mb-5 justify-content-center">
  <div class="mb-3 mb-lg-0 col-lg-4">
    <div id="selected-coach-avatar" class="rounded-circle z-depth-1"
     style="background-image: url('<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo/radicalskincare-logo.svg'); ?>');">
      <span class="sr-only">Selected Referring Brand Partner</span>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="find-coach-inner-wrap">
      <form id="find-coach">
        <div class="row">
          <div class="col">
            <h4>Search</h4>
          </div>
        </div>
        <div class="row">
          <div class="col-md-5">
            <div class="md-form mt-4">
              <label for="full_name" class="mb-0">Full Name</label>
              <input type="text" id="full_name" name="full_name" class="form-control" required>
            </div>
          </div>
          <div class="col-md-2 pt-4 text-center">
            <span class="text-uppercase font-weight-bold">Or</span>
          </div>
          <div class="col-md-5">
            <div class="md-form mt-4">
              <label for="city_state_zip" class="mb-0">City, State, or Zip Code</label>
              <input type="text" id="city_state_zip" name="city_state_zip" class="form-control" required >
            </div>
          </div>
        </div>
      </form>
      <div class="row coaches-listing-row mt-3" style="display: none;">
        <div class="col">
          <ul class="coaches-listing max-height-200-overflow-y-scroll"></ul>
        </div>
      </div>
      <div class="row coaches-filter-result-row" style="display: none;">
        <div class="col">
          <div class="alert alert-danger alert-coaches-filter-result mt-3 mb-0">
            <span class="fa fa-exclamation-triangle"></span> There are no results that match your search. Please try again.
          </div>
        </div>
      </div>
    </div>
    <div class="coach-selected-wrap" style="display: none;" >
      <h3 class="selected-coach-name text-capitalize"></h3>
      <p><span class="selected-coach-city"></span>, <span class="selected-coach-state"></span></p>
      <form id="set-coach" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="POST" class="d-inline-block" >
        <input id="affiliate_parent_id" type="hidden" name="affiliate_parent_id" value="<?php echo esc_attr($default_parent_affiliate_id); ?>"/>
        <?php if (isset($general_settings->affiliate_plugin) && $general_settings->affiliate_plugin === 'affiliate-wp') : ?>
          <input type="hidden" name="action" value="process_set_mlm_connections"/>
        <?php else : ?>
          <input type="hidden" name="action" value="process_set_affiliate_referrer"/>
        <?php endif; ?>
        <?php wp_nonce_field('radical_ajax_nonce', 'nonce', true, true); ?>
        <button type="submit" class="d-inline-block btn btn-darkergray btn-select-coach mr-2 ml-0 text-uppercase">Select</button>
      </form>
      <a href="javascript:void(0)" class="d-inline-block btn btn-darkergray btn-un-select-coach mr-3 text-uppercase">X</a>
    </div>
  </div>
</div>
<div id="skip-referred-by" class="switch-coach-row row justify-content-center grey-color" <?php echo ($affiliate_parent && !$no_referrer_chosen) ? 'style="display:none"' : ''; ?>>
  <div class="col-lg p-5 text-center">
    <p class="mb-0">Or I was not referred by a Brand Partner and wish to <a href="javascript:void(0)" id="btn-skip-referred-by" class="switch-coach link-underline link-underline_darker-gray">skip this step.</a></p>
  </div>
</div>
<div class="modal fade" id="skipReferredByConfirmModal" tabindex="-1" role="dialog" aria-labelledby="skipReferredByConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
				<div class="container px-0" style="position: relative;">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h3 class="grey-text">Are You Sure?</h3>
					<p>Are you sure you were not referred by a Brand Partner and want to skip this step?</p>
					<div class="row">
						<div class="col-lg text-center">
							<button type="button" class="link-underline link-underline_darker-gray mr-3" data-dismiss="modal">Cancel</button>
							<a id="skipReferredByConfirmModalButton" class="btn btn-dark" href="javascript:void(0);" title="Continue To Collections" data-dismiss="modal">Yes, I Am Sure</a>
						</div>
					</div>
				</div>
			</div>
    </div>
  </div>
</div>
