<?php

if ( !class_exists( 'Thim_Image_Box_Widget' ) ) {
    class Thim_Image_Box_Widget extends Thim_Widget {

        function __construct() {

            parent::__construct(
                'image-box',
                esc_html__( 'Thim: Image Box', 'eduma' ),
                array(
                    'description'   => esc_html__( 'Show Image box', 'eduma' ),
                    'help'          => '',
                    'panels_groups' => array( 'thim_widget_group' ),
                    'panels_icon'   => 'thim-widget-icon thim-widget-icon-icon-box'
                ),
                array(),
                array(
	                'layout'    => array(
		                'type'    => 'select',
		                'label'   => esc_html__( 'Style', 'eduma' ),
		                'options' => array(
			                'base'  => esc_html__( 'Default', 'eduma' ),
			                'layout-2'  => esc_html__( 'Layout 2', 'eduma' ),
		                ),
		                'default' => 'base',
		                'state_emitter' => array(
			                'callback' => 'select',
			                'args'     => array( 'layout_type' )
		                ),
	                ),
                    'title'          => array(
                        'type'  => 'text',
                        'label' => esc_html__( 'Title', 'eduma' )
                    ),
	                'description'         => array(
		                'type'  => 'textarea',
		                'label' => esc_html__( 'Description', 'eduma' ),
		                'allow_html_formatting' => true,
		                'state_handler' => array(
			                'layout_type[base]' => array( 'hide' ),
			                'layout_type[layout-2]' => array( 'show' ),
		                ),
	                ),
                    'image' => array(
                        "type"        => "media",
                        "label"       => esc_html__( "Upload Image:", 'eduma' ),
                        "description" => esc_html__( "Upload the image.", 'eduma' ),
                        "class_name"  => 'custom',
                    ),
	                'title_bg_color'   => array(
		                'type'  => 'color',
		                'label' => esc_html__( 'Title Background Color', 'eduma' ),
		                'state_handler' => array(
			                'layout_type[base]' => array( 'hide' ),
			                'layout_type[layout-2]' => array( 'show' ),
		                ),
	                ),
                    'link'          => array(
                        'type'  => 'text',
                        'label' => esc_html__( 'Link', 'eduma' )
                    ),

                ),
                THIM_DIR . 'inc/widgets/image-box/'
            );
        }

        /**
         * Initialize the Image Box widget
         */


        function get_template_name( $instance ) {
            if ( !empty( $instance['layout'] ) ) {
                return $instance['layout'];
            } else {
                return 'base';
            }
        }

        function get_style_name( $instance ) {
            return false;
        }
    }
}

function thim_image_box_register_widget() {
    register_widget( 'Thim_Image_Box_Widget' );
}

add_action( 'widgets_init', 'thim_image_box_register_widget' );