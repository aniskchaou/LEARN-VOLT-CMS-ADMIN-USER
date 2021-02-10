<?php

defined( 'ABSPATH' ) || exit();

class LP_WC_Payment {

	public static function init() {
		if ( is_admin() ) {
			add_filter( 'learn_press_payment_method', array( __CLASS__, 'add_payment' ) );
			/**
			 * @editor tungnx
			 * @reason not use
			 * @deprecated 3.2.1
			 */
			//add_action( 'wp_footer', array( __CLASS__, 'scripts' ) );
		}
	}

	/**
	 * Add Woo payment
	 */
	public static function add_payment( $methods ) {
		require_once LP_ADDON_WOO_PAYMENT_PATH . '/incs/class-lp-gateway-woo.php';
		$methods['woocommerce'] = 'LP_Gateway_Woo';

		return $methods;
	}
}

LP_WC_Payment::init();
