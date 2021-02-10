<?php
/**
 * Template for displaying students list tab in single course page.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/addons/students-list/student-list.php.
 *
 * @author ThimPress
 * @package LearnPress/Students-List/Templates
 * @version 3.0.0
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;
?>

<?php if ( $course ) { ?>
	<?php do_action( 'learn_press_before_students_list' ); ?>

    <div class="course-students-list">

		<?php
		$curd  = new LP_Course_CURD();
		$limit = isset( $limit ) ? $limit : - 1;
		?>

		<?php if ( $students = $curd->get_user_enrolled( $course->get_ID(), $limit ) ) { ?>
			<?php
			$show_avatar               = apply_filters( 'learn_press_students_list_avatar', true );
			$students_list_avatar_size = apply_filters( 'learn_press_students_list_avatar_size', 32 );
			$filter                    = isset( $filter ) ? $filter : 'all';
			$filters                   = apply_filters( 'learn_press_get_students_list_filter',
				array(
					'all'         => esc_html__( 'All', 'learnpress' ),
					'in-progress' => esc_html__( 'In Progress', 'learnpress' ),
					'finished'    => esc_html__( 'Finished', 'learnpress' )
				) );
			?>

            <div class="filter-students">
                <label for="students-list-filter"><?php esc_html_e( 'Student filter', 'learnpress' ); ?></label>
                <select class="students-list-filter">
					<?php foreach ( $filters as $key => $_filter ) {
						echo '<option value="' . esc_attr( $key ) . '">' . esc_html( $_filter ) . '</option>';
					} ?>
                </select>
            </div>

            <ul class="students">
				<?php foreach ( $students as $student ) {
					$result  = $process = '';
					$student = learn_press_get_user( $student->ID );

					$course_data       = $student->get_course_data( $course->get_id() );
					$course_results    = $course_data->get_results( false );
					$passing_condition = $course->get_passing_condition();

					$result = $course_results['result'];
					?>

					<?php $process .= ( $result == 100 ) ? 'finished' : 'in-progress'; ?>

					<?php if ( $filter == $process || $filter == 'all' ) { ?>
                        <li class="students-enrolled <?php echo ( isset( $result ) ) ? 'user-login ' . $process : ''; ?>">
                            <div class="user-info">
								<?php if ( $show_avatar ): ?>
									<?php echo get_avatar( $student->get_id(), $students_list_avatar_size, '', $student->get_data( 'display_name' ), array( 'class' => 'students_list_avatar' ) ); ?>
								<?php endif; ?>
                                <a class="name" href="<?php echo learn_press_user_profile_link( $student->get_id() ) ?>"
                                   title="<?php echo $student->get_data( 'display_name' ) . ' profile'; ?>">
									<?php echo $student->get_data( 'display_name' ); ?>
                                </a>
                            </div>

                            <div class="lp-course-status">
                                   <span class="number"><?php echo round( $course_results['result'], 2 ); ?>
                                       <span class="percentage-sign">%</span>
                                   </span>
								<?php if ( $grade = $course_results['grade'] ) { ?>
                                    <span class="lp-label grade <?php echo esc_attr( $grade ); ?>">
                                    <?php learn_press_course_grade_html( $grade ); ?>
                                    </span>
								<?php } ?>
                            </div>

                            <div class="learn-press-progress lp-course-progress <?php echo $course_data->is_passed() ? ' passed' : ''; ?>"
                                 data-value="<?php echo $course_results['result']; ?>"
                                 data-passing-condition="<?php echo $passing_condition; ?>">
                                <div class="progress-bg lp-progress-bar">
                                    <div class="progress-active lp-progress-value"
                                         style="left: <?php echo $course_results['result']; ?>%;">
                                    </div>
                                </div>
                                <div class="lp-passing-conditional"
                                     data-content="<?php printf( esc_html__( 'Passing condition: %s%%', 'learnpress' ), $passing_condition ); ?>"
                                     style="left: <?php echo $passing_condition; ?>%;">
                                </div>
                            </div>
                        </li>
					<?php } ?>
				<?php } ?>
            </ul>
			<?php
			$other_student = $course->get_data( 'fake_students' );
			if ( $other_student && $limit == - 1 ) {
				echo '<p class="additional-students">and ' . sprintf( _n( 'one student enrolled.', '%s students enrolled.', $other_student, 'learnpress-students-list' ), $other_student ) . '</p>';
			} ?>
		<?php } else { ?>
            <div class="students empty">
				<?php if ( $course->get_users_enrolled() ) {
					echo apply_filters( 'learn_press_course_count_student', sprintf( _n( 'One student enrolled.', '%s students enrolled.', $course->get_users_enrolled(), 'learnpress-students-list' ), $course->get_users_enrolled() ) );
				} else {
					echo apply_filters( 'learn_press_course_no_student', __( 'No student enrolled.', 'learnpress-students-list' ) );
				} ?>
            </div>
		<?php } ?>
    </div>
	<?php do_action( 'learn_press_after_students_list' );
} else {
	echo __( 'Course ID invalid, please check it again.', 'learnpress-students-list' );
}