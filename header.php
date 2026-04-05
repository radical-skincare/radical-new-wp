<!doctype html>
<html <?php echo get_language_attributes(); ?>>
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <?php wp_head(); ?>
  <?php if (function_exists('get_field')) : ?>
    <?php echo get_field('head_scripts', 'option'); ?>
  <?php endif; ?>
</head>
<body <?php body_class('woocommerce'); ?>
  <?php if (is_singular('product')) : ?>
    data-spy="scroll" data-target=".scroll-spy-navbar" data-offset="224"
  <?php endif; ?>
>
  <?php if (function_exists('get_field')) : ?>
    <?php echo get_field('below_body_script', 'option'); ?>
  <?php endif; ?>
  <?php do_action('get_header'); ?>
  <?php get_template_part('template-parts/header/header'); ?>
  <main role="document">
