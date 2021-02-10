<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Counters_Box_El extends Widget_Base {
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		wp_register_script( 'thim-waypoints', THIM_URI . 'assets/js/jquery.waypoints.min.js', array( 'jquery' ), THIM_THEME_VERSION, true );
		wp_register_script( 'CountTo', THIM_URI . 'assets/js/jquery.countTo.min.js', array( 'jquery', 'thim-waypoints' ), THIM_THEME_VERSION, true );
	}

	public function get_script_depends() {
		return [ 'CountTo' ];
	}

	public function get_name() {
		return 'thim-counters-box';
	}

	public function get_title() {
		return esc_html__( 'Thim: Counters Box', 'eduma' );
	}

	public function get_icon() {
		return 'thim-widget-icon thim-widget-icon-counters-box';
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
				'label' => esc_html__( 'Counters Box', 'eduma' )
			]
		);

		$this->add_control(
			'counters_label',
			[
				'label'       => esc_html__( 'Counters Label', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Add your text here', 'eduma' ),
				'label_block' => true
			]
		);

		$this->add_control(
			'counters_value',
			[
				'label'   => esc_html__( 'Counters Value', 'eduma' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 20,
				'min'     => 1,
				'step'    => 1
			]
		);

		$this->add_control(
			'text_number',
			[
				'label'       => esc_html__( 'Text Number', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Add your text here', 'eduma' ),
				'label_block' => true
			]
		);

		$this->add_control(
			'view_more_text',
			[
				'label'       => esc_html__( 'View More Text', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Add your text here', 'eduma' ),
				'label_block' => true
			]
		);

		$this->add_control(
			'view_more_link',
			[
				'label'         => esc_html__( 'View More Link', 'plugin-domain' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'eduma' ),
				'show_external' => false,
				'default'       => [
					'url' => ''
				]
			]
		);

		$this->add_control(
			'style',
			[
				'label'   => esc_html__( 'Counter Style', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					"home-page"     => esc_html__( "Home Page", 'eduma' ),
					"about-us"      => esc_html__( "Page About Us", 'eduma' ),
					"number-left"   => esc_html__( "Number Left", 'eduma' ),
					"text-gradient" => esc_html__( "Text Gradient", 'eduma' )
				],
				'default' => 'home-page'
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'icon_group',
			[
				'label' => __( 'Icon', 'eduma' ),
			]
		);

		$this->add_control(
			'icon_type',
			[
				'label'   => __( 'Icon', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					"font-awesome"  => esc_html__( "Font Awesome Icon", 'eduma' ),
					"font-7-stroke" => esc_html__( "Font 7 stroke Icon", 'eduma' ),
					"font-flaticon" => esc_html__( "Font Flat Icon", 'eduma' ),
					"custom"        => esc_html__( "Custom Image", 'eduma' )
				],
				'default' => 'font-awesome'
			]
		);

		$this->add_control(
			'icon',
			[
				'label'       => esc_html__( 'Select Icon:', 'eduma' ),
				'type'        => Controls_Manager::ICON,
				'placeholder' => esc_html__( 'Choose...', 'eduma' ),
				'condition'   => [
					'icon_type' => [ 'font-awesome' ]
				]
			]
		);

		$this->add_control(
			'icon_flat',
			[
				'label'       => esc_html__( 'Select Icon:', 'eduma' ),
				'type'        => Controls_Manager::ICON,
				'placeholder' => esc_html__( 'Choose...', 'eduma' ),
				'options'     => \Thim_Elementor_Extend_Icons::get_font_flaticon(),
				'exclude'     => array_keys( Control_Icon::get_icons() ),
				'condition'   => [
					'icon_type' => [ 'font-flaticon' ]
				]
			]
		);

		$this->add_control(
			'icon_stroke',
			[
				'label'       => esc_html__( 'Select Icon:', 'eduma' ),
				'type'        => Controls_Manager::ICON,
				'placeholder' => esc_html__( 'Choose...', 'eduma' ),
				'options'     => \Thim_Elementor_Extend_Icons::get_font_7_stroke(),
				'exclude'     => array_keys( Control_Icon::get_icons() ),
				'condition'   => [
					'icon_type' => [ 'font-7-stroke' ]
				]
			]
		);

		$this->add_control(
			'icon_img',
			[
				'label'     => esc_html__( 'Choose Image', 'eduma' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'icon_type' => [ 'custom' ]
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style-tab',
			[
				'label' => esc_html__( 'Style', 'eduma' ),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'counter_value_color',
			[
				'label' => esc_html__( 'Counters Color', 'eduma' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => esc_html__( 'Background Color', 'eduma' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'border_color',
			[
				'label' => esc_html__( 'Border Color Icon', 'eduma' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'counter_color',
			[
				'label' => esc_html__( 'Counters Icon Color', 'eduma' ),
				'type'  => Controls_Manager::COLOR,
			]
		);
		$this->add_control(
			'counter_label_color',
			[
				'label' => esc_html__( 'Counters Label Color', 'eduma' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'css_animation',
			[
				'label'     => esc_html__( 'CSS Animation', 'eduma' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					""              => esc_html__( "No", 'eduma' ),
					"top-to-bottom" => esc_html__( "Top to bottom", 'eduma' ),
					"bottom-to-top" => esc_html__( "Bottom to top", 'eduma' ),
					"left-to-right" => esc_html__( "Left to right", 'eduma' ),
					"right-to-left" => esc_html__( "Right to left", 'eduma' ),
					"appear"        => esc_html__( "Appear from center", 'eduma' )
				],
				'default'   => '',
				'separator' => 'before'
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Map variables between Elementor and SiteOrigin
		//        $instance = array();
		$instance             = array(
			'counters_label'            => $settings['counters_label'],
			'counters_value'            => $settings['counters_value'],
			'text_number'               => $settings['text_number'],
			'view_more_text'            => $settings['view_more_text'],
			'view_more_link'            => $settings['view_more_link'],
			'icon_type'                 => $settings['icon_type'],
			'icon'                      => $settings['icon'],
			'icon_flat'                 => $settings['icon_flat'],
			'icon_stroke'               => $settings['icon_stroke'],
			'icon_img'                  => isset( $settings['icon_img'] ) ? $settings['icon_img']['id'] : '',
			'style'                     => $settings['style'],
			'counter_value_color'       => $settings['counter_value_color'],
			'background_color'          => $settings['background_color'],
			'border_color'              => $settings['border_color'],
			'counter_color'             => $settings['counter_color'],
			'counter_label_color' => $settings['counter_label_color'],
			'css_animation'             => $settings['css_animation'],
		);
		$args                 = array();
		$args['before_title'] = '<h3 class="widget-title">';
		$args['after_title']  = '</h3>';

		thim_get_widget_template(
			$this->get_base(), array(
				'instance' => $instance,
				'args'     => $args
			)
		);

	}

}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_Counters_Box_El() );