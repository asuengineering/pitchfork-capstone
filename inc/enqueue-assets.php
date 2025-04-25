<?php
/**
 * Pitchfork child theme functions and definitions
 *
 * @package pitchfork-child
 */

 // Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue child scripts and styles.
 * - Current hook makes styles and JS files available in the block editor + the front end of the site.
 * - Enqueued as a dependency of theme files provided by the parent theme.
 *
 * Other hooks and actions available if needed.
 */

add_action( 'enqueue_block_assets', 'pitchfork_capstone_assets' );
function pitchfork_capstone_assets() {
	// Get the theme data.
	$the_theme     = wp_get_theme();
	$theme_version = $the_theme->get( 'Version' );

	$css_child_version = $theme_version . '.' . filemtime( get_stylesheet_directory() . '/dist/css/blocks.css' );
	wp_enqueue_style( 'pitchfork-capstone-styles', get_stylesheet_directory_uri() . '/dist/css/blocks.css', array(), $css_child_version );

	$js_child_version = $theme_version . '.' . filemtime( get_stylesheet_directory() . '/dist/js/child-theme.js' );
	wp_enqueue_script( 'pitchfork-capstone-script', get_stylesheet_directory_uri() . '/dist/js/child-theme.js', array(), $js_child_version );
}

// Allow styles added here to also be present within the block editor.
function pitchfork_capstone_blocks_gutenberg_css() {
	add_editor_style( get_stylesheet_directory() . '/dist/css/blocks.css' );
}
add_action( 'after_setup_theme', 'pitchfork_capstone_blocks_gutenberg_css' );
