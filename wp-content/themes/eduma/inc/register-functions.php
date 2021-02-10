<?php
/***
 * Class ThimEdumaRegisterFunction
 *
 * @since 4.2.4
 */

class ThimEdumaRegisterFunction {
	protected static $_instance;
	protected $action_ajax = array();

	protected function __construct() {
		$this->addHook();
	}

	protected function addHook() {
		// register hook ajax
		foreach ( $this->action_ajax as $action ) {
			if ( $action == 'thim_register_ajax' && ! get_option( 'users_can_register' ) ) {
				continue;
			}
			add_action( 'wp_ajax_' . $action, array( $this, $action ) );
			add_action( 'wp_ajax_nopriv_' . $action, array( $this, $action ) );
		}
		// end

		/*** Login user */
		// add_filter( 'login_url', array( $this, 'thim_remove_default_login_url' ), 1000 );

		// redirect after login success
		add_filter( 'login_redirect', array( $this, 'login_success_redirect' ), 99999, 3 );

		// redirect if login false
		add_filter( 'authenticate', array( $this, 'login_authenticate' ), 99999, 2 );
		/*** End login user */

		/*** Register user */
		// Check extra register if set auto login when register
		add_action( 'register_post', array( $this, 'check_extra_register_fields' ), 10, 3 );

		// Update password if set auto login when register
		add_action( 'user_register', array( $this, 'register_update_pass_and_login' ), 99999 );

		// redirect if register false
		add_action( 'registration_errors', array( $this, 'register_failed' ), 99999, 3 );

		// redirect if register success if not set auto login when register
		add_action( 'register_new_user', array( $this, 'register_verify_mail_success_redirect' ), 999999 );

		add_filter(
			'wp_new_user_notification_email',
			array( $this, 'message_set_password_when_not_auto_login' ),
			999999,
			2
		);
		/*** End register user */

		/*** Reset password */

		/* Fix error lost password in old version wordpress - by Hungkv */
		if ( version_compare( get_bloginfo( 'version' ), '5.4.0', '>=' ) ):
 			// Transfer to wp default lost password when woocommerce active
			if ( class_exists( 'woocommerce' ) ) {
				$errors    = new WP_Error();
				$user_data = false;
				do_action( 'lostpassword_post', $errors, $user_data );
			} else {
				add_action( 'lostpassword_post', array( $this, 'lostpassword_post_failed' ), 99999, 2 );
			}

  		else:
			add_action( 'lostpassword_post', array( $this, 'lostpassword_post_failed_oldwp' ), 99999, 1 );
		endif;

		add_filter( 'login_form_middle', array( $this, 'add_lost_password_link' ), 99999 );
		add_filter( 'login_form_rp', array( $this, 'validate_password_reset' ), 99999 );
		add_filter( 'login_form_resetpass', array( $this, 'validate_password_reset' ), 99999 );
		if ( ! function_exists( 'is_wpe' ) && ! function_exists( 'is_wpe_snapshot' ) ) {
			// add_action( 'init', array( $this, 'thim_redirect_rp_url' ) );
		}

		/*** Override url redirect after lp checkout(default) success */
		add_filter(
			'learn-press/add-to-cart-redirect',
			array( $this, 'url_redirect_after_checkout_lp_course_success' ),
			10,
			4
		);

		/*** Override message send mail with case auto-login */
		add_filter( 'password_change_email', array( $this, 'message_when_user_register_auto_login' ), 999999, 1 );
	}

	/*
	public function thim_remove_default_login_url( $url ) {
		global $wp;

		if ( ! empty( $wp->query_vars['modify_user_notification'] ) ) {
			unset( $wp->query_vars['modify_user_notification'] );

			return '';
		}

		return $url;
	}*/

	/**
	 * Check login has errors
	 *
	 * @param null|WP_User|WP_Error $user
	 * @param string $username
	 *
	 * @return mixed
	 */
	public function login_authenticate( $user, $username ) {
		if ( ! $username || wp_doing_ajax() || ! isset( $_POST['eduma_login_user'] ) ) {
			return $user;
		}

		if ( $user instanceof WP_Error && $error_code = $user->get_error_code() ) {
			$error_msg = '';

			if ( $error_code == 'incorrect_password' ) {
				$error_msg = __( 'The password is incorrect', 'eduma' );
			} else {
				$error_msg = str_replace( array( '<strong>', '</strong>' ), '', $user->errors[ $error_code ][0] );
			}

			$url = add_query_arg(
				array(
					'result'         => 'failed',
					'thim_login_msg' => urlencode( $error_msg ),
				),
				thim_get_login_page_url()
			);
			wp_safe_redirect( $url );
			die;
		}

		return $user;
	}

	/**
	 * If login success change redirect url
	 *
	 * @priority
	 * 1. Redirect to checkout if click buy course before
	 * 2. Redirect to option login after success (on Customize \ General \ Utilities)
	 * 3. Redirect to $_REQUEST['redirect_to']
	 * 4. Redirect to current url
	 *
	 * @param string $redirect_to
	 * @param string $requested_redirect_to
	 * @param WP_User|WP_Error $user
	 *
	 * @return mixed|string|void
	 */
	public function login_success_redirect( $redirect_to, $requested_redirect_to, $user ) {
		if ( ! isset( $_POST['eduma_login_user'] ) || $user instanceof WP_Error ) {
			return $redirect_to;
		}

		/*** purchase course */
		$this->buyCourse();

		/*** get option redirect after login success */
		$login_redirect_option = get_theme_mod( 'thim_login_redirect', false );

		if ( ! empty( $login_redirect_option ) ) {
			$redirect_to = $login_redirect_option;
		} elseif ( ! empty( $_REQUEST['redirect_to'] ) ) {
			$redirect_to = $_REQUEST['redirect_to'];
		} else {
			$redirect_to = thim_eduma_get_current_url();
		}

		return $redirect_to;
	}

	/**
	 * @param string $user_login
	 * @param string $email
	 * @param WP_Error $errors
	 */
	public function check_extra_register_fields( $user_login, $email, $errors ) {
		if ( wp_doing_ajax() || ! isset( $_POST['eduma_register_user'] )
		     || ! isset( $_POST['password'] )
		     || ! get_theme_mod( 'thim_auto_login', true ) ) {
			return;
		}

		if ( ! isset( $_POST['repeat_password'] )
		     || $_POST['password'] !== $_POST['repeat_password'] ) {
			$errors->add( 'passwords_not_matched', __( 'Passwords must match', 'eduma' ) );
		}
	}

	/**
	 * Update password
	 *
	 * @priority
	 * 1. Redirect to checkout if click buy course before
	 * 2. Redirect to option Register after success (on Customize \ General \ Utilities)
	 * 3. Redirect to $_REQUEST['redirect_to']
	 * 4. Redirect to current url
	 *
	 * @param int $user_id
	 *
	 * @return bool|void
	 */
	public function register_update_pass_and_login( $user_id ) {
		if ( wp_doing_ajax() || ! isset( $_POST['eduma_register_user'] )
		     || ! isset( $_POST['password'] )
		     || ! get_theme_mod( 'thim_auto_login', true ) ) {
			return;
		}

		$pw = sanitize_text_field( $_POST['password'] );

		$user_data              = array();
		$user_data['ID']        = $user_id;
		$user_data['user_pass'] = $pw;

		$user_data = apply_filters( 'lp_user_data_register', $user_data, $user_id );

		$new_user_id = wp_update_user( $user_data );

		if ( $new_user_id instanceof WP_Error ) {
			return;
		}

		// Fix error become a teacher not working in register form
		if ( function_exists( 'LP' ) ) {
			$newuserdata = get_userdata( $new_user_id );
			//Check become an teacher option in register form
			if ( LP()->settings->get( 'instructor_registration' ) == 'yes' && isset( $_POST['become_teacher'] ) ) {
				update_user_meta( $new_user_id, '_requested_become_teacher', 'yes' );
				// Send email require become to teacher of user to Admin mail.
				do_action(
					'learn-press/become-a-teacher-sent',
					array(
						'bat_email'   => $newuserdata->user_email,
						'bat_message' => apply_filters( 'learnpress_become_instructor_message', esc_html__( 'I need become a instructor', 'learnpress' ) ),
					)
				);
				learn_press_add_message( __( 'Your request become a instructor has been sent. We will get back to you soon!', 'learnpress' ), 'success' );
			}
		}

		// Login after registered
		if ( ! is_admin() ) {
			wp_set_current_user( $user_id );
			wp_set_auth_cookie( $user_id );
			wp_new_user_notification(
				$user_id,
				null,
				'admin'
			); // new user registration notification only send to admin

			if ( isset( $_POST['level'] ) && $_POST['level'] &&
			     isset( $_POST['token'] ) && $_POST['token'] &&
			     isset( $_POST['gateway'] ) && $_POST['gateway'] ) {
				return;
			} elseif ( isset( $_REQUEST['level'] ) && $_REQUEST['level'] &&
			           isset( $_REQUEST['review'] ) && $_REQUEST['review'] &&
			           isset( $_REQUEST['token'] ) && $_REQUEST['token'] &&
			           isset( $_REQUEST['PayerID'] ) && $_REQUEST['PayerID'] ) {
				return;
			} elseif ( ( isset( $_POST['billing_email'] ) && ! empty( $_POST['billing_email'] ) ) ||
			           ( isset( $_POST['bconfirmemail'] ) && ! empty( $_POST['bconfirmemail'] ) ) ) {
				return;
			} else {
				$redirect_to = '';

				/*** purchase course */
				$this->buyCourse();

				$register_redirect_option = get_theme_mod( 'thim_register_redirect', false );

				if ( ! empty( $redirect_to_checkout ) ) {
					$redirect_to = $redirect_to_checkout;
				} elseif ( ! empty( $register_redirect_option ) ) {
					$redirect_to = $register_redirect_option;
				} elseif ( ! empty( $_REQUEST['redirect_to'] ) ) {
					$redirect_to = $_REQUEST['redirect_to'];
				} else {
					$redirect_to = thim_eduma_get_current_url();
				}

				wp_safe_redirect( $redirect_to );
				die;
			}
		}
	}


	/**
	 * @param WP_Error $errors
	 *
	 * @return mixed
	 */
	public function register_failed( $errors ) {
		if ( wp_doing_ajax() || ! isset( $_POST['eduma_register_user'] ) ) {
			return $errors;
		}

		if ( $error_code = $errors->get_error_code() ) {
			$error_msg = '';

			$error_msg = str_replace( array( '<strong>', '</strong>' ), '', $errors->errors[ $error_code ][0] );

			$url = add_query_arg(
				array(
					'action'            => 'register',
					'thim_register_msg' => $error_msg,
				),
				thim_get_login_page_url()
			);

			wp_redirect( $url );
			die;
		}

		return $errors;
	}

	/**
	 * Register via email - send email success
	 *
	 * @param int $user_id
	 *
	 * @return void
	 */
	public function register_verify_mail_success_redirect( $user_id ) {
		if ( get_theme_mod( 'thim_auto_login', true )
		     || wp_doing_ajax() || ! isset( $_POST['eduma_register_user'] ) ) {
			return;
		}

		$redirect_url = thim_get_login_page_url();

		if ( ! empty( $redirect_url ) ) {
			$redirect_url = add_query_arg( array( 'result' => 'registered' ), $redirect_url );
			wp_safe_redirect( $redirect_url );
			die;
		}
	}

	/**
	 * @param array $objectEmail
	 * @param WP_User $user
	 *
	 * @return array
	 */
	public function message_set_password_when_not_auto_login( $objectEmail = array(), $user = '' ) {

		$key = get_password_reset_key( $user );

		$set_password_link = thim_get_login_page_url() . "?action=rp&key=$key&login=" . rawurlencode( $user->user_login );

		$message = sprintf( __( 'Username: ', 'eduma' ) . '%s', $user->user_login ) . "\r\n\r\n";
		$message .= __( 'To set your password, visit the following address:', 'eduma' ) . "\r\n\r\n";
		$message .= $set_password_link . "\r\n\r\n";

		$message .= wp_login_url() . "\r\n";

		$objectEmail['message'] = $message;

		return $objectEmail;
	}

	/*** Send email to reset pass ***/
	public function lostpassword_post_failed( $errors, $user_data ) {
		if ( wp_doing_ajax() || ! isset( $_POST['eduma_lostpass'] ) ) {
			return;
		}
		if ( ! $user_data ) {
			$errors->add(
				'invalidcombo',
				__( '<strong>Error</strong>: There is no account with that username or email address.', 'eduma' )
			);
		}
		if ( $errors instanceof WP_Error && $errors->has_errors() && $error_code = $errors->get_error_code() ) {
			$error_msg = '';

			$error_msg = str_replace( array( '<strong>', '</strong>' ), '', $errors->errors[ $error_code ][0] );

			$url = add_query_arg(
				array(
					'action'            => 'lostpassword',
					'thim_lostpass_msg' => urlencode( $error_msg ),
				),
				thim_get_login_page_url()
			);
			wp_safe_redirect( $url );
			die;
		}

		return;
	}

	//function has 1 parameter for older wordpress version
	public function lostpassword_post_failed_oldwp( $errors ) {
		if ( wp_doing_ajax() || ! isset( $_POST['eduma_lostpass'] ) ) {
			return;
		}

		if ( $errors instanceof WP_Error && $errors->has_errors() && $error_code = $errors->get_error_code() ) {
			$error_msg = '';

			$error_msg = str_replace( array( '<strong>', '</strong>' ), '', $errors->errors[ $error_code ][0] );

			$url = add_query_arg(
				array(
					'action'            => 'lostpassword',
					'thim_lostpass_msg' => urlencode( $error_msg ),
				),
				thim_get_login_page_url()
			);
			wp_safe_redirect( $url );
			die;
		}

		return;
	}


	public function add_lost_password_link( $content ) {
		$content .= '<a class="lost-pass-link" href="' . thim_get_lost_password_url() . '" title="' . esc_attr__(
				'Lost Password',
				'eduma'
			) . '">' . esc_html__( 'Lost your password?', 'eduma' ) . '</a>';

		return $content;
	}

	public function validate_password_reset() {
		if ( wp_doing_ajax() ) {
			return;
		}

		$login_page = thim_get_login_page_url();
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {

			if ( ! isset( $_REQUEST['key'] ) || ! isset( $_REQUEST['login'] ) ) {
				return;
			}

			$error_msg = '';
			$key       = $_REQUEST['key'];
			$login     = $_REQUEST['login'];

			$user = check_password_reset_key( $key, $login );

			if ( ! $user || is_wp_error( $user ) ) {
				$error_msg = 'invalid key';

				if ( $user && $error_code = $user->get_error_code() ) {
					$error_msg = $user->errors[ $error_code ][0];

				}

				wp_redirect(
					add_query_arg(
						array(
							'action'             => 'rp',
							'thim_resetpass_msg' => $error_msg,
						),
						$login_page
					)
				);

				die;
			}

			if ( isset( $_POST['password'] ) ) {

				if ( empty( $_POST['password'] ) ) {
					// Password is empty
					wp_redirect(
						add_query_arg(
							array(
								'action'           => 'rp',
								'key'              => $_REQUEST['key'],
								'login'            => $_REQUEST['login'],
								'invalid_password' => '1',
							),
							$login_page
						)
					);
					exit;
				}

				// Parameter checks OK, reset password
				reset_password( $user, $_POST['password'] );
				wp_redirect(
					add_query_arg(
						array(
							'result' => 'changed',
						),
						$login_page
					)
				);
			} else {
				_e( 'Invalid request.', 'eduma' );
			}

			exit;
		}
	}

	public function thim_redirect_rp_url() {
		if ( ! empty( $_REQUEST['action'] ) && $_REQUEST['action'] == 'rp'
		     && ! empty( $_REQUEST['key'] ) && ! empty( $_REQUEST['login'] ) ) {
			$reset_link = add_query_arg(
				array(
					'action' => 'rp',
					'key'    => $_REQUEST['key'],
					'login'  => rawurlencode( $_REQUEST['login'] ),
				),
				thim_get_login_page_url()
			);

			if ( ! thim_is_current_url( $reset_link ) ) {
				wp_redirect( $reset_link );
				exit();
			}
		}
	}
	/*** End reset pass ***/

	/**
	 * Click buy course => Login, Register success => redirect to checkout page
	 *
	 * @apply-for
	 * 1. buy course default of learnpress
	 * 2. buy course via add on learnpress-woo-payment
	 *
	 * @param string $redirect_to
	 *
	 * @return string
	 * @since   4.2.6
	 * @author  tungnx
	 */
	private function buyCourse() {
		if ( is_plugin_active( 'learnpress-woo-payment/learnpress-woo-payment.php' )
		     && LP()->settings()->get( 'woo-payment' )['enable'] == 'yes' && isset( $_POST['add-to-cart'] )
		     && isset( $_POST['purchase-course'] ) ) {

			$curl = curl_init();

			// Set product id for param add-to-cart - woo will detected and add to cart
			$_POST['add-to-cart'] = $_POST['purchase-course'];

			curl_setopt_array(
				$curl, [
					CURLOPT_URL            => home_url(),
					CURLOPT_HEADER         => false,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_HTTPHEADER     => array()
				]
			);

			curl_setopt( $curl, CURLOPT_HTTPGET, true );

			curl_exec( $curl );

			if ( $pageCheckoutId = get_option( 'woocommerce_checkout_page_id', false ) ) {
				$redirect_to = get_permalink( $pageCheckoutId );

				wp_safe_redirect( $redirect_to );
				die;
			}
		} elseif ( ! empty( $_POST['purchase-course'] ) ) {
			$course_id = sanitize_text_field( $_POST['purchase-course'] );

			// when success will be redirect not return url so need change url redirect in url_redirect_after_checkout_lp_course_success()
			do_action( 'learn_press_request_handler_purchase-course', $course_id, 'purchase-course' );
		}
	}

	/**
	 * @param $url_redirect
	 * @param $course_id
	 * @param $cart_id
	 * @param $action
	 *
	 * @return mixed
	 * @since  4.2.6
	 * @author tungnx
	 */
	public function url_redirect_after_checkout_lp_course_success( $url_redirect, $course_id, $cart_id, $action ) {

		return $url_redirect;
	}

	/**
	 * Content mail when user register success with auto login
	 *
	 * @param array $objectEmail
	 *
	 * @return array
	 */
	public function message_when_user_register_auto_login( $objectEmail = array() ) {
		if ( get_theme_mod( 'thim_auto_login', true ) ) {
			$objectEmail['subject'] = 'Welcome to [%s]';
			$objectEmail['message'] = sprintf(
				'Hi ###USERNAME###,

You registered successfully on %s site

This email has been sent to ###EMAIL###

Regards,
All at ###SITENAME###
###SITEURL###',
				get_bloginfo()
			);

			return $objectEmail;
		}

	}

	public static function getInstance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
}

ThimEdumaRegisterFunction::getInstance();

/**
 * Get login page url
 *
 * @return false|string
 */
if ( ! function_exists( 'thim_get_login_page_url' ) ) {
	function thim_get_login_page_url() {
		if ( $page = get_option( 'thim_login_page' ) ) {
			return get_permalink( $page );
		} else {
			global $wpdb;
			$page = $wpdb->get_col(
				$wpdb->prepare(
					"SELECT p.ID FROM $wpdb->posts AS p INNER JOIN $wpdb->postmeta AS pm ON p.ID = pm.post_id
						WHERE 	pm.meta_key = %s
						AND 	pm.meta_value = %s
						AND		p.post_type = %s
						AND		p.post_status = %s",
					'thim_login_page',
					'1',
					'page',
					'publish'
				)
			);
			if ( ! empty( $page[0] ) ) {
				return get_permalink( $page[0] );
			}
		}

		return wp_login_url();
	}
}

/**
 * Filter register link
 *
 * @param $register_url
 *
 * @return string|void
 */
if ( ! function_exists( 'thim_get_register_url' ) ) {
	function thim_get_register_url() {
		$url = add_query_arg( 'action', 'register', thim_get_login_page_url() );

		return $url;
	}
}
if ( ! is_multisite() ) {
	add_filter( 'register_url', 'thim_get_register_url' );
}

/**
 * Redirect to custom register page in case multi sites
 *
 * @param $url
 *
 * @return mixed
 */
if ( ! function_exists( 'thim_multisite_register_redirect' ) ) {
	function thim_multisite_register_redirect( $url ) {

		if ( ! is_user_logged_in() ) {
			if ( is_multisite() ) {
				$url = add_query_arg( 'action', 'register', thim_get_login_page_url() );
			}

			$user_login = isset( $_POST['user_login'] ) ? $_POST['user_login'] : '';
			$user_email = isset( $_POST['user_email'] ) ? $_POST['user_email'] : '';

			$errors = register_new_user( $user_login, $user_email );

			if ( ! is_wp_error( $errors ) ) {
				$redirect_to = ! empty( $_POST['redirect_to'] ) ? $_POST['redirect_to'] : 'wp-login.php?checkemail=registered';
				wp_safe_redirect( $redirect_to );
				exit();
			}
		}

		return $url;
	}
}
add_filter( 'wp_signup_location', 'thim_multisite_register_redirect' );

/**
 * Filter lost password link
 *
 * @param $url
 *
 * @return string
 */
if ( ! function_exists( 'thim_get_lost_password_url' ) ) {
	function thim_get_lost_password_url() {
		$url = add_query_arg( 'action', 'lostpassword', thim_get_login_page_url() );

		return $url;
	}
}

/*
 * Add google captcha register check to register form ( multisite case )
 */
if ( is_multisite() && function_exists( 'gglcptch_register_check' ) ) {
	global $gglcptch_ip_in_whitelist;

	if ( ! $gglcptch_ip_in_whitelist ) {
		add_action( 'registration_errors', 'gglcptch_register_check', 10, 1 );
	}
}
