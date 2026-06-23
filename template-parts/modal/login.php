
<?php
$is_user_logged_in = $args['is_user_logged_in'] ?? is_user_logged_in();
?>
<?php if ( !$is_user_logged_in ) : ?>
  <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content" style="border-radius: .25rem;">
        <div class="position-relative modal-body p-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; top: 1rem; right: 0.5rem; z-index: 1; padding: 0.5rem; font-size: 1.75rem; width: 2.5rem; height: 2.5rem;">
            <i class="fa fa-close" aria-hidden="true"></i>
            <span class="sr-only">Close</span>
          </button>
          <div class="card card-login">
            <h5 class="card-header white-text text-center py-4">
              <strong>Login</strong>
            </h5>
            <div class="card-body p-lg-5">
              <?php
              $site_url = get_site_url();
              $redirect_to = $site_url . '/brand-partner/dashboard';
              $action = admin_url('admin-ajax.php');
              $form_id = 'login_modal';
              $input_prefix = 'login_modal';
              $submit_btn_text = 'Log In';
              get_template_part('template-parts/form/login', null, [
                'site_url'        => $site_url,
                'redirect_to'     => $redirect_to,
                'action'          => $action,
                'form_id'         => $form_id,
                'input_prefix'    => $input_prefix,
                'submit_btn_text' => $submit_btn_text,
              ]);
              ?>
              <p>Don't have an account? Create an account at <a href="<?php echo esc_html($site_url); ?>/checkout/">checkout</a> or <a href="<?php echo esc_html($site_url); ?>/brand-partner/">join brand partners</a>.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
