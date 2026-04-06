<?php if ($giving_back = get_field('giving_back')) : ?>
  <section id="giving-back" class="giving-back bg-lightestgray pb-5 pb-lg-0" style="position: relative;">
    <div class="container-fluid px-0">
      <img src="<?php echo esc_url($giving_back['image']['url']); ?>" alt="Giving Back Image" class="giving-back_img"/>
      <div class="row justify-content-center giving-back_row-card">
        <div class="col-lg-6 col-xl-4">
          <div class="px-3">
            <div class="card giving-back_card">
              <div class="card-body text-center">
                <h2 class="label-two">Giving Back</h2>
                <div class="title-c">
                  <?php echo $giving_back['heading']; ?>
                </div>
                <p class="font-weight-light"><?php echo $giving_back['content']; ?></p>
                <?php if (isset($giving_back['link'])) : ?>
                  <a href="<?php echo esc_url($giving_back['link']['url']); ?>" class="btn btn-darkergray" target="<?php echo esc_attr($giving_back['link']['target']); ?>"><?php echo esc_html($giving_back['link']['title']); ?></a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>
