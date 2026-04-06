<?php if (!is_user_logged_in()) : ?>
  <?php
  $email_signup_modal = get_field('email_signup_modal', 'option');
  ?>
  <?php if (isset($email_signup_modal) && $email_signup_modal['enable']) : ?>
    <div id="emailSubscribeModal" class="email-sign-up-modal modal" >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <div class="text-center">
              <p><strong>WAIT!</strong> Enter your email below to receive...<br/>10%<br/>Off Your First Order</strong></p>
            </div>
            <div class="row">
              <div class="col">
                <?php echo do_shortcode('[mc4wp_form id="' . $email_signup_modal['mc4wp_form_id'] . '"]'); ?>
              </div>
            </div>
            <p class="text-center">
              <small>*By completing this form you are signing up to receive our emails and you can unsubscribe at any time.</small>
            </p>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
<?php endif; ?>
