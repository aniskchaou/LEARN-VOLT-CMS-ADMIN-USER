<?php
global $post;
$limit        = $instance['limit'];
$item_visible = $instance['slider-options']['item_visible'];
$pagination   = $instance['slider-options']['show_pagination'] ? $instance['slider-options']['show_pagination'] : 0;
$navigation   = $instance['slider-options']['show_navigation'] ? $instance['slider-options']['show_navigation'] : 0;
$condition    = array(
	'post_type'           => 'lpr_course',
	'posts_per_page'      => $limit,
	'ignore_sticky_posts' => true,
);
$sort         = $instance['order'];

if ( $sort == 'category' && $instance['cat_id'] && $instance['cat_id'] != 'all' ) {
	if ( get_term( $instance['cat_id'], 'course_category' ) ) {
		$condition['tax_query'] = array(
			array(
				'taxonomy' => 'course_category',
				'field'    => 'term_id',
				'terms'    => $instance['cat_id']
			),
		);
	}
}


if ( $sort == 'popular' ) {
	global $wpdb;
	$the_query = $wpdb->get_results( $wpdb->prepare(
		"
		SELECT      p.ID, pm.meta_value AS student, pm2.meta_value AS setting
		FROM        $wpdb->posts AS p
		LEFT JOIN  $wpdb->postmeta AS pm ON p.ID = pm.post_id AND pm.meta_key = %s
		LEFT JOIN  $wpdb->postmeta AS pm2 ON p.ID = pm2.post_id AND pm2.meta_key = %s
		WHERE 		p.post_type = %s AND p.post_status = %s",
		'_lpr_course_user',
		'_lpr_course_student',
		'lpr_course',
		'publish'
	) );
	$temp      = array();
	foreach ( $the_query as $course ) {
		if ( unserialize( $course->student ) ) {
			$course->student = count( unserialize( $course->student ) );
		} else {
			$course->student = 0;
		}
		$temp[ $course->ID ] = $course->setting + $course->student;
	}
	arsort( $temp );
	$condition['post__in'] = array_keys( $temp );
	$condition['orderby']  = 'post__in';
}

$the_query = new WP_Query( $condition );

if ( $the_query->have_posts() ) :
	if ( $instance['title'] ) {
		echo ent2ncr( $args['before_title'] . $instance['title'] . $args['after_title'] );
	}

	$id = uniqid();
	?>
	<div class="thim-carousel-wrapper thim-course-carousel thim-course-grid" data-visible="<?php echo esc_attr( $item_visible ); ?>" data-pagination="<?php echo esc_attr( $pagination ); ?>" data-navigation="<?php echo esc_attr( $navigation ); ?>">
		<?php
		//$index = 1;
		while ( $the_query->have_posts() ) : $the_query->the_post();
			?>
			<div class="course-item">
				<?php
				echo '<div class="course-thumbnail">';
				echo '<a href="' . esc_url( get_the_permalink() ) . '" >';
				echo thim_get_feature_image( get_post_thumbnail_id( $post->ID ), 'full', apply_filters( 'thim_course_thumbnail_width', 450 ), apply_filters('thim_course_thumbnail_height', 450), get_the_title() );
				echo '</a>';
				thim_course_wishlist_button();
				echo '<a class="course-readmore" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Read More', 'eduma' ) . '</a>';
				echo '</div>';
				?>
				<div class="thim-course-content">
					<?php
					learn_press_course_instructor();
					?>
					<h2 class="course-title">
						<a href="<?php echo esc_url( get_the_permalink() ); ?>"> <?php echo get_the_title(); ?></a></h2>

					<div class="course-meta">
						<div class="course-student">
							<?php
							$student = learn_press_count_students_enrolled( $post->ID );
							?>
							<div class="value"><i class="fa fa-group"></i><?php echo esc_attr( $student ); ?></div>
						</div>

						<?php thim_course_ratings_count(); ?>

						<?php learn_press_course_price(); ?>
					</div>
				</div>
			</div>
			<?php
			//$index++ ;
		endwhile;
		?>
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
	<?php
endif;
wp_reset_postdata();

?>
