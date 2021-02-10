<?php // Get Related Portfolio by Category
$number_related = apply_filters( 'thim_related_posts', 3 );
$related        = thim_get_related_posts( $post->ID, $number_related );
if ( $related->have_posts() ) {
	?>
	<section class="related-archive">
		<h3 class="single-title"><?php esc_html_e( 'You may also like', 'eduma' ); ?></h3>
		<?php
		echo '<div class="archived-posts">';
		echo '<div class="thim-carousel-wrapper" data-visible="3" data-itemtablet="2" data-itemmobile="1" data-pagination="1">';
		while ( $related->have_posts() ) {
			$related->the_post();
			if ( has_post_thumbnail() ) {
				?>
				<div <?php post_class(); ?>>
					<div class="category-posts clear">
						<?php echo thim_get_feature_image( get_post_thumbnail_id(), 'full', '300', '200' ); ?>
						<div class="rel-post-text">
							<h5>
								<a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"><?php the_title(); ?></a>
							</h5>
							<div class="date">
								<?php echo get_the_date( 'j F, Y' ); ?>
							</div>
						</div>
					</div>
				</div>
				<?php
			} else {
				?>
				<div <?php post_class(); ?>>
					<div class="category-posts clear">
						<div class="rel-post-text">
							<h5 class="title-related no-images">
								<a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"><?php the_title(); ?></a>
							</h5>

							<div class="date">
								<?php echo get_the_date( 'j F, Y' ); ?>
							</div>
						</div>
						<div class="des-related">
							<?php the_excerpt(); ?>
						</div>
					</div>
				</div>
				<?php
			}
		}
		wp_reset_postdata();
		echo '</div>';
        echo '</div>';
		?>
	</section><!--.related-->
<?php }