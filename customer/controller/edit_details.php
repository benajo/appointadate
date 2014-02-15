<?php
if (isset($_POST['edit_details'])) {
	$errorMessage  = form_validate($_POST['first_name'], "req", "First Name");
	$errorMessage .= form_validate($_POST['first_name'], "name", "First Name");
	$errorMessage .= form_validate($_POST['last_name'], "req", "Last Name");
	$errorMessage .= form_validate($_POST['last_name'], "name", "Last Name");
	$errorMessage .= form_validate($_POST['email'], "req", "Email");
	$errorMessage .= form_validate($_POST['phone'], "alnum_s", "Phone");
	$errorMessage .= form_validate($_POST['address_line_1'], "req", "Address Line 1");
	$errorMessage .= form_validate($_POST['address_line_2'], "req", "Address Line 2");
	$errorMessage .= form_validate($_POST['city'], "req", "City");
	$errorMessage .= form_validate($_POST['county'], "req", "County");
	$errorMessage .= form_validate($_POST['postcode'], "req", "Postcode");

	$errorMessage .= $_POST['password1'] || $_POST['password2'] ? password_validate($_POST['password1'], $_POST['password2']) : "";

	$errorMessage .= form_validate($_POST['pass_hint'], "req", "Password Hint");

}
?>