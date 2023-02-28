<?php if ( ! defined( 'ABSPATH' ) ) exit;

final class NF_Emma_Admin_Metaboxes_Submission extends NF_Abstracts_SubmissionMetabox
{
    public function __construct()
    {
        parent::__construct();

        $this->_title = __( 'Emma Subscription', 'ninja-forms-emma' );

        if( $this->sub && ! $this->sub->get_extra_value( 'member_id' ) ){
            remove_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        }

    }

    public function render_metabox( $post, $metabox )
    {
        $member_data = $this->sub->get_extra_value( 'member_id' );

        $data = array(
            __('Member ID:', 'ninja-forms-emma') => $member_data->member_id
        );

        NF_Emma()->template( 'admin-metaboxes-submission.html.php', $data );
    }
}