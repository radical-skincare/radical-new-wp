<?php
/**
 * Template Name: Rewards
 */

if (!function_exists('radical_number_to_word')) {
  function radical_number_to_word($number) {
    $number_words = array(
      1 => 'one',
      2 => 'two',
      3 => 'three',
      4 => 'four',
      5 => 'five',
      6 => 'six',
      7 => 'seven',
      8 => 'eight',
      9 => 'nine',
      10 => 'ten',
    );
    if (array_key_exists($number, $number_words)) {
      return $number_words[$number];
    } else {
      return 'Invalid input! Please enter a number between 1 and 10.';
    }
  }
}

get_header(); ?>

  <?php while (have_posts()) : the_post(); ?>
    <?php get_template_part('template-parts/hero/hero-image-right'); ?>
    <?php if ($intro = get_field('intro')) :
      get_template_part('template-parts/modules/rewards/intro', null, [
        'divider_text' => $intro['divider_text'],
        'image' => $intro['image'],
        'heading' => $intro['heading'] ?? null,
        'content' => $intro['content'],
      ]);
    endif; ?>
    <?php if ($power_of_sharing = get_field('power_of_sharing')) :
      get_template_part('template-parts/modules/rewards/power-of-earning', null, [
        'items' => $power_of_sharing['items'],
      ]);
    endif; ?>
    <?php if ($ways_to_earn = get_field('ways_to_earn')) :
      get_template_part('template-parts/modules/rewards/ways-to-earn', null, [
        'divider_text' => 'Ways to Earn',
        'items' => $ways_to_earn['items'],
      ]);
    endif; ?>
    <?php get_template_part('template-parts/modules/rewards/earning-points'); ?>
    <?php if ($earning_steps = get_field('earning_steps')) : ?>
      <style>
      section#earning-steps .flex_image-card img {
        width: 100%;
        height: 256px;
        object-fit: contain;
        border-radius: 1rem;
        background-color: #f7f6fe;
      }
      </style>
      <section id="earning-steps">
        <?php foreach ($earning_steps as $key => $step) :
          get_template_part('template-parts/modules/flex/image-card', null, [
            'img' => $step['image'],
            'subtitle' => '<span class="font-weight-five">STEP ' . radical_number_to_word(($key + 1)) . '</span>',
            'title' => $step['heading'],
            'text' => $step['content'],
            'link' => $step['link'],
            'image_align' => $key % 2 === 0 ? 'right' : 'left',
          ]);
        endforeach; ?>
      </section>
    <?php endif; ?>
    <?php if ($intro = get_field('how_to_use')) :
      get_template_part('template-parts/modules/rewards/intro', null, [
        'divider_text' => $intro['divider_text'],
        'image' => $intro['image'],
        'heading' => $intro['heading'] ?? null,
        'content' => $intro['content'],
      ]);
    endif; ?>
    <?php if ($using_points = get_field('using_points')) :
      get_template_part('template-parts/modules/rewards/ways-to-earn', null, [
        'divider_text' => 'Using Points',
        'items' => $using_points['items'],
      ]);
    endif; ?>
    <?php get_template_part('template-parts/modules/rewards/faqs'); ?>
    <div class="my-5"></div>
    <?php get_template_part('template-parts/content-cta-earn-points'); ?>
  <?php endwhile; ?>

<?php get_footer(); ?>
