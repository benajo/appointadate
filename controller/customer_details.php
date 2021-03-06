<?php
if (isset($_POST['join_customer']) || isset($_POST['edit_details'])) {

	$errorMessage  = validate_form($_POST['first_name'], "req", "First Name");
	$errorMessage .= validate_form($_POST['first_name'], "name", "First Name");
	$errorMessage .= validate_form($_POST['last_name'], "req", "Last Name");
	$errorMessage .= validate_form($_POST['last_name'], "name", "Last Name");
	$errorMessage .= validate_form($_POST['email'], "req", "Email");
	$errorMessage .= validate_form($_POST['email'], "email", "Email");

	// figure out if on the join page, because if not, then pass the customer id as a value to check the email against.
	if (isset($_POST['join_customer'])) {
		$errorMessage .= validate_customer_email($_POST['email']);
	}
	else {
		$errorMessage .= validate_customer_email($_POST['email'], $_SESSION['customer_id']);
	}

	$errorMessage .= validate_form($_POST['phone'], "req", "Phone");
	$errorMessage .= validate_form($_POST['phone'], "num_s", "Phone");
	$errorMessage .= validate_form($_POST['address_line_1'], "req", "Address Line 1");
	$errorMessage .= validate_form($_POST['address_line_1'], "alnum_s", "Address Line 1");
	$errorMessage .= validate_form($_POST['address_line_2'], "alnum_s", "Address Line 2");
	$errorMessage .= validate_form($_POST['city'], "req", "City");
	$errorMessage .= validate_form($_POST['city'], "alpha_s", "City");
	$errorMessage .= validate_form($_POST['county'], "req", "County");
	$errorMessage .= validate_form($_POST['county'], "alpha_s", "County");
	$errorMessage .= validate_form($_POST['postcode'], "req", "Postcode");
	$errorMessage .= validate_form($_POST['postcode'], "alnum_s", "Postcode");

	// this if ensures that when a customer is editing their details they are not required to set a new password
	// but when they are joining the website, or changing their password, we have to validate the entered password
	if (isset($_POST['join_customer']) || !empty($_POST['password1']) || !empty($_POST['password2'])) {
		$errorMessage .= validate_password($_POST['password1'], $_POST['password2']);

		$hash = password_hash($_POST['password1'], PASSWORD_DEFAULT);

		$errorMessage .= !password_verify($_POST['password1'], $hash) ? "Password hashing error, please try again.<br>" : "";
	}

	if (empty($errorMessage)) {
		$post = escape_post_data();

		// joining customers must be INSERTED into the DB
		if (isset($_POST['join_customer'])) {
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
				// set customer SESSION variables to log them in
				$_SESSION['customer_logged_in'] = true;
				$_SESSION['customer_id'] = $mysqli->insert_id;

				header("Location: ./customer_appointments.php");
				exit;
			}
			else {
				$errorMessage = "There has been an unexpected error, please try again.";
			}
		}
		// not joining so UPDATE their details
		else {
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
}
?>