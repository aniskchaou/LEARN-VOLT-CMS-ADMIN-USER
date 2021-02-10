/**
 * LearnPress Content Drip Admin
 *
 * @since 3.0.0
 */

;(function ($) {
	var LP_Content_Drip_Admin = function () {

		function init() {
			$('.item-delay').on('change', '.delay-type', function () {
				$(this).parent().removeClass('immediately interval specific').addClass(this.value);
			});

			$(document).on('click', '.apply-quick-settings', function () {
				var $form = $(this).closest('.quick-settings-form'),
					start = parseFloat($form.find('input[name="start"]').val()),
					step = parseFloat($form.find('input[name="step"]').val()),
					type = $form.find('select[name="type"]').val(),
					$dripItems = $('.item-delay'),
					i = start;

				$dripItems.each(function () {
					var $item = $(this);

					$item.find('.delay-type').val('interval');
					$item.find('.delay-interval-0').val(i);
					$item.find('.delay-interval-1').val(type);
					$item.removeClass('immediately interval').addClass('interval');

					i += step;
				})
			}).on('click', '.quick-settings a', function (e) {
				e.preventDefault();
				var $settings = $(this).parent('.quick-settings');
				$settings.find('.quick-settings-form').toggle();
			}).on('click', '.close-quick-settings', function (e) {
				e.stopPropagation();
				$(this).closest('.quick-settings-form').hide();
			}).on('click', '#learn-press-reset-drip-items', function () {
				if (!confirm(lpContentDrip.confirm_reset_items)) {
					return;
				}
				$('.item-delay').each(function () {
					var $item = $(this);

					$item.find('.delay-type').val('immediately');
					$item.find('.delay-interval-0').val(0);
					$item.find('.delay-interval-1').val('minute');
					$item.removeClass('immediately interval specific').addClass('immediately');
				})
			}).on('keyup', '.learnpress_page_content-drip-items', function (e) {
				if (e.keyCode === 27) {
					$(this).find('.quick-settings-form').hide();
				}
			});

			if (typeof $.fn.datetimepicker !== 'undefined') {
				$('.delay-specific-datetimepicker').datetimepicker({
					'dateFormat': 'yy-mm-dd'
				});
			}

			$('.drip-prerequisite-items').select2({
				placeholder: lpContentDrip.prerequisite_placeholder
			});
		}

		init();
	};

	$(document).ready(function () {
		new LP_Content_Drip_Admin();
	})
})(jQuery);
