<?php

class Thim_Single_Images_Widget extends Thim_Widget {

	function __construct() {

		parent::__construct(
			'single-images',
			esc_html__( 'Thim: Single Image', 'eduma' ),
			array(
				'description'   => esc_html__( 'Display single images.', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-single-images'
			),
			array(),
			array(
				'layout'           => array(
					'type'          => 'select',
					'label'         => esc_html__( 'Layout', 'eduma' ),
					'options'       => array(
						'base'  => esc_html__( 'Default', 'eduma' ),
						'layout-2'  => esc_html__( 'Layout 2', 'eduma' )
					),
					'default'       => 'default',
					'state_emitter' => array(
						'callback' => 'select',
						'args'     => array( 'layout_type' )
					),
				),
				'image' => array(
					'type'        => 'media',
					'label'       => esc_html__( 'Image', 'eduma' ),
					'description' => esc_html__( 'Select image from media library.', 'eduma' )
				),
				'image_title'      => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Image title', 'eduma' ),
					'description' => esc_html__( 'Enter image title.', 'eduma' ),
					'state_handler' => array(
						'layout_type[base]'  => array( 'hide' ),
						'layout_type[layout-2]'  => array( 'show' ),
					),
				),
				'image_size'      => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Image size', 'eduma' ),
					'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'eduma' )
				),
				'on_click_action'           => array(
					'type'          => 'select',
					'label'         => esc_html__( 'On click action', 'eduma' ),
					'options'       => array(
						'none'  => esc_html__( 'None', 'eduma' ),
						'custom-link'  => esc_html__( 'Open custom link', 'eduma' ),
						'popup'  => esc_html__( 'Open popup', 'eduma' ),
					),
					'default'       => 'none',
					'state_emitter' => array(
						'callback' => 'select',
						'args'     => array( 'on_click_action' )
					),
				),
				'image_link'      => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Image Link', 'eduma' ),
					'description' => esc_html__( 'Enter URL if you want this image to have a link.', 'eduma' ),
					'state_handler' => array(
						'on_click_action[none]'  => array( 'hide' ),
						'on_click_action[custom-link]'  => array( 'show' ),
						'on_click_action[popup]'  => array( 'hide' ),
					),
				),
				'link_target'     => array(
					"type"    => "select",
					"label"   => esc_html__( "Link Target", 'eduma' ),
					"options" => array(
						"_self"  => esc_html__( "Same window", 'eduma' ),
						"_blank" => esc_html__( "New window", 'eduma' ),
					),
					'state_handler' => array(
						'on_click_action[none]'  => array( 'hide' ),
						'on_click_action[custom-link]'  => array( 'show' ),
						'on_click_action[popup]'  => array( 'hide' ),
					),
				),
				'image_alignment' => array(
					"type"        => "select",
					"label"       => esc_html__( "Image alignment", 'eduma' ),
					"description" => esc_html__("Select image alignment.", 'eduma'),
					"options"     => array(
						"left"   => esc_html__( "Align Left", 'eduma' ),
						"right"  => esc_html__( "Align Right", 'eduma' ),
						"center" => esc_html__( "Align Center", 'eduma' )
					),
				),

				'css_animation' => array(
					"type"    => "select",
					"label"   => esc_html__( "CSS Animation", 'eduma' ),
					"options" => array(
						""              => esc_html__( "No", 'eduma' ),
						"top-to-bottom" => esc_html__( "Top to bottom", 'eduma' ),
						"bottom-to-top" => esc_html__( "Bottom to top", 'eduma' ),
						"left-to-right" => esc_html__( "Left to right", 'eduma' ),
						"right-to-left" => esc_html__( "Right to left", 'eduma' ),
						"appear"        => esc_html__( "Appear from center", 'eduma' )
					),
				),
			),
			THIM_DIR . 'inc/widgets/single-images/'
		);
	}

	/**
	 * Initialize the CTA widget
	 */


	function get_template_name( $instance ) {
		if ( isset( $instance['layout'] ) && $instance['layout'] != 'default' ) {
			return $instance['layout'];
		} else {
			return 'base';
		}
	}

	function get_style_name( $instance ) {
		return false;
	}
}

function thim_single_images_register_widget() {
	register_widget( 'Thim_Single_Images_Widget' );
}

add_action( 'widgets_init', 'thim_single_images_register_widget' );