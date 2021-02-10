<?php
/**
 * Plugin load class.
 *
 * @author   ThimPress
 * @package  LearnPress/Random-Quiz/Classes
 * @version  3.0.0
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LP_Addon_Random_Quiz' ) ) {
	/**
	 * Class LP_Addon_Random_Quiz
	 */
	class LP_Addon_Random_Quiz extends LP_Addon {

		/**
		 * @var string
		 */
		public $version = LP_ADDON_RANDOM_QUIZ_VER;

		/**
		 * @var string
		 */
		public $require_version = LP_ADDON_RANDOM_QUIZ_REQUIRE_VER;

		/**
		 * LP_Addon_Random_Quiz constructor.
		 */
		public function __construct() {
			parent::__construct();
		}

		/**
		 * Define Learnpress Random Quiz constants.
		 *
		 * @since 3.0.0
		 */
		protected function _define_constants() {
			define( 'LP_RANDOM_QUIZ_PATH', dirname( LP_ADDON_RANDOM_QUIZ_FILE ) );
		}

		protected function _init_hooks() {

			add_filter( 'learn_press_quiz_general_meta_box', array( $this, 'admin_meta_box' ) );

			/* new hooks */
			add_action( 'learn-press/user/quiz-started', array( $this, 'on_quiz_started'), 10, 3 );
			add_action( 'learn-press/user/quiz-redone', array( $this, 'on_quiz_started'), 10, 3 );
			add_filter( 'learn-press/quiz/questions', array( $this, 'on_get_quiz_questions' ), 10, 2 );

		}
		
		public function on_quiz_started( $quiz_id, $course_id, $user_id ) {
			if ( get_post_meta( $quiz_id, '_lp_random_mode', true ) !== 'yes' ) {
				return;
			}
			$user = learn_press_get_user( $user_id );
			$course_data = $user->get_course_data( $course_id );
			$quiz        = learn_press_get_quiz( $quiz_id );
			$quiz_data   = $course_data->get_item( $quiz_id );
			$user_questions = $quiz_data->get_meta( 'user_questions' );
			$question_ids 	= $quiz->get_question_ids();
			$user_questions = $question_ids;
			$this->shuffle_assoc( $user_questions );
			$quiz_data->update_meta( 'user_questions', $user_questions );
			$quiz_data->update_meta( '_current_question', $user_questions[0] );
		}
		
		public function on_get_quiz_questions( $questions, $quiz_id  ) {
			global $wp;
			if ( is_admin() || array_key_exists( 'frontend-editor', $wp->query_vars ) || get_post_meta( $quiz_id, '_lp_random_mode', true ) !== 'yes' ) {
				return $questions;
			}

			$quiz = learn_press_get_quiz($quiz_id);
			$course_id = $quiz->get_course_id();
			$user = learn_press_get_current_user();
			$course_data = $user->get_course_data($course_id);
			$quiz_data = $course_data->get_item($quiz_id);
			if(!$quiz_data){
				return $questions;
			}
			$user_questions = $quiz_data->get_meta( 'user_questions' );
			$new_questions = array();
			if( !empty($user_questions) ) {
				foreach ($user_questions as $question_id ){
					$new_questions[$question_id] = $questions[$question_id];
				}
				return $new_questions;
			}
			return $questions;
		}
		
		
		public function is_frontend_editor(){
			
			if ( ( $page_id = get_option( 'learn_press_frontend_editor_page_id' ) ) && $page_id ) {
				if ( get_post( $page_id ) ) {
					$root_slug          = get_post_field( 'post_name', $page_id );
					$this->is_used_page = $page_id;
				}
			}
		}


		/**
		 * Add random quiz option in quiz meta box.
		 *
		 * @param $meta_boxes
		 *
		 * @return mixed
		 */
		public function admin_meta_box( $meta_boxes ) {
			$random_quiz = array(
				array(
					'name' => __( 'Random Questions', 'learnpress-random-quiz' ),
					'id'   => '_lp_random_mode',
					'type' => 'yes_no',
					'desc' => __( 'Mix all available questions in this quiz.', 'learnpress-random-quiz' ),
					'std'  => 'no'
				)
			);

			foreach ( $random_quiz as $field ) {
				// add prerequisites option on top of admin settings course
				array_unshift( $meta_boxes['fields'], $field );
			}

			return $meta_boxes;
		}


		function shuffle_assoc( &$list ) {
			if ( ! is_array( $list ) ) {
				return $list;
			}

			$keys = array_keys( $list );
			shuffle( $keys );
			$random = array();
			foreach ( $keys as $key ) {
				$random[] = $list[ $key ];
			}

			$list = $random;

			return $random;
		}
	}

}

add_action( 'plugins_loaded', array( 'LP_Addon_Random_Quiz', 'instance' ) );