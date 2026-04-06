
<section id="about" class="mb-4">
  <div class="container my-5">
    <?php if ($about_section = get_field('about_section')) : ?>
      <div class="row align-items-center">
        <div class="col-lg-4">
          <h3 class="text-uppercase fs-1x mb-3">What makes it radical</h3>
          <?php if (isset($about_section['title'])) : ?>
            <div class="h2 fs-1.5x"><?php echo esc_html($about_section['title']); ?></div>
          <?php endif; ?>
          <?php if (isset($about_section['intro'])) : ?>
            <p class="text-darkgray pl-3 border-left ml-5"><?php echo esc_html($about_section['intro']); ?></p>
          <?php endif; ?>
        </div>
        <?php if (isset($about_section['image']) && $about_section['image']) : ?>
          <div class="col-lg-8">
            <img src="<?php echo esc_html($about_section['image']['url']); ?>" alt="<?php echo esc_html($about_section['image']['alt']); ?>" class="rounded w-100"/>
          </div>
        <?php endif; ?>
      </div>
    <?php elseif ($current_user_is_admin) : ?>
      <div class="alert alert-danger mx-auto" style="width: fit-content;">
        <p class="mb-0">Product about info missing. Edit Product and add missing details.</p>
      </div>
    <?php endif; ?>
  </div>
</section>
