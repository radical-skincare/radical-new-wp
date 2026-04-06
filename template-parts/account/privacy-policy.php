
<section class="pt-5">
  <div class="alert alert-info" role="alert">
    <i class="fa fa-info-circle" aria-hidden="true"></i> We've updated our Privacy Policy, please read updates and agree to terms.
  </div>
  <div class="account-privacy-policy"><?php echo $privacy_policy['page']->post_content; ?></div>
  <form method="POST">
    <label class="form-check mb-3" for="agree-to-updates" style="cursor: pointer;">
      <input type="checkbox" class="form-check-input" id="agree-to-updates" name="agree" required/> I have read and agree to the Privacy Policy terms and updates
    </label>
    <button type="submit" class="btn btn-darkergray" name="action" value="agree_to_privacy_policy">Submit</button>
  </form>
</section>
