<?php

$show_count   = isset( $instance['list-options']['show_counts'] ) ? $instance['list-options']['show_counts'] : 0 ;
$hierarchical = isset( $instance['list-options']['hierarchical'] ) ? $instance['list-options']['hierarchical'] : true;
$taxonomy     = 'course_category';
$sub_categories = $instance['sub_categories'] ? '' : 0;
$args_cat = array(
	'show_count'   => $show_count,
	'hierarchical' => $hierarchical,
	'taxonomy'     => $taxonomy,
	'parent' => $sub_categories,
	'title_li'     => '',
);
?>
<?php if ( $instance['title'] ) {
	echo ent2ncr($args['before_title'] . $instance['title'] . $args['after_title']);
} ?>
<ul><?php wp_list_categories( $args_cat ); ?> </ul>