<?php
$user_id = get_current_user_id();
$user_affiliate_id = trim(get_user_meta($user_id, 'v_affiliate_id', true));
$affiliate_status = get_user_meta($user_id, 'v_affiliate_status', true);
$is_user_done_with_account_info = ($affiliate_status === 'pending_approval') ? true : false;
?>
<div class="part-affiliate-title-row row mb-5">
  <div class="col text-center">
    <?php if (!$is_user_done_with_account_info) : ?>
      <h3 class="section-title mb-0">Set Your Account Info</h3>
    <?php else : ?>
      <h3 class="section-title mb-0">Account Info Set</h3>
    <?php endif; ?>
  </div>
</div>
<div class="row justify-content-center mb-5">
	<div class="col col-md-10 col-lg-8 col-xl-6">
		<div id="enrollment-account-info" class="card card-affiliate" <?php echo $is_user_done_with_account_info ? 'style="display: none;"' : ''; ?>>
			<h5 class="card-header white-text text-center py-4">Account Info</h5>
			<div class="card-body px-lg-5 pt-4">
				<form id="add-affiliate" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="POST">
					<div class="form-outline mb-3">
            <label for="phone" class="mb-0">Phone</label>
						<input type="tel" id="phone" name="phone" class="form-control mb-0" value="<?php echo esc_attr(get_user_meta($user_id, 'v_phone', true)); ?>" required/>
					</div>
					<div class="form-outline mb-3">
            <label for="address_line_1" class="mb-0">Address</label>
						<input type="text" id="address_line_1" name="address_line_1" class="form-control mb-0" value="<?php echo esc_attr(get_user_meta($user_id, 'v_address_line_1', true)); ?>" required/>
					</div>
					<div class="form-outline mb-3">
            <label for="address_line_2" class="mb-0">Address Line 2</label>
						<input type="text" id="address_line_2" name="address_line_2" class="form-control mb-0" value="<?php echo esc_attr(get_user_meta($user_id, 'v_address_line_2', true)); ?>"/>
					</div>
					<div class="form-outline mb-3">
            <label for="city" class="mb-0">City</label>
						<input type="text" id="city" name="city" class="form-control mb-0"  value="<?php echo esc_attr(get_user_meta($user_id, 'v_city', true)); ?>" required/>
					</div>
					<div id="account-state-select-wrap" class="form-group mb-3">
						<label for="state">State</label>
						<select id="state" name="state" class="form-control" searchable="Search here.." required>
							<option disabled selected>Choose your option</option>
							<?php
							$selected_state = get_user_meta($user_id, 'v_state', true);
							$selected_state = ($selected_state) ? $selected_state : '';
							$states = radical_get_states();
							?>
							<?php foreach ($states as $key => $state) : ?>
								<option value="<?php echo esc_attr($key); ?>" <?php echo ($selected_state === $key) ? 'selected' : ''; ?>><?php echo esc_html($state); ?></option>
							<?php endforeach; ?>
						</select>
						<small class="description d-none text-danger" style="margin-top: -1rem;">State is required.</small>
					</div>
					<div class="form-outline mb-3">
            <label for="zip_code" class="mb-0">Zip Code</label>
						<input type="text" id="zip_code" name="zip_code" class="form-control" value="<?php echo esc_attr(get_user_meta($user_id, 'v_zip', true)); ?>" required/>
					</div>
					<div class="form-outline mb-3">
            <label for="paypal_email" class="mb-0">PayPal Email Address (Optional)</label>
						<input type="email" id="paypal_email" name="paypal_email" class="form-control" value="<?php echo esc_attr(get_user_meta($user_id, 'v_paypal_email', true)); ?>"/>
						<small class="description">For payouts via PayPal.</small>
					</div>
					<input type="hidden" name="action" value="process_new_affiliate"/>
					<?php wp_nonce_field('radical_ajax_nonce', 'nonce', true, true); ?>
					<button class="btn btn-darkergray" type="submit">Update Account Info</button>
				</form>
			</div>
		</div>
		<div class="affiliate-step-complete-checkmark-wrap text-center" style="display: none;">
			<div class="circle-loader">
			  <div class="checkmark draw"></div>
			</div>
		</div>
		<div class="text-center">
			<button id="edit-account-info" class="link-underline link-underline_darker-gray" <?php echo (!$is_user_done_with_account_info) ? 'style="display: none;"' : ''; ?>>Edit Account Info</button>
		</div>
	</div>
</div>
