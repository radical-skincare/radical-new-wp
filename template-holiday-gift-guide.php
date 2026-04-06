<?php
/**
 * Template Name: Holiday Gift Guide
 */

get_header(); ?>

<?php
do_action('get_header', 'shop');
?>
  <div class="container-fluid pb-5">
    <?php
    /**
     * Hook: woocommerce_before_main_content.
     *
     * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
     * @hooked woocommerce_breadcrumb - 20
     * @hooked WC_Structured_Data::generate_website_data() - 30
     */
    do_action('woocommerce_before_main_content');
    // $current_user_id = is_user_logged_in() ? get_current_user_id() : false;
    // $affiliate_status = $current_user_id ? get_user_meta($current_user_id, 'v_affiliate_status', true) : false;
    // $cannot_access_products = (!$affiliate_status || $affiliate_status !== 'active') ? true : false;
    $cannot_access_products = false;
    ?>
    <?php if ($cannot_access_products) : ?>
      <?php get_template_part('template-parts/components/brand-partner-exclusive'); ?>
    <?php else : ?>
      <div class="row">
        <div class="col">
          <?php
          do_action('woocommerce_before_shop_loop');
          ?>
        </div>
      </div>
      <?php
      $feat_img_url = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
      ?>
      <style>
      #holiday-gift-guide-banner {
        background-position: center;
        background-size: cover;
      }
      @media (max-width: 767px) {
        #holiday-gift-guide-banner::before {
          content: "";
          width: 100%;
          height: 100%;
          background-color: white;
          position: absolute;
          top: 0;
          left: 0;
          opacity: 0.25;
        }
      }
      [for="rangeInput"],
      #rangeInput {
        cursor: pointer;
      }
      </style>
      <div id="holiday-gift-guide-banner" class="row text-center py-10 py-lg-5 mb-5" <?php if ($feat_img_url) { ?>style="background-image: url('<?php echo esc_url($feat_img_url); ?>');"<?php } ?>>
        <div class="col-md-6 offset-md-3 col-lg-4 offset-lg-4">
          <h1 class="text-center mb-4">Holiday Gift Guide</h1>
          <form id="show-products-under-dollar-amount">
            <div class="form-group text-center">
              <label for="rangeInput">Show Products Under Dollar Amount</label>
              <input type="range" class="form-control-range" id="rangeInput" min="25" max="375" step="25" value="375"/>
              <div>
                <button id="btn-range-minus" type="button" class="btn btn-range-minus">
                  <span class="sr-only">Minus</span>
                  <i class="fa fa-minus" aria-hidden="true"></i>
                </button>
                <output class="mt-2" id="rangeOutput">$375</output>
                <button id="btn-range-plus" type="button" class="btn btn-range-plus" disabled>
                  <span class="sr-only">Plus</span>
                  <i class="fa fa-plus" aria-hidden="true"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <?php
      $order = get_field('products_order');
      $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'tax_query' => array(
          'relation' => 'OR',
          array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => 'holiday',
          ),
          array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => 'holiday-2',
          ),
        ),
        'orderby' => 'meta_value_num',
        'meta_key' => '_price',
        'order' => $order ? $order : 'DESC'
      );
      $min = isset($_GET['min']) ? $_GET['min'] : false;
      $max = isset($_GET['max']) ? $_GET['max'] : false;
      if ($min && $max) {
        $args['meta_query'] = array(
          array(
            'key' => '_price',
            'value' => array($min_price, $max_price),
            'type' => 'NUMERIC',
            'compare' => 'BETWEEN',
          ),
        );
      }
      $query = new WP_Query($args);
      ?>
      <?php if ($query->have_posts()) : ?>
        <div class="row">
          <?php while ($query->have_posts()) : $query->the_post(); ?>
            <?php
            $product = get_post();
            ?>
            <div class="col-lg-4 col-xl-3 mb-4">
              <?php get_template_part('template-parts/content', 'product'); ?>
            </div>
          <?php endwhile; ?>
        </div>
        <?php
        /**
         * woocommerce_after_shop_loop hook.
         *
         * @hooked woocommerce_pagination - 10
         */
        do_action('woocommerce_after_shop_loop');
        ?>
      <?php endif; ?>
      <?php
      /**
       * Hook: woocommerce_after_main_content.
       *
       * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
       */
      do_action('woocommerce_after_main_content');
      ?>
    <?php endif; ?>
  </div>
<script>
(function($) {
const HolidayGiftGuide = {
  onLoad: function() {
    if (!$('.template-holiday-gift-guide').length) {
      return
    }
    this.onRangeChange()
    this.onRangeIncOrDec()
  },
  onRangeChange: function() {
    const self = this
    const rangeInputEl = document.getElementById("rangeInput")
    rangeInputEl.addEventListener("input", function() {
      const rangeInput = document.getElementById('rangeInput')
      const rangeOutput = document.getElementById('rangeOutput')
      const dollarAmount = parseFloat(rangeInput.value)
      rangeOutput.textContent = `$${dollarAmount.toFixed(0)}`
      self.toggleProductsBasedOnPrice()
    })
  },
  toggleProductsBasedOnPrice: function() {
    const price = parseInt($('#rangeInput').val())
    $('.card-product').each(function() {
      const $product = $(this)
      const this_product_price = parseInt($product.find('.card-product_price .woocommerce-Price-amount').text().trim().replace('$', '').replace('$ ', '').trim())
      if (this_product_price < price) {
        $product.parent().show()
      } else {
        $product.parent().hide()
      }
    })
  },
  onRangeIncOrDec: function() {
    const self = this
    const $rangeInput = $('#rangeInput')
    $('#btn-range-minus').on('click', function() {
      const val = parseInt($rangeInput.val())
      const min = parseInt($rangeInput.attr('min'))
      if (val <= min) {
        $(this).prop('disabled', true)
        return
      }
      const new_val = val - parseInt($rangeInput.attr('step'))
      $rangeInput.val(new_val)
      self.triggerRangeOutput(new_val)
      $('#btn-range-plus').prop('disabled', false).removeAttr('disabled')
    })
    $('#btn-range-plus').on('click', function() {
      const val = parseInt($rangeInput.val())
      const max = parseInt($rangeInput.attr('max'))
      if (val >= max) {
        $(this).prop('disabled', true)
        return
      }
      const new_val = val + parseInt($rangeInput.attr('step'))
      $rangeInput.val(new_val)
      self.triggerRangeOutput(new_val)
      $('#btn-range-minus').prop('disabled', false).removeAttr('disabled')
    })
  },
  triggerRangeOutput: function(price) {
    $('#rangeOutput').text(`$${price.toFixed(0)}`)
    this.toggleProductsBasedOnPrice()
  }
}
$(document).ready(function() {
  HolidayGiftGuide.onLoad()
})
})(jQuery)
</script>
<?php
do_action('get_footer', 'shop');
?>

<?php get_footer(); ?>
