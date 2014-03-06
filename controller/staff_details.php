<?php
if (isset($_POST['add_details']) || isset($_POST['edit_details'])) {

	$errorMessage  = validate_form($_POST['first_name'], "req", "First Name");
	$errorMessage .= validate_form($_POST['first_name'], "name", "First Name");
	$errorMessage .= validate_form($_POST['last_name'], "req", "Last Name");
	$errorMessage .= validate_form($_POST['last_name'], "name", "Last Name");
	$errorMessage .= validate_form($_POST['email'], "req", "Email");

	if (isset($_POST['add_details'])) {
		$errorMessage .= validate_staff_email($_POST['email']);
	}
	else {
		$errorMessage .= validate_staff_email($_POST['email'], $_SESSION['staff_id']);
	}

	if (isset($_POST['add_details']) || !empty($_POST['password1']) || !empty($_POST['password2'])) {
		$errorMessage .= validate_password($_POST['password1'], $_POST['password2']);

		$hash = password_hash($_POST['password1'], PASSWORD_DEFAULT);

		$errorMessage .= !password_verify($_POST['password1'], $hash) ? "Password hashing error, please try again.<br>" : "";
	}

	if (empty($errorMessage)) {
		$post = escape_post_data($_POST);

		if (isset($_POST['add_details'])) {
			$sql = "INSERT INTO staff SET
					business_id    = '{$_SESSION['staff_business_id']}',
					first_name     = '{$post['first_name']}',
					last_name      = '{$post['last_name']}',
					email          = '{$post['email']}',
					password       = '{$hash}',
					pass_hint      = '{$post['pass_hint']}',
					admin          = '{$post['admin']}',
					created        = NOW(),
					updated        = NOW()";
			$result = $mysqli->query($sql);

			if ($result) {
				$message = "Staff has been created.";

				unset($_POST);
			}
			else {
				$errorMessage = "There has been an unexpected error, please try again.";
			}
		}
		else {
			$staffId = $mysqli->real_escape_string($_GET['staff']);

			$passUpdate = $post['password1'] ? "password = '{$hash}'," : "";

			$sql = "UPDATE staff SET
					first_name     = '{$post['first_name']}',
					last_name      = '{$post['last_name']}',
					email          = '{$post['email']}',
					{$passUpdate}
					pass_hint      = '{$post['pass_hint']}',
					admin          = '{$post['admin']}',
					updated        = NOW()
					WHERE staff_id = '{$staffId}'";
			$result = $mysqli->query($sql);

			if ($result) {
				$message = "Staff has been updated.";

				unset($_POST);
			}
			else {
				$errorMessage = "There has been an unexpected error, please try again.";
			}
		}
	}
}
?>