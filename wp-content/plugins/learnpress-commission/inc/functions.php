<?php
/**
 * LearnPress Commission Functions
 *
 * Define common functions for both front-end and back-end
 *
 * @author   ThimPress
 * @package  LearnPress/Commission/Functions
 * @version  3.0.0
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'lp_commission_admin_view' ) ) {
	/**
	 * Admin view.
	 *
	 * @param        $name
	 * @param string $args
	 */
	function lp_commission_admin_view( $name, $args = '' ) {
		if ( ! preg_match( '~.php$~', $name ) ) {
			$name .= '.php';
		}
		if ( is_array( $args ) ) {
			extract( $args );
		}
		include LP_ADDON_COMMISSION_INC . "admin/views/{$name}";
	}
}

if ( ! function_exists( 'lp_commission_get_instructors_by_course_id' ) ) {
	/**
	 * Get instructors by course id.
	 *
	 * @param null $course_id
	 *
	 * @TODO Need change
	 *
	 * @return array
	 */
	function lp_commission_get_instructors_by_course_id( $course_id = null ) {
		if ( empty( $course_id ) ) {
			$course_id = get_the_ID();
		}

		if ( ! $course_id ) {
			return array();
		}

		$lp_courses = new LP_Course( $course_id );
		$instructor = get_userdata( $lp_courses->post->post_author );

		$instructors = array(
			$instructor,
		);

		return $instructors;
	}
}

if ( ! function_exists( 'lp_commission_get_main_instructor_by_course_id' ) ) {
	/**
	 * @param $course_id
	 *
	 * @return false|WP_User
	 */
	function lp_commission_get_main_instructor_by_course_id( $course_id ) {
		$course = LP_Course::get_course( $course_id );

		if ( ! $course ) {
			return false;
		}

		$instructor = $course->get_instructor();

		return $instructor;
	}
}

if ( ! function_exists( 'lp_commission_is_active' ) ) {
	/**
	 * @param $course_id
	 *
	 * @return bool
	 */
	function lp_commission_is_active( $course_id ) {
		$is_active = get_post_meta( $course_id, LPC()->key_active, true );

		if ( ! isset( $is_active ) || $is_active == '' ) {
			return true;
		}

		return (bool) $is_active;
	}
}

if ( ! function_exists( 'lp_commission_query_all_course' ) ) {
	/**
	 * Get Query get all courses
	 *
	 * @return WP_Query
	 */
	function lp_commission_query_all_course() {
		$post_type = LP_COURSE_CPT;

		$args = array(
			'post_type'      => array( $post_type ),
			'post_status'    => array( 'publish', 'draft', 'pending' ),
			'posts_per_page' => - 1,
		);

		$the_query = new WP_Query( $args );

		return $the_query;
	}
}

if ( ! function_exists( 'lp_commission_get_total_commission' ) ) {
	/**
	 * Get total commission by user id.
	 *
	 * @param $user_id
	 *
	 * @return float|int|mixed
	 */
	function lp_commission_get_total_commission( $user_id, $force = false ) {
		if ( ! $force ) {
			$value = get_user_meta( $user_id, 'lp_commission_total', true );
			if ( empty( $value ) ) {
				$value = 0;
			}
			$value = floatval( $value );
		} else {
			global $wpdb;
		}

		return $value;
	}
}

/*
 * get all commission that instructor is got
 */
function lp_commission_get_total_user_commission( $user_id, $force = false ) {
	if ( ! $force ) {
		$value = get_user_meta( $user_id, 'lp_sum_commission_total', true );
		if ( empty( $value ) ) {
			$value = 0;
		}
		$value = floatval( $value );
	} else {
		global $wpdb;

	}

	return $value;
}

/*
 * get all current comminssion that instructor is got
 */
function lp_commission_get_current_user_commission( $user_id, $force = false ) {
	if ( ! $force ) {
		$value = get_user_meta( $user_id, 'lp_commission_total', true );
		if ( empty( $value ) ) {
			$value = 0;
		}
		$value = floatval( $value );
	} else {
		# get all commission of user
		lp_commission_get_total_user_commission( $user_id, $force );
		# get money that user withdrawall


	}

	return $value;
}

/*
 * get all current comminssion that instructor is got
 */
function lp_commission_get_user_withdrawal_amount( $user_id ) {
	global $wpdb;
	$sql    = $wpdb->prepare( "SELECT
			sum(pm.meta_value)
		FROM
			{$wpdb->posts} p
				INNER JOIN
			{$wpdb->postmeta} `pm` ON `p`.`ID` = `pm`.`post_id` AND `pm`.`meta_key`='lp_value'
				INNER JOIN
			{$wpdb->postmeta} `pm2` ON `p`.`ID` = `pm2`.`post_id` AND `pm2`.`meta_key`='lp_status'
		WHERE
			`p`.`post_type` = 'lp_withdraw'
			AND pm2.meta_value = '';
				AND p.post_author = %d", $user_id );
	$amount = $wpdb->get_var( $sql );

	return $amount;
}


if ( ! function_exists( 'lp_commission_update_total_commission' ) ) {
	/**
	 * @param $user_id
	 * @param $value
	 *
	 * @return bool|int
	 */
	function lp_commission_update_total_commission( $user_id, $value ) {
		$value = floatval( $value );

		return update_user_meta( $user_id, 'lp_commission_total', $value );
	}
}

if ( ! function_exists( 'lp_commission_add_commission' ) ) {
	/**
	 * @param $user_id
	 * @param $value
	 *
	 * @return bool|int
	 */
	function lp_commission_add_commission( $user_id, $value, $order_id, $order_item ) {
		$old_value = lp_commission_get_total_commission( $user_id );
		$value     = floatval( $value );
		$new_value = $old_value + $value;
		$res       = lp_commission_update_total_commission( $user_id, $new_value );
		if ( $res ) {
			learn_press_update_order_item_meta( $order_item['id'], 'lp_commission_status', 'processed' );
			learn_press_update_order_item_meta( $order_item['id'], 'lp_commission_value', $value );
		}

		return $res;
	}
}

if ( ! function_exists( 'lp_commission_subtract_commission' ) ) {
	/**
	 * @param $user_id
	 * @param $value
	 *
	 * @return bool|int
	 */
	function lp_commission_subtract_commission( $user_id, $value ) {
		$old_value = lp_commission_get_total_commission( $user_id );
		$value     = floatval( $value );
		if ( $value > $old_value ) {
			return - 1;
		}

		$new_value = $old_value - $value;

		return lp_commission_update_total_commission( $user_id, $new_value );
	}
}
