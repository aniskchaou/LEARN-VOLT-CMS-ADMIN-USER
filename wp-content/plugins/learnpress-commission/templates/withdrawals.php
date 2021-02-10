<?php
/**
 * Template for displaying withdrawals tab in profile page.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/addons/commission/withdrawals.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Commission/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

$profile = LP_Global::profile();
$user    = $profile->get_user();

/**
 * @var $notifications
 */
$total              = lp_commission_get_total_commission( $user->get_id() );
$currency           = learn_press_get_currency_symbol();
$min                = LPC()->get_commission_min();
$current_tab        = learn_press_get_current_profile_tab();
$histories          = LP_RW()->get_withdrawals_by_user_id( $user->get_id() );
$payment_methods    = LP_RW()->get_payment_methods();
$withdrawal_methods = LP_RW()->get_withdrawal_methods();
?>

    <h2><?php esc_html_e( 'Your commission', 'learnpress-commission' ); ?></h2>
    <p>
		<?php esc_html_e( 'Total:', 'learnpress-commission' ) ?>
        <span class="count"><?php echo learn_press_format_price( $total, true ); ?></span>
    </p>

<?php if ( key_exists( 'return', $notifications ) ) {
	if ( $notifications['return'] ) {
		learn_press_display_message( $notifications['msg'] );
	} else {
		learn_press_display_message( $notifications['msg'] . ' (' . $notifications['code'] . ')', 'error' );
	}
} ?>

    <h2><?php _e( 'Withdrawal History', 'learnpress-commission' ); ?></h2>
<?php if ( ! empty( $histories ) ) { ?>
    <table class="lp-list-table">
        <thead>
        <tr>
            <th><?php _e( 'ID', 'learnpress-commission' ); ?></th>
            <th><?php _e( 'Amount', 'learnpress-commission' ); ?> (<?php echo learn_press_get_currency_symbol(); ?>)
            </th>
            <th><?php _e( 'Time request', 'learnpress-commission' ); ?></th>
            <th><?php _e( 'Time resolve', 'learnpress-commission' ); ?></th>
            <th><?php _e( 'Method', 'learnpress-commission' ); ?></th>
            <th><?php _e( 'Status', 'learnpress-commission' ); ?></th>
        </tr>
        </thead>
        <tbody>
		<?php foreach ( $histories as $index => $history ) { ?>
			<?php $method = $history['method_title']; ?>
            <tr>
                <td>#<?php echo $history['ID']; ?></td>
                <td><?php echo esc_html( $history['value'] );
					learn_press_currency_positions() ?></td>
                <td><?php echo esc_html( $history['time_request'] ); ?></td>
                <td><?php echo esc_html( $history['time_resolve'] ); ?></td>
                <td><?php echo esc_html( $method ); ?></td>
                <td><?php echo esc_html( $history['status'] ); ?></td>
            </tr>
		<?php } ?>
        </tbody>
    </table>
<?php } ?>

    <h2><?php echo __( 'Withdrawal', 'learnpress-commission' ); ?></h2>
<?php if ( $total < $min ) { ?>
    <div><?php _e( 'You have not enough money to withdraw', 'learnpress-commission' ); ?></div>
<?php } else { ?>
	<?php foreach ( $withdrawal_methods as $method_key => $method_title ) { ?>
        <a href="javascript: showWithdrawalForm('<?php echo esc_attr( $method_key ); ?>');"><?php echo esc_html( $method_title ); ?></a>
	<?php } ?>
	<?php foreach ( $withdrawal_methods as $method_key => $method_title ) { ?>
		<?php $html = LP_RW()->get_withdrawal_form( $method_key, $total, $min, $currency ); ?>
        <div class="withdrawal_form_wraper <?php echo $method_key; ?>" style="display: none;">
			<?php echo $html; ?>
        </div>
	<?php } ?>
<?php } ?>