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

if ( $display === 'current' ) {
	$post_id = get_the_ID();

    if ( $post_id ) {
		$themes = wp_get_post_terms( $post_id, 'research_theme');
	}

} else {
	$themes[] = $selected;
}

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
