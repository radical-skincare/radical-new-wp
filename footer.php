  </main>
  <?php get_template_part('template-parts/scroll-to-top'); ?>
  <?php do_action('get_footer'); ?>
  <?php get_template_part('template-parts/footer/footer'); ?>
  <?php get_template_part('template-parts/modal/quick-view'); ?>
  <?php get_template_part('template-parts/modal/sale'); ?>
  <?php get_template_part('template-parts/modal/email-capture'); ?>
  <?php if (function_exists('get_field')) : ?>
    <?php echo get_field('footer_scripts', 'option'); ?>
  <?php endif; ?>
  <?php wp_footer(); ?>
</body>
</html>
