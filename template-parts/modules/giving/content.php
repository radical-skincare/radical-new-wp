<?php
$site_url = get_site_url();
?>
<section id="giving-content" class="mb-5">
  <div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">
      <?php the_content(); ?>
      <?php echo wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'flavor') . '</p>', 'after' => '</nav>']); ?>
    </div>
  </div>
</section>
<section id="donate" class="pb-4 mb-5">
  <div class="row">
    <div class="col text-center">
      <a href="https://unstoppablefoundation.org/radical" class="btn btn-dark" target="_blank">Click Here to Donate!</a>
    </div>
  </div>
</section>
<?php if ($images = get_field('images')) : ?>
  <section class="mb-5">
    <div class="row justify-content-center mb-lg-5">
      <div class="col-xl-8">
        <div class="row">
          <?php foreach ($images as $image) : ?>
            <div class="col-lg-4 mb-3 mb-lg-0">
              <div class="card">
                <img src="<?php echo esc_url($image['image']['url']); ?>" alt="Giving" class="card-img w-100" />
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <?php if ($below_images = get_field('below_images')) : ?>
      <div class="row">
        <div class="col text-center">
          <p><?php echo $below_images; ?></p>
        </div>
      </div>
    <?php endif; ?>
  </section>
<?php endif; ?>
<?php if ($by_giving_youre_creating_section = get_field('by_giving_youre_creating_section')) : ?>
  <?php
  $img = $by_giving_youre_creating_section['image'];
  $title = $by_giving_youre_creating_section['heading'];
  $text = $by_giving_youre_creating_section['text'];
  $image_align = 'left';
  get_template_part('template-parts/modules/flex/image-card');
  ?>
<?php endif; ?>
