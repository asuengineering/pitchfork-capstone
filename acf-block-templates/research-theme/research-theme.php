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
$display		= get_field( 'rtblock_display');
$selected		= get_field( 'rtblock_selected');
$use_desc		= get_field( 'rtblock_description_yn');

$themes = array();

// Using the taxonomy for the current post to display the content if indicated.
// Otherwise, grab the user suppliedlist of themes and use those.
if ( $display === 'current' ) {
	$post_id = get_the_ID();
    if ( $post_id ) {
		$themes = wp_get_post_terms( $post_id, 'research_theme');
	}
} else {
	// Handles edge case where no selection has been made yet.
	if ( $selected ) {
		$themes[] = $selected;
	}
}

/**
 * Render logic.
 * Display a "nothing selected" message only for the block editor if necessary.
 * Render the selected theme otherwise,
 */

if ( ! $themes ) {
	if ( $is_preview ) {
		echo '<div class="alert alert-info" role="alert">';

		if ( $display === 'current' ) {
			echo '<div class="alert-content">No terms selected for the capstone.</div></div>';
		} else {
			echo '<div class="alert-content">No research themes selected.</div></div>';
		}
	}
} else {

	foreach ($themes as $theme) {

		$themeicon = get_field('researchtheme_icon', $theme);

		echo '<div class="media research-theme">';
		echo '<img class="img-fluid" src="' . esc_url( $themeicon['url'] ) . '" alt="' . esc_attr( $themeicon['alt'] ) . '" />';
		echo '<div class="media-body"><p class="like-h3">' . esc_html( $theme->name ) . '</p>';
		if ( $use_desc ) {
			echo wp_kses_post( $theme->description );
		}
		echo '</div></div>';

	}
}
