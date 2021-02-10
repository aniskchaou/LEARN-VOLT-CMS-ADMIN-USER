<?php
/**
 * Template for displaying curriculum tab of single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/tabs/curriculum.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

global $course;
?>

<?php do_action('thim_begin_curriculum_button') ?>

	<div class="course-curriculum" id="learn-press-course-curriculum">

		<div class="curriculum-scrollable learn-press-4">

			<?php
			/**
			 * @since 4.0.0
			 */
			do_action( 'learn-press/before-single-course-curriculum' );
			?>

			<?php if ( $curriculum = $course->get_curriculum() ) { ?>

				<?php do_action( 'thim_before_curiculumn_item' ); ?>

				<ul class="curriculum-sections">
					<?php
					$position = 0;
					foreach ( $curriculum as $section ) {
						++ $position;
						$section->set_position( $position );
						learn_press_get_template( 'single-course/loop-section.php', array( 'section' => $section ) );
					} ?>
				</ul>

			<?php } else { ?>

				<?php echo apply_filters( 'learn_press_course_curriculum_empty', __( 'Curriculum is empty', 'eduma' ) ); ?>

			<?php } ?>

			<?php
			/**
			 * @since 4.0.0
			 */
			do_action( 'learn-press/after-single-course-curriculum' );

			?>

		</div>

	</div>

<?php do_action( 'thim_end_curriculum_button' ); ?>