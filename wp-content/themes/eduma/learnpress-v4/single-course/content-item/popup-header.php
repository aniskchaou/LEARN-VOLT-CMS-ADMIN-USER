<?php
/**
 * Template for displaying header of single course popup.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/header.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.0
 */

defined( 'ABSPATH' ) || exit();

$user   = learn_press_get_current_user();
$course = LP_Global::course();

if ( ! $course || ! $user ) {
	return;
}

/**
 * Get cookie stored for sidebar state
 */
$show_sidebar = learn_press_cookie_get( 'sidebar-toggle' );
?>

<div id="popup-header">
	
	<div class="thim-course-item-popup-right">
		<input type="checkbox" id="sidebar-toggle" class="toggle-content-item" <?php checked( $show_sidebar, true ); ?> />
		<a href="<?php echo $course->get_permalink(); ?>" class="back_course"><i class="fa fa-close"></i></a>
	</div>
    
</div>
