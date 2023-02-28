<?php if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'NF_Abstracts_ActionNewsletter' ) ) return;

/**
 * Class NF_Emma_Actions_Emma
 */

final class NF_Emma_Actions_Emma extends NF_Abstracts_ActionNewsletter
{
    /**
     * @var string
     */
    protected $_name  = 'emma';
    /**
     * @var array
     */
    protected $_tags = array();
    /**
     * @var string
     */
    protected $_timing = 'normal';
    /**
     * @var int
     */
    protected $_priority = '10';
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->_nicename = __( 'Emma', 'ninja-forms-emma' );

        unset( $this->_settings[ 'emmanewsletter_list_groups' ] );

        $settings = NF_Emma::config( 'ActionEmmaSettings' );

        $this->_settings = array_merge( $this->_settings, $settings );
    }

    /*
    * PUBLIC METHODS
    */
    public function save( $action_settings )
    {
    }

    public function process( $action_settings, $form_id, $data )
    {
        if( ! $this->is_opt_in( $data ) ) return $data;

        $opt_in_confirmation = ( isset( $action_settings[ 'opt_in_confirmation' ] ) && 0 === $action_settings[ 'opt_in_confirmation' ] ) ? FALSE : TRUE;

        $member_data = array(
            'email' => $action_settings[ 'email' ],
            'fields' => array(
                'first_name' => $action_settings[ 'first_name' ],
                'last_name' => $action_settings[ 'last_name' ],
            ),
            'group_ids' => array(
                $action_settings[ 'newsletter_list' ],
            ),
            'opt_in_confirmation' => $opt_in_confirmation
        );

        $response = NF_Emma()->subscribe( $member_data );
        
        if( $response &&  'a' == $response->status ) {
            $data[ 'extra' ][ 'member_id' ] = $response;
        }
        
        $data[ 'actions' ][ 'emma' ][ 'response' ] = $response;
        $data[ 'actions' ][ 'emma' ][ 'member_data' ] = $member_data;

        return $data;
    }

    protected function is_opt_in( $data )
    {
        $opt_in = TRUE;
        foreach( $data[ 'fields' ]as $field ){

            if( 'emma-optin' != $field[ 'type' ] ) continue;

            if( ! $field[ 'value' ] ) $opt_in = FALSE;
        }
        return $opt_in;
    }

    public function get_lists()
    {
        return NF_Emma()->get_lists();
    }
}