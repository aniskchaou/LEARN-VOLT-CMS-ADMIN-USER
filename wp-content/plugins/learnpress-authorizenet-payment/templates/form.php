<?php
/**
 * Template for displaying Authorize.Net payment form.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/addons/authorizenet-payment/form.php.
 *
 * @author   ThimPress
 * @package  Learnpress-Authorizenet/Templates
 * @version  3.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();
?>

<div id="learn-press-authorizenet-payment-form">
    <div class="row-fluid">
        <div class="span6">
            <div class="control-group">
                <label for=""
                       class="control-label"><?php esc_html_e( 'Credit card type', 'learnpress-authorizenet-payment' ) ?>
                    <span style="color:#ff0000;">*</span></label>
                <div class="controls" style="margin-left:5px;">
                    <select id="learn-press-authorizenet-payment-activated"
                            name="learn-press-authorizenet-payment[activated]" disabled="disabled">
                        <option value="Visa"><?php _e( 'Visa', 'learnpress-authorizenet-payment' ); ?></option>
                        <option value="Mastercard"><?php _e( 'Mastercard', 'learnpress-authorizenet-payment' ); ?></option>
                        <option value="AmericanExpress"><?php _e( 'American express', 'learnpress-authorizenet-payment' ); ?></option>
                        <option value="Discover"><?php _e( 'Discover', 'learnpress-authorizenet-payment' ); ?></option>
                        <option value="DinersClub"><?php _e( 'Diners club', 'learnpress-authorizenet-payment' ); ?></option>
                        <option value="JCB"><?php _e( 'Aut jcb', 'learnpress-authorizenet-payment' ); ?></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="span6">
            <div class="control-group">
                <label for="cardexp"
                       class="control-label"><?php _e( 'Expiration date (MM/YY)', 'learnpress-authorizenet-payment' ) ?>
                    <span style="color:#ff0000;">*</span></label>
                <div class="controls" style="margin-left:5px;">
                    <select id="learn-press-authorizenet-payment-expmonth"
                            name="learn-press-authorizenet-payment[expmonth]" class="inputbox required" tabindex="2"
                            disabled="disabled">
                        <option value=""><?php _e( 'Month', 'learnpress-authorizenet-payment' ); ?></option>
                        <option value="01"><?php _e( 'January', 'learnpress-authorizenet-payment' ); ?> (01)</option>
                        <option value="02"><?php _e( 'February', 'learnpress-authorizenet-payment' ); ?> (02)</option>
                        <option value="03"><?php _e( 'March', 'learnpress-authorizenet-payment' ); ?> (03)</option>
                        <option value="04"><?php _e( 'April', 'learnpress-authorizenet-payment' ); ?> (04)</option>
                        <option value="05"><?php _e( 'May', 'learnpress-authorizenet-payment' ); ?> (05)</option>
                        <option value="06"><?php _e( 'June', 'learnpress-authorizenet-payment' ); ?> (06)</option>
                        <option value="07"><?php _e( 'July', 'learnpress-authorizenet-payment' ); ?> (07)</option>
                        <option value="08"><?php _e( 'August', 'learnpress-authorizenet-payment' ); ?> (08)</option>
                        <option value="09"><?php _e( 'September', 'learnpress-authorizenet-payment' ); ?> (09)</option>
                        <option value="10"><?php _e( 'October', 'learnpress-authorizenet-payment' ); ?> (10)</option>
                        <option value="11"><?php _e( 'November', 'learnpress-authorizenet-payment' ); ?> (11)</option>
                        <option value="12"><?php _e( 'December', 'learnpress-authorizenet-payment' ); ?> (12)</option>
                    </select>&nbsp;
                    <select id="learn-press-authorizenet-payment-expyear"
                            name="learn-press-authorizenet-payment[expyear]" style="width:80px;"
                            class="inputbox required" tabindex="3" disabled="disabled">
                        <option value=""><?php _e( 'Year', 'learnpress-authorizenet-payment' ); ?></option>
						<?php
						$expyear = date( 'Y' );
						for ( $i = date( 'Y' ); $i < ( date( 'Y' ) + 10 ); $i ++ ): ?>
                            <option value="<?php esc_attr_e( $i ); ?>" <?php echo( $expyear == $i ? 'selected' : '' ); ?>><?php esc_html_e( $i ); ?></option>
						<?php
						endfor; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span6">
            <div class="control-group">
                <label for="cardnum"
                       class="control-label"><?php _e( 'Card number', 'learnpress-authorizenet-payment' ) ?>
                    <span style="color:#ff0000;">*</span></label>
                <div class="controls" style="margin-left:5px;">
                    <input class="inputbox required" id="learn-press-authorizenet-payment-cardnum" type="text"
                           name="learn-press-authorizenet-payment[cardnum]" tabindex="4" size="35" style="width:190px;"
                           value="" disabled="disabled"/>
                </div>
            </div>
        </div>
        <div class="span6">
            <div class="control-group">
                <label for="cardcvv"
                       class="control-label"><?php _e( 'Card cvv number', 'learnpress-authorizenet-payment' ) ?>
                    <span style="color:#ff0000;">*</span></label>
                <div class="controls" style="margin-left:5px;">
                    <input class="inputbox required" id="learn-press-authorizenet-payment-cardcvv" type="text"
                           name="learn-press-authorizenet-payment[cardcvv]" tabindex="5" maxlength="5" size="10"
                           style="width:50px;" value="" disabled="disabled"/>
                </div>
            </div>
        </div>
    </div>
</div>
