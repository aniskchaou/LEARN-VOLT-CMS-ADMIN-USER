<?php
/**
 * Template for displaying course content within the loop.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/content-course.php
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$user = LP_Global::user();

$theme_options_data         = get_theme_mods();
$course_item_excerpt_length = intval( get_theme_mod( 'thim_learnpress_excerpt_length', 25 ) );

$class = isset( $theme_options_data['thim_learnpress_cate_grid_column'] ) && $theme_options_data['thim_learnpress_cate_grid_column'] ? 'course-grid-' . $theme_options_data['thim_learnpress_cate_grid_column'] : 'course-grid-3';
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$class .= ' lpr_course';
?>

<div id="post-<?php the_ID(); ?>" <?php post_class( $class ); ?>>

	<?php
	// @deprecated
	do_action( 'learn_press_before_courses_loop_item' );
	?>

	<div class="course-item">

		<?php
		// @since 3.0.0
		do_action( 'learn-press/before-courses-loop-item' );
		?>

		<?php
		// @thim
		do_action( 'thim_courses_loop_item_thumb' );
		?>

		<div class="thim-course-content">
			<?php learn_press_courses_loop_item_instructor(); ?>
			<?php
			//thim_courses_loop_item_author();
			//do_action( 'learn_press_before_the_title' );
			the_title( sprintf( '<h2 class="course-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			do_action( 'learn_press_after_the_title' );
			?>
			<div class="course-meta">
				<?php learn_press_courses_loop_item_instructor(); ?>
				<?php thim_course_ratings(); ?>
				<?php learn_press_courses_loop_item_students(); ?>
				<?php thim_course_ratings_count(); ?>
				<?php //learn_press_course_tags(); ?>
				<?php learn_press_courses_loop_item_price(); ?>
			</div>

			<?php if ( $course_item_excerpt_length ): ?>
				<div class="course-description">
					<?php
					do_action( 'learn_press_before_course_content' );
					echo thim_excerpt( $course_item_excerpt_length );
					do_action( 'learn_press_after_course_content' );
					?>
				</div>
			<?php endif; ?>

			<?php learn_press_courses_loop_item_price(); ?>
			<div class="course-readmore">
				<a href="<?php echo esc_url( get_permalink() ); ?>"><?php esc_html_e( 'Read More', 'eduma' ); ?></a>
			</div>
		</div>

		<?php
		// @since 3.0.0
		do_action( 'learn-press/after-courses-loop-item' );
		?>

	</div>

	<?php
	// @deprecated
	do_action( 'learn_press_after_courses_loop_item' );
	?>

</div>