<?php
/**
 * Class LP_Request_Withdrawal.
 *
 * @author  ThimPress
 * @package LearnPress/Commission/Classes
 * @version 3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

if ( ! class_exists( 'LP_Request_Withdrawal' ) ) {
	/**
	 * Class LP_Request_Withdrawal
	 */
	class LP_Request_Withdrawal {

		/**
		 * @var null
		 */
		private static $_instance = null;

		/**
		 * @var string
		 */
		public static $key_action = 'lp_withdraw';

		/**
		 * @var string
		 */
		public static $key_post_type = 'lp_withdraw';

		/**
		 * LP_Request_Withdrawal constructor.
		 */
		private function __construct() {
			$this->init_hooks();
		}

		/**
		 * Init hooks.
		 */
		private function init_hooks() {
			// add user profile tab
			add_filter( 'learn-press/profile-tabs', array( $this, 'profile_tabs' ) );
			// add user profile endpoint
			add_filter( 'learn_press_profile_tab_endpoints', array( $this, 'profile_tab_endpoints' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			// save post
			add_action( 'save_post' , array( $this, 'save_post' ) );
		}

		/**
		 * @param $hook
		 */
		public function enqueue_scripts( $hook ) {
			if ( learn_press_is_profile() ) {
				wp_enqueue_script( 'lp_withdrawals', LP_ADDON_COMMISSION_URI . 'assets/js/withdrawals.js', array( 'jquery' ), LP_ADDON_COMMISSION_VER );
			}
		}

		/**
		 * @param $post_id
		 *
		 * @return bool
		 */
		public function save_post( $post_id ) {
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return false;
			}

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return false;
			}

			$status = ! empty( $_POST['lp_status'] ) ? $_POST['lp_status'] : '';

			$check_username = isset( $_POST['check_username'] ) && $_POST['check_username'] ? $_POST['check_username'] : '';
			$check_password = isset( $_POST['check_password'] ) && $_POST['check_password'] ? $_POST['check_password'] : '';

			if ( ! self::check_user_pass( $check_username, $check_password ) ) {
				return false;
			}

			if ( in_array( $status, array( 'complete', 'reject', 'pending', 'payon' ) ) ) {
				$wd = new LP_Withdrawal( $post_id );
				switch ( $status ) {
					case 'complete';
						$wd->complete();
						break;
					case 'reject':
						$wd->reject();
						break;
					case 'pending':
						$wd->pending();
						break;
					case 'payon':
						$wd->payon();
						break;
					default:
						break;
				}
			}

			return true;
		}

		/**
		 * Add profile tab.
		 *
		 * @param $tabs
		 *
		 * @return mixed
		 */
		public function profile_tabs( $tabs ) {
			if ( ! LPC()->is_enable() ) {
				return $tabs;
			}

			$profile      = LP_Profile::instance();
			$current_user = learn_press_get_current_user();

			if ( ! $profile->is_current_user() ) {
				return $tabs;
			}

			if ( ! $current_user->has_role( array( 'administrator', 'lp_teacher' ) ) ) {
				return $tabs;
			}

			$tabs['withdrawals'] = array(
				'title'    => __( 'Withdrawals', 'learnpress-commission' ),
				'callback' => array( $this, 'withdrawals_tab_content' )
			);

			return $tabs;
		}

		/**
		 * @return string
		 */
		public function withdrawals_tab_content() {
			$notifications = array();

			if ( strtolower( $_SERVER['REQUEST_METHOD'] ) === 'post' ) {
				$notifications = $this->handle_withdrawal_request();
			}

			ob_start();
			learn_press_get_template( 'withdrawals.php', array(
				'notifications' => $notifications,
			), learn_press_template_path() . '/addons/commission/', LP_ADDON_COMMISSION_TEMPLATE );

			return ob_get_clean();
		}

		/**
		 * @param $endpoints
		 *
		 * @return array
		 */
		public function profile_tab_endpoints( $endpoints ) {
			$endpoints[] = 'withdrawals';

			return $endpoints;
		}

		/**
		 * Create WP nonce.
		 */
		public static function nonce() {
			wp_nonce_field( self::$key_action );
		}

		/**
		 * @return array
		 */
		private function handle_withdrawal_request() {
			$nonce     = $_POST['_wpnonce'];
			$post_data = $_POST;

			$profile = LP_Profile::instance();

			$verify = wp_verify_nonce( $nonce, self::$key_action );
			if ( ! $verify ) {
				return array(
					'return' => false,
					'msg'    => __( 'Verify nonce is wrong!', 'learnpress-commission' ),
					'code'   => 'NONCE_INVALID',
					'old'    => $post_data,
				);
			}

			$cuser = wp_get_current_user();
			$code  = $_POST['lp_withdrawals_secret_code'] ? $_POST['lp_withdrawals_secret_code'] : '';
			if ( ! self::check_user_pass( $cuser->user_login, $code ) ) {
				return array(
					'return' => false,
					'msg'    => __( 'Verify password is wrong!', 'learnpress-commission' ),
					'code'   => 'PASS_INVALID',
					'old'    => $post_data,
				);
			}

			$email = $_POST['lp_withdrawals_email'] ? $_POST['lp_withdrawals_email'] : '';
			if ( ! $email || empty( $email ) ) {
				return array(
					'return' => false,
					'msg'    => __( 'Please enter a paypal email account!', 'learnpress-commission' ),
					'code'   => 'ENTER_PAYPAL_EMAIL_ACCOUNT',
					'old'    => $post_data,
				);
			}

			if ( ! $profile->is_current_user() ) {
				return array(
					'return' => false,
					'msg'    => __( 'Something went wrong!', 'learnpress-commission' ),
					'code'   => 'USER_NOT_MATCH',
					'old'    => $post_data,
				);
			}

			$current_user             = learn_press_get_current_user();
			$value_commission_request = ! empty( $_POST['lp_withdrawals'] ) ? floatval( $_POST['lp_withdrawals'] ) : 0;
			$current_commission       = lp_commission_get_total_commission( $current_user->get_id() );

			if ( $value_commission_request > $current_commission ) {
				return array(
					'return' => false,
					'msg'    => __( 'You do not have enough money to withdraw!', 'learnpress-commission' ),
					'code'   => 'NOT_ENOUGH_MONEY',
					'old'    => $post_data,
				);
			}

			if ( $value_commission_request <= 0 ) {
				return array(
					'return' => false,
					'msg'    => __( 'The amount can not be zero!', 'learnpress-commission' ),
					'code'   => 'MONEY_ZERO',
					'old'    => $post_data,
				);
			}

			$min = LPC()->get_commission_min();
			if ( $value_commission_request < $min ) {
				return array(
					'return' => false,
					'msg'    => __( 'The amount is too small!', 'learnpress-commission' ),
					'code'   => 'TOO_SMALL',
					'old'    => $post_data,
				);
			}

			$method_key = isset( $_POST['lp_payment_method'] ) && $_POST['lp_payment_method'] ? $_POST['lp_payment_method'] : '';
			if ( ! $method_key || empty( $method_key ) ) {
				return array(
					'return' => false,
					'msg'    => __( 'Please choose a payment method!', 'learnpress-commission' ),
					'code'   => 'CHOOSE_METHOD',
					'old'    => $post_data,
				);
			}

			$all_method = self::get_withdrawal_methods();
			$method     = array(
				$method_key => $all_method[ $method_key ]
			);

			$new_withdrawal_id = $this->new_withdrawal( $email, $value_commission_request, $method );
			if ( is_wp_error( $new_withdrawal_id ) ) {
				return array(
					'return' => false,
					'msg'    => __( 'Create the withdrawal request failed!', 'learnpress-commission' ),
					'code'   => 'CREATE_WITHDRAWAL_ERROR',
					'old'    => $post_data,
				);
			}

			/**
			 * No any error. Subtract commission right here.
			 */
			$update = lp_commission_subtract_commission( $current_user->get_id(), $value_commission_request );
			if ( ! $update ) {
				return array(
					'return' => false,
					'msg'    => __( 'Something went wrong!', 'learnpress-commission' ),
					'code'   => 'ERROR_UPDATE',
					'old'    => $post_data,
				);
			}

			return array(
				'return' => true,
				'msg'    => __( 'Withdrawals request is successful!', 'learnpress-commission' ),
			);
		}

		/**
		 * @param $email
		 * @param $value
		 * @param $method
		 * @param null $user_id
		 *
		 * @return int|WP_Error
		 */
		private function new_withdrawal( $email, $value, $method, $user_id = null ) {
			if ( empty( $user_id ) ) {
				$user_id = get_current_user_id();
			}

			$user              = get_user_by( 'ID', $user_id );
			$user_data         = $user->data;
			$user_display_name = $user_data->display_name;

			$now          = new DateTime();
			$time_request = $now->format( 'd/m/Y' );

			$title = $user_display_name . ' - ' . $time_request . ' - ' . $value . learn_press_get_currency_symbol();

			$new_withdrawal = wp_insert_post( array(
				'post_title'   => $title,
				'post_content' => $email,
				'post_type'    => self::$key_post_type,
				'post_status'  => 'publish',
				'post_author'  => $user_id,
				'meta_input'   => array(
					'lp_value'          => $value,
					'lp_status'         => 'pending',
					'lp_time_request'   => time(),
					'lp_payment_method' => $method,
				)
			) );

			return $new_withdrawal;
		}

		/**
		 * @param $user_id
		 *
		 * @return array
		 */
		public static function get_withdrawals_by_user_id( $user_id ) {
			// WP_Query arguments
			$args = array(
				'post_type'      => array( 'lp_withdraw' ),
				'author'         => $user_id,
				'posts_per_page' => - 1
			);

			// The Query
			$query = new WP_Query( $args );

			$histories = array();
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					$post       = $query->post;
					$post_id    = $post->ID;
					$withdrawal = new LP_Withdrawal( $post_id );

					$date_format     = get_option( 'date_format' );
					$date_format     = apply_filters( 'lp_commission_date_format', $date_format );
					$time_format     = get_option( 'time_format' );
					$time_format     = apply_filters( 'lp_commission_time_format', $time_format );
					$datetime_format = $time_format . ' ' . $date_format;
					$datetime_format = apply_filters( 'lp_commission_datetime_format', $datetime_format );

					$time_request     = $withdrawal->get_time_request();
					$time_request_str = date_i18n( $datetime_format, $time_request->getTimestamp() );

					$time_resolve     = $withdrawal->get_time_resolve();
					$time_resolve_str = '-:-:- -/-/-';
					if ( $time_resolve ) {
						$time_resolve_str = date_i18n( $datetime_format, $time_resolve->getTimestamp() );
					}

					$status_str = $withdrawal->get_title_status();
					$value      = $withdrawal->get_value();
					$method     = $withdrawal->get_title_method_payment();

					$history = array(
						'ID'           => $post->ID,
						'title'        => $post->post_title,
						'time_request' => $time_request_str,
						'time_resolve' => $time_resolve_str,
						'status'       => $status_str,
						'value'        => $value,
						'method_title' => $method,
					);

					$histories[] = $history;
				}
				wp_reset_postdata();
			}

			return $histories;
		}

		/**
		 * Get gateway payment methods.
		 *
		 * @return array
		 */
		public static function get_payment_methods() {
			$gateways = self::get_gateways();
			$methods  = $gateways;
			if ( isset( $gateways['offline-payment'] ) ) {
				if ( LPC()->support_offline_payment() ) {
					$methods['offline'] = __( 'Offline', 'learnpress-commission' );
				} else {
					unset( $methods['offline-payment'] );
				}
			}

			return $methods;
		}

		/**
		 * Get all LP available gateways.
		 *
		 * @return array
		 */
		public static function get_gateways() {
			$available = LP_Gateways::instance()->get_availabe_gateways();
			$gateways  = array();
			foreach ( $available as $key => $class ) {
				/**
				 * @var $class LP_Gateway_Abstract
				 */
				$arr[ $key ] = $class->get_method_title();
			}

			return $gateways;
		}

		/**
		 * Get withdrawal gateway payment methods.
		 *
		 * @return array
		 */
		public static function get_withdrawal_methods() {
			$methods = array(
				'paypal' => __( 'Paypal', 'learnpress-commission' )
			);

			return $methods;
		}

		/**
		 * @param $method
		 * @param $total
		 * @param $min
		 * @param $currency
		 *
		 * @return mixed|string
		 */
		public static function get_withdrawal_form( $method, $total, $min, $currency ) {
			$class_name = 'LP_Commission_Withdrawal_Method_' . $method;
			if ( ! class_exists( $class_name ) ) {
				require LP_ADDON_COMMISSION_INC . 'admin/class-lp-commission-withdrawal-method-' . $method . '.php';
			}
			if ( ! class_exists( $class_name ) ) {
				return '';
			}
			$html = call_user_func_array( array( $class_name, 'get_withdrawal_form' ), array(
				$total,
				$min,
				$currency
			) );

			return $html;
		}

		/**
		 * @param $username
		 * @param $password
		 *
		 * @return bool
		 */
		public static function check_user_pass( $username, $password ) {
			if ( ! $username || ! $password ) {
				return false;
			}
			$cuser = wp_get_current_user();
			require_once( ABSPATH . 'wp-includes/class-phpass.php' );
			$wp_hasher = new PasswordHash( 8, true );
			if ( $wp_hasher->CheckPassword( $password, $cuser->data->user_pass ) ) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * @return LP_Request_Withdrawal|null
		 */
		public static function instance() {
			if ( ! self::$_instance ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}
	}
}

if ( ! function_exists( 'LP_RW' ) ) {
	function LP_RW() {
		return LP_Request_Withdrawal::instance();
	}
}

LP_RW();
