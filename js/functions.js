$(function() {
	$(".datepicker").datepicker({
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
});