<?php
/**
 * Plugin load class.
 *
 * @author   ThimPress
 * @package  LearnPress/Commission/Classes
 * @version  3.0.0
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LP_Addon_Commission' ) ) {
	/**
	 * Class LP_Addon_Commission
	 */
	class LP_Addon_Commission extends LP_Addon {

		/**
		 * @var string
		 */
		public $version = LP_ADDON_COMMISSION_VER;

		/**
		 * @var string
		 */
		public $require_version = LP_ADDON_COMMISSION_REQUIRE_VER;

		/**
		 * LP_Addon_Commission constructor.
		 */
		public function __construct() {
			parent::__construct();
		}

		/**
		 * Define Learnpress Co-Instructor constants.
		 *
		 * @since 3.0.0
		 */
		protected function _define_constants() {
			define( 'LP_ADDON_COMMISSION_PATH', dirname( LP_ADDON_COMMISSION_FILE ) );
			define( 'LP_ADDON_COMMISSION_INC', LP_ADDON_COMMISSION_PATH . '/inc/' );
			define( 'LP_ADDON_COMMISSION_TEMPLATE', LP_ADDON_COMMISSION_PATH . '/templates/' );
			define( 'LP_ADDON_COMMISSION_URI', plugins_url( '/', LP_ADDON_COMMISSION_FILE ) );
			define( 'LP_WITHDRAW_CPT', 'lp_withdraw' );
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 *
		 * @since 3.0.0
		 */
		protected function _includes() {
			include_once LP_ADDON_COMMISSION_INC . 'functions.php';
			include_once LP_ADDON_COMMISSION_INC . 'class-lp-commission.php';
			include_once LP_ADDON_COMMISSION_INC . 'class-lp-withdrawal.php';
			include_once LP_ADDON_COMMISSION_INC . 'class-lp-withdraw-post-type.php';
			include_once LP_ADDON_COMMISSION_INC . 'class-lp-request-withdrawal.php';
			include_once LP_ADDON_COMMISSION_INC . 'admin/class-commission-list-table.php';
			include_once LP_ADDON_COMMISSION_INC . 'admin/class-lp-commission-withdrawal-method-paypal.php';
		}

		/**
		 * Hook into actions and filters.
		 */
		protected function _init_hooks() {

			// admin settings page
			add_filter( 'learn-press/admin/settings-tabs-array', array( $this, 'admin_settings' ) );

			// add_action( 'learn_press_order_status_completed', array( $this, 'calculate_commission' ), 10, 1 );
			add_action( 'learn-press/order/status-completed', array( $this, 'calculate_commission' ), 10, 1 );

			// update setting
			add_action( 'learn-press/update-settings/updated', array( $this, 'update_settings' ) );

			// add fields in admin user profile page
			add_action( 'show_user_profile', array( $this, 'admin_user_profile' ) );
			add_action( 'edit_user_profile', array( $this, 'admin_user_profile' ) );
		}

		/**
		 * Register new settings tab in LP admin settings.
		 *
		 * @param array $tabs
		 *
		 * @return mixed
		 */
		public function admin_settings( $tabs ) {
			$tabs['commission'] = include_once LP_ADDON_COMMISSION_INC . 'class-lp-commission-settings.php';

			return $tabs;
		}

		/**
		 * Calculate commission.
		 *
		 * @param $order_id
		 */
		public function calculate_commission( $order_id ) {
			$order = learn_press_get_order( $order_id );
			$items = $order->get_items();
			if ( count( $items ) ) {
				foreach ( $items as $index => $item ) {
					if ( ! isset( $item['course_id'] ) ) {
						continue;
					}

					$course_id          = $item['course_id'];
					$quantity           = intval( $item['quantity'] );
					$total_one_course   = floatval( $item['total'] );
					$total              = $quantity * $total_one_course;
					$percent_commission = LPC()->get_commission_main_instructor( $course_id );
					$commission_value   = $total * $percent_commission / 100;

					$instructor        = lp_commission_get_main_instructor_by_course_id( $course_id );
					$commission_status = learn_press_get_order_item_meta( $index, 'lp_commission_status' );
					if ( ! empty( $instructor ) && ( ! $commission_status || $commission_status !== 'processed' ) ) {
						/**
						 * @var $instructor LP_User
						 */
						lp_commission_add_commission( $instructor->get_id(), $commission_value, $order_id, $item );
					}
				}
			}
		}

		/**
		 * Update settings for manage tab.
		 */
		public function update_settings() {
			$section = isset( $_GET['section'] ) ? $_GET['section'] : false;
			if ( $section === 'manage' ) {
				$this->_update_manage_commission();
			}
		}

		/**
		 * Add fields in admin user profile page.
		 *
		 * @param $user
		 */
		public function admin_user_profile( $user ) {
			$user_data = $user->data;
			$user_id   = $user_data->ID;
			?>
            <h3><?php esc_html_e( 'Course Commission', 'learnpress-commission' ); ?></h3>
            <table class="form-table lp_commission">
                <tbody>
                <tr>
                    <th><?php _e( 'Your Commission', 'learnpress-commission' ); ?></th>
                    <td>
						<span
                                class="count"><?php echo esc_html( lp_commission_get_total_commission( $user_id ) ); ?></span>
                        <span class="unit"><?php echo learn_press_get_currency_symbol(); ?></span>
                    </td>
                </tr>
                </tbody>
            </table>
			<?php
		}

		/**
		 * Update commission value of commission.
		 */
		private function _update_manage_commission() {
			$post_data = $this->_serialize_post_data();

			foreach ( $post_data as $key => $value ) {
				// Update value commission
				$key_main_instructor = LPC()->key_main_instructor;
				if ( $key === $key_main_instructor ) {
					$array_main_commission = (array) $value;
					foreach ( $array_main_commission as $course_id => $v ) {
						update_post_meta( $course_id, $key_main_instructor, $v );
					}

					continue;
				}

				//Update active
				$key_active = LPC()->key_active;
				if ( $key === $key_active ) {
					$array_active = (array) $value;

					foreach ( $array_active as $course_id => $v ) {
						if ( $v === 'yes' ) {
							update_post_meta( $course_id, $key_active, 1 );
						} else {
							update_post_meta( $course_id, $key_active, 0 );
						}
					}

					continue;
				}
			}
		}

		/**
		 * Serialize post data commission
		 *
		 * @return mixed
		 */
		private function _serialize_post_data() {
			$post_data = $_POST;
			foreach ( $post_data as $key => $value ) {
				$key_prefix = LPC()->prefix;
				if ( strpos( $key, $key_prefix ) === false ) {
					unset( $post_data[ $key ] );
				}
			}

			return $post_data;
		}
	}

	add_action( 'plugins_loaded', array( 'LP_Addon_Commission', 'instance' ) );
}