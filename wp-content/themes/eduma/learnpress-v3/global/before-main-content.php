<?php
/**
 * Template for displaying before main content.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/global/before-main-content.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();
?>

<?php $user = learn_press_get_current_user(); ?>

<?php if ( learn_press_is_course() ){ ?>

<div id="lp-single-course" class="lp-single-course">

	<?php if ( ! learn_press_get_page_link( 'checkout' ) && ( $user->is_admin() || $user->is_instructor() ) ) { ?>

		<?php $message = __( 'LearnPress <strong>Checkout</strong> page is not set up. ', 'eduma' );

		if ( $user->is_instructor() ) {
			$message .= __( 'Please contact to administrator for setting up this page.', 'eduma' );
		} else {
			$message .= sprintf( __( 'Please <a href="%s" target="_blank">setup</a> it so user can purchase a course.', 'eduma' ), admin_url( 'admin.php?page=learn-press-settings&tab=checkout' ) );
		} ?>

		<?php learn_press_display_message( $message, 'error' ); ?>

	<?php } ?>

	<?php } else{ 
		$courses_page_id = learn_press_get_page_id('courses');
		$courses_page_url = $courses_page_id ? get_page_link($courses_page_id): learn_press_get_current_url();
		?>

    <div id="lp-archive-courses" data-all-courses-url="<?php echo esc_url($courses_page_url) ?>">

		<?php } ?>
