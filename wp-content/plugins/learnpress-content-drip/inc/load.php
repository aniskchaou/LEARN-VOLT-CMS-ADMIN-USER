<?php
/**
 * Plugin load class.
 *
 * @author   ThimPress
 * @package  LearnPress/Content-Drip/Classes
 * @version  3.0.0
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'LP_Addon_Content_Drip' ) ) {
	/**
	 * Class LP_Addon_Content_Drip
	 */
	class LP_Addon_Content_Drip extends LP_Addon {
		/**
		 * Addon version
		 *
		 * @var string
		 */
		public $version = LP_ADDON_CONTENT_DRIP_VER;

		/**
		 * Require LP version
		 *
		 * @var string
		 */
		public $require_version = LP_ADDON_CONTENT_DRIP_REQUIRE_VER;

		/**
		 * Metabox data
		 *
		 * @var null
		 */
		protected $_meta_box = null;

		/**
		 * @var array
		 */
		protected $_drip_items = array();

		/**
		 * LP_Addon_Content_Drip constructor.
		 */
		public function __construct() {
			parent::__construct();
			// add course meta box tab
			add_filter( 'learn-press/admin-course-tabs', array( $this, 'add_admin_course_tab' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ), 20 );

			add_action( 'admin_menu', array( $this, 'admin_menu' ), 30 );
			add_action( 'admin_init', array( $this, 'update_items' ), 20 );

			add_action( "save_post_lp_course", array( $this, 'save_post' ) );

			// update drip item when add new items to course
			add_action( 'learn-press/after-add-items-section', array( $this, 'after_add_items_section' ), 10, 4 );

			add_action( 'init', array( $this, 'init' ) );
		}

		public function init() {
			$user = learn_press_get_current_user();
			if ( !$user->is_admin() ) {
				// restrict content
				add_action( 'learn-press/course-item-content-header', array( $this, 'maybe_restrict_content' ), 10 );
				// 				add_action( 'learn-press/course-item-content', array( $this, 'maybe_restrict_content' ), - 10 );

 				//				add_filter( 'learn-press/course-item/is-blocked', array( $this, 'filter_item_locked' ), 100, 4 );

				//				add_action( 'learn-press/user/quiz-redone', array( $this, 'retake_quiz_handle' ), 10, 3 );

				add_filter( 'learn-press/course-item-class', array( $this, 'filter_item_class' ), 10, 4 );
			}
		}

		/**
		 * Init drip item when first save post.
		 *
		 * @param $post_id
		 *
		 * @editor tungnx
		 *
		 */
		public function save_post( $post_id ) {
			$course       = LP_Course::get_course( $post_id );
			$course_items = $course->get_items();
			$drip_items   = get_post_meta( $post_id, '_lp_drip_items', true );

			$new_drip = array();

			if ( $course_items ) {
				foreach ( $course_items as $item_id ) {
					if ( ! $drip_items ) {

						$new_drip[ $item_id ] = array(
							'type'     => 'immediately',
							'interval' => array( '0', 'minute' ),
							'date'     => date( get_option( 'date_format' ) )
						);

						$index                                = array_search( $item_id, $course_items );
						$new_drip[ $item_id ]['prerequisite'] = $index ? array( $course_items[ $index - 1 ] ) : array( 0 );
					}

					/* comment by tungnx
					# When user edit course then choose 'Drip Type' 3. Open item bases on prerequisite items and save will lose data on http://localhost/thimpress/eduma/wp-admin/admin.php?page=content-drip-items

					else {
						var_dump($drip_items[$item_id]['prerequisite']);die;

						if ( !isset( $drip_items[$item_id]['prerequisite'] ) ) {
							$new_drip[$item_id] = $drip_items[$item_id];

							$index                              = array_search( $item_id, $course_items );
							$new_drip[$item_id]['prerequisite'] = $index ? array( $course_items[$index - 1] ) : array( 0 );
						}
					}*/
				}

				if ( ! $new_drip ) {
					return;
				}

				update_post_meta( $post_id, '_lp_drip_items', $new_drip );
			}
		}

		/**
		 * Update drip item when add new items to course
		 *
		 * @param $items
		 * @param $section_id
		 * @param $course_id
		 * @param $result
		 */
		public function after_add_items_section( $items, $section_id, $course_id, $result ) {

			$course_items = wp_cache_get( 'course-' . $course_id, 'lp-course-items' );
			$drip_items   = get_post_meta( $course_id, '_lp_drip_items' ) ? get_post_meta( $course_id, '_lp_drip_items', true ) : array();

			if ( $items ) {
				foreach ( $items as $item ) {
					$index = count( $course_items );

					$new_item = array(
						'prerequisite' => $index ? array( $course_items[$index - 1] ) : array( 0 ),
						'type'         => 'immediately',
						'interval'     => array( '0', 'minute' ),
						'date'         => date( get_option( 'date_format' ) )
					);

					$drip_items[$item['id']] = $new_item;

					update_post_meta( $course_id, '_lp_drip_items', $drip_items );
				}
			}
		}

		/**
		 * Filter item classes.
		 *
		 * @param $class
		 * @param $type
		 * @param $item_id
		 * @param $course_id
		 *
		 * @return array
		 */
		public function filter_item_class( $class, $type, $item_id, $course_id ) {

			$user_id = get_current_user_id();

			if ( !$user_id ) {
				return $class;
			}

			if ( $this->is_item_locked( $item_id, $course_id, $user_id ) ) {
				$remove_classes = array( 'status-started', 'status-viewed' );
				foreach ( $remove_classes as $remove_class ) {
					if ( $key = array_search( $remove_class, $class ) ) {
						unset( $class[$key] );
					}
				}
				$class[] = 'item-locked';
			}

			return $class;
		}

		public function retake_quiz_handle( $quiz_id, $course_id, $user_id ) {
			wp_cache_set( 'retake-quiz-' . $quiz_id . '-' . $user_id, true );
		}

		/**
		 * Include files.
		 */
		protected function _includes() {
			require_once LP_ADDON_CONTENT_DRIP_INC_PATH . 'admin/class-drip-items-list-table.php';
			require_once LP_ADDON_CONTENT_DRIP_INC_PATH . 'functions.php';
		}

		/**
		 * Admin assets.
		 */
		public function admin_assets() {
			if ( LP_CONTENT_DRIP_DEBUG ) {
				wp_enqueue_style( 'learn-press-content-drip', plugins_url( '/assets/css/admin.css', LP_ADDON_CONTENT_DRIP_FILE ) );
				wp_enqueue_script( 'learn-press-content-drip', plugins_url( '/assets/js/admin.js', LP_ADDON_CONTENT_DRIP_FILE ), array( 'jquery', 'select2' ) );
			} else {
				wp_enqueue_style( 'learn-press-content-drip', plugins_url( '/assets/css/admin.css', LP_ADDON_CONTENT_DRIP_FILE ) );
				wp_enqueue_script( 'learn-press-content-drip', plugins_url( '/assets/js/admin.min.js', LP_ADDON_CONTENT_DRIP_FILE ), array( 'jquery', 'select2' ) );
			}

			wp_localize_script( 'learn-press-content-drip', 'lpContentDrip', array(
					'confirm_reset_items'      => __( 'Are you sure you want to reset drip items.', 'learnpress-content-drip' ),
					'prerequisite_placeholder' => __( 'After enrolled course', 'learnpress-content-drip' )
				)
			);
		}

		/**
		 * Add Content drip tab in admin course.
		 *
		 * @param $tabs
		 *
		 * @return array
		 */
		public function add_admin_course_tab( $tabs ) {
			$content_drip = array( 'content_drip' => new RW_Meta_Box( self::course_content_drip_meta_box() ) );

			return array_merge( $tabs, $content_drip );
		}

		/**
		 * Content drip course meta box.
		 *
		 * @return mixed
		 */
		public static function course_content_drip_meta_box() {
			global $post;
			$prefix   = '_lp_content_drip_';
			$meta_box = array(
				'id'       => 'content-drip',
				'title'    => __( 'Content drip', 'learnpress-content-drip' ),
				'icon'     => 'dashicons-filter',
				'priority' => 'high',
				'pages'    => array( LP_COURSE_CPT ),
				'fields'   => array(
					array(
						'name'    => __( 'Enable', 'learnpress-content-drip' ),
						'id'      => "{$prefix}enable",
						'type'    => 'yes-no',
						'desc'    => __( 'All settings in items of this course will become locked if turn off.', 'learnpress-content-drip' ),
						'options' => array(
							'no'  => __( 'No', 'learnpress-content-drip' ),
							'yes' => __( 'Yes', 'learnpress-content-drip' ),
						),
						'std'     => 'no',
					),
					array(
						'name'       => __( 'Drip type', 'learnpress-content-drip' ),
						'id'         => "{$prefix}drip_type",
						'type'       => 'radio',
						'desc'       => sprintf( '<span>%s</span>', join(
							'</span><span>',
							array(
								__( '<strong>Drip type:</strong>', 'learnpress-content-drip' ),
								__( '1. Open course item after enrolled course specific time.', 'learnpress-content-drip' ),
								__( '2. Open lesson #2 after completed lesson #1, open lesson #3 after completed lesson #2, and so on...', 'learnpress-content-drip' ),
								__( '3. Open course item after completed prerequisite items.', 'learnpress-content-drip' ),
								sprintf( '<a href="%s" target="_blank">%s</a>', wp_nonce_url( admin_url( 'admin.php?page=content-drip-items&course-id=' . ( $post ? $post->ID : LP_Request::get_int( 'post' ) ) ), 'content-drip-items', 'drip-items-nonce' ), __( 'Settings', 'learnpress-content-drip' ) )
							)
						) ),
						'inline'     => false,
						'options'    => lp_content_drip_types(),
						'std'        => 'specific_date',
						'visibility' => array(
							'state'       => 'show',
							'conditional' => array(
								array(
									'field'   => "{$prefix}enable",
									'compare' => '==',
									'value'   => 'yes'
								)
							)
						)
					)
				)
			);

			return apply_filters( 'learn-press/content-drip/settings-meta-box-args', $meta_box );
		}

		/**
		 * Add admin menu.
		 */
		public function admin_menu() {
			if ( 'content-drip-items' === LP_Request::get( 'page' ) ) {
				check_admin_referer( 'content-drip-items', 'drip-items-nonce' );
				$course_cap = LP_COURSE_CPT . 's';
				add_submenu_page( 'learn_press', __( 'Drip Items', 'learnpress-content-drip' ), __( 'Drip Items', 'learnpress-content-drip' ), 'edit_' . $course_cap, 'content-drip-items', array( $this, 'display_items' ) );
			}
		}

		/**
		 * Admin drip items view.
		 */
		public function display_items() {
			lp_content_drip_admin_view( 'drip-items' );
		}

		/**
		 * Save delay access content drip items.
		 */
		public function update_items() {

			$course_id = LP_Request::get_int( 'course-id' );

			if ( !( 'content-drip-items' === LP_Request::get( 'page' ) ) || !( $course_id ) || 'post' !== strtolower( $_SERVER['REQUEST_METHOD'] ) ) {
				return;
			}

			check_admin_referer( 'content-drip-items', 'drip-items-nonce' );

			// drip type
			$drip_type = get_post_meta( $course_id, '_lp_content_drip_drip_type', true );

			// present drip item meta
			$drip_meta = get_post_meta( $course_id, '_lp_drip_items', true );

			if ( $drip_items = LP_Request::get_array( 'item-delay' ) ) {
				foreach ( $drip_items as $id => $item ) {

					if ( $drip_type == 'prerequisite' ) {
						$drip_items[$id]['prerequisite'] = isset( $item['prerequisite'] ) ? $item['prerequisite'] : 0;
					} else {
						$drip_items[$id]['prerequisite'] = isset( $drip_meta[$id]['prerequisite'] ) ? $drip_meta[$id]['prerequisite'] : 0;
					}

					if ( ( $item['type'] == 'interval' && !$item['interval'][0] ) || ( $item['type'] == 'specific' && !$item['date'] ) ) {
						$drip_items[$id]['type'] = 'immediately';
					}

					switch ( $item['type'] ) {
						case 'interval':
							$drip_items[$id]['interval'][2] = strtotime( '+' . learn_press_number_to_string_time( $item['interval'][0] . ' ' . $item['interval'][1] ), 0 );
							break;
						case 'specific':
							$drip_items[$id]['date'] = strtotime( $item['date'] );
							if ( empty( $drip_items[$id]['date'] ) ) {
								$drip_items[$id]['date'] = strtotime( str_replace( '/', '-', $item['date'] ) );
							}
							break;
						default:
							break;
					}
				}
			}

			update_post_meta( $course_id, '_lp_drip_items', $drip_items ); ?>
			<?php
		}

		/**
		 * Add email classes.
		 */
		public function add_content_drip_emails() {
			LP_Emails::instance()->emails['LP_Email_Drip_Item_Available'] = include( 'class-lp-email-drip-item-available.php' );
		}

		/**
		 * Check if item is locked.
		 *
		 * @param bool $blocked
		 * @param int  $id
		 * @param int  $course_id
		 * @param int  $user_id
		 *
		 * @return bool
		 */
		public function filter_item_locked( $blocked, $id, $course_id, $user_id ) {
			if ( $this->is_item_locked( $id, $course_id, $user_id ) ) {
				$blocked = true;
			}

			return $blocked;
		}

		/**
		 * @param     $item_id
		 * @param int $course_id
		 * @param int $user_id
		 *
		 * @return bool
		 */
		public function is_item_locked( $item_id, $course_id = 0, $user_id = 0 ) {
			if ( $item = $this->get_drip_item( $item_id, $course_id, $user_id ) ) {
				return $item['locked'];
			}

			return false;
		}

		/**
		 * @param     $item_id
		 * @param int $course_id
		 * @param int $user_id
		 *
		 * @return bool|mixed
		 */
		public function get_drip_item( $item_id, $course_id = 0, $user_id = 0 ) {
			if ( $items = $this->get_drip_items( $course_id, $user_id ) ) {
				return isset( $items[$item_id] ) ? $items[$item_id] : false;
			}

			return false;
		}

		/**
		 * Get all items for dripping from cache, if there is no items then calculate.
		 *
		 * @param int $course_id
		 * @param int $user_id
		 *
		 * @return array|bool|mixed
		 */
		public function get_drip_items( $course_id = 0, $user_id = 0 ) {
			if ( !$course_id ) {
				$course_id = get_the_ID();
			}

			if ( !$user_id ) {
				$user_id = get_current_user_id();
			}

			if ( false === ( $items = wp_cache_get( 'drip-item-' . $course_id, 'drip-items-' . $user_id ) ) ) {
				$items = $this->calculate_items( $course_id, $user_id );
				wp_cache_set( 'drip-item-' . $course_id, $items, 'drip-items-' . $user_id );
			}

			return $items;
		}

		/**
		 * Calculate time to open items in a course.
		 *
		 * @param int $course_id
		 * @param int $user_id
		 *
		 * @return array
		 */
		public function calculate_items( $course_id = 0, $user_id = 0 ) {
			if ( !is_user_logged_in() ) {
				return;
			}
			$course = $course_id ? LP_Course::get_course( $course_id ) : LP_Global::course();
			$user   = $user_id ? learn_press_get_user( $user_id ) : learn_press_get_current_user();

			$items = array();

			if ( !$user || !$course ) {
				return $items;
			}

			// check enable drip item
			if ( get_post_meta( $course_id, '_lp_content_drip_enable', true ) != 'yes' ) {
				return $items;
			}

			$drip_items = get_post_meta( $course_id, '_lp_drip_items', true );

			if ( !$drip_items ) {
				return $items;
			}

			$course_items      = $course->get_items();
			$course_data       = $user->get_course_data( $course_id );
			$start_course_time = $course_data->get_start_time();

			$drip_type = get_post_meta( $course_id, '_lp_content_drip_drip_type', true );

			switch ( $drip_type ) {
				// open the course items sequentially
				case 'sequentially':
					foreach ( $drip_items as $item_id => $drip_args ) {
						// for non-first course items >> bases on completed prev item time
						if ( $index = array_search( $item_id, $course_items ) ) {
							// previous item
							$prev_item_id = $course_items[$index - 1];

							$items[$item_id] = $this->_calculate_dependent_items( $course, $user, array( $prev_item_id ), $drip_args );
						} else {
							// for first course items >> bases on enrolled course time
							$items[$item_id] = $this->_calculate_enrolled_course( $start_course_time, $drip_args );
						}
					}

					break;
				// specific time after enrolled course
				case 'specific_date':
					foreach ( $drip_items as $item_id => $drip_args ) {
						// all course items bases on enrolled course time
						$items[$item_id] = $this->_calculate_enrolled_course( $start_course_time, $drip_args );
					}
					break;
				// open item bases on prerequisite items
				case 'prerequisite':
					foreach ( $drip_items as $item_id => $drip_args ) {
						// prerequisite items
						$prerequisite_items = $drip_args['prerequisite'];

						// for item not set prerequisite items
						if ( !isset( $prerequisite_items ) || !is_array( $prerequisite_items ) || in_array( 0, $prerequisite_items ) ) {
							$items[$item_id] = $this->_calculate_enrolled_course( $start_course_time, $drip_args );
						} else {
							// for item has list prerequisite
							$items[$item_id] = $this->_calculate_dependent_items( $course, $user, $prerequisite_items, $drip_args );
						}
					}
					break;
				default:
					do_action( 'learn-press/content-drip/calculate-items', $course_id, $user_id, $drip_type );
					break;
			}

			return $items;
		}

		/**
		 * Calculate drip course item bases on enrolled course time
		 *
		 * @param $start_course_time LP_Datetime
		 * @param $args              'drip item args'
		 *
		 * @return array
		 */
		private function _calculate_enrolled_course( $start_course_time, $args ) {
			if ( isset( $args['type'] ) ) {
				switch ( $args['type'] ) {
					case 'specific':
						$open_time = new LP_Datetime( $args['date'] );
						break;
					case 'interval':
						$open_time = new LP_Datetime( $start_course_time->getTimestamp() + $args['interval'][2] );
						break;
					default:
						$open_time = new LP_Datetime( $start_course_time->getTimestamp() );
						break;
				}
			} else {
				$open_time = new LP_Datetime( $start_course_time->getTimestamp() );
			}

			return array(
				'locked'    => $open_time->is_exceeded() ? true : false,
				'open_item' => $open_time
			);
		}

		/**
		 * @param       $course     LP_Course
		 * @param       $user       LP_User
		 * @param array $deps_items depends on items
		 * @param       $args       'drip item args'
		 *
		 * @return array
		 */
		private function _calculate_dependent_items( $course, $user, $deps_items = array(), $args ) {

			$locked = false;

			foreach ( $deps_items as $deps_item_id ) {
				// get user depends item data
				$item_data = $user->get_item_data( $deps_item_id, $course->get_id() );
				if ( !$item_data ) {
					$locked = true;
					continue;
				}
				$end_time  = $item_data->get_end_time();
				$item_type = get_post_type( $deps_item_id );
				if ( !$end_time->__toString() ) {
					$end_time = ( $item_type == LP_LESSON_CPT ) ? $item_data->get_start_time() : $item_data->get_end_time_gmt();
				}
				$item_status = apply_filters( 'learn-press/content-drip/item-status', $user->get_item_status( $deps_item_id, $course->get_id() ), $deps_item_id, $course->get_id(), $user );
				if ( LP_QUIZ_CPT == $item_type && 'passed' !== $user->get_item_grade( $deps_item_id, $course->get_id() ) ) {
					$locked    = true;
					$open_time = false;
				} elseif ( $item_status != 'completed' ) {
					$locked = true;

					// return open time false for un-completed depends item
					$open_time = false;
				} else {
					switch ( $args['type'] ) {
						case 'specific':
							$open_time = new LP_Datetime( $args['date'] );
							break;
						case 'interval':
							$open_time = new LP_Datetime( $end_time->getTimestamp() + $args['interval'][2] );
							break;
						default:
							$open_time = false;
							break;
					}
				}

				$args['open_time'][$deps_item_id] = $open_time;

				if ( $open_time && $open_time->is_exceeded() ) {
					$locked = true;
				}
			}

			return array(
				'locked'  => $locked,
				'depends' => $args
			);
		}

		/**
		 * Maybe restrict content item?
		 */
		public function maybe_restrict_content() {
			$item = LP_Global::course_item();
			if ( !$item ) {
				return;
			}
// 			var_dump($item->is_preview());
			if ( $item->is_preview() ) {
				return false;
			}

			if ( !$drip_item = $this->get_drip_item( $item->get_id() ) ) {
				return;
			}

			if ( !$drip_item['locked'] ) {
				return;
			}

			global $wp_filter;

			foreach ( array( '', 'before-', 'after-' ) as $prefix ) {
				if ( isset( $wp_filter["learn-press/{$prefix}content-item-summary/lp_lesson"] ) ) {
					unset( $wp_filter["learn-press/{$prefix}content-item-summary/lp_lesson"] );
				}

				if ( isset( $wp_filter["learn-press/{$prefix}content-item-summary/lp_quiz"] ) ) {
					unset( $wp_filter["learn-press/{$prefix}content-item-summary/lp_quiz"] );
				}
			}

			// filter course item content
			add_action( 'learn-press/content-item-summary/lp_lesson', array( $this, 'filter_item_content' ), - 10 );
			add_action( 'learn-press/content-item-summary/lp_quiz', array( $this, 'filter_item_content' ), - 10 );
			if ( $drip_item['locked'] ) {
				$priority = has_action( 'learn-press/before-course-item-content', 'thim_content_item_lesson_media' );
				if ( $priority ) {
					remove_action( 'learn-press/before-course-item-content', 'thim_content_item_lesson_media', $priority );
				}
			}

			do_action( 'learn-press/content-drip/maybe-restrict-content' );
		}

		/**
		 * Filter course item content.
		 */
		public function filter_item_content() {
			$item = LP_Global::course_item();
			learn_press_get_template( 'restrict-content.php', array( 'drip_item' => $this->get_drip_item( $item->get_id() ) ), learn_press_template_path() . '/addons/content-drip/', LP_ADDON_CONTENT_DRIP_PATH . '/templates' );
		}
	}
}
?>