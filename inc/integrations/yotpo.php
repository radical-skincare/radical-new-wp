<?php

function radical_get_product_from_yotpo_review($review_id) {
  $utoken = get_field('yotpo_utoken');
  $ch = curl_init();
  $url = "https://api.yotpo.com/reviews/{$review_id}?utoken={$utoken}";
  $headers = array(
    'Content-Type: application/json',
    'accept: application/json'
  );
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $response = curl_exec($ch);
  if (curl_errno($ch)) {
    error_log('Curl error: ' . curl_error($ch));
    return false;
  }
  curl_close($ch);
  return $response;
}

function radical_yotpo_rest_api_response() {
  $data = json_decode(file_get_contents('php://input'), true);
  $data = $data['data'];
  $review = radical_get_product_from_yotpo_review($data['id']);
  $comment_author = $data['reviewer_display_name'];
  $comment_author_email = $data['customer_email'];
  $author_user  = get_user_by('email', $comment_author_email);
  $user_id = null;
  if ($author_user) {
    $comment_author = $author_user->display_name;
    $user_id = $author_user->ID;
  }
  if (!$review) {
    return;
  }
  $review = json_decode($review);
  if (!$review->status || $review->status->code != 200) {
    return;
  }
  $comments = get_comments([
    'meta_key'=> 'yotpo_id',
    'meta_value'=> $data['id']
  ]);
  if ($comments && count($comments)) {
    return 'found';
  }
  $review = $review->response->review;
  $commentdata = array(
    'comment_post_ID'      => $review->products_apps[0]->domain_key, // <=== The product ID where the review will show up
    'comment_author'       => $comment_author,
    'comment_author_email' => $data['customer_email'], // <== Important
    'comment_author_url'   => '',
    'comment_content'      => $data['content'],
    'comment_type'         => 'review',
    'comment_parent'       => 0,
    'user_id'              => $user_id, // <== Important
    'comment_author_IP'    => '',
    'comment_agent'        => '',
    'comment_date'         => date('Y-m-d H:i:s'),
    'comment_approved'     => 1,
  );
  $comment_id = wp_insert_comment( $commentdata );
  update_comment_meta( $comment_id, 'rating', $data['score']); // The rating is an integer from 1 to 5
  update_comment_meta( $comment_id, 'yotpo_id', $data['id']);
  do_action( 'comment_post', $comment_id, 1, get_comment($comment_id) );
  do_action( 'comment_unapproved_to_approved', get_comment($comment_id) );
  return true;
}

add_action( 'rest_api_init', function () {
  register_rest_route( 'radical/v1', '/yotpo', array(
    'methods' => 'POST',
    'callback' => 'radical_yotpo_rest_api_response',
  ) );
} );
