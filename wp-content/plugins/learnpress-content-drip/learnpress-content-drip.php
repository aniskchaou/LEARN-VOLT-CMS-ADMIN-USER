<?php
/*
Plugin Name: LearnPress - Content Drip
Plugin URI: http://thimpress.com/learnpress
Description: Drip content of course.
Author: ThimPress
Version: 3.1.7
Author URI: http://thimpress.com
Tags: learnpress, lms, drip, filter
Text Domain: learnpress-content-drip
Domain Path: /languages/
*/
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

define( 'LP_ADDON_CONTENT_DRIP_FILE', __FILE__ );
define( 'LP_ADDON_CONTENT_DRIP_PATH', dirname( __FILE__ ) );
define( 'LP_ADDON_CONTENT_DRIP_INC_PATH', LP_ADDON_CONTENT_DRIP_PATH . '/inc/' );
define( 'LP_ADDON_CONTENT_DRIP_VER', '3.1.7' );
define( 'LP_ADDON_CONTENT_DRIP_REQUIRE_VER', '3.0.8' );
define( 'LP_CONTENT_DRIP_DEBUG', 0 );

/**
 * Class LP_Addon_Content_Drip_Preload
 */
class LP_Addon_Content_Drip_Preload {

	/**
	 * LP_Addon_Content_Drip_Preload constructor.
	 */
	public function __construct() {
		add_action( 'learn-press/ready', array( $this, 'load' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Load addon
	 */
	public function load() {
		LP_Addon::load( 'LP_Addon_Content_Drip', 'inc/load.php', __FILE__ );
		remove_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Admin notice
	 */
	public function admin_notices() {
		?>
        <div class="error">
            <p><?php echo wp_kses(
					sprintf(
						__( '<strong>%s</strong> addon version %s requires %s version %s or higher is <strong>installed</strong> and <strong>activated</strong>.', 'learnpress-content-drip' ),
						__( 'LearnPress Content Drip', 'learnpress-content-drip' ),
						LP_ADDON_CONTENT_DRIP_VER,
						sprintf( '<a href="%s" target="_blank"><strong>%s</strong></a>', admin_url( 'plugin-install.php?tab=search&type=term&s=learnpress' ), __( 'LearnPress', 'learnpress-content-drip' ) ),
						LP_ADDON_CONTENT_DRIP_REQUIRE_VER
					),
					array(
						'a'      => array(
							'href'  => array(),
							'blank' => array()
						),
						'strong' => array()
					)
				); ?>
            </p>
        </div>
		<?php
	}
}

new LP_Addon_Content_Drip_Preload();
?>