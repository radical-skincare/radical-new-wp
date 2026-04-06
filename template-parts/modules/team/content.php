<?php if ($team_groups = get_field('team_groups')) : ?>
  <?php foreach ($team_groups as $team_group) : ?>
    <section class="mb-5">
      <div class="container-fluid bg-lightgray py-5 text-center">
        <h2 class="mb-0"><?php echo $team_group['heading']; ?></h2>
      </div>
      <?php if ($team_group['team_members']) : ?>
        <div class="container">
          <?php
          $team_members_count = count($team_group['team_members']);
          ?>
          <?php foreach ($team_group['team_members'] as $key => $team_member) : ?>
            <?php
            $image = $team_member['image'];
            $name = $team_member['name'];
            $role = $team_member['role'];
            $bio = $team_member['bio'];
            get_template_part('template-parts/modules/flex/team-card');
            ?>
            <?php if (($key + 1) < $team_members_count) : ?>
              <hr class="w-50"/>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>
  <?php endforeach; ?>
<?php endif; ?>
