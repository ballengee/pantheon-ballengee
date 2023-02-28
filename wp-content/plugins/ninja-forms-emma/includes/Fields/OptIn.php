<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Emma_Fields_OptIn
 */
class NF_Emma_Fields_OptIn extends NF_Abstracts_FieldOptIn
{
    protected $_name = 'emma-optin';

    protected $_section = 'common';

    protected $_type = 'emma-optin';

    protected $_templates = 'checkbox';

    public function __construct()
    {
        parent::__construct();

        $this->_nicename = __( 'Emma OptIn', 'ninja-forms-emma' );
    }
}