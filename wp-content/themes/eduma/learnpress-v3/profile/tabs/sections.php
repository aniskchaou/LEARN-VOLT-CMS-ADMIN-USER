<?php
/**
 * Template for displaying sections in the top of user profile tab content.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/tabs/sections.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$profile = LP_Profile::instance();


if ( ! isset( $tab_key, $tab_data ) ) {
	return;
}

if ( empty( $tab_data['sections'] ) ) {
	return;
}

$link = $profile->get_tab_link( $tab_key ); ?>

<div id="your-profile">
    <ul class="learn-press-subtabs">

        <?php foreach ( $tab_data['sections'] as $section_key => $section_data ) {

            if ( $profile->is_hidden( $section_data ) ) {
                continue;
            }

            $classes = array( 'section-tab', esc_attr( $section_key ) );
            if ( $profile->is_current_section( $section_key, $section_key ) ) {
                $classes[] = 'current';
            }

            $section_slug = $profile->get_slug( $section_data, $section_key );
            $section_link = $profile->get_tab_link( $tab_key, $section_slug );

	        // Rewrite url if use WPML in profile page
	        if ( function_exists('icl_object_id') ) {
		        $section_link = apply_filters( 'wpml_permalink', $section_link , ICL_LANGUAGE_CODE );
	        }

            ?>

            <li class="<?php echo join( ' ', $classes ); ?>">
                <?php if ( $profile->is_current_section( $section_key, $section_key ) ) { ?>
                    <span><?php echo $section_data['title']; ?></span>
                <?php } else { ?>
                    <a href="<?php echo $section_link; ?>"><?php echo $section_data['title']; ?></a>
                <?php } ?>
            </li>

        <?php } ?>

    </ul>
</div>

