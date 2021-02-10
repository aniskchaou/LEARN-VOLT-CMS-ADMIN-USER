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

if ( ! class_exists( 'LP_Withdrawal' ) ) {
	/**
	 * Class LP_Withdrawal
	 */
	class LP_Withdrawal {

		/**
		 * @var int
		 */
		public $ID = 0;

		/**
		 * @var array|null|WP_Post
		 */
		public $post = null;

		/**
		 * @var int
		 */
		public $author_id;

		/**
		 * LP_Withdrawal constructor.
		 *
		 * @param $id
		 */
		public function __construct( $id ) {
			$this->ID        = $id;
			$this->post      = get_post( $id );
			$this->author_id = intval( $this->post->post_author );
		}

		/**
		 * @param $field
		 *
		 * @return array|bool|mixed|string
		 */
		private function get_meta( $field ) {
			$value = get_post_meta( $this->ID, $field, true );

			if ( ! empty( $value ) ) {
				return is_array( $value ) ? stripslashes_deep( $value ) : stripslashes( wp_kses_decode_entities( $value ) );
			} else {
				return false;
			}
		}

		/**
		 * @return string
		 */
		public function get_status() {
			$status = get_post_meta( $this->ID, 'lp_status', true );
			if ( empty( $status ) ) {
				return 'pending';
			}

			return (string) $status;
		}

		/**
		 * @return bool
		 */
		public function is_complete() {
			$status = $this->get_status();
			if ( $status !== 'complete' ) {
				return false;
			}

			return true;
		}

		/**
		 * @return bool
		 */
		public function is_reject() {
			$status = $this->get_status();
			if ( $status !== 'reject' ) {
				return false;
			}

			return true;
		}

		/**
		 * @return bool
		 */
		public function is_resolve() {
			$status = $this->get_status();
			if ( $status !== 'pending' ) {
				return true;
			}

			return false;
		}

		/**
		 * @return bool|int
		 */
		public function complete() {
			$update = update_post_meta( $this->ID, 'lp_status', 'complete' );
			if ( $update ) {
				$this->resolve();
			}

			return $update;
		}

		/**
		 * @return bool|int
		 */
		public function reject() {
			$update = update_post_meta( $this->ID, 'lp_status', 'reject' );
			if ( $update ) {
				$this->resolve();
				$value = $this->get_value();
				lp_commission_add_commission( $this->author_id, $value );
			}

			return $update;
		}

		/**
		 * @return bool|int
		 */
		public function pending() {
			$update = update_post_meta( $this->ID, 'lp_status', 'pending' );
			if ( $update ) {
				$this->resolve();
			}

			return $update;
		}

		/**
		 * @return bool|int
		 */
		public function payon() {
			#pay withdrawal
			$receiver       = $this->post->post_content;
			$value          = $this->get_value();
			$sender_item_id = 'WD' . $this->post->ID;
			$res            = LP_Commission_Withdrawal_Method_Paypal::payment_payouts( $receiver, $value, $sender_item_id );
			$res_arr        = json_decode( $res, true );
			add_post_meta( $this->ID, 'lp_payon_result', $res );
			#if ( isset( $res_arr['batch_header']['batch_status'] ) && $res_arr['batch_header']['batch_status'] == 'SUCCESS' && isset( $res_arr['items'][0]['transaction_status'] ) && $res_arr['items'][0]['transaction_status'] == 'SUCCESS' && isset( $res_arr['items'][0]['transaction_id'] ) ) {
			if ( isset( $res_arr['batch_header']['batch_status'] ) && isset( $res_arr['batch_header']['payout_batch_id'] ) ) {
				$update = update_post_meta( $this->ID, 'lp_status', 'complete' );
				add_post_meta( $this->ID, 'payout_batch_id', $res_arr['batch_header']['payout_batch_id'] );
				if ( $update ) {
					$this->resolve();
				}

				return $update;
			}

			return false;
		}

		/**
		 * Resolve.
		 */
		public function resolve() {
			$time = time();
			update_post_meta( $this->ID, 'lp_time_resolve', $time );
		}

		/**
		 * @return array
		 */
		public static function get_all_status() {
			$status = array(
				'pending'  => __( 'Pending', 'learnpress-commission' ),
				'complete' => __( 'Complete', 'learnpress-commission' ),
				'reject'   => __( 'Reject', 'learnpress-commission' ),
			);

			return $status;
		}

		/**
		 * @return mixed|string
		 */
		public function get_title_status() {
			$status = self::get_all_status();
			$key    = $this->get_status();

			if ( ! array_key_exists( $key, $status ) ) {
				return 'Unknown';
			}

			return $status[ $key ];
		}

		/**
		 * @param $timestamp
		 *
		 * @return DateTime
		 */
		public static function convert_timestamp_to_datetime( $timestamp ) {
			$offset    = get_option( 'gmt_offset' );
			$timestamp += $offset * HOUR_IN_SECONDS;
			$time      = new DateTime();
			$time->setTimestamp( $timestamp );

			return $time;
		}

		/**
		 * @return DateTime
		 */
		public function get_time_request() {
			$timestamp = $this->get_meta( 'lp_time_request' );
			$timestamp = floatval( $timestamp );
			$time      = self::convert_timestamp_to_datetime( $timestamp );

			return $time;
		}

		/**
		 * @return DateTime|null
		 */
		public function get_time_resolve() {
			$timestamp = $this->get_meta( 'lp_time_resolve' );
			if ( empty( $timestamp ) ) {
				return null;
			}

			$timestamp = floatval( $timestamp );
			$time      = self::convert_timestamp_to_datetime( $timestamp );

			return $time;
		}

		/**
		 * @return array|bool|mixed|string
		 */
		public function get_method_payment() {
			$method = $this->get_meta( 'lp_payment_method' );

			return $method;
		}

		/**
		 * @return int|null|string
		 */
		public function get_key_method_payment() {
			$method = $this->get_method_payment();
			reset( $method );
			$method_key = key( $method );

			return $method_key;
		}

		/**
		 * @return bool|mixed
		 */
		public function get_title_method_payment() {
			$method = $this->get_method_payment();
			if ( ! $method ) {
				return false;
			}
			$method_title = reset( $method );

			return $method_title;
		}

		/**
		 * @return array|bool|float|mixed|string
		 */
		public function get_value() {
			$value = $this->get_meta( 'lp_value' );
			$value = floatval( $value );

			return $value;
		}

		/**
		 * @return array|bool|mixed|string
		 */
		public function get_receiver() {
			$value = $this->get_meta( 'lp_withdrawals_email' );

			return $value;
		}
	}
}