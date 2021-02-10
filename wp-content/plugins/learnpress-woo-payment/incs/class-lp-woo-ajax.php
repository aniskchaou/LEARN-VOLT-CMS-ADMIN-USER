<?php

/**
 * Class LP_Woo_Ajax
 *
 * Handle ajax for certificates
 *
 * @since 3.1.4
 */
class LP_Woo_Ajax {
	protected static $_instance;
	protected $_hook_arr = array( 'lpWooAddCourseToCart' );

	protected function __construct() {
		foreach ( $this->_hook_arr as $hook ) {
			add_action( 'wp_ajax_' . $hook, array( $this, $hook ) );
			add_action( 'wp_ajax_nopriv_' . $hook, array( $this, $hook ) );
		}
	}

	public static function getInstance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Add course to cart Woo
	 */
	public function lpWooAddCourseToCart() {
		$result = array( 'code' => 0, 'message' => __( 'error', 'learnpress-woo-payment' ) );

		if ( ! isset( $_POST['course-id'] ) || ! isset( $_POST['add-course-to-cart-nonce'] )
			|| ! wp_verify_nonce( LP_Helper::sanitize_params_submitted( $_POST['add-course-to-cart-nonce'] ), 'add-course-to-cart' ) ) {
			$result['message'] = __( 'Params invalid', 'learnpress-woo-payment' );

			wp_send_json( $result );
		}

		$course_id = absint( wp_unslash( $_POST['course-id'] ) );
		$course    = learn_press_get_course( $course_id );

		if ( $course ) {
			$result['message'] = __( 'course is invalid', 'learnpress-woo-payment' );
		}

		$wc_cart       = WC()->cart;
		$cart_item_key = $wc_cart->add_to_cart( $course_id );

		if ( $cart_item_key ) {
			//$wc_cart->set_session();

			$result['code']             = 1;
			$result['message']          = $cart_item_key;
			$result['button_view_cart'] = '<a class="btn-lp-course-view-cart" href="' . wc_get_cart_url() . '"><button class="lp-button">' . __( 'View cart', 'learnpress-woo-payment' ) . '</button></a>';

			/*if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
				$result['redirect_to'] = wc_get_cart_url();
			}*/

			if ( 'yes' == LP()->settings()->get( 'woo-payment_redirect_to_checkout' ) ) {
				$result['redirect_to'] = wc_get_checkout_url();
			}
		} else {
			$wc_notices = wc_get_notices();

			if ( isset( $wc_notices['error'] ) && ! empty( $wc_notices['error'] ) ) {
				//$message_error = sanitize_text_field($wc_notices['error'][0]['notice']);
				$result['message'] = __( 'You cannot add this course to your cart. Maybe certificate exists', 'learnpress-woo-payment' );
			}
		}

		wp_send_json( $result );
	}
}

LP_Woo_Ajax::getInstance();
