<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 6.1.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$site_url = get_site_url();
$essentials_collection_product_id = get_field('essentials_collection_product_id', 'option');
$sweetheart = get_field('sweetheart');
$is_sweetheart = (!is_null($sweetheart) && isset($sweetheart['enable']) && $sweetheart['enable']);
$post_id = $product->get_id();
$attribute_keys  = array_keys( $attributes );
$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

$can_access_vip_product = radical_can_access_vip_product_product_cat($post_id);
do_action( 'woocommerce_before_add_to_cart_form' );
?>
<?php if (radical_cannot_access_brand_partner_product($post_id)) : ?>
  <?php get_template_part('template-parts/components/brand-partner-exclusive'); ?>
  <?php
    return;
  ?>
<?php endif; ?>
<?php if (!$can_access_vip_product) : ?>
  <?php get_template_part('template-parts/components/active-subscriber-restricted'); ?>
  <?php
    return;
  ?>
<?php endif; ?>
<?php if ($post_id !== $essentials_collection_product_id) : ?>
  <style>
  .single-product .variations {
    display: table !important;
  }
  .single-product form.variations_form button[type=submit] {
    display: block !important;
  }
  .single_variation_wrap #loadMore {
    display: none;
  }
  .variations-options-container * {
    outline: none !important;
  }
  .variations-option {
    flex-grow : 1;
  }
  .variations-option input:checked+span, .variations-option span:hover {
    background-color: rgba(178, 7, 56, 0.25);
    box-shadow: 0 0 0 2px #b20738 inset;
  }
  .variations-option span {
    box-shadow: 0 0 0 1px #eaeaea inset;
    color: #000;
    cursor: pointer;
    height: 3.5rem;
    line-height: 1.2;
    padding: 0.25rem;
    text-align: center;
    transition: .3s;
    width: 100%;
    border-radius: 0.625em;
  }
  </style>
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    // Handle variation pill button selection
    const pillRadios = document.querySelectorAll('.variation-pill-radio');

    pillRadios.forEach(function(radio) {
      radio.addEventListener('change', function() {
        if (this.checked) {
          const attributeName = this.getAttribute('data-attribute_name');
          const attributeValue = this.getAttribute('data-attribute_value');

          const select = document.querySelector('select[name="attribute_' + sanitizeName(attributeName) + '"]');
          if (select) {
            select.value = attributeValue;

            const event = new Event('change', { bubbles: true });
            select.dispatchEvent(event);
          }
        }
      });
    });

    const selects = document.querySelectorAll('table.variations select');
    selects.forEach(function(select) {
      select.addEventListener('change', function() {
        const nameMatch = this.name.match(/attribute_(.*)/);
        if (nameMatch) {
          const sanitizedName = nameMatch[1];
          const value = this.value;

          const radio = document.querySelector('input[name="pill_attribute_' + sanitizedName + '"][data-attribute_value="' + value + '"]');
          if (radio) {
            radio.checked = true;
          }
        }
      });
    });

    function sanitizeName(name) {
      return name.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
    }
  });
  </script>
<?php endif; ?>
<form class="<?php echo $is_sweetheart ? 'is-sweetheart' : ''; ?> variations_form cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock">
      <?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'woocommerce' ) ) ); ?>
    </p>
	<?php else : ?>
		<!-- Hidden WooCommerce select dropdowns -->
		<table class="variations" cellspacing="0" role="presentation" style="display: none !important;">
			<tbody>
				<?php foreach ( $attributes as $attribute_name => $options ) : ?>
					<tr>
						<th class="label">
              <label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>">
                <?php echo wc_attribute_label( $attribute_name ); ?>
              </label>
            </th>
						<td class="value">
							<?php
              wc_dropdown_variation_attribute_options(
                array(
                  'options'   => $options,
                  'attribute' => $attribute_name,
                  'product'   => $product,
                )
              );
              echo end( $attribute_keys ) === $attribute_name ? wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'woocommerce' ) . '</a>' ) ) : '';
							?>
						</td>
					</tr>
        <?php endforeach; ?>
			</tbody>
		</table>

		<!-- Pill-style variations display -->
		<div class="variations" id="variations" role="presentation">
      <?php
      $default_attributes = $product->get_default_attributes();
      ?>
      <?php foreach ( $attributes as $attribute_name => $options ) : ?>
        <?php
        $sanitized_name = sanitize_title( $attribute_name );
        ?>
        <div class="form-group">
          <label for="<?php echo esc_attr($sanitized_name); ?>" class="d-block text-center">
            <?php echo wc_attribute_label( $attribute_name ); ?>
          </label>

          <!-- Pill-style buttons -->
          <div class="d-flex variations-options-container row">
            <?php foreach ( $options as $option ) : ?>
              <?php
              $sanitized_option = str_replace(['(', '%', ')', '$'], [''], $option);
              $input_id = $sanitized_name . '_' . $sanitized_option;
              $is_selected = isset($default_attributes[$attribute_name]) && $default_attributes[$attribute_name] === $option;
              ?>
              <label for="<?php echo esc_attr($input_id); ?>" class="text-center col-6 variations-option">
                <input class="attribute_option d-none variation-pill-radio"
                  type="radio"
                  value="<?php echo esc_attr($option); ?>"
                  id="<?php echo esc_attr($input_id); ?>"
                  name="pill_attribute_<?php echo esc_attr($sanitized_name); ?>"
                  data-attribute_name="<?php echo esc_attr($attribute_name); ?>"
                  data-attribute_value="<?php echo esc_attr($option); ?>"
                  <?php echo $is_selected ? 'checked' : ''; ?>>
                  <span class="d-flex align-items-center justify-content-center"><?php echo esc_html($option); ?></span>
              </label>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
		</div>
    <?php if ($post_id === $essentials_collection_product_id) : ?>
      <div id="my-customized-collection" class="p-3 mb-3">
        <div class="d-flex justify-content-between text-right mb-2">
          <div>My Essentials Collection</div>
          <a href="javascript:void(0)" class="edit link-underline link-underline_darker-gray" data-toggle="modal" data-target="#customizeCollectionModal">
            Edit
          </a>
        </div>
        <div class="row">
          <div class="col-6 col-lg-3 mb-2 mb-lg-0">
            <div class="selected-variant">
              <img src="<?php echo esc_url($site_url); ?>/wp-content/uploads/2018/03/HydratingCleanser.jpg" alt="Hydrating Cleanser" class="selected-variant_img"/>
              <div class="selected-variant_title">Hydrating Cleanser</div>
            </div>
          </div>
          <div class="col-6 col-lg-3 mb-2 mb-lg-0">
            <div class="selected-variant">
              <img src="<?php echo esc_url($site_url); ?>/wp-content/uploads/2022/08/age-defying-exfoliating-pads.jpg" alt="Youth Infusion Serum" class="selected-variant_img"/>
              <div class="selected-variant_title">Age-Defying Exfoliating Pads</div>
            </div>
          </div>
          <div class="col-6 col-lg-3">
            <div class="selected-variant selected-variant_step-3">
            </div>
          </div>
          <div class="col-6 col-lg-3">
            <div class="selected-variant selected-variant_step-4">
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
    <?php if (!is_null($sweetheart) && isset($sweetheart['enable']) && $sweetheart['enable']) : ?>
      <style>
      @media (max-width: 991px) {
        button.single_add_to_cart_button {
          border-radius: 0 !important;
        }
      }
      .single_variation_wrap {
        margin-top: 1rem;
      }
      @media (min-width: 992px) {
        .single_variation_wrap.hide {
          display: none !important;
        }
      }
      </style>
      <a href="#sweetheart" class="btn btn-outline-darkergray" style="text-decoration: none;">Customize My Collection</a>
    <?php endif; ?>
		<?php do_action( 'woocommerce_after_variations_table' ); ?>

		<div class="single_variation_wrap">
			<?php
      /**
       * Hook: woocommerce_before_single_variation.
       */
      do_action( 'woocommerce_before_single_variation' );

      /**
       * Hook: woocommerce_single_variation. Used to output the cart button and placeholder for variation data.
       *
       * @since 2.4.0
       * @hooked woocommerce_single_variation - 10 Empty div for variation data.
       * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
       */
      do_action( 'woocommerce_single_variation' );

      /**
       * Hook: woocommerce_after_single_variation.
       */
      do_action( 'woocommerce_after_single_variation' );
			?>
		</div>
  <?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php if ($post_id === $essentials_collection_product_id) : ?>
  <style>
  #customizeCollectionModal .badge {
    position: absolute;
    z-index: 1;
    top: -0.75rem;
    right: 0;
  }
  </style>
  <button type="button" class="btn btn-darkergray" data-toggle="modal" data-target="#customizeCollectionModal">
    Customize Your Collection
  </button>
  <div class="modal fade" id="customizeCollectionModal" tabindex="-1" role="dialog" aria-labelledby="customizeCollectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header text-center">
          <h5 class="d-block text-center modal-title w-100" id="exampleModalLabel">Customize Your Collection</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="top: 1rem; right: 1rem;">
            <i class="fa fa-close" aria-hidden="true"></i>
          </button>
        </div>
        <div class="modal-body"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-pink" data-dismiss="modal">Done</button>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
