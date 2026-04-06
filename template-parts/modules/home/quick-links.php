<?php if ($quick_links = get_field('quick_links')) : ?>
  <section id="quick-links" class="quick-links py-5">
    <div class="container">
      <div class="row justify-content-center mb-5">
        <div class="col-auto">
          <div class="text-separator" style="width: 156px;">
            <div class="text-separator_line"></div>
            <h2 class="text-separator_inner-text fs-0.75x">QUICK LINKS</h2>
            <div class="text-separator_line"></div>
          </div>
        </div>
      </div>
      <?php if ($cards = $quick_links['cards']) : ?>
        <div class="row">
          <?php foreach($cards as $quick_link) : ?>
            <div class="col-6 col-lg-3 mb-3 mb-lg-0">
              <div class="card card-quick-link">
                <?php if ($img = $quick_link['image']) : ?>
                  <img src="<?php echo esc_url($img['url']); ?>" alt="<?php echo esc_attr($img['alt']); ?>"/>
                <?php endif; ?>
                <?php if ($link = $quick_link['link']) : ?>
                  <a href="<?php echo esc_url($link['url']); ?>" class="btn btn-white" <?php echo (isset($link['target'])) ? 'target="' . esc_attr($link['target']) . '"' : ''; ?>>
                    <?php echo $link['title']; ?>
                  </a>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>
<?php endif; ?>
