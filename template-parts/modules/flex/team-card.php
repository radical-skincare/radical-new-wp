<?php
/**
 * Team Card Module
 *
 * Expected variables: $image, $name, $role, $bio
 *
 * get_template_part() loads this file inside WordPress's load_template(),
 * a real function — it does not inherit the caller's variables unless they
 * are declared global here (the caller sets these at the top-level/global
 * scope of its own template file).
 */
global $image, $name, $role, $bio;
?>
<div class="team-member row py-4">
  <?php if ($image) : ?>
    <div class="d-flex col-md-6 col-lg-4 align-items-center mb-4 mb-lg-0">
      <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($name); ?>" class="team-member_img w-100 rounded"/>
    </div>
  <?php endif; ?>
  <div class="col-md-6 col-lg-8">
    <h3 class="team-member_name d-block text-darker-gray fs-2x ff-orpheus"><?php echo $name; ?></h3>
    <h6 class="team-member_role text-darker-gray"><?php echo $role; ?></h6>
    <div class="team-member_bio text-darker-gray">
      <?php echo $bio; ?>
    </div>
  </div>
</div>
