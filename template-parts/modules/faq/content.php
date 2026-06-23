<?php
$faq_search = (isset($_GET['search'])) ? $_GET['search'] : '';
?>
<style>
.highlight {
  background-color: yellow;
  padding: 0.125rem;
}
.accordion-item_title .highlight {
  font-family: "orpheuspro", serif !important;
}
</style>
<section id="faq-content" class="py-5">
  <div class="container">
    <div class="row">
      <div class="col">
        <?php if (have_rows('faq_groups')) : ?>
          <div class="position-relative">
            <div class="form-outline">
              <label for="search-faq">Filter questions</label>
              <input id="search-faq" name="search-faq" type="text" class="form-control" value="<?php echo esc_attr($faq_search); ?>"/>
            </div>
            <button id="clear-input" class="btn btn-transparent" style="display: none; height: 3.5rem;">
              <i class="fa fa-times" aria-hidden="true"></i>
              <span class="sr-only">Clear Input</span>
            </button>
          </div>
          <?php while( have_rows('faq_groups') ) : the_row(); ?>
            <h3 class="mb-3 mt-4 title-c"><?php echo get_sub_field('group_label'); ?></h3>
            <?php if (have_rows('group_faq') ) : ?>
              <?php
              $faqs = [];
              ?>
              <?php while (have_rows('group_faq')) : ?>
                <?php
                the_row();
                $faqs[] = [
                  'title' => get_sub_field('question'),
                  'content' => get_sub_field('answer')
                ];
                ?>
              <?php endwhile; ?>
              <?php
              get_template_part('template-parts/modules/flex/accordion-list', null, [
                'id' => 'faq-accordion',
                'items' => $faqs,
              ]);
              ?>
            <?php endif; ?>
          <?php endwhile; ?>
          <div id="results-faq" class="alert alert-warning" style="display: none;">
            <p class="m-0"><span class="fa fa-exclamation-circle"></span> No results found, try something else.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
