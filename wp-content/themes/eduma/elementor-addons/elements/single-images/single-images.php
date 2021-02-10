<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Single_Images_El extends Widget_Base {

	public function get_name() {
		return 'thim-single-images';
	}

	public function get_title() {
		return esc_html__( 'Thim: Single Images', 'eduma' );
	}

	public function get_icon() {
		return 'thim-widget-icon thim-widget-icon-single-images';
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
				'label' => esc_html__( 'Single Images', 'eduma' )
			]
		);

        $this->add_control(
            'layout',
            [
                'label'   => esc_html__( 'Layout', 'eduma' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'base'  => esc_html__( 'Default', 'eduma' ),
                    'layout-2' => esc_html__( 'Layout 2', 'eduma' )
                ],
	            'default' => 'base'
            ]
        );

		$this->add_control(
			'image',
			[
				'label'         => esc_html__( 'Upload Image', 'eduma' ),
				'type'        => Controls_Manager::MEDIA,
			]
		);

		$this->add_control(
			'image_title',
			[
				'label'       => esc_html__( 'Image Title', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'condition' => [
					'layout' => [ 'layout-2' ]
				]
			]
		);

		$this->add_control(
			'image_size',
			[
				'label' => esc_html__( 'Image size', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'eduma' ),
			]
		);

		$this->add_control(
			'on_click_action',
			[
				'label'   => esc_html__( 'On click action', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'none'  => esc_html__( 'none', 'eduma' ),
					'custom-link' => esc_html__( 'Open custom link', 'eduma' ),
					'popup' => esc_html__( 'Open popup', 'eduma' ),
				],
				'default' => 'none'
			]
		);

		$this->add_control(
			'image_link',
			[
				'label'       => esc_html__( 'Image Link', 'eduma' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'condition' => [
					'on_click_action' => [ 'custom-link' ]
				]
			]
		);

		$this->add_control(
			'link_target',
			[
				'label'   => esc_html__( 'Link Target', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'_self'  => esc_html__( 'Same window', 'eduma' ),
					'_blank' => esc_html__( 'New window', 'eduma' )
				],
				'default' => '_self',
				'condition' => [
					'on_click_action' => [ 'custom-link' ]
				]
			]
		);

		$this->add_control(
			'image_alignment',
			[
				'label'   => esc_html__( 'Image alignment', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'left'  => esc_html__( 'Align Left', 'eduma' ),
					'right' => esc_html__( 'Align Right', 'eduma' ),
					'center' => esc_html__( 'Align Center', 'eduma' )
				],
				'default' => 'left',
			]
		);

		$this->add_control(
			'css_animation',
			[
				'label'   => esc_html__( 'CSS Animation', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'No', 'eduma' ),
					'top-to-bottom' => esc_html__( 'Top To Bottom', 'eduma' ),
					'bottom-to-top' => esc_html__( 'Bottom To Top', 'eduma' ),
					'left-to-right' => esc_html__( 'Left To Right', 'eduma' ),
					'right-to-left' => esc_html__( 'Right To Left', 'eduma' ),
					'appear' => esc_html__( 'Appear from center', 'eduma' ),
				],
				'default' => '',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Map variables between Elementor and SiteOrigin
		$instance = array(
			'layout'            => $settings['layout'],
			'image'             => $settings['image']['id'],
			'image_title'       => $settings['image_title'],
			'image_size'        => $settings['image_size'],
			'on_click_action'   => $settings['on_click_action'],
			'image_link'        => $settings['image_link'],
			'link_target'       => $settings['link_target'],
			'image_alignment'   => $settings['image_alignment'],
			'css_animation'     => $settings['css_animation'],
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

Plugin::instance()->widgets_manager->register_widget_type( new Thim_Single_Images_El() );