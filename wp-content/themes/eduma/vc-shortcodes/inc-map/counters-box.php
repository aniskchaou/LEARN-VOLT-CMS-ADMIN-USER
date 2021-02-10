<?php

vc_map( array(
    'name'        => esc_html__( 'Thim: Counters Box', 'eduma' ),
    'base'        => 'thim-counters-box',
    'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
    'description' => esc_html__( 'Display Counters Box.', 'eduma' ),
    'icon'        => 'thim-widget-icon thim-widget-icon-counters-box',
    'params'      => array(

        array(
            'type'        => 'textfield',
            'admin_label' => true,
            'heading'     => esc_html__( 'Counters Label', 'eduma' ),
            'param_name'  => 'counters_label',
            'std'         => '',
        ),

        array(
            'type'        => 'number',
            'admin_label' => true,
            'heading'     => esc_html__( 'Counters Value', 'eduma' ),
            'param_name'  => 'counters_value',
            'std'         => '20',
        ),

        array(
            'type'        => 'textfield',
            'admin_label' => true,
            'heading'     => esc_html__( 'Text Number', 'eduma' ),
            'param_name'  => 'text_number',
            'std'         => '',
        ),

        array(
            'type'        => 'textfield',
            'admin_label' => true,
            'heading'     => esc_html__( 'View More Text', 'eduma' ),
            'param_name'  => 'view_more_text',
            'std'         => '',
        ),

        array(
            'type'        => 'textfield',
            'admin_label' => true,
            'heading'     => esc_html__( 'View More Link', 'eduma' ),
            'param_name'  => 'view_more_link',
            'std'         => '',
        ),

        array(
            'type'        => 'colorpicker',
            'admin_label' => true,
            'heading'     => esc_html__( 'Counter Color', 'eduma' ),
            'param_name'  => 'counter_value_color',
        ),

        array(
            'type'        => 'colorpicker',
            'admin_label' => true,
            'heading'     => esc_html__( 'Background Color', 'eduma' ),
            'param_name'  => 'background_color',
        ),
        /* start */
        array(
            'type'        => 'dropdown',
            'admin_label' => false,
            'heading'     => esc_html__( 'Icon type', 'eduma' ),
            'param_name'  => 'icon_type',
            'description' => esc_html__( 'Select icon type to display', 'eduma' ),
            'value'       => array(
                esc_html__( 'Font Awesome', 'eduma' ) => 'font-awesome',
                esc_html__( 'Custom Image', 'eduma' ) => 'custom',
            ),
            'group'       => esc_html__( 'Icon Settings', 'eduma' ),
        ),

        array(
            'type'        => 'iconpicker',
            'admin_label' => false,
            'heading'     => esc_html__( 'Font Awesome Icon', 'eduma' ),
            'param_name'  => 'icon',
            'value'       => '',
            'description' => esc_html__( 'Select icon', 'eduma' ),
            'dependency'  => array(
                'element' => 'icon_type',
                'value'   => 'font-awesome',
            ),
            'group'       => esc_html__( 'Icon Settings', 'eduma' ),
        ),

        array(
            'type'        => 'attach_image',
            'admin_label' => false,
            'heading'     => esc_html__( 'Image Icon', 'eduma' ),
            'param_name'  => 'icon_img',
            'std'         => '14',
            'description' => esc_html__( 'Select custom image icon', 'eduma' ),
            'dependency'  => array(
                'element' => 'icon_type',
                'value'   => 'custom',
            ),
            'group'       => esc_html__( 'Icon Settings', 'eduma' ),
        ),
        /* end */

        array(
            'type'        => 'colorpicker',
            'admin_label' => true,
            'heading'     => esc_html__( 'Border Color Icon', 'eduma' ),
            'param_name'  => 'border_color',
            'group'       => esc_html__( 'Icon Settings', 'eduma' ),
        ),

        array(
            'type'        => 'colorpicker',
            'admin_label' => true,
            'heading'     => esc_html__( 'Counters Icon Color', 'eduma' ),
            'param_name'  => 'counter_color',
            'group'       => esc_html__( 'Icon Settings', 'eduma' ),
        ),
		array(
            'type'        => 'colorpicker',
            'admin_label' => true,
            'heading'     => esc_html__( 'Counters Label Color', 'eduma' ),
            'param_name'  => 'counter_label_color',
         ),

        array(
            'type'        => 'dropdown',
            'admin_label' => true,
            'heading'     => esc_html__( 'Style', 'eduma' ),
            'param_name'  => 'style',
            'value'       => array(
                esc_html__( 'Select', 'eduma' )        => '',
                esc_html__( 'Home Page', 'eduma' )     => 'home-page',
                esc_html__( 'Page About Us', 'eduma' ) => 'about-us',
                esc_html__( 'Number Left', 'eduma' )   => 'number-left',
                esc_html__( 'Text Gradient', 'eduma' )   => 'text-gradient',
            ),
        ),

        //Animation
        array(
            'type'        => 'dropdown',
            'heading'     => esc_html__( 'Animation', 'eduma' ),
            'param_name'  => 'css_animation',
            'admin_label' => true,
            'value'       => array(
                esc_html__( 'No', 'eduma' )                 => '',
                esc_html__( 'Top to bottom', 'eduma' )      => 'top-to-bottom',
                esc_html__( 'Bottom to top', 'eduma' )      => 'bottom-to-top',
                esc_html__( 'Left to right', 'eduma' )      => 'left-to-right',
                esc_html__( 'Right to left', 'eduma' )      => 'right-to-left',
                esc_html__( 'Appear from center', 'eduma' ) => 'appear'
            ),
            'description' => esc_html__( 'Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.', 'eduma' )
        ),

        // Extra class
        array(
            'type'        => 'textfield',
            'admin_label' => true,
            'heading'     => esc_html__( 'Extra class', 'eduma' ),
            'param_name'  => 'el_class',
            'value'       => '',
            'description' => esc_html__( 'Add extra class name that will be applied to the icon box, and you can use this class for your customizations.', 'eduma' ),
        ),
    )
) );