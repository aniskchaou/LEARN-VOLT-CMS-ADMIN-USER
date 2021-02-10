<?php
/**
 * Plugin load class.
 *
 * @author   ThimPress
 * @package  LearnPress/Students-List/Classes
 * @version  3.0.0
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LP_Addon_Students_List' ) ) {
	/**
	 * Class LP_Addon_Students_List
	 */
	class LP_Addon_Students_List extends LP_Addon {

		/**
		 * @var string
		 */
		public $version = LP_ADDON_STUDENTS_LIST_VER;

		/**
		 * @var string
		 */
		public $require_version = LP_ADDON_STUDENTS_LIST_REQUIRE_VER;

		/**
		 * LP_Addon_Students_List constructor.
		 */
		public function __construct() {
			parent::__construct();
		}

		/**
		 * Define Learnpress Students List constants.
		 *
		 * @since 3.0.0
		 */
		protected function _define_constants() {
			define( 'LP_ADDON_STUDENTS_LIST_PATH', dirname( LP_ADDON_STUDENTS_LIST_FILE ) );
			define( 'LP_ADDON_STUDENTS_LIST_INC', LP_ADDON_STUDENTS_LIST_PATH . '/inc/' );
			define( 'LP_ADDON_STUDENTS_LIST_TEMPLATE', LP_ADDON_STUDENTS_LIST_PATH . '/templates/' );
		}

		/**
		 * Includes.
		 */
		protected function _includes() {
			include_once LP_ADDON_STUDENTS_LIST_PATH . '/inc/widgets.php';
			include_once LP_ADDON_STUDENTS_LIST_PATH . '/inc/shortcodes.php';
		}

		/**
		 * Init hooks.
		 */
		protected function _init_hooks() {
			add_filter( 'learn_press_course_settings_meta_box_args', array( $this, 'add_meta_box' ) );
			add_filter( 'learn-press/course-tabs', array( $this, 'add_single_course_students_list_tab' ), 15 );
		}

		/**
		 * Assets.
		 */
		protected function _enqueue_assets() {
			wp_enqueue_style( 'learnpress-students-list', $this->get_plugin_url( 'assets/css/styles.css' ) );
			wp_enqueue_script( 'learnpress-students-list', $this->get_plugin_url( 'assets/js/scripts.js' ), array( 'jquery' ) );
		}

		/**
		 * Add students list settings in course meta box.
		 *
		 * @param $meta_box
		 *
		 * @return mixed
		 */
		public function add_meta_box( $meta_box ) {

			$meta_box['fields'][] = array(
				'name' => __( 'Students List', 'learnpress-students-list' ),
				'id'   => '_lp_hide_students_list',
				'std'  => 'yes',
				'desc' => __( 'Hide the students list in each individual course.', 'learnpress-students-list' ),
				'type' => 'yes_no',
			);

			return $meta_box;
		}

		/**
		 * Students list tab in single course page.
		 *
		 * @param $tabs
		 *
		 * @return mixed
		 */
		public function add_single_course_students_list_tab( $tabs ) {
			if ( ! $course = LP_Global::course() ) {
				return $tabs;
			}
			$hide_students_list = get_post_meta( $course->get_id(), '_lp_hide_students_list', true );

			if ( $hide_students_list != 'yes' ) {
				$tabs['students-list'] = array(
					'title'    => __( 'Students List', 'learnpress-students-list' ),
					'priority' => 40,
					'callback' => array( $this, 'single_course_students_list_tab_content' )
				);
			}

			return $tabs;
		}

		/**
		 * Students list tab content in single course page.
		 */
		public function single_course_students_list_tab_content() {
			$course = LP_Global::course();
			learn_press_get_template( 'students-list.php', array( 'course' => $course ), learn_press_template_path() . '/addons/students-list/', LP_ADDON_STUDENTS_LIST_TEMPLATE );
		}
	}
}

add_action( 'plugins_loaded', array( 'LP_Addon_Students_List', 'instance' ) );
