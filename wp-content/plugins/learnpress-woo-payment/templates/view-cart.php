<?php
/**
 * Template button view cart woo
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 2.3
 */

defined( 'ABSPATH' ) || exit();

if ( ! isset( $course ) ) {
	return;
}
?>

<a class="btn-lp-course-view-cart" href="<?php echo wc_get_cart_url() ?>">
	<button class="lp-button"><?php _e( 'View cart', 'learnpress-woo-payment' ) ?></button>
</a>
