<?php
/*
 * This file creates the page:
 * /options-general.php?page=radical-rx&tab=general
 *
*/

$success_msg = "";

if ( isset( $_POST['action'] ) && $_POST['action'] === 'update_general_settings' ) {
  $general_settings = array(
    "affiliate_plugin" => $_POST['affiliate_plugin'],

    "default_parent_affiliate_id" => $_POST['default_parent_affiliate_id'],
    "permanent_ambassador_user_ids" => $_POST['permanent_ambassador_user_ids'],

    // brand partner product ids
    "ambassador_welcome_kit_id" => $_POST['ambassador_welcome_kit_id'],
    "ambassador_basic_kit_id" => $_POST['ambassador_basic_kit_id'],
    "ambassador_best_seller_kit_id" => $_POST['ambassador_best_seller_kit_id'],
    "ambassador_ready_to_be_radical_kit_id" => $_POST['ambassador_ready_to_be_radical_kit_id'],

    // Enrollment announcement
    "enable_continue_enrollment_announment" => isset($_POST['ambassador_welcome_kit_id']) ? true : false,
	);
  update_option( 'brand_partner_setings', json_encode( $general_settings ) );

  $success_msg = __( 'General Settings Updated.' );
}

if ( ! empty( $success_msg ) ) { ?>
  <div class="notice notice-success is-dismissible">
    <p><?php echo $success_msg; ?></p>
  </div>
<?php } ?>

<form method="post" action="<?php echo get_site_url(); ?>/wp-admin/options-general.php?page=brand-partner" > 

	<?php $general_settings = json_decode( get_option( 'brand_partner_setings' ) ); ?>

	<h3>Affiliate Software Settings</h3>

	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="default_parent_affiliate_id" >Which Affiliate WordPress Plugin?</label></th>
				<td>
					<div>
						<input type="radio" id="affiliate-wp" name="affiliate_plugin" value="affiliate-wp" <?php echo ($general_settings->affiliate_plugin === 'affiliate-wp') ? 'checked' : ''; ?> required/>
						<label for="affiliate-wp">Affiliate WP</label><br>
						<input type="radio" id="gigfiliate" name="affiliate_plugin" value="gigfiliate" <?php echo ($general_settings->affiliate_plugin === 'gigfiliate') ? 'checked' : ''; ?> required/>
						<label for="gigfiliate">Gigfiliate</label><br>
					</div>
					<p class="directions">Which affiliate wordpress plugin are we using?</p>
				</td>
			</tr>
		</tbody>
	</table>

	<h3>Coach Settings</h3>

	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="default_parent_affiliate_id" >Default Parent Affiliate ID:</label></th>
				<td>
					<input type="number" id="default_parent_affiliate_id" name="default_parent_affiliate_id" value="<?php echo $general_settings->default_parent_affiliate_id; ?>" class="small-text" />
				</td>
			</tr>
			<tr>
				<th>
					<label for="permanent_ambassador_user_ids" >Permanent Brand Partner User ID(s):</label>
					<p>Enter user IDs for Brand Partners that are permament. Meaning they do not have to purchase "Brand Partner Welcome Kit" (subscription) to have Brand Partner benefits.</p>
				</th>
				<td>
					<input type="text" id="permanent_ambassador_user_ids" name="permanent_ambassador_user_ids" value="<?php echo $general_settings->permanent_ambassador_user_ids; ?>" class="large-text" />
					<p>Separate user IDs by comma.</p>
				</td>
			</tr>
		</tbody>
	</table>

	<h3>Kits Settings</h3>

	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="ambassador_welcome_kit_id" >Brand Partner Welcome Kit ID:</label></th>
				<td>
					<input type="number" id="ambassador_welcome_kit_id" name="ambassador_welcome_kit_id" value="<?php echo $general_settings->ambassador_welcome_kit_id; ?>" class="small-text" />
				</td>
			</tr>
			<tr>
				<th><label for="ambassador_basic_kit_id" >Brand Partner Basic (Starter) Kit ID:</label></th>
				<td>
					<input type="number" id="general_text" name="ambassador_basic_kit_id" value="<?php echo $general_settings->ambassador_basic_kit_id; ?>" class="small-text" />
				</td>
			</tr>
			<tr>
				<th><label for="ambassador_best_seller_kit_id" >Brand Partner Best Seller (Starter) Kit ID:</label></th>
				<td>
					<input type="number" id="ambassador_best_seller_kit_id" name="ambassador_best_seller_kit_id" value="<?php echo $general_settings->ambassador_best_seller_kit_id; ?>" class="small-text" />
				</td>
			</tr>
			<tr>
				<th><label for="ambassador_ready_to_be_radical_kit_id" >Brand Partner Ready To Be Radical (Starter) Kit ID:</label></th>
				<td>
					<input type="number" id="ambassador_ready_to_be_radical_kit_id" name="ambassador_ready_to_be_radical_kit_id" value="<?php echo $general_settings->ambassador_ready_to_be_radical_kit_id; ?>" class="small-text" />
				</td>
			</tr>
		</tbody>
	</table>

	<h3>Tracking Settings</h3>

	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="enable_continue_enrollment_announment">Enable Continue Enrollment Announement:</label></th>
				<td>
					<label for="enable_continue_enrollment_announment">
						<input type="checkbox" id="enable_continue_enrollment_announment" name="enable_continue_enrollment_announment" <?php echo ($general_settings->enable_continue_enrollment_announment) ? 'checked="checked"' : ''; ?> value="1"/>
						Enable Continue Enrollment Announement
					</label>
					<p class="directions">If a visitor has started the Brand Partner enrollment, but has not yet finished. This will show an announment bar to "Continue Enrollment".</p>
				</td>
			</tr>
		</tbody>
	</table>

	<button type="submit" name="action" value="update_general_settings" class="button button-primary" >Save Changes</button>
	
</form>
