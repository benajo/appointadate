<?php
if (isset($_POST['customer_join'])) {
	$_POST['email']="Benasd";
	$errorMessage  = validate_form($_POST['first_name'], "req", "First Name");
	$errorMessage .= validate_form($_POST['first_name'], "name", "First Name");
	$errorMessage .= validate_form($_POST['last_name'], "req", "Last Name");
	$errorMessage .= validate_form($_POST['last_name'], "name", "Last Name");
	$errorMessage .= validate_form($_POST['email'], "req", "Email");
	$errorMessage .= !empty($_POST['email']) ? validate_customer_email($_POST['email']) : "";
	$errorMessage .= validate_form($_POST['phone'], "req", "Phone");
	$errorMessage .= validate_form($_POST['phone'], "num_s", "Phone");
	$errorMessage .= validate_form($_POST['address_line_1'], "req", "Address Line 1");
	$errorMessage .= validate_form($_POST['city'], "req", "City");
	$errorMessage .= validate_form($_POST['county'], "req", "County");
	$errorMessage .= validate_form($_POST['postcode'], "req", "Postcode");
	$errorMessage .= validate_password($_POST['password1'], $_POST['password2']);

	$hash = password_hash($_POST['password1'], PASSWORD_DEFAULT);

	$errorMessage .= !password_verify($_POST['password1'], $hash) ? "Password hashing error, please try again.<br>" : "";

	if (empty($errorMessage)) {
		$post = escape_post_data($_POST);

		$sql = "INSERT INTO customer SET
				first_name     = '{$post['first_name']}',
				last_name      = '{$post['last_name']}',
				phone          = '{$post['phone']}',
				email          = '{$post['email']}',
				password       = '{$hash}',
				pass_hint      = '{$post['pass_hint']}',
				address_line_1 = '{$post['address_line_1']}',
				address_line_2 = '{$post['address_line_2']}',
				city           = '{$post['city']}',
				county         = '{$post['county']}',
				postcode       = '{$post['postcode']}',
				created        = NOW(),
				updated        = NOW()";
		$result = $mysqli->query($sql);

		if ($result) {
			$_SESSION['customer_logged_in'] = true;
			$_SESSION['customer_id'] = $mysqli->insert_id;

			header("Location: ./appointments.php");
			exit;
		}
		else {
			$errorMessage = "There has been an unexpected error, please try again.";
		}
	}
}
?>