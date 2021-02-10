<?php
/**
 * Template for displaying next/prev item in course.
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! isset( $prev_item ) && ! isset( $next_item ) ) {
	return;
}

if ( $prev_item && $next_item ) {
	$nav = 'all';
} elseif ( $prev_item ) {
	$nav = 'prev';
} else {
	$nav = 'next';
}
?>

<div class="course-item-nav" data-nav="<?php echo $nav; ?>">
	<?php if ( $prev_item ) : ?>
		<div class="prev">
			<p><?php echo esc_html_x( 'Prev', 'course-item-navigation', 'eduma' ); ?></p>
			<a href="<?php echo esc_url( $prev_item->get_permalink() ); ?>" class="course-item-nav-name"><?php echo esc_html( $prev_item->get_title() ); ?></a>
		</div>
	<?php endif; ?>

	<?php if ( $next_item ) : ?>
		<div class="next">
			<p><?php echo esc_html_x( 'Next', 'course-item-navigation', 'eduma' ); ?></p>
			<a href="<?php echo esc_url( $next_item->get_permalink() ); ?>" class="course-item-nav-name"><?php echo esc_html( $next_item->get_title() ); ?></a>
		</div>
	<?php endif; ?>
</div>


