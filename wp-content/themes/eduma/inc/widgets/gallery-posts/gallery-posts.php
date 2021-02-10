<?php

class Thim_Gallery_Post_Widget extends Thim_Widget {
	function __construct() {
 		parent::__construct(
			'gallery-posts',
			esc_attr__( 'Thim: Gallery Posts ', 'eduma' ),
			array(
				'description'   => esc_attr__( 'Display gallery posts', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'thim-widget-icon thim-widget-icon-gallery-posts'
			),
			array(),
			array(
				'cat'     => array(
					'type'    => 'select',
					'label'   => esc_attr__( 'Select Category', 'eduma' ),
					'options' => thim_get_cat_taxonomy( 'category', array( 'all' => esc_html__( 'All', 'eduma' ) )  ),
				),
                'layout' => array(
                    'type'    => 'select',
                    'label'   => esc_html__( 'Layout', 'eduma' ),
                    'options' => array(
                        '' => 'Default',
                        'isotope' => 'Isotope',
                    ),
                ),
				'columns' => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Columns', 'eduma' ),
					'options' => array(
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'6' => '6',
					),
					'default' => '4'
				),
				'filter'  => array(
					'type'    => 'checkbox',
					'label'   => esc_html__( 'Show Filter', 'eduma' ),
					'default' => true,
				),
				'limit'    => array(
					'type'    => 'number',
					'label'   => esc_html__( 'Limit', 'eduma' ),
					'default' => '8'
				),
			)
		);
	}

	/**
	 * Initialize the CTA widget
	 */

	function get_template_name( $instance ) {
	    if( isset($instance['layout']) && $instance['layout'] != '' ) {
	        return $instance['layout'];
        } else {
            return 'base';
        }
	}

	function get_style_name( $instance ) {
		return false;
	}
}

function thim_gallery_posts_widget() {
	register_widget( 'Thim_Gallery_Post_Widget' );
}

add_action( 'widgets_init', 'thim_gallery_posts_widget' );