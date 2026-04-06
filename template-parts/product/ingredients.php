
<section id="ingredients" class="my-5">
  <div class="container">
    <h3 class="text-center fs-1.5x mb-3">Featured Ingredients</h3>
    <?php if ($ingredients = get_field('ingredients')) : ?>
      <?php get_template_part('template-parts/modules/flex/accordion-list', null, [
        'id' => 'ingredients-faq',
        'items' => $ingredients,
      ]); ?>
    <?php elseif ($current_user_is_admin) : ?>
      <div class="alert alert-danger mx-auto" style="width: fit-content;">
        <p class="mb-0">Product ingredients info missing. Edit Product and add missing details.</p>
      </div>
    <?php endif; ?>
    <?php if ($full_ingredients_list = get_field('full_ingredients_list')) : ?>
      <button type="button" class="btn btn-outline-dark d-block mx-auto" data-toggle="modal" data-target="#fullIngredientsListModal">View Full Ingredients List</button>
      <div class="modal fade" id="fullIngredientsListModal" tabindex="-1" role="dialog" aria-labelledby="fullIngredientsListModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="fullIngredientsListModalLabel">Full Ingredients List</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="top: 1.25rem; right: 1rem;">
                <i class="fa fa-close" aria-hidden="true"></i>
              </button>
            </div>
            <div class="modal-body">
              <?php echo $full_ingredients_list; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>
