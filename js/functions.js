$(function() {
	$(".datepicker").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "D, d M yy",
		showOtherMonths: true,
		selectOtherMonths: true
	});

	$(".datepicker-no-past").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "D, d M yy",
		showOtherMonths: true,
		selectOtherMonths: true,
		minDate: 0
	});

	$(".confirm-delete").click(function() {
		if (!confirm("Are you sure?")) {
			return false;
		};
	});

	$('table.column-highlighter td.td-highlight').hover(function() {
	    var t = parseInt($(this).index()) + 1;
	    $('td:nth-child(' + t + ')').addClass('highlight');
	},
	function() {
	    var t = parseInt($(this).index()) + 1;
	    $('td:nth-child(' + t + ')').removeClass('highlight');
	});
});