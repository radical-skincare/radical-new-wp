<?php
/**
 * Template Name: Brand Partner Enrollment
 */

get_header(); ?>

  <div class="page-wrapper funnel-wrapper">
    <?php if (!is_user_logged_in()) : ?>
      <?php get_template_part('template-parts/brand-partner/enrollment'); ?>
    <?php else : ?>
      <?php
      $user_id = get_current_user_id();
      $site_url = get_site_url();
      ?>
      <?php if (!is_Brand_Partner_Active($user_id)) : ?>
        <?php get_template_part('template-parts/brand-partner/enrollment'); ?>
      <?php else : ?>
        <section class="container d-flex justify-content-center align-items-center" style="flex-direction: column;">
          <div class="row mb-5">
            <div class="col text-center">
              <h1 class="d-block text-center h1-responsive text-primary mb-0">Brand Partner Enrollment</h1>
            </div>
          </div>
          <div class="row justify-content-center mb-5">
            <div class="text-center col-auto col-lg-6">
              <p class="fs-1.25x"><?php echo get_field('already_a_brand_partner_notice'); ?></p>
              <?php if ($dashboard_button = get_field('dashboard_button')) : ?>
                <a href="<?php echo esc_url($dashboard_button['url']); ?>" class="btn btn-darkergray mr-3" title="<?php echo esc_attr($dashboard_button['title']); ?>" target="<?php echo $dashboard_button['target'] ? '_blank' : '_self'; ?>"><?php echo esc_html($dashboard_button['title']); ?></a>
              <?php endif; ?>
              <a href="<?php echo esc_url(wp_logout_url($site_url)); ?>" class="link-underline link-underline_darker-gray" title="Logout" target="_self">Logout <i class="fa fa-sign-out" aria-hidden="true"></i></a>
            </div>
          </div>
        </section>
      <?php endif; ?>
    <?php endif; ?>
    <?php get_template_part('template-parts/brand-partner/footer-links'); ?>
  </div>

<?php get_footer(); ?>
