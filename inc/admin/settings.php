<?php
/**
 * Admin Settings.
 *
 * @package iconic-image-swap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_filter( 'wpsf_register_settings_iconic_wis', 'iconic_wis_settings' );

/**
 * Iconic Image Swap for WooCommerce Settings
 *
 * @param array $settings Plugin settings.
 *
 * @return array
 */
function iconic_wis_settings( $settings ) {
	// Tabs.

	$settings['tabs'][] = array(
		'id'    => 'general',
		'title' => __( 'General', 'iconic-wis' ),
	);

	$settings['tabs'][] = array(
		'id'    => 'effects',
		'title' => __( 'Effect Settings', 'iconic-wis' ),
	);

	$settings['tabs'][] = array(
		'id'    => 'compatibility',
		'title' => __( 'Compatibility Settings', 'iconic-wis' ),
	);

	// Sections.

	$settings['sections'][] = array(
		'tab_id'              => 'general',
		'section_id'          => 'display',
		'section_title'       => __( 'Display Settings', 'iconic-wis' ),
		'section_description' => '',
		'section_order'       => 10,
		'fields'              => array(
			array(
				'id'       => 'effect',
				'title'    => __( 'Effect', 'iconic-wis' ),
				'subtitle' => '',
				'type'     => 'select',
				'default'  => 'fade',
				'choices'  => array(
					'fade'       => __( 'Fade on Hover', 'iconic-wis' ),
					'slide'      => __( 'Slide', 'iconic-wis' ),
					'bullets'    => __( 'Bullets', 'iconic-wis' ),
					'thumbnails' => __( 'Thumbnails', 'iconic-wis' ),
					'zoom'       => __( 'Zoom', 'iconic-wis' ),
					'pip'        => __( 'Picture in Picture', 'iconic-wis' ),
					'modal'      => __( 'Modal Gallery', 'iconic-wis' ),
					'enlarge'    => __( 'Enlarge', 'iconic-wis' ),
					'flip'       => __( 'Flip', 'iconic-wis' ),
				),
			),
		),
	);

	$settings['sections'][] = array(
		'tab_id'              => 'general',
		'section_id'          => 'colours',
		'section_title'       => __( 'Colours', 'iconic-wis' ),
		'section_description' => '',
		'section_order'       => 20,
		'fields'              => array(

			array(
				'id'       => 'icons',
				'title'    => __( 'Icon Colours', 'iconic-wis' ),
				'subtitle' => __( 'The colour of any icons used.', 'iconic-wis' ),
				'type'     => 'color',
				'default'  => '#333333',
			),

		),
	);

	$settings['sections'][] = array(
		'tab_id'              => 'effects',
		'section_id'          => 'fade',
		'section_title'       => __( 'Fade Settings', 'iconic-wis' ),
		'section_description' => '',
		'section_order'       => 10,
		'fields'              => array(

			array(
				'id'      => 'transition_speed',
				'title'   => __( 'Transition Speed (ms)', 'iconic-wis' ),
				'type'    => 'number',
				'default' => 300,
			),

		),
	);

	$settings['sections'][] = array(
		'tab_id'              => 'effects',
		'section_id'          => 'slide',
		'section_title'       => __( 'Slide Settings', 'iconic-wis' ),
		'section_description' => '',
		'section_order'       => 20,
		'fields'              => array(
			array(
				'id'      => 'mode',
				'title'   => __( 'Mode', 'iconic-wis' ),
				'type'    => 'select',
				'default' => 'horizontal',
				'choices' => array(
					'horizontal' => __( 'Horizontal', 'iconic-wis' ),
					'vertical'   => __( 'Vertical', 'iconic-wis' ),
				),
			),

			array(
				'id'       => 'image_count',
				'title'    => __( 'Image Count', 'iconic-wis' ),
				'subtitle' => __( 'Enter how many images should be displayed. Use -1 to display all available.', 'iconic-wis' ),
				'type'     => 'number',
				'default'  => - 1,
			),

			array(
				'id'       => 'navigation_icon_style',
				'title'    => __( 'Navigation Icon Style', 'iconic-wis' ),
				'subtitle' => __( 'Choose the icon style for the slider arrows.', 'iconic-wis' ),
				'type'     => 'select',
				'default'  => 'angle',
				'choices'  => array(
					'angle'          => __( 'Angle', 'iconic-wis' ),
					'arrow-circle'   => __( 'Arrow Circle', 'iconic-wis' ),
					'arrow-circle-o' => __( 'Arrow Circle Open', 'iconic-wis' ),
					'arrow'          => __( 'Arrow', 'iconic-wis' ),
					'long-arrow'     => __( 'Long Arrow', 'iconic-wis' ),
					'caret'          => __( 'Caret', 'iconic-wis' ),
					'chevron'        => __( 'Chevron', 'iconic-wis' ),
				),
			),

			array(
				'id'       => 'touch',
				'title'    => __( 'Touch Enabled?', 'iconic-wis' ),
				'subtitle' => __( 'When enabled, images can be swiped on touch devices.', 'iconic-wis' ),
				'type'     => 'select',
				'default'  => 'y',
				'choices'  => array(
					'y' => __( 'Yes', 'iconic-wis' ),
					'n' => __( 'No', 'iconic-wis' ),
				),
			),

			array(
				'id'       => 'transition',
				'title'    => __( 'Transition Effect', 'iconic-wis' ),
				'subtitle' => __( 'When sliding, apply this transition effect.', 'iconic-wis' ),
				'type'     => 'select',
				'default'  => 'shrink_out',
				'choices'  => array(
					'none'       => __( 'None', 'iconic-wis' ),
					'shrink_out' => __( 'Shrink Out', 'iconic-wis' ),
				),
			),

			array(
				'id'      => 'transition_speed',
				'title'   => __( 'Transition Speed (ms)', 'iconic-wis' ),
				'type'    => 'number',
				'default' => 300,
			),

		),
	);

	$settings['sections'][] = array(
		'tab_id'              => 'effects',
		'section_id'          => 'bullets',
		'section_title'       => __( 'Bullets Settings', 'iconic-wis' ),
		'section_description' => '',
		'section_order'       => 30,
		'fields'              => array(

			array(
				'id'      => 'position',
				'title'   => __( 'Bullets Position', 'iconic-wis' ),
				'type'    => 'select',
				'default' => 'horizontal-bottom-center',
				'choices' => array(
					'horizontal-bottom-center' => __( 'Horizontal Bottom Centre', 'iconic-wis' ),
					'horizontal-bottom-left'   => __( 'Horizontal Bottom Left', 'iconic-wis' ),
					'horizontal-bottom-right'  => __( 'Horizontal Bottom Right', 'iconic-wis' ),
					'horizontal-top-center'    => __( 'Horizontal Top Centre', 'iconic-wis' ),
					'horizontal-top-left'      => __( 'Horizontal Top Left', 'iconic-wis' ),
					'horizontal-top-right'     => __( 'Horizontal Top Right', 'iconic-wis' ),
					'vertical-left-center'     => __( 'Vertical Left Centre', 'iconic-wis' ),
					'vertical-left-bottom'     => __( 'Vertical Left Bottom', 'iconic-wis' ),
					'vertical-left-top'        => __( 'Vertical Left Top', 'iconic-wis' ),
					'vertical-right-center'    => __( 'Vertical Right Centre', 'iconic-wis' ),
					'vertical-right-bottom'    => __( 'Vertical Right Bottom', 'iconic-wis' ),
					'vertical-right-top'       => __( 'Vertical Right Top', 'iconic-wis' ),
				),
			),

			array(
				'id'      => 'mode',
				'title'   => __( 'Mode', 'iconic-wis' ),
				'type'    => 'select',
				'default' => 'horizontal',
				'choices' => array(
					'horizontal' => __( 'Horizontal', 'iconic-wis' ),
					'vertical'   => __( 'Vertical', 'iconic-wis' ),
					'fade'       => __( 'Fade', 'iconic-wis' ),
				),
			),

			array(
				'id'       => 'touch',
				'title'    => __( 'Touch Enabled?', 'iconic-wis' ),
				'subtitle' => __( 'When enabled, images can be swiped on touch devices.', 'iconic-wis' ),
				'type'     => 'select',
				'default'  => 'y',
				'choices'  => array(
					'y' => __( 'Yes', 'iconic-wis' ),
					'n' => __( 'No', 'iconic-wis' ),
				),
			),

			array(
				'id'      => 'transition_speed',
				'title'   => __( 'Transition Speed (ms)', 'iconic-wis' ),
				'type'    => 'number',
				'default' => 300,
			),

			array(
				'id'       => 'image_count',
				'title'    => __( 'Image Count', 'iconic-wis' ),
				'subtitle' => __( 'Enter how many images should be displayed. Use -1 to display all available.', 'iconic-wis' ),
				'type'     => 'number',
				'default'  => - 1,
			),

		),
	);

	$settings['sections'][] = array(
		'tab_id'              => 'effects',
		'section_id'          => 'thumbnails',
		'section_title'       => __( 'Thumbnails Settings', 'iconic-wis' ),
		'section_description' => '',
		'section_order'       => 40,
		'fields'              => array(

			array(
				'id'       => 'column_count',
				'title'    => __( 'Column Count', 'iconic-wis' ),
				'subtitle' => __( 'How many images to show in a row.', 'iconic-wis' ),
				'type'     => 'number',
				'default'  => 4,
			),

			array(
				'id'       => 'spacing',
				'title'    => __( 'Spacing (px)', 'iconic-wis' ),
				'subtitle' => __( 'Space between the thumbnails in pixels.', 'iconic-wis' ),
				'type'     => 'number',
				'default'  => 10,
			),

			array(
				'id'       => 'image_count',
				'title'    => __( 'Image Count', 'iconic-wis' ),
				'subtitle' => __( 'Enter how many images should be displayed. Use -1 to display all available.', 'iconic-wis' ),
				'type'     => 'number',
				'default'  => 3,
			),

		),
	);

	$settings['sections'][] = array(
		'tab_id'              => 'effects',
		'section_id'          => 'zoom',
		'section_title'       => __( 'Zoom Settings', 'iconic-wis' ),
		'section_description' => '',
		'section_order'       => 50,
		'fields'              => array(

			array(
				'id'       => 'max',
				'title'    => __( 'Max Zoom', 'iconic-wis' ),
				'subtitle' => __( 'How much should the image be zoomed on hover. For example, "2" will zoom no large than 2x.', 'iconic-wis' ),
				'type'     => 'number',
				'default'  => 2,
			),

		),
	);

	$settings['sections'][] = array(
		'tab_id'              => 'effects',
		'section_id'          => 'pip',
		'section_title'       => __( 'Picture in Picture (PIP) Settings', 'iconic-wis' ),
		'section_description' => '',
		'section_order'       => 60,
		'fields'              => array(

			array(
				'id'      => 'width',
				'title'   => __( 'PIP Width (%)', 'iconic-wis' ),
				'type'    => 'number',
				'default' => 25,
			),

			array(
				'id'      => 'border_color',
				'title'   => __( 'PIP Border Colour', 'iconic-wis' ),
				'type'    => 'color',
				'default' => '#CCCCCC',
			),

			array(
				'id'      => 'position',
				'title'   => __( 'Position', 'iconic-wis' ),
				'type'    => 'select',
				'default' => 'bottom-right',
				'choices' => array(
					'top-left'     => __( 'Top Left', 'iconic-wis' ),
					'top-right'    => __( 'Top Right', 'iconic-wis' ),
					'bottom-left'  => __( 'Bottom Left', 'iconic-wis' ),
					'bottom-right' => __( 'Bottom Right', 'iconic-wis' ),
				),
			),

		),
	);

	$settings['sections'][] = array(
		'tab_id'              => 'effects',
		'section_id'          => 'modal',
		'section_title'       => __( 'Modal Settings', 'iconic-wis' ),
		'section_description' => '',
		'section_order'       => 70,
		'fields'              => array(

			array(
				'id'      => 'icon',
				'title'   => __( 'Icon Style', 'iconic-wis' ),
				'type'    => 'select',
				'default' => 'expand',
				'choices' => array(
					'expand'      => __( 'Expand', 'iconic-wis' ),
					'arrows-alt'  => __( 'Arrows', 'iconic-wis' ),
					'search'      => __( 'Magnifier', 'iconic-wis' ),
					'search-plus' => __( 'Magnifier Plus', 'iconic-wis' ),
					'picture-o'   => __( 'Picture', 'iconic-wis' ),
				),
			),

		),
	);

	$settings['sections'][] = array(
		'tab_id'              => 'effects',
		'section_id'          => 'enlarge',
		'section_title'       => __( 'Enlarge Settings', 'iconic-wis' ),
		'section_description' => '',
		'section_order'       => 80,
		'fields'              => array(

			array(
				'id'      => 'transition_speed',
				'title'   => __( 'Transition Speed (ms)', 'iconic-wis' ),
				'type'    => 'number',
				'default' => 300,
			),

			array(
				'id'      => 'amount',
				'title'   => __( 'Enlarge Amount (%)', 'iconic-wis' ),
				'type'    => 'number',
				'default' => 110,
			),

			array(
				'id'       => 'crop',
				'title'    => __( 'Crop?', 'iconic-wis' ),
				'subtitle' => __( 'When enabled, images will be enlarged inside a cropped container.', 'iconic-wis' ),
				'type'     => 'select',
				'default'  => 'n',
				'choices'  => array(
					'y' => __( 'Yes', 'iconic-wis' ),
					'n' => __( 'No', 'iconic-wis' ),
				),
			),

		),
	);

	$settings['sections'][] = array(
		'tab_id'              => 'effects',
		'section_id'          => 'flip',
		'section_title'       => __( 'Flip Settings', 'iconic-wis' ),
		'section_description' => '',
		'section_order'       => 90,
		'fields'              => array(

			array(
				'id'      => 'transition_speed',
				'title'   => __( 'Transition Speed (ms)', 'iconic-wis' ),
				'type'    => 'number',
				'default' => 300,
			),

		),
	);

	$settings['sections'][] = array(
		'tab_id'              => 'compatibility',
		'section_id'          => 'general',
		'section_title'       => __( 'Compatibility Settings', 'iconic-wis' ),
		'section_description' => '',
		'section_order'       => 10,
		'fields'              => array(
			array(
				'id'       => 'ajax_events',
				'title'    => __( 'AJAX Event Triggers', 'iconic-wis' ),
				'subtitle' => __( 'Add custom JavaScript event triggers e.g. my-ajax-event, one per line, that will force Image Swap to re-initialise after new products load via AJAX.', 'iconic-wis' ),
				'type'     => 'textarea',
			),
		),
	);

	return $settings;
}
