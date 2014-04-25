<?php
if (isset($_POST['add_business'])) {

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

	$errorMessage .= validate_form($_POST['first_name'], "req", "First Name");
	$errorMessage .= validate_form($_POST['last_name'], "req", "Last Name");
	$errorMessage .= validate_form($_POST['email'], "req", "Email");

	if (!empty($_POST['password1']) || !empty($_POST['password2'])) {
		$errorMessage .= validate_password($_POST['password1'], $_POST['password2']);

		$hash = password_hash($_POST['password1'], PASSWORD_DEFAULT);

		$errorMessage .= !password_verify($_POST['password1'], $hash) ? "Password hashing error, please try again.<br>" : "";
	}

	$errorMessage .= validate_form($_POST['pass_hint'], "req", "Password Hint");

	if (empty($errorMessage)) {
		$sql = "INSERT INTO business SET
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
				created        = NOW(),
				updated        = NOW()";
		$result = $mysqli->query($sql);

		if ($result) {
			$business_id = $mysqli->insert_id();
		}

		$sql = "INSERT INTO business_timetable SET
				business_id = '{$business_id}'";
		$result2 = $mysqli->query($sql);

		$sql = "INSERT INTO staff SET
				business_id = '{$business_id}',
				first_name  = '{$post['first_name']}',
				last_name   = '{$post['last_name']}',
				email       = '{$post['email']}',
				password    = '{$hash}',
				pass_hint   = '{$post['pass_hint']}',
				admin       = 1,
				created     = NOW(),
				updated     = NOW()";
		$result3 = $mysqli->query($sql);

		if ($result3) {
			$staff_id = $mysqli->insert_id();
		}

		$sql = "INSERT INTO staff_timetable SET
				staff_id = '{$staff_id}'";
		$result4 = $mysqli->query($sql);

		// if any of the queries fail, then any others that might have succeeded must be removed/undone
		if (!$result || !$result2 || !$result3 || !$result4) {
			$sql = "DELETE FROM business WHERE business_id = {$business_id}";
			$result = $mysqli->query($sql);

			$sql = "DELETE FROM business_timetable WHERE business_id = {$business_id}";
			$result = $mysqli->query($sql);

			$sql = "DELETE FROM staff WHERE business_id = {$business_id} OR staff_id = {$staff_id}";
			$result = $mysqli->query($sql);

			$sql = "DELETE FROM staff_timetable WHERE staff_id = {$staff_id}";
			$result = $mysqli->query($sql);

			$errorMessage = "There has been an unexpected error, please try again.";
		}
		else {
			$_SESSION['staff_logged_in']   = true;
			$_SESSION['staff_id']          = $staff_id;
			$_SESSION['staff_admin']       = 1;
			$_SESSION['staff_business_id'] = $business_id;

			header("Location: ./staff_timetable.php");
			exit;
		}
	}
}
?>