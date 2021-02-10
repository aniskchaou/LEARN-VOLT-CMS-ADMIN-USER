<?php

$grid_limit     = (int) $instance['grid-options']['grid_limit'];
$grid_column    = (int) $instance['grid-options']['grid_column'];
$taxonomy       = 'course_category';
$sub_categories = $instance['sub_categories'] ? '' : 0;
$grid_class     = '';
if ( $grid_column ) {
	$grid_class = 'columns-' . $grid_column;
}
$args_cat = array(
	'taxonomy' => $taxonomy,
	'parent'   => $sub_categories
);
?>
<?php if ( $instance['title'] ) {
	echo ent2ncr( $args['before_title'] . $instance['title'] . $args['after_title'] );
} ?>

<?php
$cats = get_categories( $args_cat );
?>
<ul class="<?php echo esc_attr( $grid_class ) ?>">
	<?php
	$i = 1;
	foreach ( $cats as $category ) {
		?>
        <li>
            <a href="<?php echo esc_url( get_term_link( $category->term_id ) ); ?>">
				<?php
				$alt  = '';
				$icon = array();
				if ( get_term_meta( $category->term_id, 'thim_learnpress_cate_icon', true ) ) {
					$icon = get_term_meta( $category->term_id, 'thim_learnpress_cate_icon', true );
				}
				if ( ! empty( $icon ) ) {
					$alt = get_post_meta( $icon['id'], '_wp_attachment_image_alt', true ) ? get_post_meta( $icon['id'], '_wp_attachment_image_alt', true ) : $category->name;
					if ( is_array( $icon ) ) {
						echo '<img alt="' . $alt . '" src="' . $icon['url'] . '">';
					}
				}
				?>
				<?php echo $category->name; ?>
            </a>
        </li>
		<?php
		if ( $i == $grid_limit ) {
			break;
		}
		$i ++;
	}
	?>
</ul>
