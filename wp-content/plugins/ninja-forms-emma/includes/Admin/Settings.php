<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Emma_Admin_Settings
 */
final class NF_Emma_Admin_Settings
{
    public function __construct()
    {
        add_filter( 'ninja_forms_plugin_settings',        array( $this, 'emma_plugin_settings' ), 10 ,1 );
        add_filter( 'ninja_forms_plugin_settings_groups', array( $this, 'emma_plugin_settings_groups' ), 10, 1 );
        if ( isset( $_GET['page'] ) && 'nf-settings' == $_GET['page'] ) {
            add_filter('ninja_forms_check_setting_emma_private_api_key', array($this, 'validate_ninja_forms_emma_api'), 10);
        }
    }

    public function emma_plugin_settings( $settings )
    {
        $settings[ 'emma' ] = NF_Emma()->config( 'PluginSettings' );
        return $settings;
    }

    public function emma_plugin_settings_groups( $groups )
    {
        $groups = array_merge( $groups, NF_Emma()->config( 'PluginSettingsGroups' ) );
        return $groups;
    }

    public function validate_ninja_forms_emma_api( $setting )
    {
        if( ! class_exists( 'Emma' ) ) return $setting;
        
        $debug = defined( 'WP_DEBUG' ) && WP_DEBUG;

        $public_key =   trim( Ninja_Forms()->get_setting( 'emma_public_api_key' ) );
        $private_key =  trim( Ninja_Forms()->get_setting( 'emma_private_api_key' ) );
        $account_id =   trim( Ninja_Forms()->get_setting( 'emma_account_id' ) );

        if(  ! $public_key || ! $private_key || ! $account_id ) return $setting;

        $Emma = new Emma($account_id, $public_key, $private_key);
        $Emma->get_mailing_list();

        if(  200 == $Emma->last_emma_response_info['http_code'] ) {
            return $setting;
        } else {
            $setting[ 'errors' ][] = __('One of the Emma API keys you have entered appears to be invalid.',
                'ninja-forms-emma');
        }
        return $setting;
    }
}