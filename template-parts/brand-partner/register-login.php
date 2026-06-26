<?php
$page_id = get_the_ID();
$form = isset($_GET['form']) ? $_GET['form'] : 'register';
$site_url = get_site_url();
?>
<div id="brand-partner-register-login" class="row justify-content-center">
	<div class="col col-md-10 col-lg-8 mt-5">
    <div id="register-login-form-loader" class="form-loader text-center" style="display: none;">
      <div class="loader"></div>
    </div>
		<div class="form-register-alert alert alert-danger mb-3" style="position: relative; display: none;"></div>
    <div class="card">
      <div class="card-body">
        <ul class="nav nav-tabs nav-justified nav-tabs-underline tab-header" role="tablist">
          <li class="nav-item">
            <a class="nav-link <?php echo ($form === 'register') ? 'active' : ''; ?>" data-toggle="tab" href="#register-tab">Register</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo ($form === 'login') ? 'active' : ''; ?>" data-toggle="tab" href="#login-tab">Login</a>
          </li>
        </ul>
        <div class="tab-content py-3">
          <div id="register-tab" class="tab-pane fade <?php echo ($form === 'register') ? 'active show' : ''; ?>">
            <form id="register" method="POST" class="mb-3" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
              <div class="form-row row mb-3">
                <div class="col">
                  <div class="form-outline">
                    <label for="first_name">First name</label>
                    <input type="text" id="first_name" name="first_name" class="form-control" required/>
                  </div>
                </div>
                <div class="col">
                  <div class="form-outline">
                    <label for="last_name">Last name</label>
                    <input type="text" id="last_name" name="last_name" class="form-control" required/>
                  </div>
                </div>
              </div>
              <div class="form-outline mb-3">
                <label for="preferred_name">Preferred Name (Optional)</label>
                <input type="text" id="preferred_name" name="preferred_name" class="form-control"/>
              </div>
              <div id="register_referral-url-example_wrap" class="mb-3" style="display: none;">
                <p style="margin-bottom: 0;">
                  <span id="register_referral-url-example_uniqueness-status" class="text-danger fa fa-spin">
                    ⚆
                  </span>
                  Example Referral URL: <span id="register_referral-url-example"></span>
                </p>
                <small id="register_referral-url-example_feedback" class="text-warning" style="margin-bottom: 0;"></small>
              </div>
              <div class="form-outline mb-3">
                <label for="user_email">Email Address</label>
                <input type="email" id="user_email" name="user_email" class="form-control" required/>
                <div class="invalid-feedback"></div>
              </div>
              <div class="form-outline mb-3">
                <label for="register_password">Password</label>
                <input type="password" id="register_password" name="password" class="form-control" aria-describedby="passwordHelp" required/>
                <span toggle="#register_password" class="field-icon invisible-p toggle-password">
                  <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/eye-visibility-show.svg'); ?>" alt="Show Password" class="eye-visibility-show"/>
                  <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/eye-visibility-hide.svg'); ?>" alt="Hide Password" class="eye-visibility-hide"/>
                </span>
              </div>
              <div class="form-outline mb-3">
                <label for="register_confirm_password">Confirm Password</label>
                <input type="password" id="register_confirm_password" name="confirm_password" class="form-control" required/>
                <span toggle="#register_confirm_password" class="field-icon invisible-p toggle-password">
                  <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/eye-visibility-show.svg'); ?>" alt="Show Password" class="eye-visibility-show"/>
                  <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/eye-visibility-hide.svg'); ?>" alt="Hide Password" class="eye-visibility-hide"/>
                </span>
              </div>
              <h5>
                Independent Brand Partner Agreement
                <?php if ($terms_of_service_pdf = get_field('terms_of_service_pdf', $page_id)) : ?>
                  <a id="download-agreement" href="<?php echo esc_url($terms_of_service_pdf); ?>" title="Download" class="btn btn-transparent pt-2 py-1 px-4 m-0 float-right" target="_blank" download>
                    <span class="sr-only">Download</span><i class="fa fa-download" aria-hidden="true"></i>
                  </a>
                <?php endif; ?>
              </h5>
              <div id="brand-partner-agreement" class="mb-3 w-100">
                <?php echo get_the_content(null, false, (int) get_field('terms_of_service', $page_id)); ?>
              </div>
              <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="brand-partner-terms" name="agreement" value="1" required disabled/>
                <label class="form-check-label form-check-label-disabled" for="brand-partner-terms">I have read and agree to the terms</label>
              </div>
              <div class="alert alert-danger mt-3" role="alert">
                <p id="brand-partner-terms-description" class="form-text text-danger my-0"><span class="fa fa-exclamation-triangle"></span> Please read and scroll to the bottom of the Independent Brand Partner Agreement to continue.</p>
              </div>
              <input type="hidden" name="redirect_to" id="redirect_to" value="<?php echo esc_attr($site_url); ?>/brand-partner/enrollment/?registered=true" />
              <input type="hidden" name="action" value="radical_skincare_ajax_register_brand_partner"/>
              <button class="btn btn-darkergray my-2" type="submit">Start Enrollment</button>
            </form>
          </div>
          <div id="login-tab" class="tab-pane <?php echo ($form === 'login') ? 'active show' : ''; ?>">
            <?php
            get_template_part('template-parts/form/login', null, [
              'site_url' => $site_url,
              'action' => admin_url('admin-ajax.php'),
              'form_id' => 'enrollment_login',
              'input_prefix' => 'enrollment_login',
              'submit_btn_text' => 'Start Enrollment',
            ]);
            ?>
          </div>
        </div>
      </div>
    </div>
	</div>
</div>
<script>
(function($) {
$(document).ready(function() {
const ReferralURLExample = {
  onLoad: function() {
    if (!jQuery('#register_referral-url-example').length) {
      return
    }
    this.load()
    this.onInputChange()
  },
  load: function() {
    const first_name = jQuery('#first_name').val()
    const last_name = jQuery('#last_name').val()
    if (first_name === '' || last_name === '') {
      return
    }
    const preferred_name = jQuery('#preferred_name').val()
    const nickname = (preferred_name !== '') ? preferred_name : first_name + last_name
    jQuery('#register_referral-url-example').text(ThemeSettings.site_url + '/?ref=' + nickname.toLowerCase())
    ReferralURLExample.onCheckNicknameUniqueness(first_name, last_name, preferred_name)
  },
  onInputChange: function() {
    let delay = 500
    let timeout = null
    jQuery('#first_name, #last_name, #preferred_name').on('change input', function() {
      clearTimeout(timeout)
      timeout = setTimeout(ReferralURLExample.load, delay)
    })
  },
  onCheckNicknameUniqueness: function(first_name, last_name, preferred_name) {
    const $wrap = jQuery('#register_referral-url-example_wrap')
    if ($wrap.hasClass('checking')) {
      return
    }
    $wrap.show().addClass('checking')
    jQuery('#register_referral-url-example_is-unique').show().text('Checking uniqueness...').addClass('text-danger')
    const $uniqueness_status = jQuery('#register_referral-url-example_uniqueness-status')
    $uniqueness_status.text('⚆').removeClass('text-success').addClass('fa fa-spin text-danger')
    const $feedback = jQuery('#register_referral-url-example_feedback')
    this.checkNicknameUniqueness(first_name, last_name, preferred_name).then( function(res) {
      if (res.success) {
        jQuery('#register_referral-url-example').text(ThemeSettings.site_url + '/?ref=' + res.new_nickname_response.nickname)
        if (res.new_nickname_response.is_unique) {
          $uniqueness_status.text('☑').removeClass('fa fa-spin text-danger text-warning').addClass('text-success')
          $feedback.hide()
        } else {
          $uniqueness_status.text('☒').removeClass('fa fa-spin text-danger text-success').addClass('text-warning')
          $feedback.show().html(`Prefered name not unique so appended <strong style="font-weight: bold;">${res.new_nickname_response.increment}</strong>`)
        }
      } else {
        console.error('checkNicknameUniqueness', 'Something went wrong.')
      }
      $wrap.removeClass('checking')
    }).catch(function(err) {
      console.error('onCheckNicknameUniqueness', err)
    })
  },
  checkNicknameUniqueness: function(first_name, last_name, preferred_name) {
    return new Promise( (resolve, reject) => {
      jQuery.ajax({
        url: ThemeSettings.admin_ajax_url,
        data: {
          "first_name": first_name,
          "last_name": last_name,
          "preferred_name": preferred_name,
          "action": 'radical_ajax_create_unique_nickname',
        },
        type: 'POST',
        config: { headers: {'Content-Type': 'multipart/form-data' }},
      }).done(function(res) {
        const json_res = JSON.parse(res)
        resolve(json_res)
      }).fail(function(err) {
        reject(err)
      })
    })
  },
}
ReferralURLExample.onLoad()
})
})(jQuery)
</script>
