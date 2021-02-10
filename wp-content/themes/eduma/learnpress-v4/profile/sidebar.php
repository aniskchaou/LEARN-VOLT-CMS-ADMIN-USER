<?php
/**
 * Template for displaying sidebar in user profile.
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit;
$profile = LP_Profile::instance();
$user    = $profile->get_user();
$lp_bio  = $user->get_data( 'description' );
?>
<aside id="profile-sidebar">

	<?php do_action( 'learn-press/user-profile-account' ); ?>

	<div class="lp-profile-user">
		<h4 class="lp-username">
			<?php echo $user->get_display_name(); ?>
		</h4>
 		<?php
		if ( ! empty( $lp_bio ) ) {
			echo '  <p class="lp-bio">' . esc_html( $lp_bio ) . ' </p>';
		}
		?>
 	</div>

	<?php do_action( 'learn-press/user-profile-tabs' ); ?>

</aside>


