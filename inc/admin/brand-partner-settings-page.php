<?php
$tab = ( ! empty( $_GET['tab'] ) ) ? esc_attr( $_GET['tab'] ) : 'general'; ?>

<div class="radical-rx-admin-display-wrap wrap">
	<h1>Brand Partner Settings</h1>
	<h2 class="nav-tab-wrapper">
        <a href="<?php echo get_site_url(); ?>/wp-admin/options-general.php?page=brand-partner&tab=general" class="nav-tab <?php echo ( $tab === 'general' ) ? 'nav-tab-active': ''; ?>">General</a>
        <a href="<?php echo get_site_url(); ?>/wp-admin/options-general.php?page=brand-partner&tab=misc" class="nav-tab <?php echo ( $tab === 'misc' ) ? 'nav-tab-active': ''; ?>">Misc</a>
        <!-- <a href="<?php echo get_site_url(); ?>/wp-admin/options-general.php?page=brand-partner&tab=ambassador" class="nav-tab <?php echo ( $tab === 'ambassador' ) ? 'nav-tab-active': ''; ?>">Ambassador</a> -->
	</h2>
	<?php if ( $tab === 'general' ) {
        require_once( get_template_directory() . '/inc/admin/partials/brand-partner/general-tab.php' );
    } else if ($tab === 'misc') {
        $coupons = get_posts([
            'post_type' => 'shop_coupon',
            'posts_per_page' => '-1',
        ]);
        ?>
        <ul>
            <?php
            $site_url = get_site_url();
            foreach ($coupons as $coupon) { ?>
                <li>
                    <?php
                    $exclude_sale_items = get_post_meta($coupon->ID, 'exclude_sale_items', true);
                    if ($exclude_sale_items === 'yes') { ?>
                        <a href="<?php echo $site_url; ?>/wp-admin/post.php?post=<?php echo $coupon->ID; ?>&action=edit">Fix coupon</a>
                    <?php } ?>
                </li>
            <?php } ?>
        </ul>
    <?php }
    /*
    else if ( $tab === 'listing' ) {
        require_once( get_template_directory() . '/inc/admin/partials/brand-partner/listing-tab.php' );
    }
    */ ?>
</div>
