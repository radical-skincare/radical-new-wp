<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package radical_skincare
 */

if ( ! function_exists( 'radical_skincare_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function radical_skincare_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
      /*
      TODO: FIX
			$categories_list = get_the_category();
			if ( $categories_list ) {
				echo '<div class="categories-badges-row row mb-3">
                <div class="col">Posted in: ';
                  foreach( $categories_list as $category ) {
                    echo '<a class="badge badge-pink mr-1" href="' . get_site_url() . '/blog/category/' . $category->slug . '" title="' . $category->cat_name . '">' . $category->cat_name . '</a>';
                  }
          echo '</div>
              </div>';
			}
      */

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tags();
			if ( $tags_list ) {
				echo '<div class="tags-badges-row row mb-3"><div class="col">Tagged: ';
					foreach( $tags_list as $tag ) {
						// nav-item
							echo '<a class="badge badge-pink mr-1" href="' . get_site_url() . '/tag/' . $tag->slug . '" title="' . $tag->name . '">' . $tag->name . '</a>';
					}
				echo '</div></div>';
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'radical-skin-care' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'radical-skin-care' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'radical_skincare_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function radical_skincare_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
			<?php
			the_post_thumbnail( 'post-thumbnail', array(
				'alt' => the_title_attribute( array(
					'echo' => false,
				) ),
			) );
			?>
		</a>

		<?php
		endif; // End is_singular().
	}
endif;



if ( ! function_exists( 'radical_skincare_post_navigation' ) ) {

	function radical_skincare_post_navigation() { ?>

		<div id="post-navigation" class="row pt-3 mt-3 mb-3">
			<?php if( get_previous_post(true) ) { ?>
				<div class="col col-previous text-left">
					<?php previous_post_link('%link',"<p style='font-style: italic'>Previous Post</p>", true); ?>
				</div>
			<?php }
			if( get_next_post(true) ) { ?>
				<div class="col col-next text-right" >
					<?php next_post_link('%link',"<p style='font-style: italic'>Next Post</p>", true); ?>
				</div>
			<?php } ?>
		</div>

	<?php }	
}
