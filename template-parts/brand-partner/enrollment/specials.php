<?php
global $general_settings;
?>
<section class="specials-section">
	<div class="starter-specials-wrap">
		<div class="starter-specials-title-row row mb-5">
			<div class="col text-center">
				<h3 id="starter-specials" class="section-title grey-text mb-3">Select Your Special Product(s)</h3>
				<p class="mb-0"><sup>*</sup>Special Products are only available during Enrollment.</p>
			</div>
		</div>
		<div class="specials-section-products d-flex justify-content-center align-items-center flex-wrap mb-5">
			<?php if ($enrollment_specials_product_cat_value = get_field('enrollment_specials_product_cat_value', 'option')) : ?>
				<?php
				$args = [
					'post_type' => 'product',
					'posts_per_page' => -1,
					'tax_query' => [
						[
							'taxonomy' => 'product_cat',
							'field' => 'ID',
							'terms' => $enrollment_specials_product_cat_value,
						],
						[
							'taxonomy' => 'product_visibility',
							'field' => 'name',
							'terms' => ['exclude-from-search', 'exclude-from-catalog'],
							'operator' => 'AND',
						],
					],
				];
				$products = get_posts($args);
				?>
				<?php if ($products && count($products)) : ?>
					<?php foreach ($products as $product) : ?>
						<div class="col-6 col-lg-3 mb-4 mb-lg-0">
							<?php get_template_part('template-parts/brand-partner/enrollment/special-product', null, ['product' => $product]); ?>
						</div>
					<?php endforeach; ?>
				<?php else : ?>
					<p>No products found</p>
				<?php endif; ?>
			<?php else : ?>
				<p>No chosen enrollment specials product category.</p>
			<?php endif; ?>
		</div>
	</div>
</section>
