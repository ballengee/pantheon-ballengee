<?php if ( ! defined( 'ABSPATH' ) ) exit;

final class NF_Emma_Menu extends NF_Base_Menu
{
    public $menu_slug = 'ninja-forms-emma';

    public $group_types = array();

    public function __construct()
    {
        $this->name  = 'emma';
        $this->title = __( 'Emma', 'ninja-forms-emma' );

        $this->settings = array(
            'public_api_key' => __( 'Public API Key', 'ninja-forms-emma' ),
            'private_api_key' => __( 'Private API Key', 'ninja-forms-emma' ),
            'account_id' => __( 'Account ID', 'ninja-forms-emma' ),
        );

        $this->options = get_option( "ninja_forms_emma" );

        if( $this->options['account_id'] && $this->options['public_api_key'] && $this->options['private_api_key'] ) {
            $this->emma = new Emma(
                $this->options['account_id'],
                $this->options['public_api_key'],
                $this->options['private_api_key']
            );
        }

        if( isset( $_POST['emma_add_group'] ) ){
            $this->add_group(
                $_POST['emma_add_group']['name'],
                $_POST['emma_add_group']['type']
            );
        }

        $this->group_types = array(
            'g' => __( 'Regular', 'ninja-forms-emma' ),
            't' => __( 'Test', 'ninja-forms-emma' ),
            'h' => __( 'Hidden', 'ninja-forms-emma' )
        );

        parent::__construct();
    }

    /**
     * Display
     *
     * The default display method.
     */
    public function display()
    {
        $this->tab = ( isset( $_GET['tab'] ) ) ? $_GET['tab'] : '';

        include NF_Emma::$dir . 'includes/templates/admin-menu.html.php';
    }

    /**
     * Enqueue Styles
     */
    public function enqueue_styles()
    {
        wp_enqueue_style(
        /* Handle       */ 'ninja-forms-emma-admin-css',
            /* Source       */ NF_Emma::$url . '/assets/css/dev/ninja-forms-emma-admin.css',
            /* Dependencies */ FALSE,
            /* Version      */ NF_Emma::VERSION,
            /* In Footer    */ FALSE
        );
    }

    /**
     * Enqueue Scripts
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script(
        /* Handle       */ 'ninja-forms-emma-admin-js',
            /* Source       */ NF_Emma::$url . '/assets/js/min/ninja-forms-emma-admin.min.js',
            /* Dependencies */ array( 'jquery' ),
            /* Version      */ NF_Emma::VERSION,
            /* In Footer    */ TRUE
        );
    }

    /**
     * Add Group
     *
     * @param $new_group_name
     * @param $new_group_type
     */
    private function add_group( $new_group_name, $new_group_type )
    {
        if( ! $this->emma ) {
            return;
        }

        $new_group_data = array(
            'groups' => array(
                0 => array(
                    'group_name' => $new_group_name,
                    'group_type' => $new_group_type
                )
            )
        );

        $response = $this->emma->create_groups( $new_group_data );

        if( isset( $_GET['debug'] ) && $response->error ){
            echo "<pre>";
            var_dump($response);
            echo "</pre>";
        }
    }


} // End NF_Emma_Menu Class

new NF_Emma_Menu();
