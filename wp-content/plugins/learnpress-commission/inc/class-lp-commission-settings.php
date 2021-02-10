<?php

/**
 * Class LP_Commission_Settings.
 *
 * @author  ThimPress
 * @package LearnPress/Commission/Classes
 * @version 3.0.0
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LP_Commission_Settings' ) ) {
	/**
	 * Class LP_Commission_Settings.
	 */
	class LP_Commission_Settings extends LP_Abstract_Settings_Page {
		/**
		 * Constructor
		 */
		public function __construct() {
			$this->id   = 'commission';
			$this->text = __( 'Commission', 'learnpress-commission' );

			parent::__construct();
		}

		/**
		 * Tab's sections.
		 *
		 * @return mixed
		 */
		public function get_sections() {
			$sections = array(
				'general'           => __( 'Settings', 'learnpress-commission' ),
				'manage'            => __( 'Managing', 'learnpress-commission' ),
				'withdrawal_paypal' => __( 'Withdrawal Paypal', 'learnpress-commission' )
			);

			return apply_filters( 'learn_press_settings_sections_' . $this->id, $sections );
		}

		/**
		 * General settings.
		 *
		 * @return mixed
		 */
		public function get_settings_general() {
			return apply_filters(
				'learn_press_commission_settings', array(
					array(
						'title'   => __( 'Enable', 'learnpress-commission' ),
						'desc'    => __( 'Enable commission feature.', 'learnpress-commission' ),
						'id'      => 'enable_commission',
						'default' => 'no',
						'type'    => 'yes-no'
					),
					array(
						'title'             => __( 'Commission percent', 'learnpress-commission' ),
						'desc'              => __( 'Commission percent.', 'learnpress-commission' ),
						'id'                => 'commission_percent',
						'default'           => 0,
						'type'              => 'number',
						'min' => 0,
						'max' => 100,
						'visibility'        => array(
							'state'       => 'show',
							'conditional' => array(
								array(
									'field'   => 'enable_commission',
									'compare' => '=',
									'value'   => 'yes'
								)
							)
						)
					),
					array(
						'title'             => sprintf( __( 'Min (%s)', 'learnpress-commission' ), learn_press_get_currency_symbol() ),
						'desc'              => __( 'Minimum amount allow customer withdrawals', 'learnpress-commission' ),
						'id'                => 'commission_min',
						'default'           => 1,
						'type'              => 'number',
						'min' => 0,
						'visibility'        => array(
							'state'       => 'show',
							'conditional' => array(
								array(
									'field'   => 'enable_commission',
									'compare' => '=',
									'value'   => 'yes'
								)
							)
						)
					),
					array(
						'title'      => __( 'Support offline payment', 'learnpress-commission' ),
						'desc'       => __( 'Enable/Disable offline payment.', 'learnpress-commission' ),
						'id'         => 'commission_offline_payment',
						'default'    => 'yes',
						'type'       => 'hidden',//remove this option
						'visibility' => array(
							'state'       => 'show',
							'conditional' => array(
								array(
									'field'   => 'enable_commission',
									'compare' => '=',
									'value'   => 'yes'
								)
							)
						)
					),
				)
			);
		}

		/**
		 * Manage settings.
		 */
		public function get_settings_manage() {
			wp_enqueue_script( 'datatables', '//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js', array( 'jquery' ), false, true );
			wp_enqueue_style( 'datatables', '//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css' );

			$lp_commission_table = new LP_Commission_List_Table();
			$lp_commission_table->display();
		}

		/**
		 * Withdrawal paypal settings.
		 *
		 * @return mixed
		 */
		public function get_settings_withdrawal_paypal() {
			return apply_filters(
				'learn_press_commission_setting_withdrawal_paypal', array(
					array(
						'title'   => __( 'Enable', 'learnpress-commission' ),
						'desc'    => __( 'Enable/Disable withdrawal via Paypal.', 'learnpress-commission' ),
						'id'      => 'commission_enable_paypal_withdrawal_method',
						'default' => 'no',
						'type'    => 'yes-no'
					),
					array(
						'title'      => __( 'Sandbox Mode', 'learnpress-commission' ),
						'desc'       => __( 'Enable/Disable Sandbox mode.', 'learnpress-commission' ),
						'id'         => 'commission_enable_paypal_sandbox_mode',
						'default'    => 'no',
						'type'       => 'yes-no',
						'visibility' => array(
							'state'       => 'show',
							'conditional' => array(
								array(
									'field'   => 'commission_enable_paypal_withdrawal_method',
									'compare' => '=',
									'value'   => 'yes'
								)
							)
						)
					),
					array(
						'title'      => __( 'Client ID', 'learnpress-commission' ),
						'desc'       => __( 'Client ID is generated in PayPal\'s REST API apps. You can create REST API apps at https://developer.paypal.com/developer/applications/', 'learnpress-commission' ),
						'id'         => 'commission_paypal_app_client_id',
						'default'    => '',
						'type'       => 'text',
						'visibility' => array(
							'state'       => 'show',
							'conditional' => array(
								array(
									'field'   => 'commission_enable_paypal_withdrawal_method',
									'compare' => '=',
									'value'   => 'yes'
								)
							)
						)
					),
					array(
						'title'      => __( 'Secret', 'learnpress-commission' ),
						'desc'       => __( 'Secret Key is generated in PayPal\'s REST API apps. You can create REST API apps at https://developer.paypal.com/developer/applications/', 'learnpress-commission' ),
						'id'         => 'commission_paypal_app_secret',
						'default'    => '',
						'type'       => 'text',
						'visibility' => array(
							'state'       => 'show',
							'conditional' => array(
								array(
									'field'   => 'commission_enable_paypal_withdrawal_method',
									'compare' => '=',
									'value'   => 'yes'
								)
							)
						)
					),
				)
			);
		}
	}
}

return new LP_Commission_Settings();