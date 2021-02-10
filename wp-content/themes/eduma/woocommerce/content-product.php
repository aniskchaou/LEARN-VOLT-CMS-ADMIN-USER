<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.6.0
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

global $woocommerce_loop, $theme_options_data, $wp_query;

// color theme options
$cat_obj = $wp_query->get_queried_object();

if ( isset( $cat_obj->term_id ) ) {
	$cat_id = $cat_obj->term_id;
} else {
	$cat_id = '';
}
$thim_custom_column = get_term_meta( $cat_id, 'thim_custom_column', true );

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

// Increase loop count
$woocommerce_loop['loop'] ++;
$column_product = 4;

if ( '' != $thim_custom_column ) {
	$column_product = 12 / $thim_custom_column;
} else {
	if ( ! empty( $woocommerce_loop['columns'] ) && ! is_post_type_archive( 'product' ) && ! defined( 'DOING_AJAX' ) ) {
		$column_product = 12 / $woocommerce_loop['columns'];
	} else {
		if ( ! empty( $theme_options_data['thim_woo_product_column'] ) ) {
			$thim_custom_column = $theme_options_data['thim_woo_product_column'];
			$column_product     = 12 / $theme_options_data['thim_woo_product_column'];
		}
	}
}
if ( '2.4' == $column_product ) {
	$column_product = 'col-5';
}
// Extra post classes
$classes   = array();
$classes[] = 'col-md-' . $column_product . ' col-sm-6 col-xs-6';
?>
<li <?php post_class( $classes ); ?>>
    <div class="content__product">
		<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
        <div class="product_thumb">
			<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title' );
			?>
			<?php
			if ( isset( $theme_options_data['thim_woo_set_show_qv'] ) && '1' == $theme_options_data['thim_woo_set_show_qv'] ) {
				wp_enqueue_script( 'magnific-popup');
				wp_enqueue_script( 'flexslider' );
				wp_enqueue_script( 'wc-add-to-cart-variation' );
				echo '<div class="quick-view" data-prod="' . esc_attr( get_the_ID() ) . '"><a href="javascript:;"><i class="fa fa-search fa-fw"></i></a></div>';
			}
			?>
            <a href="<?php echo get_the_permalink(); ?>" class="link-images-product"></a>
        </div>


        <div class="product__title">
            <a href="<?php echo get_the_permalink(); ?>" class="title"><?php the_title(); ?></a>
            <div class="description">
				<?php echo apply_filters( 'woocommerce_short_description', thim_excerpt( 30 ) ); ?>
            </div>
			<?php
			/**
			 * woocommerce_after_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
			?>
			<?php

			/**
			 * woocommerce_after_shop_loop_item hook
			 *
			 * @hooked woocommerce_template_loop_add_to_cart - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item' );

			?>
        </div>
    </div>
</li>
