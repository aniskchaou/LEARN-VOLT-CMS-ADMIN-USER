<?php
/**
 * LearnPress Content Drip Functions
 *
 * Define common functions for both front-end and back-end
 *
 * @author   ThimPress
 * @package  LearnPress/Content-Drip/Functions
 * @version  3.0.0
 */
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'lp_content_drip_admin_view' ) ) {
	/**
	 * Admin view.
	 *
	 * @param        $name
	 * @param string $args
	 */
	function lp_content_drip_admin_view( $name, $args = '' ) {
		if ( ! preg_match( '~.php$~', $name ) ) {
			$name .= '.php';
		}
		if ( is_array( $args ) ) {
			extract( $args );
		}
		include LP_ADDON_CONTENT_DRIP_INC_PATH . "admin/views/{$name}";
	}
}

if ( ! function_exists( 'lp_content_drip_types' ) ) {
	/**
	 * @return mixed
	 */
	function lp_content_drip_types() {
		$types = array(
			'specific_date' => __( '1. Specific time after enrolled course', 'learnpress-content-drip' ),
			'sequentially'  => __( '2. Open the course items sequentially', 'learnpress-content-drip' ),
			'prerequisite'  => __( '3. Open item bases on prerequisite items', 'learnpress-content-drip' )
		);

		return apply_filters( ' learn-press/content-drip/drip-types', $types );
	}
}
?>