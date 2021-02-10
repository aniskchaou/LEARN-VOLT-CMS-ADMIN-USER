<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

vc_map( array(
    'name'        => esc_html__( 'Thim: Image Box', 'eduma' ),
    'base'        => 'thim-image-box',
    'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
    'description' => esc_html__( 'Add Image box', 'eduma' ),
    'icon'        => 'thim-widget-icon thim-widget-icon-icon-box',
    'params'      => array(
	    array(
		    'type'        => 'dropdown',
		    'admin_label' => true,
		    'heading'     => esc_html__( 'Layout', 'eduma' ),
		    'param_name'  => 'layout',
		    'value'       => array(
			    esc_html__( 'Default', 'eduma' )        => 'base',
			    esc_html__( 'Layout 2', 'eduma' )        => 'layout-2',
		    ),
	    ),
        array(
            'type'        => 'textfield',
            'admin_label' => true,
            'heading'     => esc_html__( 'Title', 'eduma' ),
            'param_name'  => 'title',
            'description' => esc_html__( 'Provide the title for this box.', 'eduma' ),
        ),
	    array(
		    'type'        => 'textarea',
		    'admin_label' => true,
		    'heading'     => esc_html__( 'Description', 'eduma' ),
		    'param_name'  => 'description',
		    'value'       => '',
		    'description' => esc_html__( 'Provide the description for this box.', 'eduma' ),
		    'dependency' => array(
			    'element' => 'layout',
			    'value'   => 'layout-2',
		    ),
	    ),
        array(
            'type'        => 'attach_image',
            'admin_label' => false,
            'heading'     => esc_html__( 'Image Of Box', 'eduma' ),
            'description' => esc_html__( 'Select image from media library.', 'eduma' ),
            'param_name'  => 'image',
        ),
	    array(
		    'type'        => 'colorpicker',
		    'admin_label' => true,
		    'heading'     => esc_html__( 'Title Background Color', 'eduma' ),
		    'param_name'  => 'title_bg_color',
		    'dependency' => array(
			    'element' => 'layout',
			    'value'   => 'layout-2',
		    ),
	    ),
        array(
            'type'        => 'textfield',
            'admin_label' => true,
            'heading'     => esc_html__( 'Link', 'eduma' ),
            'param_name'  => 'link',
            'description' => esc_html__( 'Provide the title for this box.', 'eduma' ),
        ),
        // Extra class
        array(
            'type'        => 'textfield',
            'admin_label' => false,
            'heading'     => esc_html__( 'Extra class', 'eduma' ),
            'param_name'  => 'el_class',
            'value'       => '',
            'description' => esc_html__( 'Add extra class name that will be applied to the box, and you can use this class for your customizations.', 'eduma' ),
        )
    ,
    )
) );