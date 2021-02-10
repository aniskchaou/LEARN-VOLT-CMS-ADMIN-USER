<?php
/**
 * Shopping Cart Widget
 *
 * Displays shopping cart widget
 *
 * @author        WooThemes
 * @category      Widgets
 * @package       WooCommerce/Widgets
 * @version       2.0.0
 * @extends       WP_Widget
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Thim_Custom_WC_Widget_Cart extends WC_Widget_Cart {

	var $woo_widget_cssclass;
	var $woo_widget_description;
	var $woo_widget_idbase;
	var $woo_widget_name;

	function widget( $args, $instance ) {

		$before_widget = $args['before_widget'];
		$after_widget  = $args['after_widget'];

		echo ent2ncr( $before_widget );

		if ( ! empty( $instance['title'] ) ) {
			echo ent2ncr( $args['before_title'] . $instance['title'] . $args['after_title'] );
		}

		list( $cart_items ) = thim_get_current_cart_info();
		$hide_if_empty = empty( $instance['hide_if_empty'] ) ? 0 : 1;
		if ( ! $hide_if_empty || $cart_items ) {
			echo '<a class="minicart_hover" id="header-mini-cart" href="' . get_permalink( wc_get_page_id( 'cart' ) ) . '">';
			echo '<span class="cart-items-number"><i class="fa fa-fw fa-shopping-cart"></i><span class="wrapper-items-number"><span class="items-number">' . $cart_items . '</span></span></span>';

			echo '<div class="clear"></div>';
			echo '</a>';
			// Insert cart widget placeholder - code in woocommerce.js will update this on page load
			echo '<div class="widget_shopping_cart_content" style="display: none;"></div>';
		}
		echo ent2ncr( $after_widget );
	}
}
