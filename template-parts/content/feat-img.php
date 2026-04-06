<?php
$feat_img_url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
?>
<div class="feat-img-wrapper row" style="background-image: url('<?php echo esc_url($feat_img_url); ?>') !important;">
	<div class="container clearfix">
		<div class="row">
			<div class="feat-img-title-wrapper text-center">
				<h2><?php the_title(); ?></h2>
  				<?php if( function_exists('bcn_display') ) : ?>
  					<?php bcn_display(); ?>
  				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
