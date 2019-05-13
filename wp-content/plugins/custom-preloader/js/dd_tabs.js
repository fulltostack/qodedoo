$('#advanced a').click(function () {
	var collapse_content_selector = $(this).attr('href');
	var toggle_switch = $(this);
	$(collapse_content_selector).slideToggle(function () {
		$(this).is(':visible')? toggle_switch.text('Close Positions') : toggle_switch.text('Positions');
	});
});
	