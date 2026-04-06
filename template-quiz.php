<?php
/**
 * Template Name: Quiz
 */

get_header(); ?>

<?php
do_action('get_header', 'shop');
?>
<style>
body {
  margin: 0;
  margin-top: 82px;
}
@media (min-width: 992px) {
  body {
    margin-top: 120px;
  }
}
</style>
  <?php get_template_part('template-parts/content/page-header'); ?>
  <div class="container pb-5">
    <script defer="defer" src="https://radicalskincare.com/quiz/main.3cea5b9a.js"></script>
    <link href="https://radicalskincare.com/quiz/main.3a44aca7.css" rel="stylesheet"/>
    <div id="root"></div>
  </div>
<?php
do_action('get_footer', 'shop');
?>

<?php get_footer(); ?>
