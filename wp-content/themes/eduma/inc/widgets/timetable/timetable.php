<?php

class Thim_Timetable_Widget extends Thim_Widget {

	function __construct() {

		parent::__construct(
			'timetable',
			esc_html__( 'Thim: Timetable', 'eduma' ),
			array(
				'description'   => esc_html__( 'Display Timetable', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-timetable'
			),
			array(),
			array(
				'title' => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Title', 'eduma' ),
					'default' => ''
				),

				'panel' => array(
					'type'      => 'repeater',
					'label'     => esc_html__( 'Timetable Item', 'eduma' ),
					'item_name' => esc_html__( 'Panel', 'eduma' ),
					'fields'    => array(
						'panel_title' => array(
							'type'    => 'text',
							'label'   => esc_html__( 'Course Title', 'eduma' ),
							'default' => esc_html__( 'Course Title', 'eduma' )
						),

						'panel_time' => array(
							'type'    => 'text',
							'label'   => esc_html__( 'Time Activity', 'eduma' ),
							'default' => esc_html__( '8:00 AM - 10:00 AM', 'eduma' )
						),

						'panel_teacher' => array(
							'type'    => 'text',
							'label'   => esc_html__( 'Teacher Name', 'eduma' ),
							'default' => esc_html__( 'Mr John Doe', 'eduma' )
						),

						'panel_location' => array(
							'type'    => 'text',
							'label'   => esc_html__( 'Location', 'eduma' ),
							'default' => esc_html__( 'Playground', 'eduma' )
						),

						'panel_background' => array(
							'type'  => 'color',
							'label' => esc_html__( 'Background Color', 'eduma' ),
						),

						'panel_background_hover' => array(
							'type'  => 'color',
							'label' => esc_html__( 'Background Hover Color', 'eduma' ),
						),

						'panel_color_style' => array(
							"type"        => "select",
							"label"       => esc_html__( "Color Style", 'eduma' ),
							"default"     => "light",
							"options"     => array(
								"light" => esc_html__( "Light", 'eduma' ),
								"dark"  => esc_html__( "Dark", 'eduma' ),
								"gray"  => esc_html__( "Gray", 'eduma' ),
							),
							"description" => esc_html__( "Select Color Style.", 'eduma' )
						),
					),
				),
			)
		);


	}


	function get_template_name( $instance ) {
		return 'base';
	}

	function get_style_name( $instance ) {
		return false;
	}

}

function thim_timetable_register_widget() {
	register_widget( 'Thim_Timetable_Widget' );
}

add_action( 'widgets_init', 'thim_timetable_register_widget' );