<?php
/**
 * Class LP_Commission_Withdrawal_Method_Paypal.
 *
 * @author  ThimPress
 * @package LearnPress/Commission/Classes
 * @version 3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

if ( ! class_exists( 'LP_Commission_Withdrawal_Method_Paypal' ) ) {
	/**
	 * Class LP_Commission_Withdrawal_Method_Paypal
	 */
	class LP_Commission_Withdrawal_Method_Paypal {

		/**
		 * @param $receiver
		 * @param $value
		 * @param $sender_item_id
		 *
		 * @return mixed
		 */
		public static function payment_payouts( $receiver, $value, $sender_item_id ) {
			$email_subject = __( 'You have a payment', 'learnpress-commission' );
			$note          = __( 'Payment for withdrawal', 'learnpress-commission' ) . ' ' . $sender_item_id;
			$currency      = learn_press_get_currency();
			$data          = <<<MIT
{
	"sender_batch_header":{
		"email_subject":"{$email_subject}"
	},
	"items":[
		{
			"recipient_type":"EMAIL",
			"amount":{
				"value":{$value},
				"currency":"{$currency}"
			},
			"receiver":"{$receiver}",
			"note":"{$note}",
			"sender_item_id":"{$sender_item_id}"
		}
	]
}
MIT;

			$data              = trim( $data );
			$prefix            = self::get_paypal_url_prefix();
			$url               = $prefix . '/v1/payments/payouts?sync_mode=false';
			$access_token_json = self::get_access_token();
			$access_token_obj  = json_decode( $access_token_json );
			$access_token      = $access_token_obj->access_token;

			$httpheader = array(
				'Accept:application/json',
				'Content-Type:application/json',
				'Authorization:Bearer ' . $access_token
			);

			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_HTTPHEADER, $httpheader );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch, CURLOPT_URL, $url );

			curl_setopt( $ch, CURLOPT_TIMEOUT, 100 );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
			$res = curl_exec( $ch );

			if ( curl_errno( $ch ) ) {
				$fields_string = 'data=' . $data;
				curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields_string );
				$res = curl_exec( $ch );
			}
			curl_close( $ch );

			return $res;
		}

		/**
		 * @return string
		 */
		public static function get_paypal_url_prefix() {
			$settings    = LP()->settings;
			$sandbox_mod = $settings->get( 'commission_enable_paypal_sandbox_mode' );
			if ( $sandbox_mod == 'yes' ) {
				$url = 'https://api.sandbox.paypal.com';
			} else {
				$url = 'https://api.paypal.com';
			}

			return $url;
		}

		/**
		 * @return mixed
		 */
		public static function get_access_token() {
			$settings      = LP()->settings;
			$client_id     = $settings->get( 'commission_paypal_app_client_id' );
			$client_secret = $settings->get( 'commission_paypal_app_secret' );
			$data          = array( 'grant_type' => 'client_credentials' );
			$prefix        = self::get_paypal_url_prefix();
			$url           = $prefix . '/v1/oauth2/token';
			$httpheader    = array( 'Accept: application/json', 'Accept-Language: en_US' );

			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_HTTPHEADER, $httpheader );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_USERPWD, $client_id . ':' . $client_secret );
			curl_setopt( $ch, CURLOPT_TIMEOUT, 100 );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
			$res = curl_exec( $ch );

			if ( curl_errno( $ch ) || ! $res || ! self::check_if_res_error( $res ) ) {
				$fields_string = '';
				foreach ( $data as $key => $value ) {
					$fields_string .= $key . '=' . $value . '&';
				}
				rtrim( $fields_string, '&' );
				curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields_string );
				$res = curl_exec( $ch );
			}
			curl_close( $ch );

			return $res;
		}

		/**
		 * Get withdrawal form.
		 *
		 * @param $total
		 * @param $min
		 * @param $currency
		 *
		 * @return string
		 */
		public static function get_withdrawal_form( $total, $min, $currency ) {
			ob_start();
			learn_press_get_template( 'withdrawals-form-paypal.php', array(
				'total'    => $total,
				'min'      => $min,
				'currency' => $currency,
			), learn_press_template_path() . '/addons/commission/', LP_ADDON_COMMISSION_TEMPLATE );
			$html = ob_get_contents();
			ob_get_clean();

			return $html;
		}

		public static function check_if_res_error( $res ) {
			$res_obj = json_decode( $res );
			if ( ! is_object( $res_obj ) || isset( $res_obj->error ) && $res_obj->error != '' ) {
				return false;
			} else {
				return true;
			}
		}
	}
}