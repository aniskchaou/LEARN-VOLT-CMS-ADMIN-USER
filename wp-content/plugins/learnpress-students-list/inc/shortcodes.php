<?php
/**
 * Students list shortcode class.
 *
 * @author   ThimPress
 * @package  LearnPress/Students-List/Classes
 * @version  3.0.0
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LP_Abstract_Shortcode' ) ) {
	return;
}

if ( ! class_exists( 'LP_Students_List_Shortcode' ) ) {
	/**
	 * Class LP_Students_List_Shortcode.
	 */
	class LP_Students_List_Shortcode extends LP_Abstract_Shortcode {

		/**
		 * LP_Students_List_Shortcode constructor.
		 *
		 * @param mixed $atts
		 */
		public function __construct( $atts = '' ) {
			parent::__construct( $atts );
			$this->_atts = shortcode_atts(
				array(
					'title'     => '',
					'course_id' => '',
					'limit'     => '',
					'filter'    => ''
				),
				$this->_atts
			);
		}

		/**
         * Shortcode output.
         *
		 * @return mixed|string
		 */
		public function output() {
			$atts = $this->_atts;

			if ( $atts['title'] ) { ?>
                <h3 class="students-list-title"><?php echo esc_html( $atts['title'] ); ?></h3>
			<?php }

			if ( ! $atts['course_id'] ) {
				echo __( 'Please enter Course ID.', 'learnpress-students-list' );
			} else {
				$course_id  = esc_html( $atts['course_id'] );
				$course_ids = array();
				if ( strpos( $course_id, ',' ) ) {
					$course_ids = explode( ',', $course_id );
				} else {
					$course_ids[] = $course_id;
				}
				ob_start();

				foreach ( $course_ids as $course_id ) {

					$course = learn_press_get_course( $course_id );

					if ( ! $course ) {
						echo __( 'Course ID invalid, please check it again.', 'learnpress-students-list' );
					} else {
						$student_limit = $atts['limit'] ? ( $atts['limit'] > 0 ? $atts['limit'] : '-1' ) : '-1';
						$filter        = $atts['filter'] ? $atts['filter'] : 'all';

						echo esc_html__( 'Course: ', 'learnpress-students-list' ) . '<a href="' . get_permalink( $course_id ) . '">' . get_the_title( $course_id ) . '</a>';

						learn_press_get_template(
							'students-list.php',
							array(
								'course' => $course,
								'limit'  => $student_limit,
								'filter' => $filter
							),
							learn_press_template_path() . '/addons/students-list/',
							LP_ADDON_STUDENTS_LIST_TEMPLATE
						);
					}
				}

				return ob_get_clean();
			}

			return false;
		}
	}
}

new LP_Students_List_Shortcode();