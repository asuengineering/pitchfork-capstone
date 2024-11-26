<?php
/**
 * Capstone: Research Theme
 *
 * - Provides a single block for displaying the research theme details.
 * - Applicable for a whole list of themes or a single theme
 *
 * @package Pitchfork_Capstone
 */

// Load selected values from block.
$display		= get_field( 'sponsor_block_display');
$selected		= get_field( 'sponsor_block_selected');
$use_desc		= get_field( 'sponsor_block_description_yn');

$spacing = pitchfork_blocks_acf_calculate_spacing( $block );

// Retrieve additional classes from the 'advanced' field in the editor.
$additional_classes = '';
if ( ! empty( $block['className'] ) ) {
	$additional_classes = $block['className'];
}

$sponsors = array();

// Use the taxonomy for the current post for the display if indicated.
// Otherwise, grab the user supplied list of sponsors and use those.
if ( $display === 'current' ) {
	$post_id = get_the_ID();

    if ( $post_id ) {
		$sponsors = wp_get_post_terms( $post_id, 'sponsor');
	}

} else {
	// Handles edge case where no user selection has been made yet.
	// Variable remains empty if $select is null/false.
	if ( $selected ) {
		$sponsors[] = $selected;
	}
}


/**
 * Render logic.
 * Display a "nothing selected" message only for the block editor if necessary.
 * Render the selected sponsors otherwise,
 */

 if ( ! $sponsors ) {
	if ( $is_preview ) {
		echo '<div class="alert alert-info" role="alert">';

		if ( $display === 'current' ) {
			echo '<div class="alert-content">No terms selected for the capstone.</div></div>';
		} else {
			echo '<div class="alert-content">No sponsor selected.</div></div>';
		}
	}

} else {

	echo '<div class="sponsors ' . esc_html( $additional_classes ) . '" style="' . $spacing . '">';

	foreach ($sponsors as $sponsor) {

		$sponsor_logo = get_field('sponsor_image', $sponsor);
		$sponsor_url = get_field('sponsor_url', $sponsor);
		$sponsor_termlink = get_term_link($sponsor);

		echo '<div class="media project-sponsor">';
		echo '<img class="img-fluid" src="' . esc_url( $sponsor_logo['url'] ) . '" alt="' . esc_attr( $sponsor_logo['alt'] ) . '" />';
		echo '<p class="lead sponsor-name">' . esc_html( $sponsor->name ) . '</p>';

		echo '<p class="sponsor-data">';
		echo '<a href="' . $sponsor_termlink . '">' . $sponsor->count . ' sponsored projects</a> &middot; ';
		echo '<a href="' . esc_html( $sponsor_url ) . '" target="_blank">Visit</a>';
		echo '</p>';

		if ( $use_desc ) {
			echo wp_kses_post( $sponsor->description );
		}

		echo '</div>';

	}

	echo '</div>';
}
