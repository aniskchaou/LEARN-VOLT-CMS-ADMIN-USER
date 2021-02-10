<?php
/**
 * Template for displaying withdrawal Paypal form.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/addons/commission/withdrawals-form-paypal.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Commission/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();
?>

<h3><?php echo __( 'Paypal Withdrawal', 'learnpress-commission' ); ?></h3>
<form action="" method="post">
	<ul class="lp-form-field-wrap">
		<li class="lp-form-field">
			<label class="lp-form-field-label"><?php _e( 'Enter your password', 'learnpress-commission' ); ?></label>
			<div class="lp-form-field-input">
				<input type="password" name="lp_withdrawals_secret_code" id="lp_withdrawals_secret_code" required="required" />
			</div>
		</li>
		<li class="lp-form-field">
			<label class="lp-form-field-label"><?php _e( 'Paypal email account', 'learnpress-commission' ); ?></label>
			<div class="lp-form-field-input">
				<input type="text" name="lp_withdrawals_email" id="lp_withdrawals_email" required="required" />
			</div>
		</li>
		<li class="lp-form-field">
			<label class="lp-form-field-label"><?php _e( 'Amount', 'learnpress-commission' ); ?> (<?php echo $currency; ?>)</label>
			<div class="lp-form-field-input">
				<input type="number" step="any" max="<?php echo esc_attr( $total ); ?>" 
					min="<?php echo esc_attr( $min ); ?>" name="lp_withdrawals"
					id="lp_withdrawals" data-all="<?php echo esc_attr( $total ); ?>"
					required="required" />
			</div>
		</li>
		<li class="lp-form-field">
			<div class="lp-form-field-input">
				<label for="lp_all" class="lp-form-field-label"><input type="checkbox" value="Request all" name="lp_all" id="lp_all"> <?php _e( 'Request all', 'learnpress-commission' ); ?></label>
			</div>
		</li>
		<li class="lp-form-field">
			<div class="lp-form-field-input">
				<button class="submit"><?php _e( 'Submit request', 'learnpress-commission' ); ?></button>
			</div>
		</li>
		<?php LP_Request_Withdrawal::nonce(); ?>
		<input type="hidden" name="lp_payment_method" value="paypal" />
	</ul>	
</form>