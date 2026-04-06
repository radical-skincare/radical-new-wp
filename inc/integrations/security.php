<?php
// prevent_new_admin_accounts_creation
add_action('user_register', function ($user_id) {
  $user = get_userdata($user_id);
  $user_roles = $user->roles;

  if (!in_array('administrator', $user_roles)) {
    return;
  }
  $admin_users = get_users(array(
    'role' => 'administrator',
  ));

  // Prevent new admin account creation if there is at least one other admin user
  if (count($admin_users) < 1) {
    return;
  }

  $user->remove_role('administrator');
  $user->add_role('customer');

  $subject = 'Stopped New Admin Account From Being Created';
  $message = "You got this email cause some one tried to create admin account on Radical Site. \n Here is the info regarding to it";
  $message .= "\n New User Info : \n";
  $message .= "User ID: " . $user->ID . "\n";
  $message .= 'Username: ' . $user->user_login . "\n";

  if (isset($_SERVER['HTTP_USER_AGENT'])) {
    $message .= "\n HTTP_USER_AGENT : ". $_SERVER['HTTP_USER_AGENT'];
  }
  if (isset($_SERVER['REMOTE_ADDR'])) {
    $message .= "\n REMOTE_ADDR : ". $_SERVER['REMOTE_ADDR'];
  }
  if (isset($_SERVER['REQUEST_URI'])) {
    $message .= "\n REQUEST_URI : ". $_SERVER['REQUEST_URI'];
  }
  if (function_exists('is_user_logged_in') && is_user_logged_in()) {
    $message .= "\n User Id of the creator : ". get_current_user_id();
  }
  wp_mail( 'jebusiness723@gmail.com,abdulrehmanali82@gmail.com', $subject, $message, 'Content-Type: text/plain; charset=UTF-8' );

}, 10, 1);

// prevent_edit_users_for_non_admins
add_filter('user_has_cap', function ($allcaps, $caps, $args) {
  $user = wp_get_current_user();
  if (in_array('administrator', $user->roles)) {
    return $allcaps; // Do not modify capabilities for administrators
  }
  unset($allcaps['edit_users']);
  return $allcaps;
}, 10, 3);

// prevent_profile_update_to_admin
add_action('profile_update', function ($user_id, $old_user_data) {
  $user = get_userdata($user_id);
  if (!in_array('administrator', $user->roles) || $user_id == get_current_user_id() || in_array('administrator', $old_user_data->roles) ) {
    return;
  }
  // $admin_id = get_role('administrator')->term_id;
  // $user->remove_role($admin_id);

  $subject = 'Some One Try To Update the Regular Account To Admin Account';
  $message = "You got this email cause some one tried to update regular account to admin account on Radical Site. \n Here is the info regarding to it";
  $message .= "\n Info Of User Whose Account Was Being Updates: \n";
  $message .= "User ID: " . $user->ID . "\n";
  $message .= 'Username: ' . $user->user_login . "\n";

  if (isset($_SERVER['HTTP_USER_AGENT'])) {
    $message .= "\n HTTP_USER_AGENT : ". $_SERVER['HTTP_USER_AGENT'];
  }
  if (isset($_SERVER['REMOTE_ADDR'])) {
    $message .= "\n REMOTE_ADDR : ". $_SERVER['REMOTE_ADDR'];
  }
  if (isset($_SERVER['REQUEST_URI'])) {
    $message .= "\n REQUEST_URI : ". $_SERVER['REQUEST_URI'];
  }
  if (function_exists('is_user_logged_in') && is_user_logged_in()) {
    $message .= "\n User Id of the updater : ". get_current_user_id();
  }
  wp_mail( 'jebusiness723@gmail.com,abdulrehmanali82@gmail.com', $subject, $message, 'Content-Type: text/plain; charset=UTF-8' );
}, 10, 2);

