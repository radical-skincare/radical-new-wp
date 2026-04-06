<?php
/**
 * Template Name: Radical Rituals
 */

get_header(); ?>

  <?php get_template_part('template-parts/hero/hero-image-right'); ?>
  <?php /* get_template_part('template-parts/modules/radical-rituals/intro'); */ ?>
  <?php get_template_part('template-parts/content/intro'); ?>
  <?php if ($sections = get_field('sections-image_cards')) : ?>
    <?php foreach ($sections as $key => $section) :
      $img = $section['image'];
      $title = $section['heading'];
      $text = $section['content'];
      $link = $section['link'];
      $subtitle = '';
      $image_align = (($key % 2) === 0) ? 'right' : 'left';
      get_template_part('template-parts/modules/flex/image-card');
    endforeach; ?>
  <?php endif; ?>
  <div class="my-5"></div>
  <?php get_template_part('template-parts/content-cta-shop'); ?>

<?php get_footer(); ?>
