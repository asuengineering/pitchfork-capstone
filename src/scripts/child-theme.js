/**
 * Starter JS file for child theme.
 * wp.domReady is a useful JS hook.
*/

wp.domReady(() => {

	/**
	 * Register styles associated with acf/alert
	 */
	wp.blocks.registerBlockStyle(
		'acf/project-sponsor', [{
			name: 'vertical',
			label: 'Vertical',
			isDefault: true,
		}]
	);

	wp.blocks.registerBlockStyle(
		'acf/project-sponsor', [{
			name: 'horizontal',
			label: 'Horizontal',
			isDefault: false,
		}]
	);

});
