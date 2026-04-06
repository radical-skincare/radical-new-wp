<style>
.hero {
  height: 50vh;
  height: calc(50vh - 82px);
  background-size: cover;
  background-position: center;
  background-image: url('<?php echo get_template_directory_uri() . '/assets/images/blog/hero.jpeg'; ?>');
}
@media (min-width: 992px) {
  .hero {
    height: calc(50vh - 120px);
  }
}
</style>
<section id="hero" class="hero d-flex flex-column justify-content-center align-items-center">
  <div class="text-white fs-2x fs-lg-3x mb-3 font-weight-normal text-underline_white">
    <?php if ( is_home() || is_category()) : ?>
      Radical Blog
    <?php else : ?>
      <?php the_title(); ?>
    <?php endif; ?>
  </div>
  <?php if ( is_home() ) : ?>
    <div class="text-white text-center font-weight-normal fs-1.5x fs-lg-2x mb-3">
      Advice and tips from bloggers on how <br class="d-none d-lg-block"/>to live a more radical life
    </div>
  <?php elseif( is_category() ) : ?>
    <nav class="text-white text-center font-weight-normal fs-1.5x fs-lg-2x mb-3">
      <a href="<?php echo esc_url(get_permalink( get_option( 'page_for_posts' ) )); ?>" class="text-white font-weight-normal fs-1.5x fs-lg-2x">Blogs</a> / <?php echo esc_html(get_the_category()[0]->name); ?>
    </nav>
  <?php endif; ?>
</section>
