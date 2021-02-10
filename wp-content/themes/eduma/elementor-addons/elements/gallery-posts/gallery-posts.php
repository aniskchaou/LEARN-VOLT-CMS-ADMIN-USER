<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Gallery_Posts_El extends Widget_Base {

	public function get_name() {
		return 'thim-gallery-posts';
	}

	public function get_title() {
		return esc_html__( 'Thim: Gallery Posts', 'eduma' );
	}

	public function get_icon() {
		return 'thim-widget-icon thim-widget-icon-gallery-posts';
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
				'label' => esc_html__( 'Gallery Posts', 'eduma' )
			]
		);

		$this->add_control(
			'cat',
			[
				'label'    => esc_html__( 'Select Category', 'eduma' ),
				'type'     => Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => thim_get_cat_taxonomy( 'category', array( 'all' => esc_html__( 'All', 'eduma' ) )  ),
 				'default'  => 'all'
			]
		);

		$this->add_control(
			'layout',
			[
				'label'   => esc_html__( 'Layout', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'base'    => 'Default',
					'isotope' => 'Isotope'
				],
				'default' => 'base'
			]
		);

		$this->add_control(
			'columns',
			[
				'label'   => esc_html__( 'Columns', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6'
				],
				'default' => '4'
			]
		);

		$this->add_control(
			'filter',
			[
				'label'   => esc_html__( 'Show Filter?', 'eduma' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'limit',
			[
				'label'   => esc_html__( 'Limit', 'eduma' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 8,
				'min'     => 0,
				'step'    => 1
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Map variables between Elementor and SiteOrigin
		$instance = array(
			'cat'     => $settings['cat'],
			'layout'  => $settings['layout'],
			'columns' => $settings['columns'],
			'filter'  => $settings['filter'],
			'limit'   => $settings['limit']
		);

		thim_get_widget_template( $this->get_base(), array(
			'instance' => $instance
		), $settings['layout'] );
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_Gallery_Posts_El() );
