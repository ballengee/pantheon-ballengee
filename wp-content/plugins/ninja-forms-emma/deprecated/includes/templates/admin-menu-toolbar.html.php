<h2 class="nav-tab-wrapper">

    <a href="<?php echo admin_url( 'admin.php?page=' . $this->menu_slug ); ?>" class="nav-tab <?php if( '' == $this->tab OR 'settings' == $this->tab ) echo 'nav-tab-active';?>">
        <?php _e( 'Settings', 'ninja-forms-emma' ); ?>
    </a>

    <a href="<?php echo admin_url( 'admin.php?page=' . $this->menu_slug . '&tab=audience' ); ?>" class="nav-tab <?php if( 'audience' == $this->tab ) echo 'nav-tab-active';?>">
        <?php _e( 'Audience', 'ninja-forms-emma' ); ?>
    </a>

    <a href="http://myemma.com/partners/get-started?utm_source=NinjaForms&utm_medium=integrationpartner&utm_campaign=NinjaForms-integrationpartner-partner-trial" target="_blank" class="button button-secondary">
        <span class="dashicons dashicons-external" style="padding: 2px 2px 0 0;"></span>
        MyEmma.com
    </a>

</h2>