<?php
$site_url = get_site_url();
$blog_widgets = get_field('blog_widgets', 'option');
?>
<?php if ( have_rows('blog_widgets', 'option') ) : ?>
  <?php while ( have_rows('blog_widgets', 'option') ) : the_row(); ?>
    <?php
    $learn_more_link = get_sub_field('learn_more_link');
    $image = get_sub_field('image');
    ?>
    <div class="widget-item card mb-5">
      <a href="<?php echo esc_url($learn_more_link); ?>" title="<?php echo esc_attr($image['title']); ?>" class="p-3" >
        <img alt="<?php echo esc_attr($image['title']); ?>" src="<?php echo esc_url($image['url']); ?>" class="img-fluid w-100"/>
      </a>
      <?php if ( get_sub_field('show_learn_more_button') ) : ?>
        <div class="card-body" style="margin-top: -1rem;">
          <a href="<?php echo esc_url($learn_more_link); ?>" class="btn btn-outline-darkergray" title="View <?php echo esc_attr($image['title']); ?>">Learn More</a>
        </div>
      <?php endif; ?>
    </div>
  <?php endwhile; ?>
<?php endif; ?>
<div class="widget-item card mb-5 mt-4">
  <a href="<?php echo esc_url($site_url); ?>/trylacel/"  title="View Trylacel Technology">
    <img src="<?php echo esc_url($site_url); ?>/wp-content/uploads/2018/04/liz-and-rachel.jpg" class="img-fluid w-100 rounded-top" alt="The Radical System"/>
  </a>
  <div class="card-body">
    <h4 class="card-title">The Radical System</h4>
    <p>How The Regimen Works</p>
    <a href="<?php echo esc_url($site_url); ?>/trylacel/" class="btn btn-outline-darkergray" title="View Trylacel Technology" >Learn More</a>
  </div>
</div>
<div class="widget-item card mb-5">
  <a href="<?php echo esc_url($site_url); ?>/living/stories/share/" title="Share Your Story">
    <img alt="Share A Story" src="<?php echo esc_url($site_url); ?>/wp-content/uploads/2018/06/radical-blog.jpg" class="img-fluid w-100 rounded-top"/>
  </a>
  <div class="card-body">
    <h4 class="card-title">Share A Story</h4>
    <p class="textwidget">Inspired by someone or have a story to tell?</p>
    <a href="<?php echo esc_url($site_url); ?>/living/stories/share/" class="btn btn-outline-darkergray" title="Share Your Story">Learn More</a>
  </div>
</div>
