<?php
/**
 * Declare custom post types for the theme.
 * Yes, this is "supposed" to be in a plugin. ¯\_(ツ)_/¯
 *
 * @package uds-wordpress-child-theme
 */

/**
 * CPT: Project
 */

 function pitchfork_capstone_project_cpt() {
    $labels = array(
        'name'               => _x('Projects', 'Post type general name', 'textdomain'),
        'singular_name'      => _x('Project', 'Post type singular name', 'textdomain'),
        'menu_name'          => _x('Projects', 'Admin Menu text', 'textdomain'),
        'name_admin_bar'     => _x('Project', 'Add New on Toolbar', 'textdomain'),
        'add_new'            => __('Add New', 'textdomain'),
        'add_new_item'       => __('Add New Project', 'textdomain'),
        'new_item'           => __('New Project', 'textdomain'),
        'edit_item'          => __('Edit Project', 'textdomain'),
        'view_item'          => __('View Project', 'textdomain'),
        'all_items'          => __('All Projects', 'textdomain'),
        'search_items'       => __('Search Projects', 'textdomain'),
        'parent_item_colon'  => __('Parent Projects:', 'textdomain'),
        'not_found'          => __('No projects found.', 'textdomain'),
        'not_found_in_trash' => __('No projects found in Trash.', 'textdomain'),
    );

	$args = array(
		'label'                 => __( 'Capstone Project', 'pitchfork-capstone' ),
		'description'           => __( 'A student project', 'pitchfork-capstone' ),
		'labels'                => $labels,
		'supports'           	=> array('title', 'editor', 'thumbnail', 'page-attributes', 'excerpt', 'revisions'),
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 20,
		'menu_icon'             => 'dashicons-portfolio',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'show_in_rest'          => true,
	);

    register_post_type('project', $args);
}
add_action('init', 'pitchfork_capstone_project_cpt');
add_theme_support( 'post-thumbnails' );

/**
 * TAX: program
 * Supports CPT: project
 */

 function pitchfork_capstone_program_tax() {
    $labels = array(
        'name'              => _x('Program', 'taxonomy general name', 'textdomain'),
        'singular_name'     => _x('Program', 'taxonomy singular name', 'textdomain'),
        'search_items'      => __('Search Program', 'textdomain'),
        'all_items'         => __('All Programs', 'textdomain'),
        'edit_item'         => __('Edit Program', 'textdomain'),
        'update_item'       => __('Update Program', 'textdomain'),
        'add_new_item'      => __('Add New Program', 'textdomain'),
        'new_item_name'     => __('New Program', 'textdomain'),
        'menu_name'         => __('Program', 'textdomain'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_in_rest'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => array('slug' => 'program'),
    );

    register_taxonomy('program', 'project', $args);
}
add_action('init', 'pitchfork_capstone_program_tax');

/**
 * TAX: program_date
 * Supports CPT: project
 */

function pitchfork_capstone_programdate_tax() {
    $labels = array(
        'name'              => _x('Program Dates', 'taxonomy general name', 'textdomain'),
        'singular_name'     => _x('Program Date', 'taxonomy singular name', 'textdomain'),
        'search_items'      => __('Search Program Dates', 'textdomain'),
        'all_items'         => __('All Program Dates', 'textdomain'),
        'edit_item'         => __('Edit Program Date', 'textdomain'),
        'update_item'       => __('Update Program Date', 'textdomain'),
        'add_new_item'      => __('Add New Program Date', 'textdomain'),
        'new_item_name'     => __('New Program Date Name', 'textdomain'),
        'menu_name'         => __('Program Dates', 'textdomain'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_in_rest'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => array('slug' => 'program-date'),
    );

    register_taxonomy('program_date', 'project', $args);
}
add_action('init', 'pitchfork_capstone_programdate_tax');


/**
 * TAX: award
 * Supports CPT: project
 */

 function pitchfork_capstone_award_tax() {
    $labels = array(
        'name'              => _x('Award', 'taxonomy general name', 'textdomain'),
        'singular_name'     => _x('Award', 'taxonomy singular name', 'textdomain'),
        'search_items'      => __('Search Awards', 'textdomain'),
        'all_items'         => __('All Awards', 'textdomain'),
        'edit_item'         => __('Edit Award', 'textdomain'),
        'update_item'       => __('Update Award', 'textdomain'),
        'add_new_item'      => __('Add New Award', 'textdomain'),
        'new_item_name'     => __('New Award Name', 'textdomain'),
        'menu_name'         => __('Awards', 'textdomain'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_in_rest'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
    );

    register_taxonomy('award', 'project', $args);
}
add_action('init', 'pitchfork_capstone_award_tax');

/**
 * TAX: Sponsor
 * Supports CPT: project
 */

 function pitchfork_capstone_sponsor_tax() {
    $labels = array(
        'name'              => _x('Sponsor', 'taxonomy general name', 'textdomain'),
        'singular_name'     => _x('Sponsor', 'taxonomy singular name', 'textdomain'),
        'search_items'      => __('Search Sponsors', 'textdomain'),
        'all_items'         => __('All Sponsors', 'textdomain'),
        'edit_item'         => __('Edit Sponsor', 'textdomain'),
        'update_item'       => __('Update Sponsor', 'textdomain'),
        'add_new_item'      => __('Add New Sponsor', 'textdomain'),
        'new_item_name'     => __('New Sponsor Name', 'textdomain'),
        'menu_name'         => __('Sponsors', 'textdomain'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_in_rest'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
    );

    register_taxonomy('sponsor', 'project', $args);
}
add_action('init', 'pitchfork_capstone_sponsor_tax');

/**
 * TAX: research_theme (rewrite: theme)
 * Supports CPT: project
 */

 function pitchfork_capstone_research_theme_tax() {
    $labels = array(
        'name'              => _x('Research Theme', 'taxonomy general name', 'textdomain'),
        'singular_name'     => _x('Research Theme', 'taxonomy singular name', 'textdomain'),
        'search_items'      => __('Search Research Themes', 'textdomain'),
        'all_items'         => __('All Research Themes', 'textdomain'),
        'edit_item'         => __('Edit Research Theme', 'textdomain'),
        'update_item'       => __('Update Research Theme', 'textdomain'),
        'add_new_item'      => __('Add New Research Theme', 'textdomain'),
        'new_item_name'     => __('New Research Theme Name', 'textdomain'),
        'menu_name'         => __('Research Themes', 'textdomain'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_in_rest'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
		'rewrite'           => array('slug' => 'theme'),
    );

    register_taxonomy('research_theme', 'project', $args);
}
add_action('init', 'pitchfork_capstone_research_theme_tax');
