<?php
/**
 * Template Name: Join Brand Partners
 */

get_header(); ?>

  <?php while (have_posts()) : the_post(); ?>
    <?php get_template_part('template-parts/hero/hero-image-right'); ?>
    <?php if ($intro = get_field('intro')) :
      $image = $intro['image'];
      $content = $intro['content'];
      get_template_part('template-parts/modules/join-brand-partners/intro');
    endif; ?>
    <?php get_template_part('template-parts/modules/join-brand-partners/how-it-works'); ?>
    <?php get_template_part('template-parts/modules/join-brand-partners/getting-started'); ?>
    <?php if ($step_one = get_field('step_one')) :
      $img = $step_one['image'];
      $subtitle = '<span class="font-weight-five">STEP ONE</span>';
      $title = $step_one['heading'];
      $text = $step_one['content'];
      $link = $step_one['link'];
      $image_align = 'left';
      get_template_part('template-parts/modules/flex/image-card');
    endif; ?>
    <?php get_template_part('template-parts/modules/join-brand-partners/collection-includes'); ?>
    <?php /*
    <?php if ($step_two = get_field('step_two')) :
      $img = $step_two['image'];
      $subtitle = '<span class="font-weight-five">STEP TWO</span>';
      $title = $step_two['heading'];
      $text = $step_two['content'];
      $image_align = 'right';
      get_template_part('template-parts/modules/flex/image-card');
    endif; ?>
    <?php get_template_part('template-parts/modules/join-brand-partners/collections'); ?>
    */ ?>
    <?php if ($step_three = get_field('step_three')) :
      $img = $step_three['image'];
      $subtitle = '<span class="font-weight-five">STEP TWO</span>';
      $title = $step_three['heading'];
      $text = $step_three['content'];
      $link = $step_three['link'];
      $image_align = 'right';
      get_template_part('template-parts/modules/flex/image-card');
    endif; ?>
    <?php get_template_part('template-parts/modules/join-brand-partners/bp-faq'); ?>
    <div class="my-5"></div>
    <?php get_template_part('template-parts/content-cta-join'); ?>
  <?php endwhile; ?>

<?php get_footer(); ?>
