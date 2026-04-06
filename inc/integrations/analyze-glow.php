<?php
/*
 * API - Save Analyze & Glow submissions
 */
add_action('rest_api_init', function () {
  /**
   * API - POST Save Analyze & Glow submissions
   */
  register_rest_route('radical/v1', '/users/(?P<user_id>\d+)/analyze/submissions', array(
    'methods' => 'POST',
    'callback' => 'radical_api_save_analyze_submissions',
    'permission_callback' => '__return_true',
    'args' => array(
      'user_id' => array(
        'required' => true,
        'validate_callback' => function ($param) {
          return is_numeric($param);
        }
      )
    ),
  ));
});

// Save Analyze & Glow submissions
function radical_api_save_analyze_submissions($request)
{
  $user_id = (int)$request['user_id'];
  $body = $request->get_body();
  $body = json_decode($body, true);
  if ($body['contact'] && $body['answers']) {
    $contact = $body['contact'];
    $answers = $body['answers'];
    $result = sync_profile_to_klaviyo_list($contact['email'], $body['resultsCategory'], $contact['first_name'], $contact['last_name']);
    $now = current_time('mysql');
    if ($user_id == 0) {
      $email_body = "Submitted at: $now\n";
      $email_body .= "Contact: " . $contact['email'] . "\n";
      $email_body .= "Answers: $answers\n";
      wp_mail('jebusiness723@gmail.com', 'Radical Skincare | Analyze & Glow | Submission', $email_body);
      return wp_send_json(array(
        'message' => 'Quiz answers emailed.'
      ), 200);
    }
    // Retrieve existing quiz answers or initialize as an empty array
    $existing_answers = get_user_meta($user_id, 'analyze_submissions', true);
    if (!is_array($existing_answers)) {
      $existing_answers = array();
    }
    // Add new answers with current timestamp as key
    $existing_answers[current_time('mysql')] = $answers;
    update_user_meta($user_id, 'analyze_submissions', json_encode($existing_answers));
    return new WP_REST_Response(array(
      'message' => 'Quiz answers saved.',
      'klaviyo_results' => $result
    ), 200);
  }
  return new WP_REST_Response(array(
    'message' => 'Quiz answers failed to save.'
  ), 500);
}

function sync_profile_to_klaviyo_list($email, $tag, $first_name = '', $last_name = '')
{
  $api_key = get_field('klaviyo_api_key', 'option');
  $list_id = get_field('analyze_and_glow_quiz_klaviyo_list_id', 'option');
  $profile_id = get_klaviyo_profile_id($api_key, $email);
  $added = add_profile_to_klaviyo_list($api_key, $list_id, $profile_id);

  if (!$added) {
    return "Error adding profile to list.";
  }

  if (!$profile_id) {
    $profile_id = create_klaviyo_profile($api_key, $email, $first_name, $last_name);
    if (!$profile_id) {
      return 'Error: Failed to create profile.';
    }
  }

  // Step 4: Assign a tag to the profile
  if (!empty($tag)) {
      $tagged = add_tag_to_klaviyo_profile($api_key, $profile_id, $tag);
      if (!$tagged) {
          return "Profile added to list, but tagging failed.";
      }
  }

  return "Profile successfully added to the list and tagged.";
}

// Function to check if the profile exists in Klaviyo
function get_klaviyo_profile_id($api_key, $email)
{
  $url = "https://a.klaviyo.com/api/profiles/?filter=equals(email,'$email')";

  $response = wp_remote_get($url, [
    'headers' => [
      'Authorization' => 'Klaviyo-API-Key ' . $api_key,
      'Accept'        => 'application/json',
      'revision'      => '2025-01-15'
    ]
  ]);

  if (is_wp_error($response)) {
    return null;
  }

  $body = json_decode(wp_remote_retrieve_body($response), true);

  return $body['data'][0]['id'] ?? null;
}

function create_klaviyo_profile($api_key, $email, $first_name, $last_name)
{
  $url = "https://a.klaviyo.com/api/profiles/";

  $body = [
    "data" => [
      "type" => "profile",
      "attributes" => [
        "email" => $email,
        "first_name" => $first_name,
        "last_name" => $last_name,
      ]
    ]
  ];

  $response = wp_remote_post($url, [
    'headers' => [
      'Authorization' => 'Klaviyo-API-Key ' . $api_key,
      'Accept'        => 'application/json',
      'Content-Type'  => 'application/json',
      'revision'      => '2025-01-15'
    ],
    'body'    => json_encode($body),
    'method'  => 'POST',
  ]);
  if (is_wp_error($response)) {
    return null;
  }

  $body = json_decode(wp_remote_retrieve_body($response), true);
  return $body['data']['id'] ?? null;
}

function add_profile_to_klaviyo_list($api_key, $list_id, $profile_id)
{
  $url = "https://a.klaviyo.com/api/lists/{$list_id}/relationships/profiles/";
  $body = [
    "data" => [
      [
        "type" => "profile",
        "id"   => $profile_id
      ]
    ]
  ];

  $response = wp_remote_post($url, [
    'headers' => [
      'Authorization' => 'Klaviyo-API-Key ' . $api_key,
      'Accept'        => 'application/json',
      'Content-Type'  => 'application/json',
      'revision'      => '2025-01-15'
    ],
    'body'    => json_encode($body),
    'method'  => 'POST',
  ]);
  $wc_logger = wc_get_logger();
  $wc_logger->debug( json_encode($response), array( 'source' => 'analyze-glow-logs' ) );
  return !is_wp_error($response);
}

function add_tag_to_klaviyo_profile($api_key, $profile_id, $tag) {
  $url = "https://a.klaviyo.com/api/profiles/{$profile_id}/";

  $body = [
      "data" => [
          "type" => "profile",
          "id"   => $profile_id,
          "attributes" => [
              "properties" => [
                  "analyze_and_glow_submission" => $tag
              ]
          ]
      ]
  ];

  $response = wp_remote_post($url, [
      'headers' => [
          'Authorization' => 'Klaviyo-API-Key ' . $api_key,
          'Accept'        => 'application/json',
          'Content-Type'  => 'application/json',
          'revision'      => '2025-01-15'
      ],
      'body'    => json_encode($body),
      'method'  => 'PATCH',
  ]);

  return !is_wp_error($response);
}

// Admin menu and WP List Table for Analyze & Glow submissions
if (is_admin()) {
    add_action('admin_menu', function () {
        add_menu_page(
            'Analyze & Glow',
            'Analyze & Glow',
            'manage_options',
            'analyze-glow',
            'render_analyze_glow_page',
            'dashicons-visibility',
            26
        );
    });
}

function extract_inner_json($input) {
  preg_match('/"(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})":"({.*})"/', $input, $matches);

  if (!empty($matches[2])) {
    $inner_json = $matches[2];

    // Decode the inner JSON string
    $decoded = json_decode($inner_json, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        return $decoded;
    } else {
        return ['error' => 'Inner JSON decode failed: ' . json_last_error_msg()];
    }
  }
  return ['error' => 'Pattern did not match'];
}

function render_analyze_glow_page() {
    global $wpdb;

    echo '<div class="wrap"><h1>Analyze & Glow Submissions</h1>';
    $user_meta = $wpdb->get_results("
        SELECT user_id, meta_value
        FROM {$wpdb->usermeta}
        WHERE meta_key = 'analyze_submissions'
    ");

    $total_submissions = $wpdb->get_var("
        SELECT COUNT(*) 
        FROM {$wpdb->usermeta}
        WHERE meta_key = 'analyze_submissions'
    ");
    echo '<p><strong>Total Submissions:</strong> ' . esc_html($total_submissions) . '</p>';

    if (empty($user_meta)) {
        echo '<p>No submissions found.</p>';
        echo '</div>';
        return;
    }

    echo '<table class="widefat fixed striped">';
    echo '<thead><tr>';
    echo '<th>Email</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>Answers</th>';
    echo '</tr></thead><tbody>';

  foreach ($user_meta as $meta) {
    // Decode outer JSON as associative array
    // $outer = json_decode($meta->meta_value, true);

    // if (!is_array($outer)) {
    //   $email = get_userdata($meta->user_id)->user_email ?? 'N/A';
    //   echo '<tr>';
    //   echo '<td>' . esc_html($email) . '</td>';
    //   echo '<td colspan="7"></td>';
    //   echo '<td>' . esc_html($meta->meta_value) . '</td>';
    //   echo '</tr>';
    //   continue;
    // }

    // foreach ($outer as $timestamp => $inner_json) {
      // Decode the inner JSON (string) into array
      $inner_json = extract_inner_json($meta->meta_value);
      $answers = is_string($inner_json) ? json_decode($inner_json, true) : $inner_json;
      if (!is_array($answers)) {
          $email = get_userdata($meta->user_id)->user_email ?? 'N/A';
          echo '<tr>';
          echo '<td>' . esc_html($email) . '</td>';
          echo '<td colspan="7"></td>';
          echo '<td>' . esc_html($meta->meta_value) . '</td>';
          echo '</tr>';
          continue;
      }

      $email = get_userdata($meta->user_id)->user_email ?? 'N/A';
      echo '<tr>';
      echo '<td>' . esc_html($email) . '</td>';
      for ($i = 1; $i <= 7; $i++) {
          echo '<td>' . esc_html($answers[(string)$i] ?? '') . '</td>';
      }
      echo '<td>' . esc_html($meta->meta_value) . '</td>';
      echo '</tr>';
    // }
  }

    echo '</tbody></table>';
    echo '</div>';
}