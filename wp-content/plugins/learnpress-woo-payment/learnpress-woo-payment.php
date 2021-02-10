<?php
/**
 * Plugin Name: LearnPress - WooCommerce Payment Methods Integration
 * Plugin URI: https://thimpress.com/product/woocommerce-add-on-for-learnpress/
 * Description: Using the payment system provided by WooCommerce.
 * Author: ThimPress
 * Version: 3.2.7
 * Author URI: http://thimpress.com
 * Tags: learnpress, woocommerce
 * Text Domain: learnpress-woo-payment
 * Domain Path: /languages/
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
define( 'LP_ADDON_WOO_PAYMENT_FILE', __FILE__ );
define( 'LP_ADDON_WOO_PAYMENT_PATH', dirname( __FILE__ ) );
define( 'LP_WOO_TEMPLATE_DEFAULT', LP_ADDON_WOO_PAYMENT_PATH . '/templates/' );
define( 'LP_ADDON_WOO_PAYMENT_VER', '3.2.6' );
define( 'LP_ADDON_WOO_PAYMENT_REQUIRE_VER', '3.2.8.2' );
define( 'LP_ADDON_WOOCOMMERCE_PAYMENT_VER', '4.8.0' );

class LP_Addon_Woo_Payment_Preload {

	/**
	 * LP_Addon_Wishlist_Preload constructor.
	 */
	public function __construct() {
		add_action( 'learn-press/ready', array( $this, 'load' ) );

		// Check plugin require
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		// Check Learnpress
		$this->checkLearnpress();

		// Check Woo activated
		$this->checkWooActivated();
	}

	public function checkLearnpress() {
		if ( ! is_plugin_active( 'learnpress/learnpress.php' ) ) {
			add_action( 'admin_notices', array( $this, 'show_note_errors_install_plugin_learnpress' ) );

			deactivate_plugins( plugin_basename( __FILE__ ) );

			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}

			return;
		}
	}

	public function checkWooActivated() {
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			add_action( 'admin_notices', array( $this, 'show_note_errors_install_plugin_woo' ) );

			deactivate_plugins( plugin_basename( __FILE__ ) );

			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}

			return;
		}
	}

	/**
	 * Load addon
	 */
	public function load() {
		// Check version Learnpress
		if ( ! version_compare( LEARNPRESS_VERSION, '3.2.8.2', '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'show_note_errors_install_plugin_learnpress' ) );

			deactivate_plugins( plugin_basename( __FILE__ ) );

			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}

			return;
		}

		LP_Addon::load( 'LP_Addon_Woo_Payment', 'incs/load.php', __FILE__ );
	}

	public function show_note_errors_install_plugin_learnpress() {
		?>
		<div class="notice notice-error">
			<p><?php _e( 'Please active <strong>Learnpress version 3.2.8.2 or later</strong> before active <strong>LearnPress - Woo payment</strong>', 'learnpress-woo-payment' ); ?></p>
		</div>
		<?php
	}

	public function show_note_errors_install_plugin_woo() {
		?>
		<div class="notice notice-error">
			<p><?php _e( 'Please active plugin <strong>Woocomerce</strong> before active plugin <strong>LearnPress - Woo payment</strong>', 'learnpress-woo-payment' ); ?></p>
		</div>
		<?php
	}
}

new LP_Addon_Woo_Payment_Preload();

