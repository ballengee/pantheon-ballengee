<?php

/**
 * Class NF_Emma_Subs
 */
class NF_Emma_Subs
{
    /**
     * Function that constructs our class.
     *
     * @since 1.0
     */
    public function __construct() {
        // Add our submission table actions
        add_filter( 'nf_sub_table_columns', array( $this, 'filter_sub_table_columns' ), 10, 2 );

        // Add the appropriate data for our custom columns.
        add_action( 'manage_posts_custom_column', array( $this, 'emma_columns' ), 10, 2 );

        // Add our CSV filters
        add_filter( 'ninja_forms_export_subs_label_array', array( $this, 'filter_csv_labels' ), 10, 2 );

        // Add our submission editor action / filter.
        add_action( 'add_meta_boxes', array( $this, 'add_emma_info' ), 11, 2 );

    } // function __construct

    /**
     * Filter our submissions table columns
     *
     * @since 1.0.7
     * @return void
     */
    function filter_sub_table_columns( $cols, $form_id ) {

        // Bail if we don't have a form id.
        if ( $form_id == '' )
            return $cols;

        // Bail if we aren't working with an Emma form.
        if ( 1 != Ninja_Forms()->form( $form_id )->get_setting( 'emma' ) )
            return $cols;

        $cols = array_slice( $cols, 0, count( $cols ) - 1, true ) +
            array( 'emma_member_id' => __( 'Emma Member ID', 'ninja-forms-emma' ) ) +
            array_slice( $cols, count( $cols ) - 1, count( $cols ) - 1, true) ;

        return $cols;

    } // function filter_sub_table_columns

    /**
     * Output our Stripe column data
     *
     * @since 1.0.7
     * @return void
     */
    function emma_columns( $column, $sub_id ) {
        if ( $column == 'emma_member_id' ) {
            echo Ninja_Forms()->sub( $sub_id )->get_meta( '_emma_member_id' );
        }

    } // function stripe_columns

    /**
     * Modifies the header-row of the exported CSV file by adding 'Emma Member ID'.
     *
     * @since 1.0
     * @return $label_array array
     */
    function filter_csv_labels( $label_array, $sub_id_array ) {
        $form_id = Ninja_Forms()->sub( $sub_id_array[0] )->form_id;
        if ( Ninja_Forms()->form( $form_id )->get_setting( 'emma' ) == 1 ) {
            $label_array[0]['_emma_member_id'] = __( 'Emma Member ID', 'ninja-forms-emma' );
        }

        return $label_array;
    } // function filter_csv_labels

    /**
     * Register a metabox to the side of the submissions page for displaying Emma Member ID.
     *
     * @since 1.0.7
     * @return void
     */
    public function add_emma_info( $post_type, $post ) {
        if ( $post_type != 'nf_sub' )
            return false;

        $form_id = Ninja_Forms()->sub( $post->ID )->form_id;
        if ( Ninja_Forms()->form( $form_id )->get_setting( 'emma' ) == 1 ) {
            // Add our save field values metabox
            add_meta_box( 'nf_emma_info', __( 'Emma Information', 'ninja-forms-emma' ), array( $this, 'emma_info_metabox' ), 'nf_sub', 'side', 'default');
        }
    }

    /**
     * Output Emma Member ID.
     *
     * @since 1.0.7
     * @return void
     */
    function emma_info_metabox( $sub ) {
        $form_id = Ninja_Forms()->sub( $sub->ID )->form_id;
        if ( Ninja_Forms()->form( $form_id )->get_setting( 'emma' ) == 1 ) {
            $member_id = Ninja_Forms()->sub( $sub->ID )->get_meta( '_emma_member_id' );
            include NF_Emma::$dir . 'includes/templates/admin-submissions.html.php';
        }
    } // function stripe_info_metabox

} // End NF_Emma_Subs

// Initiate our sub settings class if we are on the admin.
function ninja_forms_emma_modify_sub(){
    if ( is_admin() ) {
        $NF_Emma_Subs = new NF_Emma_Subs();
    }
}
add_action( 'init', 'ninja_forms_emma_modify_sub', 11 );
