<?php
/**
 * Thim_Builder functions
 *
 * @version     1.0.0
 * @package     Thim_Builder/Classes
 * @category    Classes
 * @author      Thimpress, leehld
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'thim_builder_get_template' ) ) {
	/**
	 * @param        $template_name
	 * @param array  $args
	 * @param string $template_path
	 * @param string $default_path
	 */
	function thim_builder_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
		if ( is_array( $args ) && isset( $args ) ) {
			extract( $args );
		}

		if ( false === strpos( $template_name, '.php' ) ) {
			$template_name .= '.php';
		}
		$template_file = thim_builder_locate_template( $template_name, $template_path, $default_path );

		if ( ! file_exists( $template_file ) ) {
			_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file ), '1.0.0' );

			return;
		}

		include $template_file;
	}
}

if ( ! function_exists( 'thim_builder_locate_template' ) ) {
	/**
	 * @param        $template_name
	 * @param string $template_path
	 * @param string $default_path
	 *
	 * @return mixed
	 */
	function thim_builder_locate_template( $template_name, $template_path = '', $default_path = '' ) {

		if ( ! $template_path ) {
			$template_path = TP_THEME_THIM_DIR.'inc/shortcodes/';
		}
		// Set default plugin templates path.
		if ( ! $default_path ) {
			$default_path = TP_THEME_THIM_DIR . 'inc/shortcodes/' . $template_path; // Path to the template folder
		}

 		$base = substr( ( substr( $template_path, 0, strpos( $template_path, '/tpl' ) ) ), strpos( $template_path, '/' ) + 1 );
 		// Search template file in theme folder.
		$template = locate_template( array(
			"inc/shortcodes/$base/$template_name",
			$template_name
		) );

		// Get plugins template file.
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}

		return apply_filters( 'thim-builder/locate-template', $template, $template_name, $template_path, $default_path );
	}
}

if ( ! function_exists( 'thim_builder_get_elements' ) ) {
	/**
	 * @return mixed
	 */
	function thim_builder_get_elements() {
		$TB      = ThimCore_Builder();
		$elements = $TB->get_elements();

		// allow unset elements
		$unset = apply_filters( 'thim-builder/elements-unset', array() );

		foreach ( $elements as $plugin => $_elements ) {
			foreach ( $unset as $item ) {
				$index = array_search( $item, $_elements );

				if ( $index != false ) {
					unset( $elements[ $plugin ][ $index ] );
				}
			}
		}

		return $elements;
	}
}

if ( ! function_exists( 'thim_builder_get_group' ) ) {
	/**
	 * Get group of element (widget/shortcode) by name
	 *
	 * @param $name
	 *
	 * @return int|mixed|string
	 */
	function thim_builder_get_group( $name ) {
		$TB       = ThimCore_Builder();
		$elements = $TB->get_elements();

		foreach ( $elements as $group => $_elements ) {
			if ( in_array( $name, $_elements ) ) {
				return $group;
			}
		}

		return apply_filters( 'thim-builder/default-group', 'general', $name );
	}
}