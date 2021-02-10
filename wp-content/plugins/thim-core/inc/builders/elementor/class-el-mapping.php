<?php
/**
 * Thim_Builder Elementor Mapping class
 *
 * @version     1.0.0
 * @author      Thim_Builder
 * @package     Thim_Builder/Classes
 * @category    Classes
 * @author      Thimpress, leehld
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! class_exists( 'Thim_Builder_El_Mapping' ) ) {
	/**
	 * Class Thim_Builder_El_Mapping
	 */
	class Thim_Builder_El_Mapping {

		/**
		 * Mapping Visual Composer type to Elementor
		 *
		 * @param $type
		 *
		 * @return bool|mixed
		 */
		private static function _mapping_types( $type ) {
			$mapping = array(
				'number'         => Controls_Manager::NUMBER,
				'textfield'      => Controls_Manager::TEXT,
				'vc_link'        => Controls_Manager::URL,
				'param_group'    => Controls_Manager::REPEATER,
				'attach_image'   => Controls_Manager::MEDIA,
				'attach_images'  => Controls_Manager::GALLERY,
				'iconpicker'     => Controls_Manager::ICON,
				'dropdown'       => Controls_Manager::SELECT,
				'colorpicker'    => Controls_Manager::COLOR,
				'textarea'       => Controls_Manager::TEXTAREA,
				'textarea_html'  => Controls_Manager::WYSIWYG,
				'checkbox'       => Controls_Manager::SWITCHER,
				'radio_image'    => Controls_Manager::CHOOSE,
				'datetimepicker' => Controls_Manager::DATE_TIME,
				'bp_heading'     => Controls_Manager::HEADING,
				'bp_hidden'      => Controls_Manager::HIDDEN,
				'loop'           => ''
			);

			if ( ! array_key_exists( $type, $mapping ) ) {
				return Controls_Manager::TEXT;
			}

			return apply_filters( 'thim-builder/el-mapping-types', $mapping[ $type ] );
		}

		/**
		 * @param $params
		 *
		 * @return array
		 */
		public static function mapping( $params ) {

			if ( ! is_array( $params ) ) {
				return array();
			}

			$controls = array();

			foreach ( $params as $param ) {

				$type  = $param['type'];
				$field = array();

				// get mapping field
				$field['type']        = self::_mapping_types( $type );
				$field['label']       = isset( $param['heading'] ) ? $param['heading'] : '';
				$field['description'] = isset( $param['description'] ) ? $param['description'] : '';
				$field['default']     = isset( $param['std'] ) ? $param['std'] : [];

				switch ( $param['type'] ) {
					// common structure field
					case 'number':
					case 'textfield':
					case 'iconpicker':
						break;
					case 'attach_image':
					case 'attach_images':
						$field['default'] = array( 'url' => Utils::get_placeholder_image_src() );
						break;
					case 'vc_link':
						$field['placeholder'] = __( 'https://your-link.com', 'thim-core' );
						$field['default']     = array( 'url' => '#' );
						break;
					case 'param_group':
						$repeater = new Repeater();

						// repeats options
						$repeats = self::mapping( $param['params'] );

						foreach ( $repeats as $key => $repeat ) {
							$repeater->add_control( $key, $repeat );
						}

						$field = array_merge( $field, array(
							'fields' => $repeater->get_controls()
						) );
						break;
					case 'dropdown':
						$field['options'] = array_flip( $param['value'] );
						break;
					case 'radio_image':
						$field['options'] = $param['options'];
						foreach ( $field['options'] as $k_o => $option ) {
							$field['options'][ $k_o ] = array(
								'title' => '<img src="' . $option . '">',
								'icon'  => 'bp_el_class'
							);
						}
						break;
					default:
						$field = array_merge( $field, apply_filters( 'thim-builder/field-el-param', array(), $type ) );
						break;
				}

				// handle dependency to condition
				if ( isset( $param['dependency'] ) ) {
					$dependency = $param['dependency'];

					if ( isset( $dependency['value'] ) ) {
						$field['condition'] = array( $dependency['element'] => $dependency['value'] );
					} else if ( isset( $dependency['not_empty'] ) ) {
						$field['condition'] = array( $dependency['element'] . '!' => '' );
					}
				}

				$controls[ $param['param_name'] ] = $field;
			}

			return $controls;
		}
	}
}

new Thim_Builder_El_Mapping();