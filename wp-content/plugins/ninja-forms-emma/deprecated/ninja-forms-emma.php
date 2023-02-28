<?php if ( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists( 'NF_Base_Menu' ) ) {
    require_once 'classes/menu.class.php';
}
if( ! class_exists( 'Emma' ) ) {
    require_once 'classes/emma.class.php';
}
require_once 'includes/admin/menu.php';
require_once 'includes/admin/submissions.php';



/**
 * Class NF_Emma
 */
class NF_Emma
{
    const VERSION = '3.0.4';

    const OFFLINE = TRUE;

    /**
     * Plugin Directory
     *
     * @var string $dir
     */
    public static $dir = '';

    /**
     * Plugin URL
     *
     * @var string $url
     */
    public static $url = '';

    /**
     * Ninja Forms Extension Updater
     *
     * @var NF_Extension_Updater
     */
    public $NF_Extension_Updater;



    /**
     * Constructor
     */
    public function __construct()
    {
        self::$dir = plugin_dir_path( __FILE__ );

        self::$url = plugin_dir_url( __FILE__ );

        add_action( 'plugins_loaded', array( $this, 'load_lang' ) );
        
        add_action( 'plugins_loaded', array( $this, 'ninja_forms_includes' ) );

        add_action( 'admin_init', array( $this, 'ninja_forms_extension_setup_license' ) );
    }



    /*
    * Public Methods
    */

    /**
     * Ninja Forms Includes
     *
     * Include plugin files for use in Ninja Forms
     */
    public function ninja_forms_includes()
    {
        require_once self::$dir . 'includes/actions/emma.php';
    }

    /**
     * Extension Setup License
     *
     * Register with the Ninja Forms licensing system through Easy Digital Downloads
     */
    public function ninja_forms_extension_setup_license()
    {
        if ( class_exists( 'NF_Extension_Updater' ) ) {
            $this->NF_Extension_Updater = new NF_Extension_Updater( 'Emma', self::VERSION, 'WP Ninjas', __FILE__, 'emma' );
        }
    }

    /**
     * Load Language
     *
     * Load our language/translation files
     */
    public function load_lang()
    {
        /** The 'plugin_locale' filter is also used by default in load_plugin_textdomain() */
        $locale = apply_filters( 'plugin_locale', get_locale(), 'ninja-forms-emma' );

        /** Set filter for WordPress languages directory */
        $wp_lang_dir = apply_filters(
            'ninja_forms_emma_wp_lang_dir',
            WP_LANG_DIR . "/ninja-forms-emma/ninja-forms-emma-$locale.mo"
        );

        /** Translations: First, look in WordPress' "languages" folder = custom & update-secure! */
        load_textdomain( 'ninja-forms-emma', $wp_lang_dir );

        /** Translations: Secondly, look in plugin's "lang" folder = default */
        $plugin_dir = basename( dirname( __FILE__ ) );
        $lang_dir = apply_filters( 'ninja_forms_emma_lang_dir', $plugin_dir . '/lang/' );
        load_plugin_textdomain( 'ninja-forms-emma', FALSE, $lang_dir );
    }


    /*
     * Private Methods
     */

    //Add private methods here
}

new NF_Emma();