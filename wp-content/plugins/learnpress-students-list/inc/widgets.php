<?php
/**
 * Students list widget class.
 *
 * @author   ThimPress
 * @package  LearnPress/Students-List/Classes
 * @version  3.0.0
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LP_Students_List' ) ) {
	/**
	 * Class LP_Students_List.
	 */
	class LP_Students_List extends WP_Widget {

		/**
		 * LP_Students_List constructor.
		 */
		public function __construct() {
			parent::__construct(
				'students_list_widget',
				__( 'Learnpress - Students List', 'learnpress-students-list' ),
				array( 'description' => __( 'Display students list of course.', 'learnpress-students-list' ) )
			);
		}

		/**
		 * Front-end display
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}

			if ( $instance['course_id'] ) {

				$course_id  = esc_html( $instance['course_id'] );
				$course_ids = array();
				if ( strpos( $course_id, ',' ) ) {
					$course_ids = explode( ',', $course_id );
				} else {
					$course_ids[] = $course_id;
				}
				foreach ( $course_ids as $course_id ) {
					$course        = learn_press_get_course( $course_id );
					$student_limit = $instance['number_student'] ? ( $instance['number_student'] > 0 ? $instance['number_student'] : '-1' ) : '-1';
					$filter        = $instance['filter'] ? $instance['filter'] : 'all';

					echo esc_html__( 'Course: ', 'learnpress-students-list' ) . '<a href="' . get_permalink( $course_id ) . '">' . get_the_title( $course_id ) . '</a>';

					learn_press_get_template( 'students-list.php', array(
						'course' => $course,
						'limit'  => $student_limit,
						'filter' => $filter
					),
						learn_press_template_path() . '/addons/students-list/',
						LP_ADDON_STUDENTS_LIST_TEMPLATE
					);
				}

			} else {
				echo __( 'Please enter Course ID.', 'learnpress-students-list' );
			}

			echo $args['after_widget'];
		}

		/**
		 * @param array $instance
		 *
		 * @return string|void
		 */
		public function form( $instance ) {
			$title     = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Students List', 'learnpress-students-list' );
			$course_id = ! empty( $instance['course_id'] ) ? $instance['course_id'] : '';
			$number    = ! empty( $instance['number_student'] ) ? $instance['number_student'] : '';
			$filter    = ! empty( $instance['filter'] ) ? $instance['filter'] : '';
			?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'learnpress-students-list' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
                       value="<?php echo esc_attr( $title ); ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'course_id' ); ?>"><?php _e( 'Course ID:', 'learnpress-students-list' ); ?></label>
                <input style="width: 100%" type="text" value="<?php echo esc_attr( $course_id ); ?>"
                       id="<?php echo $this->get_field_id( 'course_id' ); ?>"
                       name="<?php echo $this->get_field_name( 'course_id' ); ?>">
            <div class="field-description">
                <i><?php echo esc_html__( 'Course IDs separated by commas (,)', 'learnpress-students-list' ) ?></i>
            </div>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'number_student' ); ?>"><?php _e( 'Number students to show:', 'learnpress-students-list' ); ?></label>
                <input type="number" class="tiny-text" size="3" min="1" step="1"
                       value="<?php echo esc_attr( $number ); ?>"
                       id="<?php echo $this->get_field_id( 'number_student' ); ?>"
                       name="<?php echo $this->get_field_name( 'number_student' ); ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'filter' ); ?>"><?php _e( 'Filter:', 'learnpress-students-list' ); ?>
                    <select class='widefat' id="<?php echo $this->get_field_id( 'filter' ); ?>"
                            name="<?php echo $this->get_field_name( 'filter' ); ?>" type="text">
						<?php
						$filters_students = learn_press_get_students_list_filter();

						if ( is_array( $filters_students ) ) {
							foreach ( $filters_students as $key => $_filter ) { ?>
                                <option value="<?php echo esc_attr( $key ) ?>" <?php echo ( $filter == $key ) ? ' selected' : ''; ?>><?php esc_html_e( $_filter, 'learnpress-students-list' ); ?></option>
							<?php }
						}
						?>
                    </select>
                </label>
            </p>
			<?php
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @param array $new_instance
		 * @param array $old_instance
		 *
		 * @return array
		 */
		public function update( $new_instance, $old_instance ) {
			$instance                   = array();
			$instance['title']          = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['course_id']      = ( ! empty( $new_instance['course_id'] ) ) ? strip_tags( $new_instance['course_id'] ) : '';
			$instance['number_student'] = ( ! empty( $new_instance['number_student'] ) ) ? strip_tags( $new_instance['number_student'] ) : '';
			$instance['filter']         = ( ! empty( $new_instance['filter'] ) ) ? strip_tags( $new_instance['filter'] ) : '';

			return $instance;
		}
	}
}

if ( ! function_exists( 'register_students_list_widget' ) ) {
	/**
	 * Register widget.
	 */
	function register_students_list_widget() {
		register_widget( 'LP_Students_List' );
	}
}

add_action( 'widgets_init', 'register_students_list_widget' );
