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

/**
 * Custom auto excerpt generation for post type = projects.
 *
 * - Replaces default wp_trim_excerpt behavior for 'project' post type.
 * - Applies content filters, strips shortcodes and HTML tags.
 * - Removes leading "Summary" or "Abstract" sections from content.
 * - Truncates excerpt to 55 words (using excerpt_length and excerpt_more).
 * - Returns sanitized, trimmed excerpt for use in archive, taxonomy, etc.
 */
function custom_trim_excerpt_remove_headers($excerpt, $post = null) {
    global $post;

    if (!$post || $post->post_type !== 'project') {
        return $excerpt;
    }

    $content = $post->post_content;

    // Apply content filters and strip shortcodes to approximate excerpt behavior
    $content = apply_filters('the_content', $content);
    $content = strip_shortcodes($content);
    $content = wp_strip_all_tags($content);

    // Remove line breaks to help regex parse more smoothly
    $content = preg_replace('/\s+/', ' ', $content);

	// do_action('qm/debug', $content);

    // Use regex to remove leading "Summary" or "Abstract" sections
    if (preg_match('/(?:Summary|Abstract)\s*(.*)/i', $content, $matches)) {
		$trimmed = trim($matches[1]);
    } else {
        $trimmed = $content;
    }

    // Truncate to standard excerpt length
    $excerpt_length = apply_filters('excerpt_length', 55);
    $excerpt_more = apply_filters('excerpt_more', ' [&hellip;]');
    $words = preg_split("/\s+/", $trimmed, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);

    if (count($words) > $excerpt_length) {
        array_pop($words);
        $trimmed = implode(' ', $words) . $excerpt_more;
    } else {
        $trimmed = implode(' ', $words);
    }

    return $trimmed;
}
add_filter('wp_trim_excerpt', 'custom_trim_excerpt_remove_headers', 10, 2);
