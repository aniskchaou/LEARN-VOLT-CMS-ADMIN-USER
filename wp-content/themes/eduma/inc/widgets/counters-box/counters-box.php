<?php

if ( !class_exists( 'Thim_Counters_Box_Widget' ) ):
	class Thim_Counters_Box_Widget extends Thim_Widget {

		function __construct() {

			parent::__construct(
				'counters-box',
				esc_html__( 'Thim: Counters Box', 'eduma' ),
				array(
					'description'   => esc_html__( 'Counters Box', 'eduma' ),
					'help'          => '',
					'panels_groups' => array( 'thim_widget_group' ),
					'panels_icon'   => 'thim-widget-icon thim-widget-icon-counters-box'
				),
				array(),
				array(

					'counters_label' => array(
						"type"  => "text",
						"label" => esc_html__( "Counters Label", 'eduma' ),
					),

					'counters_value'      => array(
						"type"    => "number",
						"label"   => esc_html__( "Counters Value", 'eduma' ),
						"default" => "20",
					),
					'text_number'         => array(
						"type"  => "text",
						"label" => esc_html__( "Text Number", 'eduma' ),
					),
					'view_more_text'      => array(
						"type"  => "text",
						"label" => esc_html__( "View More Text", 'eduma' ),
					),
					'view_more_link'      => array(
						"type"  => "text",
						"label" => esc_html__( "View More Link", 'eduma' ),
					),
					'counter_value_color' => array(
						"type"  => "color",
						"label" => esc_html__( "Counter Color", 'eduma' ),
					),
					'background_color'    => array(
						"type"  => "color",
						"label" => esc_html__( "Background Color", 'eduma' ),
						"class" => "color-mini",
					),

					'icon_type' => array(
						"type"          => "select",
						"class"         => "",
						"label"         => esc_html__( "Icon to display:", 'eduma' ),
						"default"       => "font-awesome",
						"options"       => array(
							"font-awesome"  => esc_html__( "Font Awesome Icon", 'eduma' ),
							"font-7-stroke" => esc_html__( "Font 7 stroke Icon", 'eduma' ),
							"font-flaticon" => esc_html__( "Font Flat Icon", 'eduma' ),
							"custom"        => esc_html__( "Custom Image", 'eduma' ),
						),
						'state_emitter' => array(
							'callback' => 'select',
							'args'     => array( 'icon_type_op' )
						)
					),

					'icon' => array(
						"type"          => "icon",
						"label"         => esc_html__( "Icon", 'eduma' ),
						'state_handler' => array(
							'icon_type_op[font-awesome]'  => array( 'show' ),
							'icon_type_op[custom]'        => array( 'hide' ),
							'icon_type_op[font-7-stroke]' => array( 'hide' ),
							'icon_type_op[font-flaticon]' => array( 'hide' ),
						),
					),

					'icon_stroke' => array(
						"type"          => "icon-7-stroke",
						"label"         => esc_html__( "Icon:", 'eduma' ),
						'state_handler' => array(
							'icon_type_op[font-awesome]'  => array( 'hide' ),
							'icon_type_op[font-flaticon]' => array( 'hide' ),
							'icon_type_op[custom]'        => array( 'hide' ),
							'icon_type_op[font-7-stroke]' => array( 'show' ),
						),
					),

					'icon_img' => array(
						"type"          => "media",
						"label"         => esc_html__( "Upload Image Icon:", 'eduma' ),
						"description"   => esc_html__( "Upload the custom image icon.", 'eduma' ),
						"class_name"    => 'custom',
						'state_handler' => array(
							'icon_type_op[font-awesome]'  => array( 'hide' ),
							'icon_type_op[custom]'        => array( 'show' ),
							'icon_type_op[font-7-stroke]' => array( 'hide' ),
							'icon_type_op[font-flaticon]' => array( 'hide' ),
						),
					),

					'icon_flat' => array(
						"type"          => "icon-youniverse",
						"label"         => esc_html__( "Icon:", 'eduma' ),
						'state_handler' => array(
							'icon_type_op[font-awesome]'  => array( 'hide' ),
							'icon_type_op[custom]'        => array( 'hide' ),
							'icon_type_op[font-7-stroke]' => array( 'hide' ),
							'icon_type_op[font-flaticon]' => array( 'show' ),
						),
					),

					'border_color' => array(
						"type"  => "color",
						"label" => esc_html__( "Border Color Icon", 'eduma' ),
					),

					'counter_color' => array(
						"type"  => "color",
						"label" => esc_html__( "Counters Icon Color", 'eduma' ),
					),

                    'counter_label_color' => array(
                        "type"  => "color",
                        "label" => esc_html__( "Counters Label Color", 'eduma' ),
                    ),

					'style' => array(
						"type"    => "select",
						"label"   => esc_html__( "Counter Style", 'eduma' ),
						"options" => array(
							"home-page"     => esc_html__( "Home Page", 'eduma' ),
							"about-us"      => esc_html__( "Page About Us", 'eduma' ),
							"number-left"   => esc_html__( "Number Left", 'eduma' ),
							"text-gradient" => esc_html__( "Text Gradient", 'eduma' ),

						),
						'default' => 'home-page'
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
					)
				),
				THIM_DIR . 'inc/widgets/counters-box/'
			);
		}

		/**
		 * Initialize the CTA widget
		 */

		function get_template_name( $instance ) {
			return 'base';
		}

		function get_style_name( $instance ) {
			return false;
		}

		function enqueue_instance_frontend_scripts( $instance ) {
			wp_enqueue_script( 'waypoints' );
			wp_enqueue_script( 'thim-CountTo' );
		}
	}
endif;

function thim_counters_box_widget() {
	register_widget( 'Thim_Counters_Box_Widget' );
}

add_action( 'widgets_init', 'thim_counters_box_widget' );