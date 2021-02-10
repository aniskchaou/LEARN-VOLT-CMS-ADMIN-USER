<?php

class Thim_Carousel_Categories_Widget extends Thim_Widget {

	function __construct() {
		parent::__construct(
			'carousel-categories',
			esc_html__( 'Thim: Carousel Categories', 'eduma' ),
			array(
				'description'   => esc_html__( 'Display categories with Carousel', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-carousel-categories'
			),
			array(),
			array(
				'title'           => array(
					'type'                  => 'text',
					'label'                 => esc_html__( 'Heading', 'eduma' ),
					'default'               => esc_html__( 'Carousel Categories', 'eduma' ),
					'allow_html_formatting' => true
				),
				'cat_id'          => array(
					'type'     => 'select',
					'label'    => esc_html__( 'Select Category', 'eduma' ),
					'default'  => 'all',
					'multiple' => true,
					'options'  => thim_get_cat_taxonomy( 'category', array( 'all' => esc_html__( 'All', 'eduma' ) ) )
				),
				'visible'         => array(
					'type'    => 'number',
					'label'   => esc_html__( 'Visible items', 'eduma' ),
					'default' => '1'
				),
				'post_limit'      => array(
					'type'    => 'number',
					'label'   => esc_html__( 'Posts limit display on each category', 'eduma' ),
					'default' => '6'
				),
				'show_nav'        => array(
					'type'    => 'radio',
					'label'   => esc_html__( 'Show Navigation', 'eduma' ),
					'default' => 'yes',
					'options' => array(
						'no'  => esc_html__( 'No', 'eduma' ),
						'yes' => esc_html__( 'Yes', 'eduma' ),
					)
				),
				'show_pagination' => array(
					'type'    => 'radio',
					'label'   => esc_html__( 'Show Pagination', 'eduma' ),
					'default' => 'no',
					'options' => array(
						'no'  => esc_html__( 'No', 'eduma' ),
						'yes' => esc_html__( 'Yes', 'eduma' ),
					)
				),
				'auto_play'       => array(
					'type'        => 'number',
					'label'       => esc_html__( 'Auto Play Speed (in ms)', 'eduma' ),
					'description' => esc_html__( 'Set 0 to disable auto play.', 'eduma' ),
					'default'     => '0'
				),
				'link_view_all'   => array(
					'type'  => 'text',
					'label' => esc_html__( 'Link View All', 'eduma' ),
				),
				'text_view_all'   => array(
					'type'  => 'text',
					'label' => esc_html__( 'Text View All', 'eduma' ),
				),

			)
		);
	}

	/**
	 * Initialize the CTA widget
	 */

	function get_template_name( $instance ) {
		if ( ! empty( $instance['layout'] ) ) {
			return $instance['layout'];
		} else {
			return 'base';
		}

	}

	function get_style_name( $instance ) {
		return false;
	}


}

function thim_carousel_categories_widget() {
	register_widget( 'Thim_Carousel_Categories_Widget' );
}

add_action( 'widgets_init', 'thim_carousel_categories_widget' );