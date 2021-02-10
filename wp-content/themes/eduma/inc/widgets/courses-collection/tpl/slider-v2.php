<?php
global $post;

$limit         = $instance['limit'] ? $instance['limit'] : 4;
$columns       = $instance['columns'] ? $instance['columns'] : 3;
$feature_items = $instance['feature_items'] ? $instance['feature_items'] : 2;

$condition = array(
    'post_type'           => 'lp_collection',
    'posts_per_page'      => $limit,
    'ignore_sticky_posts' => true,
);

$features_html = $items_html = '';

$the_query = new WP_Query( $condition );

if ( $the_query->have_posts() ) :
    while ( $the_query->have_posts() ) : $the_query->the_post();

        ob_start();
        ?>
        <div class="item">
            <div class="thumbnail">
                <?php
                echo '<a href="' . esc_url( get_the_permalink() ) . '" >';
                echo thim_get_feature_image( get_post_thumbnail_id( $post->ID ), 'full', 300, 210, get_the_title() );
                echo '</a>';
                ?>
            </div>
            <div class="content">
                <h3><a class="title" href="<?php echo esc_url( get_the_permalink() ); ?>"> <?php echo get_the_title(); ?></a></h3>
                <?php echo thim_excerpt(10);?>
            </div>
        </div>
        <?php
        $items_html .= ob_get_contents();
        ob_end_clean();

    endwhile;

endif;

wp_reset_postdata();

if ( $instance['title'] ) {
    echo ent2ncr( $args['before_title'] . $instance['title'] . $args['after_title'] );
}
?>
<div class="thim-courses-collection">
    <div class="thim-carousel-wrapper thim-collection-carousel" data-visible="<?php echo esc_attr( $columns ); ?>"
         data-pagination="0" data-navigation="1" data-autoplay="0">
        <?php echo ent2ncr( $items_html ); ?>
    </div>

	<script type="text/javascript">
		jQuery(document).ready(function(){
			"use strict";

			jQuery('.thim-carousel-wrapper').each(function() {
				var item_visible = jQuery(this).data('visible') ? parseInt(
					jQuery(this).data('visible')) : 4,
					item_desktopsmall = jQuery(this).data('desktopsmall') ? parseInt(
						jQuery(this).data('desktopsmall')) : item_visible,
					itemsTablet = jQuery(this).data('itemtablet') ? parseInt(
						jQuery(this).data('itemtablet')) : 2,
					itemsMobile = jQuery(this).data('itemmobile') ? parseInt(
						jQuery(this).data('itemmobile')) : 1,
					pagination = !!jQuery(this).data('pagination'),
					navigation = !!jQuery(this).data('navigation'),
					autoplay = jQuery(this).data('autoplay') ? parseInt(
						jQuery(this).data('autoplay')) : false,
					navigation_text = (jQuery(this).data('navigation-text') &&
						jQuery(this).data('navigation-text') === '2') ? [
						'<i class=\'fa fa-long-arrow-left \'></i>',
						'<i class=\'fa fa-long-arrow-right \'></i>',
					] : [
						'<i class=\'fa fa-chevron-left \'></i>',
						'<i class=\'fa fa-chevron-right \'></i>',
					];

				jQuery(this).owlCarousel({
					items            : item_visible,
					itemsDesktop     : [1200, item_visible],
					itemsDesktopSmall: [1024, item_desktopsmall],
					itemsTablet      : [768, itemsTablet],
					itemsMobile      : [480, itemsMobile],
					navigation       : navigation,
					pagination       : pagination,
					lazyLoad         : true,
					autoPlay         : autoplay,
					navigationText   : navigation_text
				});
			});
		});
	</script>
</div>
