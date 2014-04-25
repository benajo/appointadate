<?php
if (isset($_POST['update_business_details'])) {

	$errorMessage  = validate_form($_POST['name'], "req", "Business Name");
	$errorMessage .= validate_form($_POST['address_line_1'], "req", "Address Line 1");
	$errorMessage .= validate_form($_POST['address_line_2'], "req", "Address Line 2");
	$errorMessage .= validate_form($_POST['city'], "req", "City");
	$errorMessage .= validate_form($_POST['county'], "req", "County");
	$errorMessage .= validate_form($_POST['postcode'], "req", "Postcode");
	$errorMessage .= validate_form($_POST['latitude'], "req", "Latitude");
	$errorMessage .= validate_form($_POST['longitude'], "req", "Longitude");
	$errorMessage .= validate_form($_POST['contact_name'], "req", "Contact Name");
	$errorMessage .= validate_form($_POST['contact_phone'], "req", "Contact Phone");
	$errorMessage .= validate_form($_POST['contact_email'], "req", "Contact Email");

	if (empty($errorMessage)) {
		$post = escape_post_data();

		$sql = "UPDATE business SET
				name           = '{$post['name']}',
				address_line_1 = '{$post['address_line_1']}',
				address_line_2 = '{$post['address_line_2']}',
				city           = '{$post['city']}',
				county         = '{$post['county']}',
				postcode       = '{$post['postcode']}',
				latitude       = '{$post['latitude']}',
				longitude      = '{$post['longitude']}',
				contact_name   = '{$post['contact_name']}',
				contact_phone  = '{$post['contact_phone']}',
				contact_email  = '{$post['contact_email']}',
				description    = '{$post['description']}',
				updated        = NOW()
				WHERE business_id = '{$_SESSION['staff_business_id']}'";
		$result = $mysqli->query($sql);

		if ($result) {
			$message = "Business details have been updated.";

			unset($_POST);
		}
		else {
			$errorMessage = "There has been an unexpected error, please try again.";
		}
	}
}
?>