<?php

global $post;

$limit           = $instance['limit'];
$columns         = $instance['grid-options']['columns'];
$view_all_course = ( $instance['view_all_courses'] && '' != $instance['view_all_courses'] ) ? $instance['view_all_courses'] : false;
$sort            = $instance['order'];
$featured        = !empty( $instance['featured'] ) ? true : false ;

$condition = array(
	'post_type'           => 'lp_course',
	'posts_per_page'      => $limit,
	'ignore_sticky_posts' => true,
);

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
    $post_in = eduma_lp_get_popular_courses( $limit );
 	$condition['post__in'] = $post_in;
	$condition['orderby']  = 'post__in';
}

if( $featured ) {
	$condition['meta_query'] = array(
		array(
			'key' => '_lp_featured',
			'value' =>  'yes',
		)
	);
}

$the_query = new WP_Query( $condition );

if ( $the_query->have_posts() ) :
	if ( $instance['title'] ) {
		echo ent2ncr( $args['before_title'] . $instance['title'] . $args['after_title'] );
	}
	?>
	<div class="thim-course-megamenu">
		<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
            <?php _learn_press_count_users_enrolled_courses( array( $post->ID ) ); ?>
			<div class="lpr_course <?php echo 'course-grid-' . $columns; ?>">
				<div class="course-item">
					<?php
					echo '<div class="course-thumbnail">';
					echo '<a class="thumb" href="' . esc_url( get_the_permalink() ) . '" >';
					echo thim_get_feature_image( get_post_thumbnail_id( $post->ID ), 'full', apply_filters( 'thim_course_thumbnail_width', 450 ), apply_filters('thim_course_thumbnail_height', 450), get_the_title() );
					echo '</a>';
					echo '</div>';
					?>
					<div class="thim-course-content">
						<h2 class="course-title">
							<a href="<?php echo esc_url( get_the_permalink() ); ?>"> <?php echo get_the_title(); ?></a>
						</h2>

						<div class="course-meta">
							<?php learn_press_courses_loop_item_price(); ?>
						</div>
						<?php
						echo '<a class="course-readmore" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Read More', 'eduma' ) . '</a>';
						?>
					</div>
				</div>
			</div>
			<?php
		endwhile;
		?>
	</div>
	<?php

endif;

wp_reset_postdata();
