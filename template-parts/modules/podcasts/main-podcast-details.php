<?php
$main_podcast = radical_get_podcasts('all', 1);
?>
<section id="main-podcast" class="card card-main-podcast main-podcast">
  <div id="main-podcast_loader-wrap" class="radical-loader-wrap">
    <?php get_template_part('template-parts/components/loader'); ?>
  </div>
  <div id="main-podcast-content">
    <?php if ($main_podcast['podcasts']) : ?>
      <?php
      $main_podcast = $main_podcast['podcasts'][0];
      $feat_img_url = wp_get_attachment_url(get_post_thumbnail_id( $main_podcast->ID ));
      $start_date = get_field('start_date', $main_podcast->ID);
      $end_date = get_field('end_date', $main_podcast->ID);
      $event_link = get_field('event_link', $main_podcast->ID);
      $start_time = get_field('start_time', $main_podcast->ID);
      $end_time = get_field('end_time', $main_podcast->ID);
      $all_day = get_field('all_day', $main_podcast->ID);
      $is_past_or_today = (strtotime($start_date) <= strtotime(date('F j, Y')));
      $is_upcoming = strtotime($start_date) > strtotime(date('F j, Y'));
      $add_to_calendar = get_field('add_to_calendar', $main_podcast->ID);
      $title = get_the_title($main_podcast->ID);
      $embed_player = get_field('embed_player', $main_podcast->ID);
      ?>
      <?php if ($embed_player) : ?>
        <div class="embed-player-wrap">
          <?php echo $embed_player; ?>
        </div>
      <?php elseif ($feat_img_url) : ?>
        <img src="<?php echo esc_url($feat_img_url); ?>" alt="<?php echo esc_attr($title); ?>" class="card-img"/>
      <?php endif; ?>
      <div class="card-body" main-post-id="<?php echo esc_attr($main_podcast->ID); ?>">
        <div class="row align-items-center">
          <div class="col-sm-8 mb-3 mb-sm-0">
            <h3 class="mb-0">
              <?php echo $title; ?>
            </h3>
          </div>
          <div class="col-sm-4 text-sm-right">
            <?php if ((get_field('button_enabled', $main_podcast->ID) || $event_link) && $is_past_or_today) : ?>
            <a href="<?php echo esc_url((get_field('button_link', $main_podcast->ID) && get_field('button_enabled', $main_podcast->ID)) ? get_field('button_link', $main_podcast->ID) : $event_link); ?>" target="_blank" class="btn btn-pink">
              Listen
            </a>
            <?php endif; ?>
          </div>
        </div>
        <hr/>
        <div class="row align-items-center mb-3">
          <div class="col-sm-8">
            <?php if (get_field('all_day', $main_podcast->ID)) : ?>
              <div class="main-podcast_start-date"><?php echo esc_html($start_date); ?></div>
              <?php if ($start_date !== $end_date) : ?>
                <div class="main-podcast_end-date"><?php echo esc_html($end_date); ?></div>
              <?php endif; ?>
            <?php else : ?>
              <div class="main-podcast_start-date"><?php echo esc_html($start_date); ?> <?php echo esc_html($start_time); ?></div>
              <?php if ($start_date !== $end_date) : ?>
                <div class="main-podcast_end-date"><?php echo esc_html($end_date); ?> <?php echo esc_html($end_time); ?></div>
              <?php endif; ?>
            <?php endif; ?>
          </div>
          <div class="col-sm-4 text-sm-right">
            <?php if ($is_upcoming && $add_to_calendar) : ?>
              <div class="dropdown">
                <button class="btn btn-outline-pink dropdown-toggle" type="button" id="addToCalDropdown"
                  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-title="<?php echo esc_attr(get_the_title($main_podcast->ID)); ?>" data-link="<?php echo esc_attr($event_link); ?>" data-start-date="<?php echo esc_attr($start_date); ?>" data-end-date="<?php echo esc_attr($end_date); ?>" data-excerpt="<?php echo esc_attr(get_the_excerpt($main_podcast->ID)); ?>" data-start-time="<?php echo esc_attr($start_time); ?>" data-end-time="<?php echo esc_attr($end_time); ?>" data-all-day="<?php echo esc_attr($all_day); ?>">
                  Add to Cal
                </button>
                <div class="dropdown-menu dropdown-menu-sm-right" aria-labelledby="addToCalDropdown">
                  <a class="dropdown-item google" href="#" target="_blank">Google</a>
                  <a class="dropdown-item apple" href="#" target="_blank">Apple</a>
                  <a class="dropdown-item office365" href="#" target="_blank">Office365</a>
                  <a class="dropdown-item outlook" href="#" target="_blank">Outook</a>
                  <a class="dropdown-item yahoo" href="#" target="_blank">Yahoo</a>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <div><?php echo get_the_content(null, false, $main_podcast->ID); ?></div>
      </div>
    <?php else : ?>
      <div class="alert alert-info" role="alert">
        <i class="fa fa-info-circle mr-3" aria-hidden="true"></i>No upcoming podcasts found.
      </div>
    <?php endif; ?>
  </div>
</section>
