<?php if ( ! defined( 'ABSPATH' ) ) exit;

final class NF_Admin_Menus_SystemStatus extends NF_Abstracts_Submenu
{
    public $parent_slug = 'ninja-forms';

    public $menu_slug = 'nf-system-status';

    public $position = 6;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_page_title()
    {
        return esc_html__( 'Get Help', 'ninja-forms' );
    }

    public function get_capability()
    {
        return apply_filters( 'ninja_forms_admin_status_capabilities', $this->capability );
    }

    public function display()
    {
        /** @global wpdb $wpdb */
        global $wpdb;

        wp_enqueue_style( 'nf-admin-system-status', Ninja_Forms::$url . 'assets/css/admin-system-status.css' );
        wp_enqueue_script( 'nf-admin-system-status-script', Ninja_Forms::$url . 'assets/js/admin-system-status.js', array( 'jquery' ) );
        wp_enqueue_script( 'jBox', Ninja_Forms::$url . 'assets/js/min/jBox.min.js', array( 'jquery' ) );
        wp_enqueue_style( 'jBox', Ninja_Forms::$url . 'assets/css/jBox.css' );
        wp_enqueue_style( 'nf-font-awesome', Ninja_Forms::$url . 'assets/css/font-awesome.min.css' );
        
        //PHP locale
        $locale = localeconv();

        if ( is_multisite() ) {
            $multisite = esc_html__( 'Yes', 'ninja-forms' );
        } else {
            $multisite = esc_html__( 'No', 'ninja-forms' );
         }

         //TODO: Possible refactor
         foreach( $locale as $key => $val ){
             if( is_string( $val ) ){
                $data = $key . ': ' . $val . '</br>';
             }
         }

         //TODO: Ask if this check is need
         //if ( function_exists( 'phpversion' ) ) echo esc_html( phpversion() );

         //WP_DEBUG
         if ( defined('WP_DEBUG') && WP_DEBUG ){
             $debug = esc_html__( 'Yes', 'ninja-forms' );
         } else {
            $debug =  esc_html__( 'No', 'ninja-forms' );
         }

         //WPLANG
         if ( defined( 'WPLANG' ) && WPLANG ) {
            $lang = WPLANG;
         } else {
            $lang = esc_html__( 'Default', 'ninja-forms' );
         }

         //TODO: Ask if this long list of ini_get checks are need?

        //  if( function_exists( 'ini_get' ) ){
        //     $get_ini = size_format( ini_get('post_max_size') );
        //  }

        //SUHOSIN
        if ( extension_loaded( 'suhosin' ) ) {
            $suhosin =  esc_html__( 'Yes', 'ninja-forms' );
        } else {
            $suhosin =  esc_html__( 'No', 'ninja-forms' );
        }
        
        //max_input_nesting_level check for 5.2.2
        if ( version_compare( PHP_VERSION, '5.2.2', '>' ) ) {
            $max_input_nesting_level = ini_get( 'max_input_nesting_level' );
        } else {
            $max_input_nesting_level = esc_html__( 'Unknown', 'ninja-forms' );
        }
        
        //max_input_vars check for 5.3.8
        if ( version_compare( PHP_VERSION, '5.3.8', '>' ) ) {
            $max_input_vars = ini_get( 'max_input_vars' );
        } else {
            $max_input_vars = esc_html__( 'Unknown', 'ninja-forms' );
        }


        //Time Zone Check
        //TODO: May need refactored
        $default_timezone = get_option( 'timezone_string' );

        //Check for active plugins
        $active_plugins = (array) get_option( 'active_plugins', array() );

        if ( is_multisite() ) {
            $active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
        }

        $all_plugins = array();

        foreach ( $active_plugins as $plugin ) {
            $plugin_data    = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
            $dirname        = dirname( $plugin );
            $version_string = '';

            if ( ! empty( $plugin_data['Name'] ) ) {

                // link the plugin name to the plugin url if available
                $plugin_name = $plugin_data['Name'];
                if ( ! empty( $plugin_data['PluginURI'] ) ) {
                    $plugin_name = '<a href="' . esc_attr( $plugin_data[ 'PluginURI' ] ) . '" title="' . esc_html__( 'Visit plugin homepage' , 'ninja-forms' ) . '">' . $plugin_name . '</a>';
                }

                $all_plugins[] = $plugin_name . ' ' . esc_html__( 'by', 'ninja-forms' ) . ' ' . $plugin_data['Author'] . ' ' . esc_html__( 'version', 'ninja-forms' ) . ' ' . $plugin_data['Version'] . $version_string;
            }
        }

        if ( sizeof( $all_plugins ) == 0 ) {
            $site_wide_plugins = '-';
        } else {
            $site_wide_plugins = implode( ', <br/>', $all_plugins );
        }

        $server_ip = '';
        if( array_key_exists( 'SERVER_ADDR', $_SERVER ) )
            $server_ip = $_SERVER[ 'SERVER_ADDR' ];
        elseif( array_key_exists( 'LOCAL_ADDR', $_SERVER ) )
            $server_ip = $_SERVER[ 'LOCAL_ADDR' ];
        $host_name = gethostbyaddr( $server_ip );

        $wp_version = get_bloginfo('version');
        $wp_compatible = ( version_compare( $wp_version, Ninja_Forms::WP_MIN_VERSION ) >= 0 ) ? esc_html__( 'Supported', 'ninja-forms' ) : esc_html__( 'Not Supported', 'ninja-forms' );

        /* 
         * Error log
         */
        $error_log = array();

        $log = $wpdb->get_results( 'SELECT * FROM `' . $wpdb->prefix . 'nf3_objects` WHERE type = "log" ORDER BY created_at DESC LIMIT 10', ARRAY_A );
        
        if ( is_array( $log ) && 0 < count( $log ) ) {
            foreach ( $log as $error ) {
                $error_object = Ninja_Forms()->form()->object( $error[ 'id' ] )->get();
                // Make sure we don't have a duplicate message
                if ( false === in_array( $error_object->get_setting( 'message' ) ,$error_log ) ) {
                    $error_log[] = $error_object->get_setting( 'message' );
                }
            }
        } else {
            $error_log[] = esc_html__( 'None Logged', 'ninja-forms' );
        }

        $dev_mode = Ninja_Forms()->get_setting('builder_dev_mode' );

        $sql_version_variable = $wpdb->get_row("show variables like 'version'");
        if($sql_version_variable && property_exists($sql_version_variable, 'Value')){
            $sql_version_variable_value = $sql_version_variable->Value;
        } else {
            $sql_version_variable_value = 'unknown';
        }

        //Output array
        $environment = array(
            esc_html__( 'Home URL','ninja-forms' ) => home_url(),
            esc_html__( 'Site URL','ninja-forms' ) => site_url(),
            esc_html__( 'Ninja Forms Version','ninja-forms' ) => esc_html( Ninja_Forms::VERSION ),
            esc_html__( 'Ninja Forms DB Version', 'ninja-forms' ) => get_option( 'ninja_forms_db_version' ),
            esc_html__( 'Ninja Forms Gatekeeper', 'ninja-forms' ) => WPN_Helper::get_zuul(),
            esc_html__( 'Ninja Forms "Dev Mode"', 'ninja-forms' ) => ( $dev_mode ) ? esc_html__('Enabled') : esc_html__('Disabled'),
            esc_html__( 'WP Version','ninja-forms' ) => $wp_version . ' - ' . $wp_compatible,
            esc_html__( 'WP Multisite Enabled','ninja-forms' ) => $multisite,
            esc_html__( 'Web Server Info','ninja-forms' ) => esc_html( $_SERVER['SERVER_SOFTWARE'] ),
            esc_html__( 'PHP Version','ninja-forms' ) => esc_html( phpversion() ),
            //TODO: Possibly Refactor with Ninja forms global $_db?
            esc_html__( 'MySQL Version','ninja-forms' ) => $wpdb->db_version(),
            esc_html__( 'SQL Version Variable','ninja-forms' ) => $sql_version_variable_value,
            esc_html__( 'PHP Locale','ninja-forms' ) =>  $data,
            //TODO: Possibly move the ninja_forms_letters_to_numbers function over.
            esc_html__( 'WP Memory Limit','ninja-forms' ) => WP_MEMORY_LIMIT,
            esc_html__( 'WP Debug Mode', 'ninja-forms' ) => $debug,
            esc_html__( 'WP Language', 'ninja-forms' ) => $lang,
            esc_html__( 'WP Max Upload Size','ninja-forms' ) => size_format( wp_max_upload_size() ),
            esc_html__( 'PHP Post Max Size','ninja-forms' ) => ini_get( 'post_max_size' ),
            esc_html__( 'Max Input Nesting Level','ninja-forms' ) => $max_input_nesting_level,
            esc_html__( 'PHP Time Limit','ninja-forms' ) => ini_get('max_execution_time'),
            esc_html__( 'PHP Max Input Vars','ninja-forms' ) => $max_input_vars,
            esc_html__( 'SUHOSIN Installed','ninja-forms' ) => $suhosin,
            esc_html__( 'Server IP Address', 'ninja-forms' ) => $server_ip,
            esc_html__( 'Host Name', 'ninja-forms' ) => $host_name,
            esc_html__( 'SMTP','ninja-forms' ) => ini_get('SMTP'),
            esc_html__( 'smtp_port','ninja-forms' ) => ini_get('smtp_port'),
            esc_html__( 'Default Timezone','ninja-forms' ) => $default_timezone,
        );

        Ninja_Forms::template( 'admin-menu-system-status.html.php', compact( 'environment', 'site_wide_plugins', 'error_log' ) );
    }
} // End Class NF_Admin_SystemStatus
