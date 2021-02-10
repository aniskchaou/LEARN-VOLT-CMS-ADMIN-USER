<?php
/**
 * Template for displaying loop course of section.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/loop-section.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.0
 */

defined( 'ABSPATH' ) || exit();

if ( ! isset( $section ) ) {
	return;
}

$course = learn_press_get_the_course();

if ( ! apply_filters( 'learn-press/section-visible', true, $section, $course ) ) {
	return;
}

$user        = learn_press_get_current_user();
$user_course = $user->get_course_data( get_the_ID() );
$items       = $section->get_items();

?>
<li<?php $section->main_class(); ?> id="section-<?php echo $section->get_slug(); ?>"
									data-id="<?php echo $section->get_slug(); ?>"
									data-section-id="<?php echo $section->get_id(); ?>">
	<?php do_action( 'learn-press/before-section-summary', $section, $course->get_id() ); ?>
	<h4 class="section-header">
		<div class="section-left">
			<span class="section-toggle collapse"></span>
			<?php
			$title = $section->get_title();
			echo ! $title ? _x( 'Untitled', 'template title empty', 'learnpress' ) : $title;
			?>
			<span class="meta">
			<?php if ( $user->has_enrolled_course( $section->get_course_id() ) ) { ?>
				<span
					class="step"><?php printf( '%d/%d', $user_course->get_completed_items( '', false, $section->get_id() ), $section->count_items( '', true ) ); ?></span>
			<?php } else { ?>
				<span class="step"><?php printf( '%d', $section->count_items( '', true ) ); ?></span>
			<?php } ?>
				</span>
		</div>
	</h4>
	<?php $description = $section->get_description(); ?>

	<?php if ( $description ) : ?>
		<p class="section-desc"><?php echo $description; ?></p>
	<?php endif; ?>

	<?php do_action( 'learn-press/before-section-content', $section, $course->get_id() ); ?>

	<?php if ( ! $items ) : ?>
		<?php learn_press_display_message( __( 'No items in this section', 'learnpress' ) ); ?>
	<?php else : ?>

		<ul class="section-content">

			<?php foreach ( $items as $item ) : ?>
				<?php if ( $item->is_visible() ) :
					$post_type = str_replace( 'lp_', '', $item->get_item_type() );
					if ( empty( $count[$post_type] ) ) {
						$count[$post_type] = 1;
					} else {
						$count[$post_type] ++;
					}
					?>

					<li class="<?php echo implode( ' ', $item->get_class() ); ?>"
						data-id="<?php echo esc_attr( $item->get_id() ); ?>">

						<div class="meta-left">
							<?php do_action( 'learn_press_before_section_item_title', $item, $section, $course ); ?>
							<?php if ( $item->get_item_type() == 'lp_quiz' || $item->get_item_type() == 'lp_h5p' ) { ?>
								<div
									class="index"><?php echo '<span class="label">' . esc_html__( 'Quiz', 'eduma' ) . '</span>' . $section->get_position() . '.' . $count[$post_type]; ?></div>
							<?php } elseif ( $item->get_item_type() == 'lp_assignment' ) { ?>
								<div
									class="index"><?php echo '<span class="label">' . esc_html__( 'Assignment', 'eduma' ) . '</span>' . $section->get_position() . '.' . $count[$post_type]; ?></div>
							<?php } else { ?>
								<div
									class="index"><?php echo '<span class="label">' . esc_html__( 'Lecture', 'eduma' ) . '</span>' . $section->get_position() . '.' . $count[$post_type]; ?></div>
							<?php } ?>
						</div>
						<?php $item_link = $user->can_view_item( $item->get_id() ) ? $item->get_permalink() : 'javascript:void(0);'; ?>

						<a class="<?php echo $post_type; ?>-title course-item-title button-load-item"
						   href="<?php echo apply_filters( 'learn-press/section-item-permalink', $item_link, $item, $section, $course ); ?>">
							<?php
							do_action( 'learn-press/before-section-loop-item-title', $item, $section, $course );

							learn_press_get_template(
								'single-course/section/' . $item->get_template(),
								array(
									'item'    => $item,
									'section' => $section,
								)
							); ?>
						</a>
						<?php
						do_action( 'learn-press/after-section-loop-item-title', $item, $section, $course );
						?>

						<?php do_action( 'learn-press/after-section-loop-item', $item, $section, $course ); ?>
					</li>

				<?php else : ?>
					<?php do_action( 'learn-press/section-loop-item-invisible', $item, $section, $course ); ?>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<?php do_action( 'learn-press/after-section-summary', $section, $course->get_id() ); ?>
</li>
