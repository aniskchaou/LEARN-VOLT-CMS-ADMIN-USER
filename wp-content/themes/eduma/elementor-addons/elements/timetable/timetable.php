<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Timetable_El extends Widget_Base {

	public function get_name() {
		return 'thim-timetable';
	}

	public function get_title() {
		return esc_html__( 'Thim: Timetable', 'eduma' );
	}

	public function get_icon() {
		return 'thim-widget-icon thim-widget-icon-timetable';
	}

	public function get_categories() {
		return [ 'thim-elements' ];
	}

	public function get_base() {
		return basename( __FILE__, '.php' );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'content',
			[
				'label' => esc_html__( 'Timetable', 'eduma' )
			]
		);

        $this->add_control(
            'title',
            [
                'label'       => esc_html__( 'Title', 'eduma' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );
		$repeater = new Repeater();

		$repeater->add_control(
			'panel_title',
			[
				'label'       => esc_html__( 'Course Title', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => '',
				'label_block' => true
			]
		);

		$repeater->add_control(
			'panel_time',
			[
				'label'       => esc_html__( 'Time Activity', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => '',
				'label_block' => true
			]
		);

		$repeater->add_control(
			'panel_teacher',
			[
				'label'       => esc_html__( 'Teacher Name', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => '',
				'label_block' => true
			]
		);

		$repeater->add_control(
			'panel_location',
			[
				'label'       => esc_html__( 'Location', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => '',
				'label_block' => true
			]
		);

		$repeater->add_control(
			'panel_background',
			[
				'label' => esc_html__( 'Background Color', 'eduma' ),
				'type'  => Controls_Manager::COLOR,
				'label_block' => true
			]
		);

		$repeater->add_control(
			'panel_background_hover',
			[
				'label' => esc_html__( 'Background Hover Color', 'eduma' ),
				'type'  => Controls_Manager::COLOR,
				'label_block' => true
			]
		);

		$repeater->add_control(
			'panel_color_style',
			[
				'label'   => esc_html__( 'Color Style', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'light'  => esc_html__( 'Light', 'eduma' ),
					'dark'   => esc_html__( 'Dark', 'eduma' ),
					'category' => esc_html__( 'Gray', 'eduma' )
				],
				'default' => 'light'
			]
		);

		$this->add_control(
			'panel',
			[
				'label'       => esc_html__( 'Panel List', 'eduma' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ panel_title }}}',
				'separator'   => 'before'
			]
		);
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Map variables between Elementor and SiteOrigin
		$instance = array(
			'title'             => $settings['title'],
			'panel'             => $settings['panel'],
		);

        $args                 = array();
        $args['before_title'] = '<h3 class="widget-title">';
        $args['after_title']  = '</h3>';

        thim_get_widget_template( $this->get_base(), array(
            'instance' => $instance,
            'args'     => $args
        ) );
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_Timetable_El() );