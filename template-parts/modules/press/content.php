<?php
$posts_per_page = 12;
$current_page = 1;
$args = [
  'post_type' => 'press_item',
  'orderby' => 'date',
  'order' => 'DESC',
  'posts_per_page' => $posts_per_page,
];
$pressQuery = new WP_Query( $args );
?>
<div class="bg-lightest-gray py-5 px-large-5">
  <div class="container row mx-auto" id="press-container">
    <?php if ( $pressQuery->have_posts() ) : ?>
    <?php while ( $pressQuery->have_posts() ) : $pressQuery->the_post(); ?>
      <div class="col-12 col-lg-4 mb-4">
        <div class="h-100 card border-0">
          <div class="card-body d-flex flex-column justify-content-between">
            <h4 class="card-title"><?php the_title(); ?></h4>
            <div>
              <img src="<?php echo get_template_directory_uri() . '/assets/images/quote.svg'; ?>" alt="Quote" class="blockquote_img mb-3" style="height: 28px;"/>
              <blockquote><?php the_content(); ?></blockquote>
            </div>
            <div>
              <a role='button' data-toggle="modal" data-target="#press-model" data-image_url="<?php the_post_thumbnail_url(); ?>" data-image_alt="<?php the_title_attribute(); ?>" class="link-underline link-underline_darker-gray view-btn">View Press</a>
            </div>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    <?php endif; ?>
  </div>
  <div class="container row mx-auto">
    <div class="col-12 col-lg-4 mb-4 loader d-none">
      <div class="placeholder-item"></div>
    </div>
    <div class="col-12 col-lg-4 mb-4 loader d-none">
      <div class="placeholder-item"></div>
    </div>
    <div class="col-12 col-lg-4 mb-4 loader d-none">
      <div class="placeholder-item"></div>
    </div>
    <div class="col-12 load-more">
      <button class="btn btn-darkergray d-block mx-auto" id="load-more" data-post_per_page="<?php echo esc_attr($posts_per_page); ?>" data-current_page="<?php echo esc_attr($current_page); ?>" data-quote_image_src="<?php echo get_template_directory_uri() . '/assets/images/quote.svg'; ?>">Load More</button>
    </div>
  </div>
</div>
<div class="modal fade" id="press-model" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <button type="button" class="close d-block text-darker-gray text-right ml-auto mr-3 mt-1" data-dismiss="modal" aria-label="Close">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#333" class="bi bi-x-lg" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"/>
          <path fill-rule="evenodd" d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"/>
        </svg> <span style="font-size: 12px">Close</span>
      </button>
      <div class="modal-body">
        <img id="press-image" alt="Press Image" src="" class="w-100"/>
      </div>
    </div>
  </div>
</div>
