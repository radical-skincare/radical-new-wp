<?php

// add_filter('wlr_my_account_reward_desc', function() {
// });

add_action('wlr_new_coupon', function($id) {
  update_post_meta($id, 'work_with_individual', 1);
});