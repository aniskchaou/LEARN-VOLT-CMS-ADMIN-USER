<?php
/**
 * Plugin Name: LearnPress - Certificates
 * Plugin URI: https://thimpress.com/product/certificates-add-on-for-learnpress/
 * Description: Create certificates for online courses.
 * Author: ThimPress
 * Version: 3.2.0
 * Author URI: http://thimpress.com
 * Tags: learnpress, lms
 * Text Domain: learnpress-certificates
 * Domain Path: /languages/
 */

defined( 'ABSPATH' ) || exit;

define( 'LP_ADDON_CERTIFICATES_FILE', __FILE__ );
define( 'LP_ADDON_CERTIFICATES_VERSION_REQUIRES', '3.0.9' );
define( 'LP_ADDON_CERTIFICATES_VER', '3.2.0' );
define( 'LP_ADDON_CERTIFICATES_DEBUG', 0 );
define( 'LP_CERTIFICATE_FOLDER_NAME',
	str_replace( array( '/', basename( __FILE__ ) ), "", plugin_basename( __FILE__ ) )
);

/**
 * Class LP_Addon_Certificates_Preload
 */
class LP_Addon_Certificates_Preload {

	/**
	 * LP_Addon_Certificates_Preload constructor.
	 */
	public function __construct() {
		add_action( 'learn-press/ready', array( $this, 'load' ) );

		// Check Learnpress version
		$this->checkLPInstalled();
	}

	/**
	 * Load addon
	 */
	public function load() {
		// Check version Learnpress require
		if ( ! version_compare( LEARNPRESS_VERSION, '3.2.8', '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'show_note_errors_install_plugin_learnpress' ) );

			deactivate_plugins( plugin_basename( __FILE__ ) );

			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}

			return;
		}

		LP_Addon::load( 'LP_Addon_Certificates', 'inc/load.php', __FILE__ );
	}

	public function checkLPInstalled() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( ! is_plugin_active( 'learnpress/learnpress.php' ) ) {
			add_action( 'admin_notices', array( $this, 'show_note_errors_install_plugin_learnpress' ) );

			deactivate_plugins( plugin_basename( __FILE__ ) );

			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}

			return;
		}
	}

	public function show_note_errors_install_plugin_learnpress() {
		?>
        <div class="notice notice-error">
            <p><?php _e( 'Please active <strong>Learnpress version 3.2.8 or later</strong> before active <strong>LearnPress - Certificate</strong>', 'learnpress-certificates' ); ?></p>
        </div>
		<?php
	}
}

new LP_Addon_Certificates_Preload();
