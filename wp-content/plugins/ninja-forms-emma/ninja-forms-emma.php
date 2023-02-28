<?php if ( ! defined( 'ABSPATH' ) ) exit;

/*
 * Plugin Name: Ninja Forms - Emma
 * Plugin URI: https://ninjaforms.com/extensions/emma/
 * Description: Emma's simple and stylish email marketing add-on for Ninja Forms.
 * Version: 3.0.4
 * Author: The WP Ninjas
 * Author URI: http://ninjaforms.com/
 * Text Domain: ninja-forms-emma
 *
 * Copyright 2016 The WP Ninjas.
 */

if( version_compare( get_option( 'ninja_forms_version', '0.0.0' ), '3', '<' ) || get_option( 'ninja_forms_load_deprecated', FALSE ) ) {

    include 'deprecated/ninja-forms-emma.php';

} else {

    include_once 'includes/Libraries/Emma.php';

    /**
     * Class NF_Emma
     */
    final class NF_Emma
    {
        const VERSION = '3.0.4';
        const SLUG    = 'emma';
        const NAME    = 'Emma';
        const AUTHOR  = 'The WP Ninjas';
        const PREFIX  = 'NF_Emma';

        /**
         * @var NF_Emma
         * @since 3.0
         */
        private static $instance;

        /**
         * Plugin Directory
         *
         * @since 3.0
         * @var string $dir
         */
        public static $dir = '';

        /**
         * Plugin URL
         *
         * @since 3.0
         * @var string $url
         */
        public static $url = '';

        /**
         * @var Emma
         */
        private $_api;


        /**
         * Main Plugin Instance
         *
         * Insures that only one instance of a plugin class exists in memory at any one
         * time. Also prevents needing to define globals all over the place.
         *
         * @since 3.0
         * @static
         * @static var array $instance
         * @return NF_Emma Highlander Instance
         */
        public static function instance()
        {
            if (!isset(self::$instance) && !(self::$instance instanceof NF_Emma)) {
                self::$instance = new NF_Emma();

                self::$dir = plugin_dir_path(__FILE__);

                self::$url = plugin_dir_url(__FILE__);

                /*
                 * Register our autoloader
                 */
                spl_autoload_register(array(self::$instance, 'autoloader'));

                new NF_Emma_Admin_Settings();

            }
            return self::$instance;
        }

        public function __construct()
        {
            /*
             * Required for all Extensions.
             */
            add_action( 'admin_init', array( $this, 'setup_license') );

            /*
             * Optional. If your extension creates a new field interaction or display template...
             */
            add_filter( 'ninja_forms_register_fields', array($this, 'register_fields'));

            /*
             * Optional. If your extension processes or alters form submission data on a per form basis...
             */
            add_filter( 'ninja_forms_register_actions', array($this, 'register_actions'));

            add_action( 'ninja_forms_loaded', array( $this, 'ninja_forms_loaded' ) );
            
        }

        public function ninja_forms_loaded()
        {
            new NF_Emma_Admin_Metaboxes_Submission();
        }

        /**
         * Optional. If your extension creates a new field interaction or display template...
         */
        public function register_fields($actions)
        {
            $actions[ 'emma-optin' ] = new NF_Emma_Fields_OptIn(); // includes/Fields/EmmaExample.php

            return $actions;
        }

        /**
         * Optional. If your extension processes or alters form submission data on a per form basis...
         */
        public function register_actions($actions)
        {
            $actions[ 'emma' ] = new NF_Emma_Actions_Emma(); // includes/Actions/Emma.php

            return $actions;
        }

        /*
         * Optional methods for convenience.
         */
        public function autoloader($class_name)
        {
            if (class_exists($class_name) ) return;

            if ( false === strpos( $class_name, self::PREFIX ) ) return;

            $class_name = str_replace( self::PREFIX, '', $class_name );
            $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
            $class_file = str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';

            if (file_exists($classes_dir . $class_file)) {
                require_once $classes_dir . $class_file;
            }
        }
        
        /**
         * Template
         *
         * @param string $file_name
         * @param array $data
         */
        public static function template( $file_name = '', array $data = array() )
        {
            if( ! $file_name ) return;

            extract( $data );

            include self::$dir . 'includes/Templates/' . $file_name;
        }

        /**
         * @return array containing the groups from emma
         */
        public function get_lists()
        {
            if( ! $this->api() ) return array();
            
            $response = $this->api()->list_groups( 'all' );

            if(  ! NULL == $response  ) {

                $group_names = array();

                foreach ($response as $data) {

                    Ninja_Forms()->update_setting('emma_list_' . $data->member_group_id, $data->group_name);

                    $group_names[] = array(
                        'label' => $data->group_name,
                        'value' => $data->member_group_id,
                        'fields' => array(
                            array(
                                'label' => __('First Name', 'ninja-forms-emma'),
                                'value' => 'first_name',
                            ),
                            array(
                                'label' => __('Last Name', 'ninja-forms-emma'),
                                'value' => 'last_name',
                            ),
                            array(
                                'label' => __('Email', 'ninja-forms-emma'),
                                'value' => 'email',
                            ),
                        ),
                    );
                }
                return $group_names;
            }
        }

        public function subscribe( $member_data )
        {
            $response = $this->api()->make_request('members/signup', 'POST', $member_data);

            return $response;
        }

        /**
         * @return Emma
         * Connects to the Emma API.
         */
        public function api()
        {
            if ( ! $this->_api ) {

                $debug = defined( 'WP_DEBUG' ) && WP_DEBUG;
                $public_key =   trim( Ninja_Forms()->get_setting( 'emma_public_api_key' ) );
                $private_key =  trim( Ninja_Forms()->get_setting( 'emma_private_api_key' ) );
                $account_id =   trim( Ninja_Forms()->get_setting( 'emma_account_id' ) );

                try {
                    $this->_api = new Emma( $account_id, $public_key, $private_key );
                } catch ( Exception $e ) {
                    //TODO: Log Error,
                }
            }
            return $this->_api;
        }

        
        /**
         * Config
         *
         * @param $file_name
         * @return mixed
         */
        public static function config( $file_name )
        {
            return include self::$dir . 'includes/Config/' . $file_name . '.php';
        }

        /*
         * Required methods for all extension.
         */

        public function setup_license()
        {
            if ( ! class_exists( 'NF_Extension_Updater' ) ) return;

            new NF_Extension_Updater( self::NAME, self::VERSION, self::AUTHOR, __FILE__, self::SLUG );
        }
    }

    /**
     * The main function responsible for returning The Highlander Plugin
     * Instance to functions everywhere.
     *
     * Use this function like you would a global variable, except without needing
     * to declare the global.
     *
     * @since 3.0
     * @return {class} Highlander Instance
     */
    function NF_Emma()
    {
        return NF_Emma::instance();
    }

    NF_Emma();
}

add_filter( 'ninja_forms_upgrade_action_Emma', 'NF_Emma_Upgrade_Action' );
function NF_Emma_Upgrade_Action( $action ){
    if( ! 'Emma' == $action[ 'type' ] ) return $action;
    $data = array(
        'active'            => '1',
        'name'              => __( 'Emma', 'ninja-forms-emma'),
        'type'              => 'emma',
        'newsletter_list'   => $action[ 'groups' ],
    );
    if( isset( $action[ 'first-name-field' ] ) ){
        $data[ 'first_name' ] = '{field:' . $action[ 'first-name-field' ] . '}';
    }
    if( isset( $action[ 'last-name-field' ] ) ){
        $data[ 'last_name' ] = '{field:' . $action[ 'last-name-field' ] . '}';
    }
    if( isset( $action[ 'email-field' ] ) ){
        $data[ 'email' ] = '{field:' . $action[ 'email-field' ] . '}';
    }

    $action = array_merge( $action, $data );
    return $action;
}


add_filter( 'ninja_forms_upgrade_settings', 'NF_Emma_Upgrade_Settings' );
function NF_Emma_Upgrade_Settings( $data ){
    
    $plugin_settings = get_option( 'ninja_forms_emma' );
    
    Ninja_Forms()->update_settings(
        array(
            'emma_public_api_key'  => $plugin_settings[ 'public_api_key' ],
            'emma_private_api_key' => $plugin_settings[ 'private_api_key' ],
            'emma_account_id'      => $plugin_settings[ 'emma_account_id' ],
        )
    );

   foreach( $data[ 'actions' ] as $action ) {
       if( 'Emma' != $action[ 'type' ] ) {
           continue;
       } else {
            foreach( $data['fields'] as $key => $field){
                if(  $action[ 'optin-field' ] == $field[ 'id' ] ){
                    $data['fields'][$key]['type'] = 'emma-optin';
                }
            }
       }
   }
    return $data;
}