<?php
/**
 * Template for displaying add-to-cart button
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 2.2
 */

defined( 'ABSPATH' ) || exit();

if ( ! isset( $course ) ) {
	return;
}

$course_id = $course->get_id();
?>
<?php if ( learn_press_is_course_archive() ) { ?>
	<input type="hidden" disabled="disabled" name="course_url"
		   value="<?php echo esc_attr( get_permalink( $course_id ) ); ?>"/>
<?php } ?>

<?php do_action( 'learn-press/before-add-course-to-cart-form' ); ?>

	<div class="wrap-btn-add-course-to-cart">
		<form name="form-add-course-to-cart" method="post">

			<?php do_action( 'learn-press/before-add-course-to-cart-button' ); ?>

			<input type="hidden" name="course-id" value="<?php echo esc_attr( $course->get_id() ); ?>"/>
			<input type="hidden" name="add-course-to-cart-nonce"
				   value="<?php echo wp_create_nonce( 'add-course-to-cart' ); ?>"/>

			<button class="lp-button btn-add-course-to-cart">
				<?php _e( 'Add to cart', 'learnpress-woo-payment' ); ?>
			</button>

			<?php do_action( 'learn-press/after-add-course-to-cart-button' ); ?>

		</form>
	</div>

<?php do_action( 'learn-press/after-add-course-to-cart-form' ); ?>