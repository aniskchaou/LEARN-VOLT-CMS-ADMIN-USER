<?php

class Thim_Carousel_Post_Widget extends Thim_Widget {

	function __construct() {
 		parent::__construct(
			'carousel-post',
			esc_html__( 'Thim: Carousel Posts', 'eduma' ),
			array(
				'description'   => esc_html__( 'Display Post with Carousel', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-carousel-post',
			),
			array(),
			array(
				'title'           => array(
					'type'                  => 'text',
					'label'                 => esc_html__( 'Heading', 'eduma' ),
					'default'               => esc_html__( 'Post Carousel', 'eduma' ),
					'allow_html_formatting' => true
				),
				'cat_id'          => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Select Category', 'eduma' ),
					'default' => 'all',
					'options' => thim_get_cat_taxonomy( 'category', array( 'all' => esc_html__( 'All', 'eduma' ) ) )
				),
				'layout'          => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Layout', 'eduma' ),
					'options' => array(
						'base'     => esc_html__( 'Default', 'eduma' ),
						'layout-2' => esc_html__( 'Layout 2', 'eduma' ),
						'layout-3' => esc_html__( 'Layout 3', 'eduma' ),
					),
				),
				'visible_post'    => array(
					'type'    => 'number',
					'label'   => esc_html__( 'Posts visible', 'eduma' ),
					'default' => '3'
				),
				'number_posts'    => array(
					'type'    => 'number',
					'label'   => esc_html__( 'Number posts', 'eduma' ),
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
				'orderby'         => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Order by', 'eduma' ),
					'options' => array(
						'popular' => esc_html__( 'Popular', 'eduma' ),
						'recent'  => esc_html__( 'Date', 'eduma' ),
						'title'   => esc_html__( 'Title', 'eduma' ),
						'random'  => esc_html__( 'Random', 'eduma' ),
					),
				),
				'order'           => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Order', 'eduma' ),
					'options' => array(
						'asc'  => esc_html__( 'ASC', 'eduma' ),
						'desc' => esc_html__( 'DESC', 'eduma' )
					),
				),

			),
			THIM_DIR . 'inc/widgets/carousel-post/'
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

	function enqueue_frontend_scripts() {
		wp_enqueue_script( 'thim-owl-carousel' );
	}

	//Get list post categories
 }

function thim_carousel_post_widget() {
	register_widget( 'Thim_Carousel_Post_Widget' );
}

add_action( 'widgets_init', 'thim_carousel_post_widget' );