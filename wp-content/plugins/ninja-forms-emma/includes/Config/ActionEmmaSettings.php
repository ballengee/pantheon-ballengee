<?php if ( ! defined( 'ABSPATH' ) ) exit;

return apply_filters( 'ninja_forms_action_emma_settings', array(
    'opt_in_confirmation'   => array(
        'name'              => 'opt_in_confirmation',
        'type'              => 'toggle',
        'group'             => 'primary',
        'value'             => 1,
        'label'             => __( 'Send Default Plaintext Confirmation Email?', 'ninja-forms' ),
    ),

));
