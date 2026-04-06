<?php
$site_url = get_site_url();
?>
<?php
$quick_links = [
  [
    'img_url' => $site_url.'/wp-content/uploads/2018/06/trylacel.jpg',
    'name' => 'Our Story',
    'link' => $site_url . '/our-story'
  ],
  [
    'img_url' => $site_url . '/wp-content/uploads/2018/06/radical-blog.jpg',
    'name' => 'Radical Blog',
    'link' => $site_url . '/blog'
  ],
  [
    'img_url' => $site_url . '/wp-content/uploads/2018/06/radical-regimen.jpeg',
    'name' => '<span class="d-none d-lg-inline-block font-weight-normal">The </span> Radical System',
    'link' => '#howItWorksModal',
    'model' => true
  ],
];
?>
<section class="container mb-5">
  <div class="row">
    <?php foreach($quick_links as $quick_link) : ?>
      <div class="col-lg-4 mb-3 mb-lg-0">
        <div class="card card-quick-link">
          <img src="<?php echo esc_url($quick_link['img_url']); ?>" alt="<?php echo esc_attr(wp_strip_all_tags($quick_link['name'])); ?>"/>
          <a href="<?php echo esc_url($quick_link['link']); ?>" class="btn btn-white" <?php echo isset($quick_link['model']) ? 'data-toggle="modal" data-target="' . esc_attr($quick_link['link']) . '"' : ''; ?>><?php echo $quick_link['name']; ?></a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<?php get_template_part('template-parts/modal/how-it-works'); ?>
