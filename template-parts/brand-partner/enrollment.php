<?php
$site_url = get_site_url();
$is_user_logged_in = is_user_logged_in();
$user_id = get_current_user_id();
$affiliate_status = $is_user_logged_in ? get_user_meta($user_id, 'v_affiliate_status', true) : false;

function brand_partner_get_affiliate_id_by_user_id($user_id) {
  return trim(get_user_meta($user_id, 'v_affiliate_id', true));
}

function brand_partner_is_step_1_completed($affiliate_status) {
  if ($affiliate_status === 'pending_approval' || $affiliate_status === 'active') {
    return 'completed complete';
  }
  return 'disabled';
}

function brand_partner_is_step_2_completed() {
  $user_id = get_current_user_id();
  if (get_user_meta($user_id, 'v_referrer_id', true)) {
    return 'completed complete';
  }
  if (get_user_meta($user_id, 'v_enrollment_no_referrer_chosen', true)) {
    return 'completed complete';
  }
  return 'disabled';
}
$current_user = $is_user_logged_in ? wp_get_current_user() : false;
?>
<?php if ($is_user_logged_in) : ?>
  <a href="<?php echo esc_url($site_url); ?>">
    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo/radicalskincare-logo.png'); ?>" class="radical-logo d-block mx-auto mb-5"/>
  </a>
	<section class="container">
		<div class="row mb-5">
			<div class="col text-center">
				<h1 class="enrollment-intro-text text-primary">
					Hello <span class="enrollment-first-name"><?php echo esc_html($current_user->first_name); ?></span> <span class="enrollment-last-name"><?php echo esc_html($current_user->last_name); ?></span>
				</h1>
				<?php if ($affiliate_status !== 'inactive') : ?>
					<h2 class="d-block h2-responsive text-primary mb-0">Continue your Brand Partner Enrollment</h2>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php endif; ?>
<section class="section-ambassador-enrollment container-fluid mb-5">
	<div class="container">
		<?php if (!$is_user_logged_in) : ?>
			<?php get_template_part('template-parts/brand-partner/register-login'); ?>
		<?php else : ?>
			<?php if ($affiliate_status === 'inactive') : ?>
				<?php if ($reactivation_form_shortcode = get_field('request_brand_partner_reactivation_form_shortcode', 'option')) : ?>
					<div class="row justify-content-center">
						<div class="col-lg-8">
							<?php echo do_shortcode($reactivation_form_shortcode); ?>
						</div>
					</div>
				<?php endif; ?>
			<?php else : ?>
				<?php
				global $general_settings;
				$general_settings = json_decode(get_option('brand_partner_setings'));
				$step_1_classes = brand_partner_is_step_1_completed($affiliate_status);
				$step_2_classes = brand_partner_is_step_2_completed();
				$step_3_classes = 'disabled';
				$step_4_classes = 'disabled';
				$step_5_classes = 'disabled';
				$next_step = '2';
				// if step 1 is not completed
				if ($step_1_classes !== 'completed complete') {
				    // then step 1 is active
				    $step_1_classes = 'active';
				} else {
				    // if step_2 is not completed
				    if ($step_2_classes !== 'completed complete') {
				        // then step 2 is active
				        $step_2_classes = 'active';
				        $next_step = '3';
				    } else {
				        // steps 1 - 2 completed
				        // then show specials/activation/checkout
				        $step_3_classes = 'active';
				        $next_step = '4';
				    }
				}
				?>
				<div class="row">
					<div class="col">
						<ul class="nav nav-tabs nav-justified md-tabs p-0" id="ambassadorEnrollmentTabs" role="tablist">
							<li class="nav-item">
								<a class="nav-link <?php echo esc_attr($step_1_classes); ?>" id="account-tab" data-toggle="tab" href="#account" role="tab" aria-controls="account" aria-selected="true">
									<span class="step-completed badge-pill mr-2"><i class="fa fa-check text-success"></i></span>
									<span class="step-number d-md-none">1</span>
									<span class="d-none d-md-inline-block step-name">Account</span>
								</a>
							</li>
							<li class="nav-item <?php echo $next_step === '2' ? 'next' : ''; ?>">
								<a class="nav-link <?php echo esc_attr($step_2_classes); ?>" id="coach-tab" data-toggle="tab" href="#coach" role="tab" aria-controls="coach" aria-selected="false">
									<span class="step-completed badge-pill mr-2"><i class="fa fa-check text-success"></i></span>
									<span class="step-number d-md-none">2</span>
									<span class="d-none d-md-inline-block step-name">Referred By</span>
								</a>
							</li>
							<li class="nav-item <?php echo $next_step === '3' ? 'next' : ''; ?>">
							<a class="nav-link <?php echo esc_attr($step_3_classes); ?>" id="special-tab" data-toggle="tab" href="#specials" role="tab" aria-controls="specials" aria-selected="false">
								<span class="step-completed badge-pill mr-2"><i class="fa fa-check text-success"></i></span>
								<span class="step-number d-md-none">3</span>
								<span class="d-none d-md-inline-block step-name">Specials</span>
							</a>
						</li>
						<li class="nav-item <?php echo $next_step === '4' ? 'next' : ''; ?>">
							<a class="nav-link <?php echo esc_attr($step_4_classes); ?>" id="activation-tab" data-toggle="tab" href="#activation" role="tab" aria-controls="activation" aria-selected="false">
								<span class="step-completed badge-pill mr-2"><i class="fa fa-check text-success"></i></span>
								<span class="step-number d-md-none">4</span>
								<span class="d-none d-md-inline-block step-name">Activation</span>
							</a>
						</li>
						<li class="nav-item <?php echo $next_step === '5' ? 'next' : ''; ?>">
							<a class="nav-link <?php echo esc_attr($step_5_classes); ?>" id="checkout-tab" data-toggle="tab" href="#checkout" role="tab" aria-controls="checkout" aria-selected="false">
								<span class="step-number d-md-none">5</span>
									<span class="d-none d-md-inline-block step-name">
										<span class="badge-pill mr-2">
											<i class="fa fa-lock" aria-hidden="true"></i>
											<span class="sr-only">Secure</span>
										</span>
										Checkout
									</span>
								</a>
							</li>
						</ul>
						<div id="ambassadorEnrollmentTabContentJust" class="tab-content card pt-5 my-3">
							<div class="tab-pane fade <?php echo strpos($step_1_classes, 'active') !== false ? 'show active' : ''; ?>" id="account" role="tabpanel" aria-labelledby="account-tab">
								<?php get_template_part('template-parts/brand-partner/enrollment/account'); ?>
							</div>
							<div class="tab-pane fade <?php echo strpos($step_2_classes, 'active') !== false ? 'show active' : ''; ?> pb-5" id="coach" role="tabpanel" aria-labelledby="coach-tab">
								<?php get_template_part('template-parts/brand-partner/enrollment/referred-by'); ?>
							</div>
						<div class="tab-pane fade <?php echo strpos($step_3_classes, 'active') !== false ? 'show active' : ''; ?>" id="specials" role="tabpanel" aria-labelledby="special-tab">
							<?php get_template_part('template-parts/brand-partner/enrollment/specials'); ?>
						</div>
						<div class="tab-pane fade<?php echo strpos($step_4_classes, 'active') !== false ? 'show active' : ''; ?>" id="activation" role="tabpanel" aria-labelledby="activation-tab">
							<?php
								$current_user_id = get_current_user_id();
								$auto_activation_progress = json_decode(get_user_meta($current_user_id, 'auto_activation_progress', true), true);
								$max_activation_steps = 4;
								$_max_activation_steps = 0;
								if ($auto_activation_progress) {
									for ($i = 0; $i < count($auto_activation_progress); $i++) {
										if ($auto_activation_progress[$i]['step'] > $_max_activation_steps && $auto_activation_progress[$i]['success']) {
											$_max_activation_steps = $auto_activation_progress[$i]['step'];
										}
									}
								}
								$is_activation_complete = (($max_activation_steps === $_max_activation_steps) ? true : false);
								$affiliate_obj = get_option('gigfiliate_integration_settings');
							?>
              <div class="p-3">
                <?php do_action('gigfiliate_render_auto_activation_sequence_theme', $current_user_id, $auto_activation_progress, $is_activation_complete, $general_settings); ?>
              </div>
						</div>
						<div class="tab-pane fade<?php echo strpos($step_5_classes, 'active') !== false ? 'show active' : ''; ?>" id="checkout" role="tabpanel" aria-labelledby="checkout-tab">
								<div class="row mb-5">
									<div class="col text-center">
										<h3 class="section-title grey-text mb-5">Enrollment Complete</h3>
										<a href="<?php echo esc_url($site_url); ?>/checkout/" class="btn btn-darkergray" title="Checkout">Go To Checkout</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</section>
<?php if ($is_user_logged_in) : ?>
	<div id="collections-progress" class="kits-progress-wrap position-fixed fixed-bottom" style="display: none;">
		<div class="kits-progress-close-msg position-absolute" style="display: none; top: 1rem; right: 1rem;">
			<i class="fa fa-close" aria-hidden="true"></i>
			<span class="sr-only">Close</span>
		</div>
		<div class="container-fluid">
			<div class="row">
				<div class="container p-3">
					<div class="row">
						<div class="d-flex col align-items-center">
							<p class="kits-instruction mb-0">Add <strong>Brand Partner Welcome Collection</strong> to your basket.</p>
						</div>
						<div class="col-auto">
							<div class="starter-kit-added-progress-wrap" style="display: none;">
								<button type="button" class="btn btn-darkergray my-0 mx-auto float-right" next_step="enrollment_collections" disabled>Add Another Starter Collection</button>
								<sup>or</sup>
							</div>
							<button type="button" class="btn btn-darkergray btn-kits-next-step my-0 mx-auto float-right" next_step="enrollment_collections" disabled>Continue To Next Step <i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="areYouSureModal" tabindex="-1" role="dialog" aria-labelledby="areYouSureModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-body">
	        <div class="container" style="position: relative;">
	          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 0; z-index: 900;">
	            <span aria-hidden="true">&times;</span>
	          </button>
	          <h3 class="grey-text">Are You Sure?</h3>
	          <p id="areYouSureModalLabel">Are you sure you want to continue to specials? <strong style="font-weight: bold;">Enrollment Collections are only available during Enrollment!</strong></p>
	          <div class="row">
	            <div class="col-lg text-center">
	              <a id="are-you-sure-btn-dismiss" class="link-underline link-underline_darker-gray mr-3" href="javascript:void(0);" title="Add An Enrollment Collection" data-dismiss="modal">No, I Want to Add An Enrollment Collection</a>
	              <a id="are-you-sure-btn-next" class="btn btn-darkergray next_slide_button" go-to-tab="specials" href="javascript:void(0);" title="Continue To Specials">Yes, Take Me To Specials</a>
	            </div>
	          </div>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>
<?php endif; ?>
