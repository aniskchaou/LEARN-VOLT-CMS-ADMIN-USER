<?php
/**
 * Class LP_Commission.
 *
 * @author  ThimPress
 * @package LearnPress/Commission/Classes
 * @version 3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

if ( ! class_exists( 'LP_Commission' ) ) {
	/**
	 * Class LP_Commission.
	 */
	class LP_Commission {

		/**
		 * @var string
		 */
		public $key_active = '_lp_commission_active_';

		/**
		 * @var string
		 */
		public $prefix = '_lp_commission_';

		/**
		 * @var string
		 */
		public $key_main_instructor = '_lp_commission_main_';

		/**
		 * @var
		 */
		private static $instance;

		/**
		 * @return mixed
		 */
		public function is_enable() {
			$is_enable = false;
			$value     = LP()->settings->get( 'enable_commission' );
			if ( ! empty( $value ) && $value === 'yes' ) {
				$is_enable = true;
			}

			return apply_filters( 'lp_commission_is_enable', $is_enable );
		}

		/**
		 * @return int|mixed
		 */
		public function get_commission_global() {
			$value = LP()->settings->get( 'commission_percent' );
			if ( empty( $value ) ) {
				$value = 0;
			}
			$value = apply_filters( 'lp_commission_percent_global', intval( $value ) );

			return $value;
		}

		/**
		 * @return int|mixed
		 */
		public function get_commission_min() {
			$value = LP()->settings->get( 'commission_min' );
			if ( empty( $value ) || intval( $value ) < 0 ) {
				$value = 0;
			}
			$value = apply_filters( 'lp_commission_min', intval( $value ) );

			return $value;
		}

		/**
		 * @return bool|mixed
		 */
		public function support_offline_payment() {
			$is_enable = false;
			$value     = LP()->settings->get( 'commission_offline_payment' );
			if ( ! empty( $value ) && $value === 'yes' ) {
				$is_enable = true;
			}

			$is_enable = apply_filters( 'lp_commission_offline_payment', $is_enable );

			return $is_enable;
		}

		/**
		 * @param string $course_id
		 *
		 * @return string
		 */
		public function get_key_main_instructor( $course_id = '' ) {
			return $this->key_main_instructor . $course_id;
		}

		/**
		 * @param $course_id
		 * @param string $instructor_id
		 *
		 * @return int|mixed
		 */
		public function get_commission_instructor( $course_id, $instructor_id = '' ) {
			$key = $this->get_key_main_instructor( $instructor_id );

			$commission_global = $this->get_commission_global();
			$value             = get_post_meta( $course_id, $key, true );
			if ( empty( $value ) ) {
				return $commission_global;
			}

			return intval( $value );
		}

		/**
		 * @param $course_id
		 *
		 * @return int|mixed
		 */
		public function get_commission_main_instructor( $course_id ) {
			return $this->get_commission_instructor( $course_id );
		}

		/**
		 * Get instance
		 *
		 * @return LP_Commission
		 */
		public static function instance() {
			if ( ! self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}
}

if ( ! function_exists( 'LPC' ) ) {
	/**
	 * @return LP_Commission
	 */
	function LPC() {
		return LP_Commission::instance();
	}
}