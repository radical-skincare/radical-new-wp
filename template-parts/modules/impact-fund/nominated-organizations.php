<?php if ($nominated_organizations = get_field('nominated_organizations')) : ?>
  <section id="selected-organizations" class="selected-organiztions py-5">
    <div class="text-center mb-4">
      <h2 class="mb-0">
        <?php echo $nominated_organizations['heading']; ?>
      </h2>
    </div>
    <?php if ($organizations = $nominated_organizations['organizations']) : ?>
      <?php foreach ($organizations as $key => $organization) : ?>
        <div class="relative mb-4">
          <?php
          $tabs = [
            'why', 'story', 'mission'
          ];
          $text = '';
          if (isset($organization['nominated_by'])) {
            $text .= '<p class="fs-0.75x">Nominated by ' . $organization['nominated_by'] . '</p>';
          }
          $text .= '<ul class="nav nav-tabs organization-tabs mb-3" id="organizationTab' . $key . '" role="tablist">';
            for ($i = 0; $i < count($tabs); $i++) {
              if (isset($organization['text_' . $tabs[$i]]) && $organization['text_' . $tabs[$i]]) {
                $tab_id = 'org-tab' . $key . '-' . $tabs[$i];
                $text .= '<li class="nav-item">
                            <a class="nav-link ' . ($i === 0 ? 'active' : '' ) . ' ff-orpheus" id="' . $tab_id . '-tab" data-toggle="tab" href="#' . $tab_id . '" role="tab" aria-controls="' . $tab_id . '" aria-selected="' . ($i === 0 ? 'true' : 'false' ) . '">' . $tabs[$i] . '</a>
                          </li>';
              }
            }
          $text .= '</ul>';
          $text .= '<div class="tab-content" id="organizationTab' . $key . 'Content">';
            for ($i = 0; $i < count($tabs); $i++) {
              if (isset($organization['text_' . $tabs[$i]]) && $organization['text_' . $tabs[$i]]) {
                $tab_id = 'org-tab' . $key . '-' . $tabs[$i];
                $text .= '<div class="tab-pane fade ' . ($i === 0 ? 'show active' : '' ) . '" id="' . $tab_id . '" role="tabpanel" aria-labelledby="' . $tab_id . '-tab">' . $organization['text_' . $tabs[$i]] . '</div>';
              }
            }
          $text .= '</div>';
          ?>
          <?php
          $link = isset($organization['link']) && $organization['link'] ? $organization['link'] : false;
          $image_bg_color = isset($organization['image_bg_color']) ? $organization['image_bg_color'] : null;
          get_template_part('template-parts/modules/flex/image-card', null, [
            'img' => $organization['image'],
            'title' => $organization['name'],
            'text' => $text,
            'image_align' => (($key%2) === 0) ? 'right' : 'left',
            'link' => $link,
            'link_classes' => 'link-underline link-underline_darker-gray',
            'image_bg_color' => $image_bg_color,
          ]);
          ?>
          <?php if ($key) : ?>
            <div class="d-block border border-1 border-dark my-4 mr-auto ml-auto vertical-line"></div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>
<?php endif; ?>
