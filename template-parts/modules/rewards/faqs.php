<?php if ($faqs = get_field('faqs')) : ?>
  <section class="container my-5">
    <div class="row justify-content-center mb-5">
      <div class="col-auto">
        <div class="text-separator" style="width: 200px;">
          <div class="text-separator_line"></div>
          <h2 class="text-separator_inner-text fs-0.75x text-uppercase">Rewards FAQ's</h2>
          <div class="text-separator_line"></div>
        </div>
      </div>
    </div>
    <?php
      $items = [];
      foreach ($faqs as $faq) {
        $items[] = [
          'title' => $faq['question'],
          'content' => $faq['answer']
        ];
      }
      get_template_part('template-parts/modules/flex/accordion-list', null, [
        'id' => 'bp-faq',
        'items' => $items,
      ]);
    ?>
  </section>
<?php endif; ?>
