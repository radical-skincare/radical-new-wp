<section id="page-header" class="page-header bg-lightgray py-5">
  <div class="container">
    <div class="row">
      <div class="col text-center">
        <h2 class="mb-0">
          <?php if (is_404()) : ?>
            404 - Page Not Found
          <?php elseif (is_home()) : ?>
            Radical Blog
          <?php else : ?>
            <?php the_title(); ?>
          <?php endif; ?>
        </h2>
        <?php if ($sub_title = get_field('sub_title')) : ?>
          <h4 class="grey-text mb-0">
            <?php echo $sub_title; ?>
          </h4>
        <?php endif; ?>
        <?php if (is_home()) : ?>
          <p class="mb-0">Advice and tips from bloggers on how to live a more radical life.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
