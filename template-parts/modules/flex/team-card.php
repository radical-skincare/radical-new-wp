<?php
/**
 * Team Card Module
 *
 * Expected $args: image, name, role, bio
 */
$image = $args['image'] ?? null;
$name = $args['name'] ?? null;
$role = $args['role'] ?? null;
$bio = $args['bio'] ?? null;
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
