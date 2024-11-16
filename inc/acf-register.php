<?php
/**
 * Additional functions for Advanced Custom Fields.
 *
 * Contents:
 *   - Load path for ACF groups from the parent.
 *   - Register custom blocks for the theme.
 *
 * @package pitchfork-child
 */

/**
 * Add additional loading point for the parent theme's ACF groups.
 *
 * @return $paths
 */
function pitchfork_load_parent_theme_field_groups( $paths ) {
	$path = get_template_directory() . '/acf-json';
	$paths[] = $path;
	return $paths;
}
add_filter( 'acf/settings/load_json', 'pitchfork_load_parent_theme_field_groups' );

/**
 * Create save point for the child theme's ACF groups.
 *
 * @return $path
 */
function pitchfork_child_theme_field_groups( $path ) {
	$path = get_stylesheet_directory() . '/acf-json';
	return $path;
}
add_filter( 'acf/settings/save_json', 'pitchfork_child_theme_field_groups' );

/**
 * Register additional custom blocks for the theme.
 */
function pitchfork_capstone_theme_acf_init_block_types() {
	// Array of block folders to use. Each contains a block.json file.
	$block_includes = array(
		'/hero-capstone',               // Capstone Hero
		'/research-theme',              // Research Theme Block
		'/project-sponsor',             // Sponsor
	);

	// Loop through array items and register each block.
	foreach ( $block_includes as $folder ) {
		register_block_type( get_stylesheet_directory() . '/acf-block-templates' . $folder );
	}
}
add_action( 'acf/init', 'pitchfork_capstone_theme_acf_init_block_types' );
