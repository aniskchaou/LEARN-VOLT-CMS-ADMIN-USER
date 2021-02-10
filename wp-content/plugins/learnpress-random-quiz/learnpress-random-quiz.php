<?php
/*
Plugin Name: LearnPress - Random Quiz
Plugin URI: http://thimpress.com/learnpress
Description: Randomize questions inside quiz.
Author: ThimPress
Version: 3.1.1
Author URI: http://thimpress.com
Tags: learnpress, lms
Text Domain: learnpress-random-quiz
Domain Path: /languages/
*/

define( 'LP_ADDON_RANDOM_QUIZ_FILE', __FILE__ );
define( 'LP_ADDON_RANDOM_QUIZ_VER', '3.1.1' );
define( 'LP_ADDON_RANDOM_QUIZ_REQUIRE_VER', '3.0.0' );
define( 'LP_RANDOM_QUIZ_QUESTIONS_VER', '3.1.1' );
/**
 * Class LP_Addon_Random_Quiz_Preload
 */
class LP_Addon_Random_Quiz_Preload {

	/**
	 * LP_Addon_Random_Quiz_Preload constructor.
	 */
	public function __construct() {
		add_action( 'learn-press/ready', array( $this, 'load' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Load addon
	 */
	public function load() {
		LP_Addon::load( 'LP_Addon_Random_Quiz', 'inc/load.php', __FILE__ );
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
						__( '<strong>%s</strong> addon version %s requires %s version %s or higher is <strong>installed</strong> and <strong>activated</strong>.', 'learnpress-random-quiz' ),
						__( 'LearnPress Random Quiz', 'learnpress-random-quiz' ),
						LP_ADDON_RANDOM_QUIZ_VER,
						sprintf( '<a href="%s" target="_blank"><strong>%s</strong></a>', admin_url( 'plugin-install.php?tab=search&type=term&s=learnpress' ), __( 'LearnPress', 'learnpress-random-quiz' ) ),
						LP_ADDON_RANDOM_QUIZ_REQUIRE_VER
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

new LP_Addon_Random_Quiz_Preload();