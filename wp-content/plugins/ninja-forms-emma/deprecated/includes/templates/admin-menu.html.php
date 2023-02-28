<div class="wrap">

    <h2><?php _e( 'Ninja Forms', 'ninja-forms' ); ?> <?php _e( 'Emma', 'ninja-forms-emma' ); ?></h2>

    <p><?php _e( "Emma's simple and stylish email marketing add-on for Ninja Forms.", 'ninja-forms-emma' ); ?></p>

    <?php include NF_Emma::$dir . 'includes/templates/admin-menu-toolbar.html.php'; ?>

    <?php if( '' == $this->tab OR 'settings' == $this->tab )
        include NF_Emma::$dir . 'includes/templates/admin-menu-settings.html.php';
    ?>

    <?php if( 'audience' == $this->tab )
        include NF_Emma::$dir . 'includes/templates/admin-menu-audience.html.php';
    ?>

</div>