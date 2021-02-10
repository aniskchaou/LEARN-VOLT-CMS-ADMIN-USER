<?php
/**
 * Admin View: Withdraw details Meta box
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();
?>

<?php
global $post;
$post_id    = $post->ID;
$withdrawal = new LP_Withdrawal( $post_id );
$wd_status  = $withdrawal->get_status();

$datetime_format  = 'H:i:s d/m/Y';
$time_request     = $withdrawal->get_time_request();
$time_request_str = $time_request->format( $datetime_format );
$time_resolve     = $withdrawal->get_time_resolve();
$time_resolve_str = '-:-:- -/-/-';
if ( $time_resolve ) {
	$time_resolve_str = $time_resolve->format( $datetime_format );
}
$method_title = $withdrawal->get_title_method_payment();
$method_key   = $withdrawal->get_key_method_payment();
?>

<h1><?php echo esc_html( $post->post_title ); ?></h1>
<br>

<table class="widefat">
    <thead>
    <tr>
        <th><?php _e( 'ID', 'learnpress-commission' ); ?></th>
        <th><?php _e( 'Time request', 'learnpress-commission' ); ?></th>
        <th><?php _e( 'Time resolve', 'learnpress-commission' ); ?></th>
        <th><?php _e( 'Amount', 'learnpress-commission' ); ?></th>
        <th><?php _e( 'Method', 'learnpress-commission' ); ?></th>
        <th><?php _e( 'Status', 'learnpress-commission' ); ?></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><?php echo $post_id; ?></td>
        <td><?php echo $time_request_str; ?></td>
        <td><?php echo $time_resolve_str; ?></td>
        <td><?php echo esc_html( $withdrawal->get_value() . learn_press_get_currency_symbol() ); ?></td>
        <td><?php echo esc_html( $method_title ); ?>
            (<?php echo esc_html( $withdrawal->post->post_content ); ?>)
        </td>
        <td><?php echo $withdrawal->get_title_status(); ?></td>
    </tr>
    </tbody>
</table>
<div class="paid">
    <div class="check_user_pass">
        <label><?php _e( 'Username', 'learnpress-commission' ); ?></label>
        <input type="text" name="check_username"/>
        <label><?php _e( 'Password', 'learnpress-commission' ); ?></label>
        <input type="password" name="check_password"/>
	    <?php if ( ! $withdrawal->is_resolve() ) { ?>
		    <?php if ( $method_key !== 'offline' ) { ?>
                <button id="lp_paid" type="submit"
                        class="button button-primary button-large"><?php _e( 'Pay On', 'learnpress-commission' ); ?></button>
		    <?php } else { ?>
                <button id="lp_complete" type="submit"
                        class="button button-primary button-large"><?php _e( 'Pay Off', 'learnpress-commission' ); ?></button>
		    <?php } ?>
	    <?php } ?>
        <input id="lp_input_status" name="lp_status" type="hidden" value="" data-reject="reject">
    </div>
</div>

<div class="log">
    <div>
		<?php $results = get_post_meta( $post_id, 'lp_payon_result' ); ?>
		<?php if ( ! empty( $results ) ) { ?>
            <div><h2><?php echo __( 'Logs', 'learnpress-commission' ); ?></h2></div>
			<?php foreach ( $results as $result ) {
				$obj = json_decode( $result, true );
				if ( isset( $obj['name'] ) && isset( $obj['message'] ) && isset( $obj['message'] ) ) { ?>
                    <div>
                        <div><strong><?php echo esc_html( $obj['name'] ); ?></strong></div>
                        <div><p><?php echo esc_html( $obj['message'] ); ?></p></div>
                    </div>
				<?php } ?>
                <div style="display:none;"><?php print_r( $result, true ); ?></div>
                <hr/>
			<?php } ?>
		<?php } ?>
    </div>
</div>
