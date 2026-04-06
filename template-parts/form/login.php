
<div class="position-relative">
  <div id="<?php echo esc_html($form_id); ?>_loader" class="form-loader text-center" style="display: none;">
    <div class="loader"></div>
  </div>
  <form id="<?php echo esc_html($form_id); ?>" method="post" class="p-1" action="<?php echo esc_html($action); ?>">
    <div class="invalid-feedback" id="ip-blocker-invalid-feedback"></div>
    <div class="form-outline mb-3">
      <input type="text" id="<?php echo esc_html($input_prefix); ?>_user_login" name="user_login" class="form-control form-control-lg" required/>
      <label for="<?php echo esc_html($input_prefix); ?>_user_login" class="form-label">Username or Email Address</label>
      <div class="invalid-feedback"></div>
    </div>
    <div class="form-outline">
      <input type="password" id="<?php echo esc_html($input_prefix); ?>_password" name="user_password" class="form-control form-control-lg" required/>
      <label for="<?php echo esc_html($input_prefix); ?>_password" class="form-label">Password</label>
      <span toggle="#<?php echo esc_html($input_prefix); ?>_password" class="field-icon invisible-p toggle-password">
        <img src="<?php echo get_template_directory_uri() . '/assets/images/eye-visibility-show.svg'; ?>" alt="Show Password" class="eye-visibility-show"/>
        <img src="<?php echo get_template_directory_uri() . '/assets/images/eye-visibility-hide.svg'; ?>" alt="Hide Password" class="eye-visibility-hide"/>
      </span>
      <small class="invalid-feedback my-3"></small>
    </div>
    <div class="d-flex justify-content-between my-3">
      <small>
        <div class="form-check pl-3">
          <input type="checkbox" class="form-check-input" id="<?php echo esc_html($input_prefix); ?>_remember" name="remember" value="1"/>
          <label class="form-check-label" for="<?php echo esc_html($input_prefix); ?>_remember" style="position: relative; top: 2px;">Remember me</label>
        </div>
      </small>
      <small class="text-left">
        <a href="<?php echo esc_html($site_url); ?>/wp-login.php?action=lostpassword">Forgot password?</a>
      </small>
    </div>
    <?php /* Antispam field */ ?>
    <input id="website" name="website" type="text" value="" class="d-none"/>
    <?php /* ^ This #website field should not have a value, its merely here to trick bots */ ?>
    <input type="hidden" name="action" value="radical_skincare_ajax_login"/>
    <button class="btn btn-darkergray mb-3" type="submit"><?php echo esc_html($submit_btn_text); ?></button>
  </form>
</div>
