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
$program_index = array();
$program_date_index = array();
$sponsor_index = array();
$research_tag_index = array();
$team_index = array();
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
                if (!isset($program_index[$term->slug])) {
                    $program_index[$term->slug] = $term->name;
                }
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
            if (!isset($team_index[$team_slug])) {
                $team_index[$team_slug] = $team_name;
            }
        }

        $class_attr = implode(' ', array_unique(array_map('sanitize_html_class', $post_class_list)));

        // Card content
        $title = get_the_title();
        $excerpt = get_the_excerpt();
        $permalink = get_permalink();

        $card_output = '<div class="' . esc_attr($class_attr) . '" data-title="' . esc_attr($title) . '" data-team="' . esc_attr($team_name) . '">';
        $card_output .= '<div class="card">';
        if (has_post_thumbnail()) {
            $card_output .= '<img src="' . get_the_post_thumbnail_url($post_id, 'medium') . '" alt="' . esc_attr($title) . '" class="card-img-top" />';
        }
        $card_output .= '<div class="card-body">';
        $card_output .= '<h5 class="card-title"><a href="' . esc_url($permalink) . '">' . esc_html($title) . '</a></h5>';
        if (!empty($team_name)) {
            $card_output .= '<p class="card-team">Team: ' . esc_html($team_name) . '</p>';
        }
        $card_output .= '<p class="card-text">' . esc_html($excerpt) . '</p>';
        $card_output .= '</div></div></div>';

        $cards_output .= $card_output;
    }

    // Build filters
    $filter_output = '<div class="isotope-filters">';

    $filter_output .= '<div class="filter-group"><label for="filter-program-date">Program Date</label><select id="filter-program-date" class="filter-select" data-filter-group="program_date"><option value="">All Program Dates</option>';
    foreach ($program_date_index as $slug => $name) {
        $filter_output .= '<option value=".program_date-' . esc_attr($slug) . '">' . esc_html($name) . '</option>';
    }
    $filter_output .= '</select></div>';

    $filter_output .= '<div class="filter-group"><label for="filter-program">Program</label><select id="filter-program" class="filter-select" data-filter-group="program"><option value="">All Programs</option>';
    foreach ($program_index as $slug => $name) {
        $filter_output .= '<option value=".program-' . esc_attr($slug) . '">' . esc_html($name) . '</option>';
    }
    $filter_output .= '</select></div>';

    $filter_output .= '<div class="filter-group"><label for="filter-sponsor">Sponsor</label><select id="filter-sponsor" class="filter-select" data-filter-group="sponsor"><option value="">All Sponsors</option>';
    foreach ($sponsor_index as $slug => $name) {
        $filter_output .= '<option value=".sponsor-' . esc_attr($slug) . '">' . esc_html($name) . '</option>';
    }
    $filter_output .= '</select></div>';

    $filter_output .= '<div class="filter-group"><label for="filter-research-tag">Research Tag</label><select id="filter-research-tag" class="filter-select" data-filter-group="research_tag"><option value="">All Research Tags</option>';
    foreach ($research_tag_index as $slug => $name) {
        $filter_output .= '<option value=".research_tag-' . esc_attr($slug) . '">' . esc_html($name) . '</option>';
    }
    $filter_output .= '</select></div>';

    $filter_output .= '<div class="filter-group"><label for="filter-team">Team Name</label><select id="filter-team" class="filter-select" data-filter-group="team"><option value="">All Teams</option>';
    foreach ($team_index as $slug => $name) {
        $filter_output .= '<option value=".team-' . esc_attr($slug) . '">' . esc_html($name) . '</option>';
    }
    $filter_output .= '</select></div>';

    $filter_output .= '<div class="filter-group"><label for="filter-search">Search</label><input type="text" id="filter-search" class="filter-search" placeholder="' . esc_attr($search_placeholder) . '" /></div>';

    $filter_output .= '</div>';

    $output = $filter_output . '<div class="isotope-grid">' . $cards_output . '</div>';

}

/**
 * Output the block
 */
echo $blockwrap . $output . '</div>';
?>
