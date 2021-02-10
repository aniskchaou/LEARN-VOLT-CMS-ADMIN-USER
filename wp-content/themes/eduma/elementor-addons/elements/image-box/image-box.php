<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Image_Box_El extends Widget_Base {

	public function get_name() {
		return 'thim-image-box';
	}

	public function get_title() {
		return esc_html__( 'Thim: Image Box', 'eduma' );
	}

	public function get_icon() {
		return 'thim-widget-icon thim-widget-icon-image-box';
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
				'label' => esc_html__( 'Image box', 'eduma' )
			]
		);

		$this->add_control(
			'style',
			[
				'label'   => esc_html__( 'Style', 'eduma' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'base'  => esc_html__( 'Default', 'eduma' ),
					'layout-2'  => esc_html__( 'Layout 2', 'eduma' ),
				],
				'default' => 'base'
			]
		);

        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'eduma' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

		$this->add_control(
			'description',
			[
				'label'       => esc_html__( 'Description', 'eduma' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Add your description here', 'eduma' ),
				'label_block' => true,
				'condition' => [
					'style' => [ 'layout-2' ]
				]
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
			'title_bg_color',
			[
				'label' => esc_html__( 'Title Background Color', 'eduma' ),
				'type'  => Controls_Manager::COLOR,
				'condition' => [
					'style' => [ 'layout-2' ]
				]
			]
		);

        $this->add_control(
            'link',
            [
                'label' => esc_html__( 'Link', 'eduma' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Map variables between Elementor and SiteOrigin
		$instance = array(
			'layout'    => $settings['style'],
			'title'     => $settings['title'],
			'description'     => $settings['description'],
			'image'     => $settings['image']['id'],
			'title_bg_color'    => $settings['title_bg_color'],
			'link' => $settings['link'],
		);

		thim_get_widget_template( $this->get_base(), array(
			'instance' => $instance
		), $settings['style'] );
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Thim_Image_Box_El() );
