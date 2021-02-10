<?php
add_filter( 'learn-press/override-templates', '__return_true' );

if ( ! function_exists( 'thim_remove_learnpress_hooks' ) ) {
	function thim_remove_learnpress_hooks() {

		remove_action( 'learn-press/course-section-item/before-lp_lesson-meta', LP()->template( 'course' )->func( 'item_meta_duration' ), 10 );
		remove_action( 'learn-press/course-section-item/before-lp_quiz-meta', LP()->template( 'course' )->func( 'quiz_meta_questions' ), 10 );
		remove_action( 'learn-press/course-section-item/before-lp_quiz-meta', LP()->template( 'course' )->func( 'item_meta_duration' ), 20 );
		remove_action( 'learn-press/course-section-item/before-lp_quiz-meta', 'learn_press_item_meta_duration', 10 );
		remove_action( 'learn-press/course-section-item/before-lp_quiz-meta', 'learn_press_quiz_meta_questions', 5 );

		add_action( 'thim_single_course_payment', LP()->template( 'course' )->func( 'course_pricing' ), 5 );
		// 		add_action( 'thim_single_course_payment', LP()->template( 'course' )->func( 'course_external_button' ), 10 );
		//		add_action( 'thim_single_course_payment', LP()->template( 'course' )->func( 'course_purchase_button' ), 15 );
		//		add_action( 'thim_single_course_payment', LP()->template( 'course' )->func( 'course_enroll_button' ), 20 );
		add_action( 'thim_single_course_meta', LP()->template( 'course' )->callback( 'single-course/instructor' ), 5 );
		add_action( 'thim_single_course_meta', LP()->template( 'course' )->callback( 'single-course/meta/category' ), 15 );
		add_action( 'thim_single_course_meta', 'thim_course_forum_link', 20 );
		add_action( 'thim_single_course_meta', 'thim_course_ratings', 25 );
		add_action( 'thim_single_course_meta', LP()->template( 'course' )->func( 'user_progress' ), 30 );

		add_action( 'thim_single_course_before_meta', 'thim_course_thumbnail_item', 5 );

		add_action(
			'init', function () {
			if ( thim_plugin_active( 'learnpress-wishlist/learnpress-wishlist.php' ) && class_exists( 'LP_Addon_Wishlist' ) && is_user_logged_in() && thim_is_version_addons_wishlist( '3' ) ) {
				$instance_addon = LP_Addon_Wishlist::instance();
				remove_action( 'learn-press/after-course-buttons', array( $instance_addon, 'wishlist_button' ), 100 );
				add_action( 'thim_after_course_info', array( $instance_addon, 'wishlist_button' ), 10 );
				add_action( 'thim_inner_thumbnail_course', array( $instance_addon, 'wishlist_button' ), 10 );
			}
			if ( thim_plugin_active( 'learnpress-bbpress/learnpress-bbpress.php' ) && class_exists( 'LP_Addon_bbPress' ) && thim_is_version_addons_bbpress( '3' ) ) {
				$instance_addon = LP_Addon_bbPress::instance();
				remove_action( 'learn-press/single-course-summary', array( $instance_addon, 'forum_link' ), 0 );
			}
			if ( thim_plugin_active( 'learnpress-woo-payment/learnpress-woo-payment.php' ) && class_exists( 'LP_Addon_Woo_Payment' ) && thim_is_version_addons_woo( 3 ) ) {
				$instance_addon = LP_Addon_Woo_Payment::instance();
				remove_action(
					'learn-press/before-course-buttons', array(
						$instance_addon,
						'purchase_course_notice'
					)
				);
				remove_action( 'learn-press/after-course-buttons', array( $instance_addon, 'after_course_buttons' ) );
				//add_action( 'learn-press/before-single-course', array( $instance_addon, 'purchase_course_notice' ) );
				//add_action( 'learn-press/before-single-course', array( $instance_addon, 'after_course_buttons' ) );
			}
			if ( thim_plugin_active( 'learnpress-paid-membership-pro/learnpress-paid-memberships-pro.php' ) && thim_plugin_active( 'paid-memberships-pro/paid-memberships-pro.php' ) && class_exists( 'LP_Addon_Paid_Memberships_Pro' ) ) {
				$instance_addon = LP_Addon_Paid_Memberships_Pro::instance();
				remove_action(
					'learn-press/before-course-buttons', array(
					$instance_addon,
					'add_buy_membership_button'
				), 10
				);
				add_action(
					'thim_single_course_payment', array(
					$instance_addon,
					'learn_press_before_course_buttons'
				), 8
				);
			}
			if ( thim_plugin_active( 'learnpress-assignments/learnpress-assignments.php' ) && class_exists( 'LP_Addon_Assignment' ) ) {
				$instance_addon = LP_Addon_Assignment::instance();
				remove_action(
					'learn-press/course-section-item/before-lp_assignment-meta', array(
					$instance_addon,
					'learnpress_assignment_show_duration'
				), 10
				);
				add_action( 'learn-press/course-section-item/before-lp_assignment-meta', 'thim_assignment_show_duration', 10 );
				if ( ! function_exists( 'thimthim_assignment_show_duration_assignment_show_duration' ) ) {
					function thim_assignment_show_duration( $item ) {
						$duration = get_post_meta( $item->get_id(), '_lp_duration', true );
						if ( absint( $duration ) > 1 ) {
							$duration .= 's';
						}
						$duration_number = absint( $duration );
						$time = trim( str_replace( $duration_number,'',$duration ) );
						switch($time){
							case 'minutes' :
								$time = _x("minutes",'duration','eduma');
								break;
							case 'hours' :
								$time = _x("hours",'duration','eduma');
								break;
							case 'days' :
								$time = _x("days",'duration','eduma');
								break;
							case 'weeks':
								$time = _x("weeks",'duration','eduma');
								break;
							case 'minute' :
								$time = _x("minute",'duration','eduma');
								break;
							default:
								$time = _x("week",'duration','eduma');
						}
						echo '<span class="meta duration">' . $duration_number.' '.$time  . '</span>';
 					}
				}
			}

		}, 99
		);

		remove_action( 'learn-press/after-checkout-form', LP()->template( 'checkout' )->func( 'account_logged_in' ), 20 );
		remove_action( 'learn-press/after-checkout-form', LP()->template( 'checkout' )->func( 'order_comment' ), 60 );
		add_action( 'learn-press/before-checkout-form', LP()->template( 'checkout' )->func( 'account_logged_in' ), 9 );
		add_action( 'learn-press/before-checkout-form', LP()->template( 'checkout' )->func( 'order_comment' ), 11 );
		remove_action( 'learn-press/before-user-profile', LP()->template( 'profile' )->func( 'header' ), 10 );

		// remove action for page profile - tuanta
		// remove html in begin loop and end loop
		add_filter(
			'learn_press_course_loop_begin', function () {
			return '';
		}
		);
		add_filter(
			'learn_press_course_loop_end', function () {
			return '';
		}
		);

		remove_action( 'learn-press/profile/before-dashboard', LP()->template( 'profile' )->func( 'dashboard_statistic' ), 10 );
		remove_action( 'learn-press/profile/dashboard-summary', LP()->template( 'profile' )->func( 'dashboard_featured_courses' ), 20 );
		//		remove_action( 'learn-press/single-item-summary', LP()->template( 'course' )->func( 'popup_header' ), 10 );
		//		remove_action( 'learn-press/single-item-summary', LP()->template( 'course' )->func( 'popup_sidebar' ), 20 );
		//		remove_action( 'learn-press/single-item-summary', LP()->template( 'course' )->func( 'popup_content' ), 30 );

		//		remove_action( 'learn-press/single-item-summary', LP()->template( 'course' )->func( 'popup_footer' ), 40 );

		//		add_action('learn-press/course-item-content-header', LP()->template( 'course' )->func( 'popup_header' ), 5);

		//		add_action( 'learn-press/single-item-summary', LP()->template( 'course' )->callback( 'single-course/tabs/curriculum.php' ), 5 );
		//		add_action( 'learn-press/single-item-summary', LP()->template( 'course' )->callback( 'single-course/content-item.php' ), 10 );

		/**
		 * @see LP_Template_Course::popup_footer_nav()
		 */
		//		remove_action( 'learn-press/popup-footer', LP()->template( 'course' )->func( 'popup_footer_nav' ), 10 );
		//		add_action( 'learn-press/after-course-item-content', LP()->template( 'course' )->func( 'popup_footer_nav' ), 5 );
		add_action( 'learn-press/after-course-item-content', 'learn_press_lesson_comment_form', 10 );


	}
}

add_action( 'after_setup_theme', 'thim_remove_learnpress_hooks', 15 );

remove_all_actions( 'learn-press/course-content-summary', 10 );
remove_all_actions( 'learn-press/course-content-summary', 15 );
remove_all_actions( 'learn-press/course-content-summary', 85 );
remove_all_actions( 'learn-press/before-main-content' );

add_filter( 'lp_item_course_class', 'thim_item_course_class_custom' );
function thim_item_course_class_custom( $class ) {
	$class[] = 'thim-course-grid';

	return $class;
}

/**
 * @see LP_Template_Course::popup_header()
 * @see LP_Template_Course::popup_sidebar()
 * @see LP_Template_Course::popup_content()
 * @see LP_Template_Course::popup_footer()
 */


add_action( 'learn-press/before-main-content', 'lp_archive_courses_open', - 100 );
if ( ! function_exists( 'lp_archive_courses_open' ) ) {
	function lp_archive_courses_open() {
		$courses_page_id  = learn_press_get_page_id( 'courses' );
		$courses_page_url = $courses_page_id ? get_page_link( $courses_page_id ) : learn_press_get_current_url();
		if ( thim_check_is_course_taxonomy() || thim_check_is_course() ) {
			?>
			<div id="lp-archive-courses" data-all-courses-url="<?php echo esc_url( $courses_page_url ) ?>">
			<?php
		} elseif ( is_singular( LP_COURSE_CPT ) ) {
			?>
			<div id="lp-single-course" class="lp-single-course learn-press-4">
			<?php
		}
	}
}


if ( ! function_exists( 'thim_remove_learnpress_hooks' ) ) {
	function thim_remove_learnpress_hooks() {

		add_action(
			'init', function () {
			if ( thim_plugin_active( 'learnpress-bbpress/learnpress-bbpress.php' ) && class_exists( 'LP_Addon_bbPress' ) && thim_is_version_addons_bbpress( '3' ) ) {
				$instance_addon = LP_Addon_bbPress::instance();
				remove_action( 'learn-press/single-course-summary', array( $instance_addon, 'forum_link' ), 0 );
			}
		}, 99
		);
	}
}

add_action( 'after_setup_theme', 'thim_remove_learnpress_hooks', 15 );


if ( get_theme_mod( 'thim_layout_content_page', 'normal' ) != 'new-1' ) {
	add_action( 'learn-press/single-course-summary', 'learn_press_course_thumbnail', 2 );
}

function eduma_add_video_lesson() {
	lp_meta_box_textarea_field(
		array(
			'id'          => '_lp_lesson_video_intro',
			'label'       => esc_html__( 'Media', 'eduma' ),
			'description' => esc_html__( 'Add an embed link like video, PDF, slider...', 'eduma' ),
			'default'     => '',
		)
	);
}

add_action( 'learnpress/lesson-settings/after', 'eduma_add_video_lesson' );

add_action(
	'learnpress_save_lp_lesson_metabox', function ( $post_id ) {
	$video = ! empty( $_POST['_lp_lesson_video_intro'] ) ? $_POST['_lp_lesson_video_intro'] : '';

	update_post_meta( $post_id, '_lp_lesson_video_intro', $video );
}
);
// add cusom field for course
function eduma_add_custom_field_course() {
	lp_meta_box_text_input_field(
		array(
			'id'          => 'thim_course_duration',
			'label'       => esc_html__( 'Duration Info', 'eduma' ),
			'description' => esc_html__( 'Display duration info', 'eduma' ),
			'default'     => esc_html__( '50 hours', 'eduma' )
		)
	);
	lp_meta_box_text_input_field(
		array(
			'id'          => 'thim_course_language',
			'label'       => esc_html__( 'Languages', 'eduma' ),
			'description' => esc_html__( 'Language\'s used for studying', 'eduma' ),
			'default'     => esc_html__( 'English', 'eduma' )
		)
	);

	lp_meta_box_textarea_field(
		array(
			'id'          => 'thim_course_media_intro',
			'label'       => esc_html__( 'Media Intro', 'eduma' ),
			'description' => esc_html__( 'Enter media intro', 'eduma' ),
			'default'     => '',
		)
	);
}

add_action( 'learnpress/course-settings/after-general', 'eduma_add_custom_field_course' );

add_action('learnpress_save_lp_course_metabox', function ( $post_id ) {
	$video         = ! empty( $_POST['thim_course_media_intro'] ) ? $_POST['thim_course_media_intro'] : '';
	$language      = ! empty( $_POST['thim_course_language'] ) ? $_POST['thim_course_language'] : '';
	$duration_info = ! empty( $_POST['thim_course_duration'] ) ? $_POST['thim_course_duration'] : '';

	update_post_meta( $post_id, 'thim_course_media_intro', $video );
	update_post_meta( $post_id, 'thim_course_language', $language );
	update_post_meta( $post_id, 'thim_course_duration', $duration_info );
}
);

/**
 * @param Remaining time
 */
function thim_get_remaining_time() {
	$user   = LP_Global::user();
	$course = LP_Global::course();

	if ( ! $course ) {
		return false;
	}

	if ( ! $user ) {
		return false;
	}

	$remaining_time = $user->get_course_remaining_time( $course->get_id() );

	if ( false === $remaining_time ) {
		return false;
	}

	$time = '';
	$time .= '<div class="course-remaining-time message message-warning learn-press-message">';
	$time .= '<p>';
	$time .= sprintf( __( 'You have %s remaining for the course', 'eduma' ), $remaining_time );
	$time .= '</p>';
	$time .= '</div>';
	echo $time;
}

add_action( 'thim_begin_curriculum_button', 'thim_get_remaining_time', 5 );

add_action( 'learn-press/course-content-summary', 'thim_landing_tabs', 22 );
