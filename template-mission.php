<?php
/**
 * Template Name: Mission
 */

get_header(); ?>

  <?php while (have_posts()) : the_post(); ?>
    <?php get_template_part('template-parts/modules/mission/hero'); ?>
    <?php get_template_part('template-parts/modules/mission/intro'); ?>
    <?php get_template_part('template-parts/modules/mission/father-story'); ?>
    <?php if ($our_story = get_field('our_story')) : ?>
      <div class="bg-lightergray">
        <?php
        get_template_part('template-parts/modules/flex/image-card', null, [
          'img' => $our_story['image'],
          'title' => 'OUR RADICAL STORY',
          'text' => $our_story['content'],
          'image_align' => 'right',
          'subtitle' => '',
          'link' => '',
        ]);
        ?>
      </div>
    <?php endif; ?>
    <?php get_template_part('template-parts/modules/mission/timeline'); ?>
    <?php get_template_part('template-parts/content-cta-shop'); ?>
  <?php endwhile; ?>

<?php get_footer(); ?>
