<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Field_Textbox
 */
class NF_Fields_Textbox extends NF_Abstracts_Input
{
    protected $_name = 'textbox';

    protected $_section = 'common';

    protected $_icon = 'text-width';

    protected $_aliases = array( 'input' );

    protected $_type = 'textbox';

    protected $_templates = 'textbox';

    protected $_test_value = 'Lorem ipsum';

    protected $_settings = array(
        'disable_browser_autocomplete',
	    'mask',
	    'custom_mask',
	    'custom_name_attribute',
	    'personally_identifiable'
    );

    public function __construct()
    {
        parent::__construct();

        $this->_nicename = esc_html__( 'Single Line Text', 'ninja-forms' );

        add_filter( 'ninja_forms_subs_export_field_value_' . $this->_name, array( $this, 'filter_csv_value' ), 10, 2 );
    }

    public function filter_csv_value( $field_value, $field ) {

        $field_value = WPN_Helper::maybe_escape_csv_column( $field_value );

        return $field_value;
    }
}
