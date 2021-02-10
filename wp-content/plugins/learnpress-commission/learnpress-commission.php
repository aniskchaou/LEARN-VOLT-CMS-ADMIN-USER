<?php
/*
Plugin Name: LearnPress - Instructor Commission
Plugin URI: http://thimpress.com/learnpress
Description: Commission add-on for LearnPress.
Author: ThimPress
Version: 3.0.5
Author URI: http://thimpress.com
Tags: learnpress
Text Domain: learnpress-commission
Domain Path: /languages/
*/

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

define( 'LP_ADDON_COMMISSION_FILE', __FILE__ );
define( 'LP_ADDON_COMMISSION_VER', '3.0.5' );
define( 'LP_ADDON_COMMISSION_REQUIRE_VER', '3.0.0' );
define( 'LP_ADDON_COMMISSION_VERSION', '3.0.4' );

/**
 * Class LP_Addon_Commission_Preload
 */
class LP_Addon_Commission_Preload {

	/**
	 * LP_Addon_Commission_Preload constructor.
	 */
	public function __construct() {
		add_action( 'learn-press/ready', array( $this, 'load' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Load add-on.
	 */
	public function load() {
		LP_Addon::load( 'LP_Addon_Commission', 'inc/load.php', __FILE__ );
		remove_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Admin notice.
	 */
	public function admin_notices() {
		?>
	<div class="error">
		<p><?php echo wp_kses(
					sprintf(
						__( '<strong>%s</strong> addon version %s requires %s version %s or higher is <strong>installed</strong> and <strong>activated</strong>.', 'learnpress-commission' ),
						__( 'LearnPress Instructor Commission', 'learnpress-commission' ),
						LP_ADDON_COMMISSION_VER,
						sprintf( '<a href="%s" target="_blank"><strong>%s</strong></a>', admin_url( 'plugin-install.php?tab=search&type=term&s=learnpress' ), __( 'LearnPress', 'learnpresscoming-commission' ) ),
						LP_ADDON_COMMISSION_REQUIRE_VER
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

new LP_Addon_Commission_Preload();
