<?php
/**
 * Pitchfork-Capstone, block bindings
 *
 * @package pitchfork-capstone
 */

add_action( 'init', 'pitchfork_capstone_register_block_bindings' );

function pitchfork_capstone_register_block_bindings() {

	register_block_bindings_source( 'capstone/program-string', array(
		'label'              => __( 'Program Name', 'pitchfork-capstone' ),
		'get_value_callback' => 'capstone_program_string_bindings_callback'
	) );

	register_block_bindings_source( 'capstone/award', array(
		'label'              => __( 'Award Name', 'pitchfork-capstone' ),
		'get_value_callback' => 'capstone_award_bindings_callback'
	) );

		register_block_bindings_source( 'capstone/team-name', array(
		'label'              => __( 'Team Name', 'pitchfork-capstone' ),
		'get_value_callback' => 'capstone_team_name_bindings_callback'
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

function capstone_award_bindings_callback() {
    $post_id = get_the_ID();
    if ( $post_id ) {
        $awardname = wp_get_post_terms( $post_id, 'award', array( 'fields' => 'names' ) );
        return ! empty( $awardname ) ? implode( ', ', $awardname ) : 'No program date selected';
    }
    return 'No award selected';
}

function capstone_team_name_bindings_callback() {
    $post_id = get_the_ID();

	if ( function_exists( 'get_field' ) ) {
        $team_name = get_field( 'capstone_meta_team_name', $post_id );
		do_action('qm/debug', $team_name);

		if ( ! empty ( $team_name ) ) {
			return wp_kses_post( $team_name );
		}
    }

    return 'No team name';
}
