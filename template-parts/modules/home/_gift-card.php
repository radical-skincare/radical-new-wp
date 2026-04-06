<?php /*
Create our Home page Gift card section like other home page sections:

Image: https://radicalskincare.com/wp-content/uploads/2026/01/give-the-gift-of-healthy-skin.jpg

Heading: Virtual Gift Card
description:
An virtual gift card from Radical Skin Care.
price options:
$25-$250

Learn more link: https://radicalskincare.com/products/virtual-gift-card/
*/ ?>
<?php if ($gift_card = get_field('gift_card')) : ?>
  <section id="gift-card" class="gift-card bg-lightestgray pb-5 pb-lg-0" style="position: relative;">
    <div class="container-fluid px-0">
      <?php if ($image = $gift_card['image']) : ?>
        <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt'] ?? 'Gift Card'); ?>" class="gift-card_img"/>
      <?php endif; ?>
      <div class="row justify-content-center gift-card_row-card">
        <div class="col-lg-6 col-xl-4">
          <div class="px-3">
            <div class="card gift-card_card">
              <div class="card-body text-center">
                <?php if ($subheading = $gift_card['subheading']) : ?>
                  <h2 class="label-two"><?php echo esc_html($subheading); ?></h2>
                <?php endif; ?>
                <?php if ($heading = $gift_card['heading']) : ?>
                  <div class="title-c">
                    <?php echo $heading; ?>
                  </div>
                <?php endif; ?>
                <?php if ($description = $gift_card['description']) : ?>
                  <p class="font-weight-light"><?php echo $description; ?></p>
                <?php endif; ?>
                <?php if ($price = $gift_card['price_options']) : ?>
                  <p class="font-weight-bold"><?php echo $price; ?></p>
                <?php endif; ?>
                <?php if (isset($gift_card['link'])) : ?>
                  <a href="<?php echo esc_url($gift_card['link']['url']); ?>" class="btn btn-darkergray" target="<?php echo esc_attr($gift_card['link']['target'] ?? '_self'); ?>"><?php echo esc_html($gift_card['link']['title']); ?></a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>
