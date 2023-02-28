<?php if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'NF_Notification_Base_Type' ) ) return;

final class NF_Emma_Action extends NF_Notification_Base_Type
{
    /**
     * @var name
     */
    public $name;

    /**
     * @var response
     */
    public $response;

    /**
     * @var options
     */
    public $options;

    /**
     * @var Emma
     */
    public $emma;

    /**
     * @var groups
     */
    public $groups;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->name = __( 'Emma', 'ninja-forms-emma' );

        add_filter( 'nf_notification_types', array( $this, 'register_action_type' ) );

        $this->options = get_option( "ninja_forms_emma" );

        if( $this->options['account_id'] && $this->options['public_api_key'] && $this->options['private_api_key'] ) {
            $this->emma = new Emma(
                $this->options['account_id'],
                $this->options['public_api_key'],
                $this->options['private_api_key']
            );

            $this->groups = $this->emma->list_groups( 'all' );
        }

    }


    /**
     * Register Action Type
     *
     * @param $types
     * @return array
     */
    public function register_action_type( $types )
    {
        $types[ $this->name ] = $this;
        return (array) $types;
    }



    /**
     * Edit Screen
     *
     * @return void
     */
    public function edit_screen( $id = '' )
    {
        $form = Ninja_Forms()->form( $_GET['form_id'] );

        $settings['groups'] = Ninja_Forms()->notification( $id )->get_setting( 'groups' );
        if( ! is_array( $settings['groups'] ) ) {
            $settings['groups'] = array(
                $settings['groups']
            );
        }

        $settings['first-name-field'] = Ninja_Forms()->notification( $id )->get_setting( 'first-name-field' );
        $settings['last-name-field'] = Ninja_Forms()->notification( $id )->get_setting( 'last-name-field' );
        $settings['email-field'] = Ninja_Forms()->notification( $id )->get_setting( 'email-field' );
        $settings['optin-field'] = Ninja_Forms()->notification( $id )->get_setting( 'optin-field' );

        include NF_Emma::$dir . 'includes/templates/action-emma.html.php';
    }



    /**
     * Process
     *
     * @param string $id
     * @return void
     */
    public function process( $id = '' )
    {
        global $ninja_forms_processing;

        //Check optin-field before processing
        $settings['optin-field'] = Ninja_Forms()->notification( $id )->get_setting( 'optin-field' );

        if( $settings['optin-field'] AND 'checked' != $ninja_forms_processing->get_field_value( $settings['optin-field'] ) ){
            return;
        }

        //TODO: move to getter
        $settings['groups'] = Ninja_Forms()->notification( $id )->get_setting( 'groups' );

        if( ! $settings['groups'] ){
            //Bail if no group is selected.
            return;
        } elseif( ! is_array( $settings['groups'] ) ) {
            //The Emma API requires the group(s) to be passes as ana rray.
            $settings['groups'] = array(
                $settings['groups']
            );
        }

        $settings['first-name-field'] = Ninja_Forms()->notification( $id )->get_setting( 'first-name-field' );
        $settings['last-name-field'] = Ninja_Forms()->notification( $id )->get_setting( 'last-name-field' );
        $settings['email-field'] = Ninja_Forms()->notification( $id )->get_setting( 'email-field' );

        $member_data = array(
            "email" => $ninja_forms_processing->get_field_value( $settings['email-field'] ),
            "fields" => array(
                "first_name" => $ninja_forms_processing->get_field_value( $settings['first-name-field'] ),
                "last_name" => $ninja_forms_processing->get_field_value( $settings['last-name-field'] ),
            ),
            "group_ids" => $settings['groups']
        );


        $this->response = $this->emma->make_request('members/signup', 'POST', $member_data);

        if( $this->response->error ) {
            echo "<pre>"; var_dump($this->response); echo "</pre>"; die();
        } else {
            $this->add_emma_member_id(
                $sub_id = $ninja_forms_processing->get_form_setting( 'sub_id' ),
                $this->response->member_id
            );

            //TODO: Find a more efficient way to do this.
            $form_id = $ninja_forms_processing->get_form_ID();
            $form = Ninja_Forms()->form( $form_id );
            $form->update_setting( 'emma', 1 );
            $form->dump_cache();

        }
    }

    /**
     * Add Emma Member ID to Submission
     *
     * @param $sub_id
     * @param $member_id
     */
    public function add_emma_member_id( $sub_id, $member_id )
    {
        Ninja_Forms()->sub( $sub_id )->add_meta( '_emma_member_id', $member_id );
    }
}

new NF_Emma_Action;
