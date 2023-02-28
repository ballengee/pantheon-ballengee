<?php if ( ! defined( 'ABSPATH' ) ) exit;

return apply_filters( 'nf_emma_plugin_settings', array(
    /*
    |--------------------------------------------------------------------------
    | Public API Key
    |--------------------------------------------------------------------------
    */

    'emma_public_api_key' => array(
        'id'    => 'emma_public_api_key',
        'type'  => 'textbox',
        'label' => __( 'Public API Key', 'ninja-forms' ),
        'desc'  => sprintf(
            __( 'Grab your %sAPI Key%s from your Emma Account.', 'ninja-forms-emma' ),
            //TODO: ADD API URL HERE
            '<a "http://myemma.com/partners/get-started?utm_source=NinjaForms&utm_medium=integrationpartner&utm_campaign=NinjaForms-integrationpartner-partner-trial" target="_blank">', '</a>'
        ),
    ),

    /*
    |--------------------------------------------------------------------------
    | Private API Key
    |--------------------------------------------------------------------------
    */

    'emma_private_api_key' => array(
        'id'    => 'emma_private_api_key',
        'type'  => 'textbox',
        'label' => __( 'Private API Key', 'ninja-forms' ),
        'desc'  => sprintf(
            __( 'Grab your %sAPI Key%s from your Emma Account.', 'ninja-forms-emma' ),
            //TODO: ADD API URL HERE
            '<a "http://myemma.com/partners/get-started?utm_source=NinjaForms&utm_medium=integrationpartner&utm_campaign=NinjaForms-integrationpartner-partner-trial" target="_blank">', '</a>'
        ),
    ),

    /*
    |--------------------------------------------------------------------------
    | Account ID
    |--------------------------------------------------------------------------
    */

    'emma_account_id' => array(
        'id'    => 'emma_account_id',
        'type'  => 'textbox',
        'label' => __( 'Account ID', 'ninja-forms-emma' ),
    ),

));
