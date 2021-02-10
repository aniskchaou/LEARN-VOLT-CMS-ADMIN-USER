<?php
/**
 * Class LP_Drip_Items_List_Table.
 *
 * @author  ThimPress
 * @package LearnPress/Content-Drip/Classes
 * @version 3.0.0
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

// WP_List_Table is not loaded automatically so we need to load it in our application
if ( !class_exists( 'WP_List_Table' ) ) {
	include_once ABSPATH . '/wp-admin/includes/class-wp-list-table.php';
}

if ( !class_exists( 'LP_Drip_Items_List_Table' ) ) {
	/**
	 * Class LP_Drip_Items_List_Table
	 */
	class LP_Drip_Items_List_Table extends WP_List_Table {

		/**
		 * @var LP_Course
		 */
		protected $course = null;

		/**
		 * LP_Drip_Items_List_Table constructor.
		 *
		 * @param $course_id
		 */
		public function __construct( $course_id ) {
			parent::__construct();

			$this->course = learn_press_get_course( $course_id );
			$this->prepare_items();
		}

		/**
		 * @return array
		 */
		public function get_columns() {
			ob_start(); ?>
			<div class="quick-settings">
				<a href=""></a>
				<div class="quick-settings-form">
					<p>
						<label><?php _e( 'Start', 'learnpress-content-drip' ); ?></label>
						<input type="number" name="start" value="1" min="0" max="100" step="0.5">
					</p>
					<p>
						<label><?php _e( 'Step', 'learnpress-content-drip' ); ?></label>
						<input type="number" name="step" value="1" min="0.5" max="100" step="0.5">
					</p>
					<p>
						<label><?php _e( 'Type', 'learnpress-content-drip' ); ?></label>
						<select name="type">
							<?php $intervals = learn_press_get_course_duration_support();
							foreach ( $intervals as $k => $v ) {
								echo sprintf( '<option value="%s">%s</option>', $k, $v );
							} ?>
						</select>
					</p>
					<p>
						<button class="button button-primary apply-quick-settings"
								type="button"><?php _e( 'Apply', 'learnpress-content-drip' ); ?></button>
						<button class="button close-quick-settings"
								type="button"><?php _e( 'Close', 'learnpress-content-drip' ); ?></button>
					</p>
				</div>
			</div>
			<?php
			$html = ob_get_clean();

			$columns = array(
				//'cb'    => '<input type="checkbox" />',
				'title'        => __( 'Item name', 'learnpress-content-drip' ),
				'type'         => __( 'Type', 'learnpress-content-drip' ),
				'prerequisite' => __( 'Prerequisite', 'learnpress-content-drip' ),
				'delay'        => __( 'Delay access', 'learnpress-content-drip' ) . $html
			);

			if ( get_post_meta( $this->course->get_id(), '_lp_content_drip_drip_type', true ) != 'prerequisite' ) {
				unset( $columns['prerequisite'] );
			}

			return $columns;
		}

		/**
		 * @param object $item
		 */
		public function column_cb( $item ) {
			echo '<input type="checkbox" name="items[]" value="' . $item . '">';
		}

		/**
		 * Prepare items.
		 */
		public function prepare_items() {
			$columns               = $this->get_columns();
			$hidden                = array();
			$sortable              = array();
			$this->_column_headers = array( $columns, $hidden, $sortable );
			$course                = learn_press_get_course( $this->course->get_id() );
			$items                 = $course->get_items();

			$this->items = array();
			if ( is_object( $items[0] ) ) {
				foreach ( $items as $item ) {
					$this->items[] = $item->item_id;
				}
			} elseif ( is_string( $items[0] ) ) {
				$this->items = $items;
			}

// 			$this->items = LP_Object_Cache::get( 'course-' . $this->course->get_id(), 'lp-course-items' );
// 			$this->items           = wp_cache_get( 'course-' . $this->course->get_id(), 'lp-course-items' );
		}

		/**
		 * @param string $which
		 */
		protected function extra_tablenav( $which ) { ?>
			<button class="button button-primary"><?php _e( 'Update', 'learnpress-content-drip' ); ?></button>
			<button class="button" type="button"
					id="learn-press-reset-drip-items"><?php _e( 'Reset', 'learnpress-content-drip' ); ?></button>
			<?php
		}

		/**
		 * @param $post
		 * @param $classes
		 * @param $data
		 * @param $primary
		 */
		protected function _column_title( $post, $classes, $data, $primary ) {
			echo '<td class="' . $classes . ' page-title" ', $data, '>';
			echo get_the_title( $post );
			echo $this->handle_row_actions( get_post( $post ), 'title', $primary );
			echo '</td>';
		}

		/**
		 * @param object $post
		 * @param string $column_name
		 * @param string $primary
		 *
		 * @return string
		 */
		protected function handle_row_actions( $post, $column_name, $primary ) {
			if ( $primary !== $column_name ) {
				return '';
			}

			$can_edit_post = current_user_can( 'edit_post', $post->ID );
			$actions       = array();

			if ( $can_edit_post && 'trash' != $post->post_status ) {
				$actions['edit'] = sprintf(
					'<a href="%s">%s</a>',
					get_edit_post_link( $post->ID ),
					__( 'Edit', 'learnpress-content-drip' )
				);
				$actions['view'] = sprintf(
					'<a href="%s">%s</a>',
					$this->course->get_item_link( $post->ID ),
					__( 'View', 'learnpress-content-drip' )
				);
			}

			return $this->row_actions( $actions );
		}

		/**
		 * @param object $item
		 * @param string $column_name
		 */
		public function column_default( $item, $column_name ) {
			RWMB_Datetime_Field::admin_enqueue_scripts();

			switch ( $column_name ) {
				case 'title':
					echo get_the_title( $item );
					break;
				case 'type':
					echo $this->get_item_type( $item );
					break;
				case 'prerequisite':
					$course_items = $this->items;
					if ( $course_items ) {
						$index       = array_search( $item, $course_items );
						$pre_item_id = $index ? $course_items[$index - 1] : 0;
						$drip_items  = get_post_meta( $this->course->get_id(), '_lp_drip_items', true );
						?>
						<select class="drip-prerequisite-items"
								name="item-delay[<?php echo esc_attr( $item ); ?>][prerequisite][]" multiple="multiple">
							<?php
							unset( $course_items[$index] );
							foreach ( $course_items as $course_item ) {
								$selected_item = isset( $drip_items[$item]['prerequisite'] ) ? $drip_items[$item]['prerequisite'] : $pre_item_id; ?>
								<?php
								$selected_val = ( is_array( $selected_item ) ? ( in_array( $course_item, $selected_item ) ? 'selected' : '' ) : $selected_item == $course_item ) ? 'selected' : '';
								?>
								<option
									value="<?php echo esc_attr( $course_item ); ?>" <?php echo $selected_val; ?>><?php echo get_the_title( $course_item ); ?></option>
							<?php }
							?>
						</select>
					<?php }
					break;
				case 'delay':
					$config = $this->_get_item_config( $item, $this->course->get_id() );

					$delay_types = array(
						'immediately' => __( 'Immediately', 'learnpress-content-drip' ),
						'interval'    => __( 'After...', 'learnpress-content-drip' ),
						'specific'    => __( 'Specific date', 'learnpress-content-drip' )
					); ?>
					<div class="item-delay <?php echo $config['type']; ?>">
						<select class="delay-type" name="item-delay[<?php echo $item; ?>][type]">
							<?php foreach ( $delay_types as $k => $v ) { ?>
								<option
									value="<?php echo $k; ?>" <?php selected( $k == $config['type'] ); ?>><?php echo $v; ?></option>
							<?php } ?>
						</select>
						<div class="delay-interval">
							<input type="number" class="delay-interval-0"
								   name="item-delay[<?php echo $item; ?>][interval][]"
								   value="<?php echo isset( $config['interval'] ) ? $config['interval'][0] : 0; ?>"
								   min="0"
								   step="0.5">
							<select class="delay-interval-1" name="item-delay[<?php echo $item; ?>][interval][]">
								<?php $intervals = learn_press_get_course_duration_support();
								foreach ( $intervals as $k => $v ) {
									echo sprintf( '<option value="%s" %s>%s</option>', $k, selected( $k == $config['interval'][1], true, false ), $v );
								} ?>
							</select>
						</div>

						<div class="delay-specific">
							<input type="text" class="delay-specific-datetimepicker"
								   name="item-delay[<?php echo $item; ?>][date]"
								   value="<?php echo isset( $config['date'] ) && is_int( $config['date'] ) ? date( 'Y-m-d H:i', $config['date'] ) : '' ?>">
						</div>
					</div>
					<?php
					break;
				default:
					echo "[$column_name]";
			}
		}

		/**
		 * @param $item
		 *
		 * @return string
		 */
		protected function get_item_type( $item ) {
			if ( $post_type = get_post_type_object( get_post_type( $item ) ) ) {
				return $post_type->labels->singular_name;
			}

			return __( 'Unknown Type', 'learnpress-content-drip' );
		}

		/**
		 * Get item data in list table.
		 *
		 * @param     $item
		 * @param int $course_id
		 *
		 * @return array|mixed
		 */
		private function _get_item_config( $item, $course_id = 0 ) {
			if ( !$course_id ) {
				$course_id = get_the_ID();
			}
			$drip_items = get_post_meta( $course_id, '_lp_drip_items', true );

			return
				is_array( $drip_items ) && !empty( $drip_items[$item] ) ? $drip_items[$item] : array(
					'type'     => 'immediately',
					'interval' => array( 0, 'minute' ),
					'date'     => time()
				);
		}
	}
}
?>