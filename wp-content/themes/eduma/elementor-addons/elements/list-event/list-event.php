<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_List_Event_El extends Widget_Base {

	public function get_name() {
		return 'thim-list-event';
	}

	public function get_title() {
		return esc_html__( 'Thim: List Events', 'eduma' );
	}

	public function get_icon() {
		return 'thim-widget-icon thim-widget-icon-list-event';
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
				'label' => esc_html__( 'List Events', 'eduma' )
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Heading', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => esc_html__( 'Add your text here', 'eduma' )
			]
		);

		$this->add_control(
			'layout',
			[
				'label'   => esc_html__( 'Layout', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'base'     => esc_html__( 'Default', 'eduma' ),
					'slider'   => esc_html__( 'Slider', 'eduma' ),
					'layout-2' => esc_html__( 'Layout 2', 'eduma' ),
					'layout-3' => esc_html__( 'Layout 3', 'eduma' ),
					'layout-4' => esc_html__( 'Layout 4', 'eduma' ),
					'layout-5' => esc_html__( 'Layout 5', 'eduma' )
				],
				'default' => 'base'
			]
		);

		$this->add_control(
			'cat_id',
			[
				'label'   => esc_html__( 'Select Category', 'eduma' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => thim_get_cat_taxonomy( 'tp_event_category', array( 'all' => esc_html__( 'All', 'eduma' ) ) ),
 				'default' => 'all'
			]
		);

		$this->add_control(
			'status',
			[
				'label'       => esc_html__( 'Select Status', 'eduma' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => false,
				'options'     => array(
					'upcoming'  => esc_html__( 'Upcoming', 'eduma' ),
					'happening' => esc_html__( 'Happening', 'eduma' ),
					'expired'   => esc_html__( 'Expired', 'eduma' ),
				)
			]
		);

		$this->add_control(
			'number_posts_slider',
			[
				'label'   => esc_html__( 'Number posts slider', 'eduma' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3,
				'min'     => 1,
				'step'    => 1,
				'condition' => [
					'layout' => [ 'layout-5' ]
				]
			]
		);

		$this->add_control(
			'background_image',
			[
				'label'         => esc_html__( 'Background Image Bottom', 'eduma' ),
				'type'        => Controls_Manager::MEDIA,
				'condition' => [
					'layout' => [ 'layout-5' ]
				]
			]
		);

		$this->add_control(
			'number_posts',
			[
				'label'   => esc_html__( 'Number Posts', 'eduma' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3,
				'min'     => 1,
				'step'    => 1
			]
		);

		$this->add_control(
			'text_link',
			[
				'label'   => esc_html__( 'Text Link All', 'eduma' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'View All', 'eduma' )
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Map variables between Elementor and SiteOrigin
		$instance = array(
			'title'        => $settings['title'],
			'layout'       => $settings['layout'],
			'number_posts' => $settings['number_posts'],
			'number_posts_slider' => $settings['number_posts_slider'],
			'background_image' => isset( $settings['background_image']) ? $settings['background_image']['id'] :'',
			'text_link'    => $settings['text_link'],
			'cat_id'       => $settings['cat_id'],
			'status'       => $settings['status'],
		);

		$args                 = array();
		$args['before_title'] = '<h3 class="widget-title">';
		$args['after_title']  = '</h3>';

		thim_get_widget_template( $this->get_base(), array(
			'instance' => $instance,
			'args'     => $args
		), $settings['layout'] );
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_List_Event_El() );