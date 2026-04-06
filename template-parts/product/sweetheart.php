<?php
global $product;
$attributes = $product->get_variation_attributes();
?>
<style>
#sweetheart .list-group-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  column-gap: 0.5rem;
  line-height: 1.25rem;
  text-align: left;
  cursor: pointer;
}
#sweetheart .list-group-item > div {
  display: flex;
  align-items: center;
  column-gap: 0.5rem;
}
#sweetheart .list-group-item .fa-check-square-o {
  display: none;
}
#sweetheart .list-group-item.selected .fa-square-o {
  display: none;
}
#sweetheart .list-group-item.selected .fa-check-square-o {
  display: block;
}
</style>
<?php
$attribute_images = [
  'accessory' => [
    [
      'name' => 'Natural Shimmer Highlighter - Sweet Peony',
      'img_url' => 'https://radicalskincare.com/wp-content/uploads/2023/10/sweet.jpg',
      'price' => 24,
    ],
    [
      'name' => 'GuaSha Glow Sculptor',
      'img_url' => 'https://radicalskincare.com/wp-content/uploads/2023/10/guasha-glow-sculptor.jpg',
      'price' => 15,
    ],
    [
      'name' => 'Hyaluronic Antixodant Micro-Needle Eye Patch',
      'img_url' => 'https://radicalskincare.com/wp-content/uploads/2023/10/hyaluronic-antixodant-micro-needle-eye-patch1.jpg',
      'price' => 10,
    ],
    [
      'name' => 'Lip Luster Lip Liner - Espresso',
      'img_url' => 'https://radicalskincare.com/wp-content/uploads/2023/10/Lip-Luster-Lip-Liner_Espresso.jpg',
      'price' => 18,
    ],
    [
      'name' => 'Lip Luster Lip Liner - Rose Blush',
      'img_url' => 'https://radicalskincare.com/wp-content/uploads/2023/10/Lip-Luster-Lip-Liner_Rose.jpg',
      'price' => 18,
    ],
    [
      'name' => 'Collagen Renewal Eye Mask',
      'img_url' => 'https://radicalskincare.com/wp-content/uploads/2023/10/collagen_eye_gel_mask_2023_holiday_collection.jpg',
      'price' => 49,
    ],
    [
      'name' => 'Gentle Silk Sleep Mask',
      'img_url' => 'https://radicalskincare.com/wp-content/uploads/2023/10/Sleep-Mask.jpg',
      'price' => 18,
    ],
    [
      'name' => 'Lip Recovery Mask',
      'img_url' => 'https://radicalskincare.com/wp-content/uploads/2023/10/Lip-Recovery-Mask.jpg',
      'price' => 28,
    ],
    [
      'name' => 'Lip Luster Hyaluronic Lip Gloss',
      'img_url' => 'https://radicalskincare.com/wp-content/uploads/2023/06/lip-luster-hyaluronic-infused-rose-lip-gloss_4.jpg',
      'price' => 35,
    ],
  ],
  'serum' => [
    [
      'name' => 'Rejuvafirm CBD Facial Oil 30mL',
      'img_url' => 'https://radicalskincare.com/wp-content/uploads/2018/08/4.png',
      'price' => 145,
    ],
    [
      'name' => 'Youth Infusion Serum 15mL',
      'img_url' => 'https://radicalskincare.com/wp-content/uploads/2018/03/youth-infusion-serum.jpg',
      'price' => 85,
    ],
    [
      'name' => 'Advanced Peptide Antioxidant Serum 15mL',
      'img_url' => 'https://radicalskincare.com/wp-content/uploads/2018/03/advanced-peptide-antioxidant-serum.jpg',
      'price' => 95,
    ],
    [
      'name' => 'Rejuvafirm Resurfacing Serum 30mL',
      'img_url' => 'https://radicalskincare.com/wp-content/uploads/2021/05/1.jpg',
      'price' => 150,
    ],
    [
      'name' => 'Perfection Fluid Serum 30mL',
      'img_url' => 'https://radicalskincare.com/wp-content/uploads/2018/03/Radical-Perfection-Fluid.jpg',
      'price' => 75,
    ],
    [
      'name' => 'Multi Brightening Serum 30mL',
      'img_url' => 'https://radicalskincare.com/wp-content/uploads/2018/06/MultiBrighteningSerum.jpg',
      'price' => 150,
    ],
  ],
  'skincare' => [
    [
      'name' => 'Age-Defying Exfoliating Pads 15ct',
      'img_url' => 'https://radicalskincare.com/wp-content/uploads/2018/03/age-defying-exfoliating-pads-e1560032406714.jpg',
      'price' => 20,
    ],
    [
      'name' => 'Hydrating Cleanser',
      'img_url' => 'https://radicalskincare.com/wp-content/uploads/2018/03/HydratingCleanser.jpg',
      'price' => 45,
    ],
    [
      'name' => 'Anti-Aging Restorative Moisture 15mL',
      'img_url' => 'https://radicalskincare.com/wp-content/uploads/2019/10/RestorativeMoisture-scaled.jpg',
      'price' => 50,
    ],
    [
      'name' => 'Skin Perfecting Screen SPF 30 40mL',
      'img_url' => 'https://radicalskincare.com/wp-content/uploads/2018/03/3.png',
      'price' => 50,
    ],
    [
      'name' => 'Express Delivery Enzyme Peel 50mL',
      'img_url' => 'https://radicalskincare.com/wp-content/uploads/2018/11/Express-Delivery-Enzyme-Peel-1.jpg',
      'price' => 45,
    ],
    [
      'name' => 'Detox Charcoal Enzyme Peel 50mL',
      'img_url' => 'https://radicalskincare.com/wp-content/uploads/2019/10/RS_Detox_Peel_MI_Closed_Lid.jpeg',
      'price' => 45,
    ],
    [
      'name' => 'Express Delivery Enzyme Body Peel 178mL',
      'img_url' => 'https://radicalskincare.com/wp-content/uploads/2018/08/Express-Delivery-Enzyme-Body-Peel-e1535560110105.jpg',
      'price' => 49,
    ],
    [
      'name' => 'Hand and Nail Multi Repair',
      'img_url' => 'https://radicalskincare.com/wp-content/uploads/2018/03/Hand-And-Nail-Multi-Repair.jpg',
      'price' => 55,
    ],
  ],
];
?>
<section id="sweetheart">
  <div class="container bg-lightestgray py-5 px-lg-5 rounded">
    <div class="row mb-4">
      <div class="col text-center">
        <h2 class="mb-0">Build Your Bundle</h2>
      </div>
    </div>
    <?php if ($attributes) : ?>
      <div class="row mb-4" style="row-gap: 1rem;">
        <?php foreach ( $attributes as $attribute_name => $options ) : ?>
          <?php
          $attribute_name_lowered = strtolower($attribute_name);
          ?>
          <div class="col">
            <h3 class="text-center mb-3"><?php echo wc_attribute_label( $attribute_name ); ?></h3>
            <?php if ($img_url = $attribute_images[$attribute_name_lowered][0]['img_url']) { ?>
              <img id="sweetheart-attribute-img_<?php echo $attribute_name_lowered; ?>" class="sweetheart-attribute-img border rounded mb-3" src="<?php echo $img_url; ?>" alt="Selected Attribute Image"/>
            <?php } ?>
            <ul class="list-group">
              <?php foreach ($options as $key => $option) : ?>
                <?php
                $price = $attribute_images[$attribute_name_lowered][$key]['price'];
                ?>
                <li class="list-group-item list-group-item_sweetheart" option-key="<?php echo esc_html($key); ?>" option-attribute="<?php echo strtolower($attribute_name); ?>" option-img="<?php echo $attribute_images[$attribute_name_lowered][$key]['img_url']; ?>" option-price="<?php echo esc_html($price); ?>">
                  <div>
                    <i class="fa fa-square-o" aria-hidden="true"></i>
                    <i class="fa fa-check-square-o" aria-hidden="true"></i>
                    <span class="list-group-item_name"><?php echo $option; ?></span>
                  </div>
                  <span>$<?php echo esc_html($price); ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <div class="row">
      <div class="col text-center">
        <span class="h2 d-block mb-3">Discount 15%</span>
        <p class="select-bundle-text">Select your bundle items to view total discounted pricing.</p>
        <p class="fs-1.5x mb-3">
          <strike id="sweetheart_original-price" style="opacity: 0.5; display: none;"></strike>
          <strong id="sweetheart_sale-price" style="display: none;"></strong>
        </p>
        <div>
          <button type="button" class="btn btn-darkgray" id="sweetheart-add-to-cart" disabled>Add To Cart</button>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
(function($) {
$(document).ready(function() {
const Sweetheart = {
  onLoad: function() {
    this.calcPrice()
    this.onClickAttribute()
    $('#sweetheart-add-to-cart').on('click', function() {
      $('.variations_form.cart button[type="submit"]').trigger('click')
    })
    $('form.is-sweetheart .variations select').each(function() {
      const val = $(this).val()
      $('.list-group-item_name').each(function() {
        if (val === $(this).text().trim()) {
          $(this).parent().parent().addClass('selected')
        }
      })
    })
    if ($('#sweetheart .list-group-item.selected').length === 3) {
      $('#sweetheart-add-to-cart').prop('disabled', false).removeAttr('disabled')
    }
    $('form.is-sweetheart .variations select').on('change', function() {
      const attribute_name = $(this).data('attribute_name').toLowerCase().replace('attribute_', '')
      $(`#sweetheart .list-group-item[option-attribute="${attribute_name}"].selected`).removeClass('selected')
      const val = $(this).val()
      $('.list-group-item_name').each(function() {
        if (val === $(this).text().trim()) {
          $(this).parent().parent().addClass('selected')
        }
      })
      Sweetheart.calcPrice()
      Sweetheart.showPriceEnableSweetheartCart()
    })
  },
  calcPrice: function() {
    let price = 0
    $('.list-group-item.selected').each(function(index) {
      price += parseFloat($(this).attr('option-price'))
      const option_text = $(this).find('.list-group-item_name').text()
      const option_attribute = $(this).attr('option-attribute')
      const option_img = $(this).attr('option-img')
      $(`#selected-attribute-card_${option_attribute}`).html(`<div class="card-body"><img src="${option_img}" alt="${option_text}"/>${option_text}</div>`)
    })
    $('#sweetheart_original-price').text('$' + price)
    $('#sweetheart_sale-price').text('$' + ((price - (price * 0.15))).toFixed(2))
  },
  onClickAttribute: function() {
    $('#sweetheart .list-group-item').on('click', function() {
      $('#sweetheart-add-to-cart').prop('disabled', true)
      const option_text = $(this).find('.list-group-item_name').text()
      const option_attribute = $(this).attr('option-attribute')
      const option_key = parseInt($(this).attr('option-key'))
      const new_val = $(`#${option_attribute} option:nth-child(${(option_key + 2)})`).val()
      $(`#${option_attribute}`).val(new_val)
      const option_img = $(this).attr('option-img')
      $(`#sweetheart-attribute-img_${option_attribute}`).attr('src', option_img)
      $(`#sweetheart .list-group-item[option-attribute="${option_attribute}"]`).removeClass('selected')
      $(this).addClass('selected')
      $('#my-sweetheart-collection_attributes').slideDown()
      Sweetheart.calcPrice()
      Sweetheart.showPriceEnableSweetheartCart()
      $(`[name="attribute_${option_attribute}"]`).trigger('change')
    })
  },
  showPriceEnableSweetheartCart: function() {
    if ($('#sweetheart .list-group-item.selected').length === 3) {
      $('.select-bundle-text').hide()
      $('#sweetheart_original-price, #sweetheart_sale-price').show()
      setTimeout(function() {
        $('#sweetheart-add-to-cart').prop('disabled', false).removeAttr('disabled')
      }, 1500)
    }
  },
}
Sweetheart.onLoad()
})
})(jQuery)
</script>
