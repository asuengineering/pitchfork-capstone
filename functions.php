<?php
/**
 * Pitchfork child theme functions
 *
 * @package pitchfork-child
 */

 // Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require get_stylesheet_directory() . '/inc/enqueue-assets.php';
require get_stylesheet_directory() . '/inc/custom-post-types.php';
require get_stylesheet_directory() . '/inc/acf-register.php';

add_action( 'init', 'pitchfork_capstone_register_block_bindings' );

function pitchfork_capstone_register_block_bindings() {

	register_block_bindings_source( 'capstone/program-string', array(
		'label'              => __( 'Program Name', 'pitchfork-capstone' ),
		'get_value_callback' => 'capstone_program_string_bindings_callback'
	) );

	// register_block_bindings_source( 'capstone/program-date', array(
	// 	'label'              => __( 'Program Date', 'pitchfork-capstone' ),
	// 	'get_value_callback' => 'capstone_program_date_bindings_callback'
	// ) );

	register_block_bindings_source( 'capstone/award', array(
		'label'              => __( 'Award Name', 'pitchfork-capstone' ),
		'get_value_callback' => 'capstone_award_bindings_callback'
	) );
}

function capstone_program_string_bindings_callback() {
    $post_id = get_the_ID();
    if ( $post_id ) {
        $program = wp_get_post_terms( $post_id, 'program', array( 'fields' => 'names' ) );
        return ! empty( $program ) ? implode( ', ', $program ) : 'No program selected';
    }
    return 'No program selected';
}

// function capstone_program_date_bindings_callback() {
//     $post_id = get_the_ID();
//     if ( $post_id ) {
//         $programdate = wp_get_post_terms( $post_id, 'program_date', array( 'fields' => 'names' ) );
//         return ! empty( $programdate ) ? implode( ', ', $programdate ) : 'No program date selected';
//     }
//     return 'No program date selected';
// }

function capstone_award_bindings_callback() {
    $post_id = get_the_ID();
    if ( $post_id ) {
        $awardname = wp_get_post_terms( $post_id, 'award', array( 'fields' => 'names' ) );
        return ! empty( $awardname ) ? implode( ', ', $awardname ) : 'No program date selected';
    }
    return 'No award selected';
}
