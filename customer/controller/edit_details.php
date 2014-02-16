<?php
if (isset($_POST['edit_details'])) {
	$errorMessage  = form_validate($_POST['first_name'], "req", "First Name");
	$errorMessage .= form_validate($_POST['first_name'], "name", "First Name");
	$errorMessage .= form_validate($_POST['last_name'], "req", "Last Name");
	$errorMessage .= form_validate($_POST['last_name'], "name", "Last Name");
	$errorMessage .= form_validate($_POST['email'], "req", "Email");
	$errorMessage .= form_validate($_POST['phone'], "req", "Phone");
	$errorMessage .= form_validate($_POST['phone'], "num_s", "Phone");
	$errorMessage .= form_validate($_POST['address_line_1'], "req", "Address Line 1");
	$errorMessage .= form_validate($_POST['city'], "req", "City");
	$errorMessage .= form_validate($_POST['county'], "req", "County");
	$errorMessage .= form_validate($_POST['postcode'], "req", "Postcode");
	$errorMessage .= $_POST['password1'] || $_POST['password2'] ? password_validate($_POST['password1'], $_POST['password2']) : "";

	$hash = password_hash($_POST['password1'], PASSWORD_DEFAULT);

	$errorMessage .= !password_verify($_POST['password1'], $hash) ? "Password hashing error, please try again.<br>" : "";

	if (empty($errorMessage)) {
		$post = escape_post_data($_POST);

		$passUpdate = $post['password1'] ? "password = '{$hash}'," : "";

		$sql = "UPDATE customer SET
				first_name     = '{$post['first_name']}',
				last_name      = '{$post['last_name']}',
				phone          = '{$post['phone']}',
				email          = '{$post['email']}',
				{$passUpdate}
				pass_hint      = '{$post['pass_hint']}',
				address_line_1 = '{$post['address_line_1']}',
				address_line_2 = '{$post['address_line_2']}',
				city           = '{$post['city']}',
				county         = '{$post['county']}',
				postcode       = '{$post['postcode']}',
				updated        = NOW()
				WHERE customer_id = {$_SESSION['customer_id']}";
		$result = $mysqli->query($sql);

		if ($result) {
			$message = "Your details have been updated.";
		}
		else {
			$errorMessage = "There has been an unexpected error, please try again.";
		}
	}
}
?>