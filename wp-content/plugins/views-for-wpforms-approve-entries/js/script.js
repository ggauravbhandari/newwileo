(function ($) {
	$(function () {
		$(document).on("change", ".wpf_approve_entries_select", function (e) {
			var entryId = $(this).data("entryid");
			var formId = $(this).data("formid");

			var data = {
				action: "wpf_views_approve_entry",
				entry_id: entryId,
				approval_status: $(this).val(),
				form_id: formId,
			};

			jQuery.post(wpf_approve_entries.ajaxurl, data, function (response) {
				if (response.success) {
				}
			});
		});
	});
})(jQuery);
