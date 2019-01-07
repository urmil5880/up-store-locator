<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;	// Exit if accessed directly
}

function up_store_add_meta_box() {
//this will add the metabox for the member post type
$screens = array( 'store-locator' );

foreach ( $screens as $screen ) {

    add_meta_box(
        'member_sectionid',
        __( 'Company Details', 'member_textdomain' ),
        'up_store_meta_box_callback',
        $screen
    );
   }
}
add_action( 'add_meta_boxes', 'up_store_add_meta_box' );

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function up_store_meta_box_callback( $post ) {

// Add a nonce field so we can check for it later.
wp_nonce_field( 'up_store_save_meta_box_data', 'up_store_meta_box_nonce' );

/*
 * Use get_post_meta() to retrieve an existing value
 * from the database and use the value for the form.
 */
    $contact_name_value = get_post_meta( $post->ID, '_contact_name', true );

    echo '<label for="contact_name_field">';
    _e( 'Company Name', 'member_textdomain' );
    echo '</label> ';
    echo '<input type="text" id="contact_name_field" name="contact_name_field" value="' . esc_attr( $contact_name_value ) . '" size="100" /><br>';

    $contact_email_value = get_post_meta( $post->ID, '_contact_email', true );

    echo '<label for="contact_email_field">';
    _e( 'Company Email Id', 'member_textdomain' );
    echo '</label> ';
    echo '<input type="text" id="contact_email_field" name="contact_email_field" value="' . esc_attr( $contact_email_value ) . '" size="100" /><br>';

    $contact_phone_value = get_post_meta( $post->ID, '_contact_phone', true );

    echo '<label for="contact_phone_field">';
    _e( 'Phone Number', 'member_textdomain' );
    echo '</label> ';
    echo '<input type="text" id="contact_phone_field" name="contact_phone_field" value="' . esc_attr( $contact_phone_value ) . '" size="100" /><br>';
    $contact_address_value = get_post_meta( $post->ID, '_contact_address', true );

    echo '<label for="contact_address_field">';
    _e( 'Address', 'member_textdomain' );
    echo '</label> ';
    echo '<input type="text" id="contact_address_field" name="contact_address_field" value="' . esc_attr( $contact_address_value ) . '" size="50" />';

}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
 function up_store_save_meta_box_data( $post_id ) {

 if ( ! isset( $_POST['up_store_meta_box_nonce'] ) ) {
    return;
 }

 if ( ! wp_verify_nonce( $_POST['up_store_meta_box_nonce'], 'up_store_save_meta_box_data' ) ) {
    return;
 }

 if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
 }

 // Check the user's permissions.
/* if ( isset( $_POST['post_type'] ) && 'store-locator' == $_POST['post_type'] ) {

    if ( ! current_user_can( 'edit_page', $post_id ) ) {
        return;
    }

 } else {

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
 }*/

 if ( ! isset( $_POST['contact_name_field'] ) ) {
    return;
 }

 $contact_name = sanitize_text_field( $_POST['contact_name_field'] );

 update_post_meta( $post_id, '_contact_name', $contact_name );

 if ( ! isset( $_POST['contact_email_field'] ) ) {
    return;
 }
 $contact_email = sanitize_text_field( $_POST['contact_email_field'] );

 update_post_meta( $post_id, '_contact_email', $contact_email );

 if ( ! isset( $_POST['contact_phone_field'] ) ) {
    return;
 }
 $contact_phone = sanitize_text_field( $_POST['contact_phone_field'] );

 update_post_meta( $post_id, '_contact_phone', $contact_phone );

if ( ! isset( $_POST['contact_address_field'] ) ) {
    return;
 }
$contact_address = sanitize_text_field( $_POST['contact_address_field'] );

 update_post_meta( $post_id, '_contact_address', $contact_address );

}
add_action( 'save_post', 'up_store_save_meta_box_data' );

?>
