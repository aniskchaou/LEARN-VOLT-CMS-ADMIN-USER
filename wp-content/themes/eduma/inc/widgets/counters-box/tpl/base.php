<?php

$style_counter_label_color = $counters_icon_value = $counters_value  = $counter_value_color = $counters_label = $jugas_animation = $icon = $label = $box_style = $text_number = $border_color = $counter_style = $view_more_button = $view_more_text = $view_more_link = '';
$jugas_animation .= thim_getCSSAnimation( $instance['css_animation'] );

if ( ! empty( $instance['text_number'] ) ) {
	$text_number = $instance['text_number'];
}
if ( ! empty ( $instance['view_more_text'] ) ) {
	$view_more_text = $instance['view_more_text'];
}

if ( ! empty ( $instance['view_more_link'] ) ) {
	$view_more_link = $instance['view_more_link'];
}

if ( $instance['counters_label'] <> '' ) {
	$counters_label = $instance['counters_label'];
}

if ( $instance['counter_value_color'] <> '' ) {
	$counter_value_color = 'style="color:' . $instance['counter_value_color'] . '"';
}

if ( $instance['border_color'] <> '' ) {
	$border_color = 'border-color:' . $instance['border_color'] . ';';
}

if ( $instance['counter_color'] <> '' ) {
	$counters_icon_value = 'color:' . $instance['counter_color'] . ';';
}

if ( $instance['counter_label_color'] <> '' ) {
	$style_counter_label_color = ' style="color:' . $instance['counter_label_color'] . '"';
}

if ( $instance['background_color'] <> '' ) {
	$box_style .= ' background-color:' . $instance['background_color'] . ';';
}

if ( $instance['counters_value'] <> '' ) {
	$counters_value = $instance['counters_value'];
}

/* show icon or custom icon */
$html_icon = '';
if ( $instance['icon_type'] == 'font-awesome' ) {
	if ( $instance['icon'] == '' ) {
		$instance['icon'] = 'none';
	}
	if ( $instance['icon'] != 'none' ) {
		if ( thim_plugin_active( 'js_composer/js_composer.php' ) || thim_plugin_active( 'elementor/elementor.php' ) ) {
			$icon = '<i class="' . $instance['icon'] . '"></i>';
		} else {
			$icon = '<i class="fa fa-' . $instance['icon'] . '"></i>';
		}
	}
} else {
	if ( $instance['icon_type'] == 'font-7-stroke' ) {
		if ( $instance['icon_stroke'] == '' ) {
			$instance['icon_stroke'] = 'none';
		}
		if ( $instance['icon_stroke'] != 'none' ) {
			if ( strpos( $instance['icon_stroke'], 'pe-7s' ) !== false ) {
				$class = $instance['icon_stroke'];
			} else {
				$class = 'pe-7s-' . $instance['icon_stroke'];
			}

			$icon = '<i class="' . $class . '"></i>';
		}
	} else if ( $instance['icon_type'] == 'font-flaticon' ) {
		if ( $instance['icon_flat'] == '' ) {
			$instance['icon_flat'] = 'none';
		}
		if ( $instance['icon_flat'] != 'none' ) {
			if ( strpos( $instance['icon_flat'], 'flaticon' ) !== false ) {
				$class = $instance['icon_flat'];
			} else {
				$class = 'flaticon-' . $instance['icon_flat'];
			}

			$icon = '<i class="' . $class . '"></i>';
		}
	} else {
		$icon .= '<span class="icon icon-images">' . thim_get_feature_image( $instance['icon_img'] ) . '</span>';
	}
}
/* end show icon or custom icon */

if ( $instance['style'] <> '' ) {
	$counter_style = $instance['style'];
}
echo '<div class="counter-box ' . $jugas_animation . ' ' . $counter_style . '" style="' . $box_style . '">';
if ( $icon ) {
	echo '<div class="icon-counter-box" style="' . $border_color . $counters_icon_value .'">' . $icon . '</div>';
}
if ( $counters_label != '' ) {
	$label = '<div class="counter-box-content"' . $style_counter_label_color . '>' . $counters_label . '</div>';
}
if ( '' != $view_more_text && '' != $view_more_link ) {
	if ( isset( $view_more_link['url'] ) ) {
		$view_more_button = '<a class="view-more" href="' . $view_more_link['url'] . '">' . $view_more_text . '<i class="fa fa-chevron-right"></i></a>';
	} else {
		$view_more_button = '<a class="view-more" href="' . $view_more_link . '">' . $view_more_text . '<i class="fa fa-chevron-right"></i></a>';
	}
}

if ( $counters_value != '' ) {
	echo '<div class="content-box-percentage">
		<div class="wrap-percentage">
		<div class="display-percentage" data-percentage="' . $counters_value . '" ' . $counter_value_color . '>'
	     . $counters_value . '</div><div class="text_number">' . $text_number . '</div></div>';
	echo '<div class="counter-content-container">' . $label . $view_more_button . '</div></div>';
}

echo '</div>';


?>