(function ($) {

	$(document).on('ready', function () {

		var filter = 'all',
			user_process = $('.course-students-list .students-enrolled'),
			additional_student = $('.additional-students');
		$('.students-list-filter').change(function () {

				var filter = ($(this)).val();

				if (filter === 'all') {
					user_process.css('display', 'inline-block');
					additional_student.css('display', 'block');
				}
				else {
					additional_student.css('display', 'none');
					$.each(user_process, function () {
						if (!$(this).hasClass(filter)) {
							$(this).css('display', 'none');
						} else {
							$(this).css('display', 'inline-block');
						}
					});
				}
			}
		);

	});
})(jQuery);