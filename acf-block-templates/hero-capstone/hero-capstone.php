<?php
/**
 * Hero: Capstone
 * - Grid based lockup of several core inner blocks.
 * - Will replace the post title when used in the template.
 *
 * @package Pitchfork_Blocks
 */

/**
 * Additional margin/padding settings
 * Returns a string for inclusion with style=""
 * --------------------
 */
$spacing = pitchfork_blocks_acf_calculate_spacing( $block );

// Retrieve additional classes from the 'advanced' field in the editor.
$additional_classes = '';
if ( ! empty( $block['className'] ) ) {
	$additional_classes = $block['className'];
}

// Retreive alignment setting from toolbar.
// $alignment = 'alignfull';
if ( ! empty( $block['align'] ) ) {
	$alignment = ' alignfull';
}

// Sets InnerBlocks with default content and default block arrangement.
$allowed_blocks = array( 'core/html', 'core/group', 'core/heading', 'core/post-title', 'core/paragraph', 'core/post-featured-image', 'core/post-terms' );
$template       = array(
	array(
		'acf/subtitle',
		array(
			'uds_subtitle_text'            => 'Example subtitle',
			'uds_subtitle_highlight_color' => 'highlight-black',
		),
	),
	array(
		'core/heading',
		array(
			'level'   => 1,
			'content' => 'Your Hero Headline',
		),
	),
	array(
		'core/group',
		array(
			'className' => 'content',
		),
		array(
			array(
				'core/paragraph',
				array(
					'content' => 'Example hero paragraph text.',
				),
			),
		),
	),
	array(
		'core/buttons',
		array(
			'className' => 'btn-row',
		),
		array(
			array(
				'core/button',
				array(
					'className' => 'is-style-',
				),
			),
		),
	),
);

$template       = array(
	array(
		'core/post-featured-image',
		array(
			'lock' => array(
				'move' => true,
				'remove' => false
			)
		),
		array()
	),
	array(
		'core/post-terms',
		array(
			'term' => 'program_date',
			'lock' => array(
				'move' => true,
				'remove' => false
			),
			'className' => 'like-h3-gold'
		),
		array()
	),
	array(
		'core/post-title',
		array(
			'level' => 1,
			'lock' => array(
				'move' => true,
				'remove' => false
			),
			'className' => 'article'
		),
		array()
	),
	array(
		'core/group',
		array(
			'lock' => array(
				'move' => true,
				'remove' => false
			),
			'metadata' => array(
				'name' => 'Program name lockup'
			),
			'style' => array(
				'spacing' => array(
					'blockGap' => '1.42rem',
					'padding' => array(
						'bottom' => 'var:preset|spacing|uds-size-1'
					)
				)
			),
			'layout' => array(
				'type' => 'flex',
				'flexWrap' => 'nowrap'
			)
		),
		array(
			array(
				'outermost/icon-block',
				array(
					'iconName' => '',
					'width' => '1.5rem',
					'lock' => array(
						'move' => true,
						'remove' => false
					),
					'metadata' => array(
						'name' => 'Icon - Clipboard'
					)
				),
				array()
			),
			array(
				'core/paragraph',
				array(
					'lock' => array(
						'move' => true,
						'remove' => false
					),
					'metadata' => array(
						'bindings' => array(
							'content' => array(
								'source' => 'capstone/program-string',
								'args' => array()
							)
						),
						'name' => 'Program Name'
					),
					'className' => 'is-style-lead'
				),
				array()
			),

		)
	),
	array(
		'core/group',
		array(
			'lock' => array(
				'move' => true,
				'remove' => false
			),
			'metadata' => array(
				'name' => 'Student names lockup'
			),
			'style' => array(
				'spacing' => array(
					'blockGap' => 'var:preset|spacing|uds-size-2'
				)
			),
			'layout' => array(
				'type' => 'flex',
				'flexWrap' => 'nowrap'
			)
		),
		array(
			array(
				'outermost/icon-block',
				array(
					'iconName' => '',
					'width' => '2rem',
					'lock' => array(
						'move' => true,
						'remove' => true
					),
					'metadata' => array(
						'name' => 'Icon - People (FA)'
					),
					'content' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M320 64l-40 0-9.6 0C263 27.5 230.7 0 192 0s-71 27.5-78.4 64L104 64 64 64C28.7 64 0 92.7 0 128L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-320c0-35.3-28.7-64-64-64zM80 112l0 24c0 13.3 10.7 24 24 24l88 0 88 0c13.3 0 24-10.7 24-24l0-24 16 0c8.8 0 16 7.2 16 16l0 320c0 8.8-7.2 16-16 16L64 464c-8.8 0-16-7.2-16-16l0-320c0-8.8 7.2-16 16-16l16 0zm88-32a24 24 0 1 1 48 0 24 24 0 1 1 -48 0zM136 272a24 24 0 1 0 -48 0 24 24 0 1 0 48 0zm40-16c-8.8 0-16 7.2-16 16s7.2 16 16 16l96 0c8.8 0 16-7.2 16-16s-7.2-16-16-16l-96 0zm0 96c-8.8 0-16 7.2-16 16s7.2 16 16 16l96 0c8.8 0 16-7.2 16-16s-7.2-16-16-16l-96 0zm-64 40a24 24 0 1 0 0-48 24 24 0 1 0 0 48z"></path></svg>',
				),
				array()
			),
			array(
				'core/paragraph',
				array(
					'className' => 'is-style-lead',
					'content' => 'Type student names',
				),
				array()
			),

		)
	)
);


// Block output.
echo '<div class="hero-capstone-wrap ' . esc_html( $alignment ) . esc_html( $additional_classes ) . '" style="' . $spacing . '">';
echo '<div class="hero-capstone">';
echo '<InnerBlocks allowedBlocks="' . esc_attr( wp_json_encode( $allowed_blocks ) ) . '" template="' . esc_attr( wp_json_encode( $template ) ) . '" />';
echo '</div></div>';
