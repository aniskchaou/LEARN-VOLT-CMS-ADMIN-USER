jQuery(document).ready(function ($) {
	$('#lp_reject').on('click', function (e) {
		e.preventDefault();
		var $form = $('#post');
		var $status_input = $('#lp_input_status');
		var reject = $status_input.data('reject');
		$status_input.val(reject);
		$form.submit();
	});

	$('#lp_paid').on('click', function (e) {
		e.preventDefault();
		var $form = $('#post');
		var $status_input = $('#lp_input_status');
		//var reject = $status_input.data('reject');
		$status_input.val('payon');
		$form.submit();
	});


	$('#lp_withdraw_apply_btn').click(function(event){
		event.preventDefault();
		var $form = $('#post');
		var status = $('#lp_withdraw_status_select_box').val();
		$('#lp_input_status').val(status);
		$form.submit();
	});

});