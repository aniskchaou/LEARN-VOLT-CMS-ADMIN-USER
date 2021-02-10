<?php
/**
 * Admin View: Withdraw actions Meta box
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
?>

<div class="submitbox" id="submitpost">
    <div id="minor-publishing">
        <div id="misc-publishing-actions">
            <div class="misc-pub-section">
                <select name="withdraw_status" id="lp_withdraw_status_select_box">
                    <option value="pending"<?php echo $wd_status == 'pending' ? 'selected="selected"' : ''; ?>><?php _e( 'Pending', 'learnpress-commission' ); ?></option>
                    <option value="reject"<?php echo $wd_status == 'reject' ? 'selected="selected"' : ''; ?>><?php _e( 'Reject', 'learnpress-commission' ); ?></option>
                    <option value="complete"<?php echo $wd_status == 'complete' ? 'selected="selected"' : ''; ?>><?php _e( 'Complete', 'learnpress-commission' ); ?></option>
                </select>
            </div>
        </div>
        <div id="major-publishing-actions">
            <div id="delete-action">
				<?php
				if ( current_user_can( "delete_post", $post->ID ) ) {
					if ( ! EMPTY_TRASH_DAYS ) {
						$delete_text = __( 'Delete Permanently' );
					} else {
						$delete_text = __( 'Move to Trash' );
					}
					?>
                    <a class="submitdelete deletion" href="<?php echo get_delete_post_link( $post->ID ); ?>">
						<?php echo $delete_text; ?>
                    </a>
				<?php } ?>
            </div>

            <div id="publishing-action">
                <span class="spinner"></span>
                <input name="original_publish" type="hidden" id="original_publish" value="Update">
                <input name="save" type="submit" class="button button-primary button-large" id="lp_withdraw_apply_btn" value="Update">
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
