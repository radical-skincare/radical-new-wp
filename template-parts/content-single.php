<article <?php post_class(); ?>>
  <header>
    <h1 class="entry-title"><?php the_title(); ?></h1>
    <?php get_template_part('template-parts/entry-meta'); ?>
  </header>
  <div class="entry-content">
    <?php the_content(); ?>
  </div>
  <footer>
    <?php echo wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'flavor'), 'after' => '</p></nav>']); ?>
  </footer>
  <?php comments_template(); ?>
</article>
