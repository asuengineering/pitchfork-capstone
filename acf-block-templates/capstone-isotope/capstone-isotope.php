<?php
/**
 * Pitchfork Capstone - Capstone Isotope Block
 *
 * Interactive block displaying capstone projects with isotope filters.
 *
 * @package pitchfork-capstone
 */

/**
 * Set initial get_field declarations.
 */

$query_programs = get_field('capstone_isotope_programs') ?? [];
$query_program_dates = get_field('capstone_isotope_program_dates') ?? [];
$query_research_tags = get_field('capstone_isotope_research_tags') ?? [];
$query_size = get_field('capstone_isotope_query_size') ?? 50;
$search_placeholder = get_field('capstone_isotope_search_placeholder') ?? 'Search projects...';

/**
 * Set block classes
 */

$block_attr = array('capstone-isotope');
if (!empty($block['className'])) {
    $block_attr[] = $block['className'];
}

/**
 * Additional margin/padding settings
 */
$spacing = pitchfork_blocks_acf_calculate_spacing($block);

/**
 * Include block.json support for HTML anchor.
 */
$anchor = '';
if (!empty($block['anchor'])) {
    $anchor = 'id="' . $block['anchor'] . '"';
}

/**
 * Create the outer wrapper for the block output.
 */
$attr = implode(' ', $block_attr);
$blockwrap = '<div ' . $anchor . ' class="' . $attr . '" style="' . $spacing . '">';
$output = '';

/**
 * Build tax_query for WP_Query
 */
$tax_query = array('relation' => 'AND');

if (!empty($query_programs)) {
    $tax_query[] = array(
        'taxonomy' => 'program',
        'field'    => 'term_id',
        'terms'    => $query_programs,
        'operator' => 'IN',
    );
}

if (!empty($query_program_dates)) {
    $tax_query[] = array(
        'taxonomy' => 'program_date',
        'field'    => 'term_id',
        'terms'    => $query_program_dates,
        'operator' => 'IN',
    );
}

if (!empty($query_research_tags)) {
    $tax_query[] = array(
        'taxonomy' => 'research_tag',
        'field'    => 'term_id',
        'terms'    => $query_research_tags,
        'operator' => 'IN',
    );
}

/**
 * Query loop for projects.
 */
$args = array(
    'post_type'      => 'project',
    'post_status'    => 'publish',
    'posts_per_page' => (int) $query_size,
    'orderby'        => 'date',
    'order'          => 'DESC',
);

if (!empty($tax_query) && count($tax_query) > 1) {
    $args['tax_query'] = $tax_query;
}

// Collections for filter selects
$program_date_index = array();
$sponsor_index = array();
$research_tag_index = array();
$title_index = array(); // For search, but actually we'll use data attributes

$the_query = new WP_Query($args);

if ($the_query->have_posts()) {

    $cards_output = '';

    while ($the_query->have_posts()) {
        $the_query->the_post();
        $post_id = get_the_ID();

        // Assemble classes
        $post_class_list = array('project-card');

        // Program terms
        $program_terms = get_the_terms($post_id, 'program');
        if ($program_terms && !is_wp_error($program_terms)) {
            foreach ($program_terms as $term) {
                $slug = sanitize_html_class($term->slug);
                $post_class_list[] = 'program-' . $slug;
            }
        }

        // Program Date terms
        $program_date_terms = get_the_terms($post_id, 'program_date');
        if ($program_date_terms && !is_wp_error($program_date_terms)) {
            foreach ($program_date_terms as $term) {
                $slug = sanitize_html_class($term->slug);
                $post_class_list[] = 'program_date-' . $slug;
                if (!isset($program_date_index[$term->slug])) {
                    $program_date_index[$term->slug] = $term->name;
                }
            }
        }

        // Sponsor terms
        $sponsor_terms = get_the_terms($post_id, 'sponsor');
        if ($sponsor_terms && !is_wp_error($sponsor_terms)) {
            foreach ($sponsor_terms as $term) {
                $slug = sanitize_html_class($term->slug);
                $post_class_list[] = 'sponsor-' . $slug;
                if (!isset($sponsor_index[$term->slug])) {
                    $sponsor_index[$term->slug] = $term->name;
                }
            }
        }

        // Research Tag terms
        $research_tag_terms = get_the_terms($post_id, 'research_tag');
        if ($research_tag_terms && !is_wp_error($research_tag_terms)) {
            foreach ($research_tag_terms as $term) {
                $slug = sanitize_html_class($term->slug);
                $post_class_list[] = 'research_tag-' . $slug;
                if (!isset($research_tag_index[$term->slug])) {
                    $research_tag_index[$term->slug] = $term->name;
                }
            }
        }

        // Team name
        $team_name = get_field('capstone_meta_team_name', $post_id);
        if (!empty($team_name)) {
            $team_slug = sanitize_html_class($team_name);
            $post_class_list[] = 'team-' . $team_slug;
        }

        $class_attr = implode(' ', array_unique(array_map('sanitize_html_class', $post_class_list)));

        // Card content
        $title = get_the_title();
        $excerpt = get_the_excerpt();
        $permalink = get_permalink();

		$card_output = '<div class="card ' . esc_attr($class_attr) . '" data-title="' . esc_attr($title) . '" data-team="' . esc_attr($team_name) . '">';

		// Image
		if (has_post_thumbnail()) {
			$card_output .= '<img src="' . get_the_post_thumbnail_url($post_id, 'medium') . '" alt="' . esc_attr($title) . '" class="card-img-top" />';
		}

		// Header (title)
		$card_output .= '<div class="card-header">';
		$card_output .= '<h3 class="card-title">';
		$card_output .= '<a href="' . esc_url($permalink) . '">' . esc_html($title) . '</a>';
		$card_output .= '</h3>';
		$card_output .= '</div>';

		// Body (ONLY description now)
		$card_output .= '<div class="card-body">';
		$card_output .= '<p class="card-text">' . esc_html($excerpt) . '</p>';
		$card_output .= '</div>';

		// Meta section OUTSIDE body
		$card_output .= '<div class="card-meta-wrapper">';
		$card_output .= '<dl class="card-meta">';

		// PROGRAM DATE
		if (!empty($program_date_terms) && !is_wp_error($program_date_terms)) {
			$date_names = wp_list_pluck($program_date_terms, 'name');
			$card_output .= '<dt>Program Date</dt><dd>' . esc_html(implode(', ', $date_names)) . '</dd>';
		}

		// TEAM (for display + search)
		if (!empty($team_name)) {
			$card_output .= '<dt>Team</dt><dd>' . esc_html($team_name) . '</dd>';
		}

		// SPONSOR
		if (!empty($sponsor_terms) && !is_wp_error($sponsor_terms)) {
			$sponsor_names = wp_list_pluck($sponsor_terms, 'name');
			$card_output .= '<dt>Sponsor</dt><dd>' . esc_html(implode(', ', $sponsor_names)) . '</dd>';
		}

		// RESEARCH TAG
		if (!empty($research_tag_terms) && !is_wp_error($research_tag_terms)) {
			$tag_names = wp_list_pluck($research_tag_terms, 'name');
			$card_output .= '<dt>Research</dt><dd>' . esc_html(implode(', ', $tag_names)) . '</dd>';
		}

		$card_output .= '</dl>';

		$card_output .= '</div></div>';

		$cards_output .= $card_output;
	}

	// Filter Output
	$filter_output = '<div class="filter-bar">';

	// -------------------------
	// TOP ROW
	// -------------------------
	$filter_output .= '<div class="filter-bar-top">';

	// LEFT SIDE (75%)
	$filter_output .= '<div class="filter-controls">';

	/* =========================
	SEARCH CONTROLS (default)
	========================= */
	$filter_output .= '<div class="control-set control-search">';
;
	$filter_output .= '<form id="search" class="uds-form">';
	$filter_output .= '<div class="form-group">';
	$filter_output .= '<label for="filter-search">Search projects</label>';
	$filter_output .= '<div class="search-input-row">';
	$filter_output .= '<input type="text" id="filter-search" class="filter form-control" placeholder="' . esc_attr($search_placeholder) . '" />';
	$filter_output .= '<button type="button" class="btn btn-maroon clear-search">Clear</button>';
	$filter_output .= '</div></div></form>';

	$filter_output .= '</div>';

	/* =========================
	FILTER CONTROLS (hidden)
	========================= */
	$filter_output .= '<div class="control-set control-filters d-none">';

	// PROGRAM DATE
	$filter_output .= '<form id="program-date" class="uds-form">';
	$filter_output .= '<div class="form-group">';
	$filter_output .= '<label for="filter-program-date">Program Date</label>';
	$filter_output .= '<select id="filter-program-date" class="filter form-select" data-filter-group="program_date">';
	$filter_output .= '<option value="">-- select an option --</option>';
	foreach ($program_date_index as $slug => $name) {
		$filter_output .= '<option value=".program_date-' . esc_attr($slug) . '">' . esc_html($name) . '</option>';
	}
	$filter_output .= '</select></div></form>';

	// SPONSOR
	$filter_output .= '<form id="sponsor" class="uds-form">';
	$filter_output .= '<div class="form-group">';
	$filter_output .= '<label for="filter-sponsor">Sponsor</label>';
	$filter_output .= '<select id="filter-sponsor" class="filter form-select" data-filter-group="sponsor">';
	$filter_output .= '<option value="">-- select an option --</option>';
	foreach ($sponsor_index as $slug => $name) {
		$filter_output .= '<option value=".sponsor-' . esc_attr($slug) . '">' . esc_html($name) . '</option>';
	}
	$filter_output .= '</select></div></form>';

	// RESEARCH TAG
	$filter_output .= '<form id="research-tag" class="uds-form">';
	$filter_output .= '<div class="form-group">';
	$filter_output .= '<label for="filter-research-tag">Research Tag</label>';
	$filter_output .= '<select id="filter-research-tag" class="filter form-select" data-filter-group="research_tag">';
	$filter_output .= '<option value="">-- select an option --</option>';
	foreach ($research_tag_index as $slug => $name) {
		$filter_output .= '<option value=".research_tag-' . esc_attr($slug) . '">' . esc_html($name) . '</option>';
	}
	$filter_output .= '</select></div></form>';

	// RESET BUTTON
	$filter_output .= '<button type="button" class="btn btn-maroon reset-filters">Reset</button>';

	$filter_output .= '</div>'; // control-filters

	$filter_output .= '</div>'; // filter-controls



	// RIGHT SIDE, Toggle button.
	$filter_output .= '<div class="filter-toggle-group">';
	$filter_output .= '<button type="button" class="btn btn-dark toggle-filters">Show filters</button>';
	$filter_output .= '</div>'; // toggle group

	$filter_output .= '</div>'; // end top row


	// -------------------------
	// SECOND ROW (COUNT)
	// -------------------------
	$filter_output .= '<div class="filter-count">';
	$filter_output .= 'Now displaying <span class="count-visible">0</span> of <span class="count-total">0</span> projects.';
	$filter_output .= '</div>';

	$filter_output .= '</div>'; // end filter bar

}

/**
 * Output the block
 */
$output = '<div class="capstone-inner">';
$output .= $filter_output;
$output .= '<div class="isotope-results">';
$output .= $cards_output;
$output .= '</div>';
$output .= '</div>';

echo $blockwrap . $output . '</div>';

?>
