<?php
/**
 * Capstone: Project Sponsor
 *
 * - Provides a single block for displaying the project sponsor details.
 * - Provides multiple display options for flexability.
 *
 * @package Pitchfork_Capstone
 */

/**
 * Set initial get_field declarations.
 */

// Load selected values from block.
$display		= get_field( 'sponsor_block_display');
$selected		= get_field( 'sponsor_block_selected');
$use_desc		= get_field( 'sponsor_block_description_yn');
$use_link		= get_field( 'sponsor_block_link_yn');
$use_archive	= get_field( 'sponsor_block_archive_yn');
$verb			= get_field( 'sponsor_block_affiliation_verb');
$unit_text		= get_field( 'sponsor_block_unit_text');

/**
 * Set block classes
 * - Get additional classes from the 'advanced' field in the editor.
 * - Get alignment setting from toolbar if enabled in theme.json, or set default value
 * - Include any default classs for the block in the intial array.
 */

$block_attr = array( 'media', 'project-sponsor');
if ( ! empty( $block['className'] ) ) {
	$block_attr[] = $block['className'];
}

/**
 * Additional margin/padding settings
 * Returns a string for inclusion with style=""
 */
$spacing = pitchfork_blocks_acf_calculate_spacing( $block );

/**
 * Include block.json support for HTML anchor.
 */
$anchor = '';
if ( ! empty( $block['anchor'] ) ) {
	$anchor = 'id="' . $block['anchor'] . '"';
}

/********** BUILD ************/

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

	// This loop will currently only run once.
	foreach ($sponsors as $sponsor) {

		$sponsor_logo = get_field('sponsor_image', $sponsor);
		$sponsor_url = get_field('sponsor_url', $sponsor);
		$sponsor_termlink = get_term_link($sponsor);
		$block_attr[] = 'sponsor-' . $sponsor->slug;


		// Create the outer wrapper for the block output.
		$attr  = implode( ' ', $block_attr );
		$block = '<div ' . $anchor . ' class="' . $attr . '" style="' . $spacing . '">';

		$block .= '<img class="img-fluid" src="' . esc_url( $sponsor_logo['url'] ) . '" alt="' . esc_attr( $sponsor_logo['alt'] ) . '" />';
		$block .= '<div class="sponsor-details">';
		$block .= '<h4 class="sponsor-name">' . esc_html( $sponsor->name ) . '</h4>';

		if ($unit_text) {
			$block .= '<p class="lead unit-text">' . wp_kses_post( $unit_text ) . '</p>';
		}

		if (($use_archive) || ($use_link)) {
			$block .= '<p class="sponsor-data">';

			if ($use_archive) {
				$block .= '<a href="' . $sponsor_termlink . '">' . $sponsor->count . ' ' . $verb . ' projects</a>';
			}

			if (($use_link) && ($use_archive)) {
				$block .= " &middot; ";
			}

			if ($use_link) {
				if ($use_archive) {
					$block .= '<a href="' . esc_html( $sponsor_url ) . '" target="_blank">Visit</a>';
				} else {
					$block .= '<a href="' . esc_html( $sponsor_url ) . '" target="_blank">' . esc_html( $sponsor_url ) . '</a>';
				}
			}

			$block .= '</p>';
		}

		if ( $use_desc ) {
			$block .= wp_kses_post( $sponsor->description );
		}

		$block .= '</div></div>';
		echo $block;

	}
}
