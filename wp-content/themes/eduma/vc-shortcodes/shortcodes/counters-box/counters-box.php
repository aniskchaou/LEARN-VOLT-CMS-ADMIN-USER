<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcode Heading
 *
 * @param $atts
 *
 * @return string
 */
function thim_shortcode_counters_box( $atts ) {

	$counters_box = shortcode_atts(
		array(
			'counters_label'            => 'Counters Box',
			'counters_value'            => '20',
			'text_number'               => '',
			'view_more_text'            => '',
			'view_more_link'            => '',
			'counter_value_color'       => '',
			'background_color'          => '',
			'icon_type'                 => 'font-awesome',
			'icon'                      => '',
			'icon_img'                  => '',
			'border_color'              => '',
			'counter_color'             => '',
			'counter_label_color' => '',
			'style'                     => 'home-page',
			'css_animation'             => '',
			'el_class'                  => '',
		), $atts
	);

	$instance = array(
		'counters_label'            => $counters_box['counters_label'],
		'counters_value'            => $counters_box['counters_value'],
		'text_number'               => $counters_box['text_number'],
		'view_more_text'            => $counters_box['view_more_text'],
		'view_more_link'            => $counters_box['view_more_link'],
		'counter_value_color'       => $counters_box['counter_value_color'],
		'background_color'          => $counters_box['background_color'],
		'icon_type'                 => $counters_box['icon_type'],
		'icon'                      => $counters_box['icon'],
		'icon_img'                  => $counters_box['icon_img'],
		'border_color'              => $counters_box['border_color'],
		'counter_color'             => $counters_box['counter_color'],
		'counter_label_color' => $counters_box['counter_label_color'],
		'style'                     => $counters_box['style'],
		'css_animation'             => $counters_box['css_animation'],
		'el_class'                  => $counters_box['el_class'],
	);

	$args                 = array();
	$args['before_title'] = '<h3 class="widget-title">';
	$args['after_title']  = '</h3>';

	$widget_template       = THIM_DIR . 'inc/widgets/counters-box/tpl/base.php';
	$child_widget_template = THIM_CHILD_THEME_DIR . 'inc/widgets/counters-box/base.php';
	if ( file_exists( $child_widget_template ) ) {
		$widget_template = $child_widget_template;
	}
	wp_enqueue_script( 'waypoints' );
	wp_enqueue_script( 'thim-CountTo' );

	ob_start();
	if ( $instance['el_class'] ) {
		echo '<div class="' . $instance['el_class'] . '">';
	}
	echo '<div class="thim-widget-counters-box">';
	include $widget_template;
	echo '</div>';
	if ( $instance['el_class'] ) {
		echo '</div>';
	}
	$html_output = ob_get_contents();
	ob_end_clean();

	return $html_output;
}

add_shortcode( 'thim-counters-box', 'thim_shortcode_counters_box' );


