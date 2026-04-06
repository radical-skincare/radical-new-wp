<?php

use function Automattic\Jetpack\Extensions\Eventbrite\get_current_url;

class Woo_My_Account {

  public $is_user_logged_in;
  public $current_user_id;
  public $is_active;

  public function __construct() {
    $this->gigfiliate_settings = json_decode( get_option('gigfiliate_settings') );
    $this->is_user_logged_in = is_user_logged_in();
    $this->is_active = false;
    if ($this->is_user_logged_in) {
      $this->current_user_id = get_current_user_id();
      if (function_exists('gigfiliate_is_active_affiliate')) {
        $this->is_active = gigfiliate_is_active_affiliate( $this->current_user_id );
      }
      $this->is_administrator = current_user_can('edit_posts');
    }
    add_action( 'init', array($this, 'init') );
    add_action( 'query_vars', array($this, 'query_vars'), 0 );
    add_action( 'woocommerce_account_menu_items', array($this, 'woocommerce_account_menu_items'));
    add_action( 'woocommerce_account_brand-partner-resources_endpoint', function () {
      $this->woocommerce_account_brand_partner_resources_content();
    });
    add_action( 'woocommerce_account_brand-partner-leaderboards_endpoint', function () {
      $this->woocommerce_account_brand_partner_leaderboards_content();
    });
    add_action('wp_ajax_bp_training_quiz', [$this,'wp_ajax_bp_training_quiz']);
  }

  public function init() {
    // add_rewrite_endpoint( 'brand-partner-dashboard', EP_ROOT | EP_PAGES );
    if ($this->is_active) {
      add_rewrite_endpoint( 'brand-partner-leaderboards', EP_ROOT | EP_PAGES );
      add_rewrite_endpoint( 'brand-partner-resources', EP_ROOT | EP_PAGES );
    }
  }

  function wp_ajax_bp_training_quiz() {
    check_ajax_referer('radical_ajax_nonce', 'nonce');
    $quiz_title = $_POST['quiz_title'];
    $correct = $_POST['correct'];
    $all_correct = $_POST['all_correct'];
    $res = [];
    $correct_status = ($correct) ? 'correct' : 'incorrect';
    $meta_key = 'bp_training_quiz_' . $quiz_title . '_graded_' . $correct_status;
    $now = time();
    if (!add_user_meta($this->current_user_id, $meta_key, $now, true)) {
      update_user_meta($this->current_user_id, $meta_key, $now, true);
    }
    if ($all_correct) {
      $this->give_user_training_badge();
    }
    $res['success'] = true;
    exit(json_encode($res));
  }

  function give_user_training_badge() {
    $gigfiliate_badges = get_user_meta($this->current_user_id, 'gigfiliate_badges');
    $gigfiliate_badges = json_decode($gigfiliate_badges, true);
    $updated_gigfiliate_badges = [];
    $training_badge = null;
    foreach ($gigfiliate_badges as $gigfiliate_badge) {
      if ($gigfiliate_badge['name'] == 'Training') {
        $training_badge = $gigfiliate_badge;
        $gigfiliate_badge['awarded_at'] = date('Y-m-d H:i:s');
      }
      array_push($updated_gigfiliate_badges, $gigfiliate_badge);
    }
    if ($training_badge == null) {
      array_push($updated_gigfiliate_badges, [
        'name' => 'Training',
        'awarded_at' => date('Y-m-d H:i:s'),
      ]);
    }
    $gigfiliate_badges = json_encode($updated_gigfiliate_badges);
    update_user_meta(get_current_user_id(), 'gigfiliate_badges', $gigfiliate_badges);
  }

  public function query_vars( $vars ) {
    // $vars[] = 'brand-partner-dashboard';
    if ($this->is_active) {
      // if ($this->is_administrator) {
        $vars[] = 'brand-partner-leaderboards';
      // }
      $vars[] = 'brand-partner-resources';
    }
    return $vars;
  }

  // Insert the new endpoint into the My Account menu
  public function woocommerce_account_menu_items( $menu_links ) {
    $new_menu_links = [];
    if ($this->is_active) {
      $new_menu_links['brand-partner-leaderboards'] = 'Brand Partner Leaderboards';
      $new_menu_links['brand-partner-resources'] = 'Brand Partner Resources';
    }
    $menu_links = array_slice( $menu_links, 0, 1, true ) 
      + $new_menu_links 
      + array_slice( $menu_links, 1, NULL, true );
    $menu_links['dashboard'] = __('Account', 'sage');
    $menu_links['orders'] = __('My Orders', 'sage');
    $menu_links['subscriptions'] = __('My Subscriptions', 'sage');
    $menu_links['customer-logout'] = __('Logout <i class="fa fa-sign-out"></i>', 'sage');
    return $menu_links;
  }

  public function clean_string($string) {
    $string = str_replace(' ', '-', $string);
    return strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', $string));
  }

  public function woocommerce_account_brand_partner_resources_content() {
    if (!$this->is_active) { ?>
      <p><?php echo $this->gigfiliate_settings->dashboard->not_active_notice; ?></p>
      <?php
      return;
    }
    $resource_center = get_field('resource_center', 'option');
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'news';
    ?>
    <style>
    .woocommerce-MyAccount-content.brand-partner-resources {
      padding: 0 !important;
      box-shadow: none !important;
    }
    </style>
    <ul class="nav nav-tabs nav-justified md-tabs" id="brandPartnerResources" role="tablist">
      <?php if ($resource_center['news']['enable']) { ?>
        <li class="nav-item">
          <a class="nav-link <?php echo $active_tab === 'news' ? 'active' : ''; ?>" id="news-tab-link" data-toggle="tab" href="#news-tab" role="tab" aria-controls="news-tab" aria-selected="true">News</a>
        </li>
      <?php } ?>
      <?php if ($resource_center['events']['enable']) { ?>
        <li class="nav-item">
          <a class="nav-link <?php echo $active_tab === 'events' ? 'active' : ''; ?>" id="events-tab-link" data-toggle="tab" href="#events-tab" role="tab" aria-controls="events-tab" aria-selected="false">Events</a>
        </li>
      <?php } ?>
      <?php if ($resource_center['training']['enable']) { ?>
        <li class="nav-item">
          <a class="nav-link <?php echo $active_tab === 'training' ? 'active' : ''; ?>" id="training-tab-link" data-toggle="tab" href="#training-tab" role="tab" aria-controls="training-tab" aria-selected="false">Training</a>
        </li>
      <?php } ?>
      <?php if ($resource_center['creatives']['enable']) { ?>
        <li class="nav-item">
          <a class="nav-link <?php echo $active_tab === 'creatives' ? 'active' : ''; ?>" id="creatives-tab-link" data-toggle="tab" href="#creatives-tab" role="tab" aria-controls="creatives-tab" aria-selected="false">Creatives</a>
        </li>
      <?php } ?>
      <?php if ($resource_center['misc']['enable']) { ?>
        <li class="nav-item">
          <a class="nav-link <?php echo $active_tab === 'misc' ? 'active' : ''; ?>" id="education-tab-link" data-toggle="tab" href="#education-tab" role="tab" aria-controls="education-tab" aria-selected="false">Education</a>
        </li>
      <?php } ?>
    </ul>
    <div class="tab-content card p-3" id="brandPartnerResourcesContent">
        <?php if ($resource_center['news']['enable']) { ?>
          <div class="tab-pane fade <?php echo $active_tab === 'news' ? ' show active' : ''; ?>" id="news-tab" role="tabpanel" aria-labelledby="news-tab-link">
            <?php echo $resource_center['news']['content']; ?>
          </div>
        <?php } ?>
        <?php if ($resource_center['events']['enable']) { ?>
          <div class="tab-pane fade <?php echo $active_tab === 'events' ? ' show active' : ''; ?>" id="events-tab" role="tabpanel" aria-labelledby="events-tab-link">
            <?php echo $resource_center['events']['content']; ?>
          </div>
        <?php } ?>
        <?php if ($resource_center['training']['enable']) { ?>
          <div class="tab-pane fade <?php echo $active_tab === 'training' ? ' show active' : ''; ?>" id="training-tab" role="tabpanel" aria-labelledby="training-tab-link">
            <div class="row">
              <div class="col-lg-3">
                <?php if (!empty($resource_center['training']['lessons'])) { ?>
                  <ol id="training-videos-links">
                    <?php
                    foreach($resource_center['training']['lessons'] as $key => $lesson) {
                      $youtube_video_id = $this->get_lesson_youtube_id($lesson);
                      $graded_correct_time = get_user_meta($this->current_user_id, 'bp_training_quiz_' . $this->clean_string($lesson['title']) . '_graded_correct', true);
                      $is_correct = ($graded_correct_time !== '') ? true : false;
                      ?>
                      <li <?php echo ($key === 0 ? 'class="active"' : ''); ?>>
                        <a href="javascript:void(0);" data-target="#training-videos-carousel" data-slide-to="<?php echo $key; ?>" <?php echo ($is_correct) ? 'is-completed="1"' : ''; ?> youtube-video-id="<?php echo $youtube_video_id; ?>">
                          <?php if ( $is_correct ) { ?>
                            <i class="fa fa-check" aria-hidden="true"></i>
                            <span class="sr-only">Completed</span>
                            <?php
                          } ?>
                          <?php echo $lesson['title']; ?>
                        </a>
                      </li>
                    <?php } ?>
                  </ol>
                <?php } else { ?>
                  <p>No training lessons.</p>
                <?php } ?>
              </div>
              <div class="col-lg-9">
                <div id="training-videos-carousel" class="carousel slide" data-ride="carousel" data-interval="false">
                  <div class="carousel-inner">
                    <?php
                    if (!empty($resource_center['training']['lessons'])) {
                      $lessons_count = count($resource_center['training']['lessons']);
                      foreach($resource_center['training']['lessons'] as $key => $lesson) {
                        $youtube_video_id = $this->get_lesson_youtube_id($lesson);
                        $graded_correct_time = get_user_meta($this->current_user_id, 'bp_training_quiz_' . $this->clean_string($lesson['title']) . '_graded_correct', true);
                        $is_correct = ($graded_correct_time !== '') ? true : false;
                        ?>
                        <div class="carousel-item <?php echo ($key == 0 ? 'active' : ''); ?> px-3 pb-3">
                          <div class="card card-video mb-3">
                            <div class="embed-responsive embed-responsive-16by9">
                              <?php if ($key === 0) { ?>
                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $youtube_video_id; ?>?rel=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                              <?php } ?>
                            </div>
                          </div>
                          <div class="card card-video-question mb-3">
                            <div class="card-body p-0">
                              <div class="pt-3 px-3">
                                <h3 class="lesson-title mb-0">
                                  <?php echo $lesson['title']; ?>
                                </h3>
                              </div>
                              <?php if (!empty($lesson['answers'])) { ?>
                                <div class="quiz-question px-3">
                                  <?php echo $lesson['question']; ?>
                                </div>
                                <div id="lesson-completed_<?php echo $key; ?>" class="lesson-completed row mx-0" style="<?php echo (!$is_correct) ? 'display: none;' : ''; ?>">
                                  <div class="col-sm-6 col-md-7 mb-3 mb-sm-0">
                                    <i class="fa fa-check mr-3" aria-hidden="true"></i>
                                    <span class="sr-only">Completed</span>
                                    Well done! That's the correct answer.
                                  </div>
                                  <?php
                                  // if not last lesson
                                  $next_lesson_key = $key + 1;
                                  if ($lessons_count > ($next_lesson_key)) { ?>
                                    <div class="col-sm-6 col-md-5 text-sm-right">
                                      <?php
                                      $next_lesson = $resource_center['training']['lessons'][$next_lesson_key];
                                      $next_lesson_youtube_video_id = $this->get_lesson_youtube_id($next_lesson);
                                      ?>
                                      <a href="javascript:void(0);" data-target="#training-videos-carousel" data-slide-to="<?php echo $next_lesson_key; ?>" class="btn btn-outline-white" youtube-video-id="<?php echo $next_lesson_youtube_video_id; ?>">
                                        Next Question <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                      </a>
                                    </div>
                                  <?php } ?>
                                </div>
                                <div id="lesson-incorrect-answer_<?php echo $key; ?>" class="lesson-incorrect-answer" style="display: none">
                                  <div class="col-sm-6 col-md-7 mb-3 mb-sm-0">
                                    <i class="fa fa-exclamation-triangle mr-3" aria-hidden="true"></i>
                                    <span class="sr-only">Incorrect Answer</span>
                                    Unfortunately, that answer is incorrect.
                                  </div>
                                </div>
                                <form id="lesson-quiz-<?php echo $key; ?>" class="lesson-quiz p-3">
                                  <ul class="quiz-answers">
                                    <?php
                                    // TODO: Low Priority: We may want to hide the correct answer from the UI and instead get the correct answer from the backend on submission
                                    foreach ($lesson['answers'] as $answer_key => $answer) {
                                      $input_key = 'lesson_' . $key; ?>
                                      <li class="form-check p-1">
                                        <input type="radio" id="<?php echo $input_key . '_' . $answer_key; ?>" name="<?php echo $input_key; ?>" value="<?php echo $answer_key; ?>" class="form-check-input" is-correct="<?php echo $answer['correct'] ? 'true' : 'false'; ?>" <?php echo ($is_correct && $answer['correct']) ? 'checked="checked"' : ''; ?> <?php echo ($is_correct) ? 'disabled' : ''; ?> required/>
                                        <label for="<?php echo $input_key . '_' . $answer_key; ?>" class="mb-0">
                                          <?php echo $answer['text']; ?>
                                        </label>
                                      </li>
                                    <?php } ?>
                                  </ul>
                                  <button type="submit" class="btn btn-pink submit-quiz" lesson-id="<?php echo $key; ?>" lesson-title="<?php echo $this->clean_string($lesson['title']); ?>" <?php echo ($is_correct) ? 'disabled' : ''; ?>>Submit</button>
                                </form>
                              <?php } else { ?>
                                <div class="p-3">
                                  <p class="mb-0">Quiz Answers Coming Soon</p>
                                </div>
                              <?php } ?>
                            </div>
                          </div>
                        </div>
                      <?php }
                      } ?>
                  </div>
                  <?php /*
                  <a class="carousel-control-prev" href="#training-videos-carousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                  </a>
                  <a class="carousel-control-next" href="#training-videos-carousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                  </a>
                  */ ?>
                </div>              
              </div>
            </div>
          </div>
        <?php }
        if ($resource_center['creatives']['enable']) { ?>
          <div class="tab-pane fade <?php echo $active_tab === 'creatives' ? ' show active' : ''; ?>" id="creatives-tab" role="tabpanel" aria-labelledby="creatives-tab-link">
            <?php echo $resource_center['creatives']['content']; ?>
          </div>
        <?php }
        if ($resource_center['misc']['enable']) { ?>
          <div class="tab-pane fade <?php echo $active_tab === 'misc' ? ' show active' : ''; ?>" id="education-tab" role="tabpanel" aria-labelledby="education-tab-link">
            <?php echo $resource_center['misc']['content']; ?>
          </div>
        <?php } ?>
    </div>
    <?php    
  }

  public function get_lesson_youtube_id($lesson) {
    $url_parts = parse_url($lesson['video']);
    parse_str($url_parts['query'], $query_parts);
    return $query_parts['v'];
  }

  public function woocommerce_account_brand_partner_leaderboards_content() {
    // Add content to the new endpoint
    if (!$this->is_active) { ?>
      <p><?php echo $this->gigfiliate_settings->dashboard->not_active_notice; ?></p>
      <?php
      return;
    }
    $leaderboards_content = get_field('leaderboards_content', 'option');
    ?>
    <div class="p-3">
      <?php echo $leaderboards_content; ?>
    </div>
    <?php
  }
};

new Woo_My_Account;
