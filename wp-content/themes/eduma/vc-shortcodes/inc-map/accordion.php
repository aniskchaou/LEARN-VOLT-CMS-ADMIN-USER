<?php
vc_map( array(
	'name'        => esc_html__( 'Thim: Accordion', 'eduma' ),
	'base'        => 'thim-accordion',
	'category'    => esc_html__( 'Thim Shortcodes', 'eduma' ),
	'description' => esc_html__( 'Display accordion.', 'eduma' ),
	'icon'        => 'thim-widget-icon thim-widget-icon-timetable',
	'params'      => array(
		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Title', 'eduma' ),
			'param_name'  => 'title',
			'value'       => '',
		),

		array(
			'type'        => 'param_group',
			'admin_label' => false,
			'heading'     => esc_html__( 'Accordion Items', 'eduma' ),
			'param_name'  => 'items',
			'params'      => array(
				array(
					'type'       => 'textfield',
                    'admin_label' => true,
					'value'      => '',
					'heading'    => esc_html__( 'Title', 'eduma' ),
					'std'        => esc_html__( 'Title', 'eduma' ),
					'param_name' => 'panel_title',
				),
                array(
                    'type'        => 'textarea',
                    'admin_label' => false,
                    'heading'     => esc_html__( 'Content', 'eduma' ),
                    'param_name'  => 'panel_body',
                    'std'         => esc_html__( 'Write a short description, that will describe the title or something informational and useful.', 'eduma' ),
                ),

			)
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