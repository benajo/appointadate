<?php
if (isset($_POST['login'])) {
	$errorMessage  = validate_form($_POST['loginEmail'], "req", "Email");
	$errorMessage .= validate_form($_POST['loginEmail'], "email", "Email");
	$errorMessage .= validate_form($_POST['loginPassword'], "req", "Password");

	if (empty($errorMessage)) {
		$post = escape_post_data();

		$customer = $_POST['loginType'] == "customer" ? true : false; // determines if a customer or staff is loggin in

		if ($customer) { // if it's a customer logging in
			$sql = "SELECT customer_id, password FROM customer
					WHERE email = '{$post['loginEmail']}'";
		}
		else { // if it's a staff logging in
			$sql = "SELECT staff_id, business_id, password, admin FROM staff
					WHERE email = '{$post['loginEmail']}'";
		}

		$result = $mysqli->query($sql);

		// ensure the email is in the DB
		if ($result && $result->num_rows > 0){
			$row = $result->fetch_assoc();

			// check their password is correct
			if (password_verify($_POST['loginPassword'], $row['password'])) {
				if ($customer) { // login customer in
					$_SESSION['customer_logged_in'] = true;
					$_SESSION['customer_id']        = $row['customer_id'];

					header("Location: ./customer_appointments.php");
					exit;
				}
				else { // login staff in
					$_SESSION['staff_logged_in']   = true;
					$_SESSION['staff_id']          = $row['staff_id'];
					$_SESSION['staff_admin']       = $row['admin'];
					$_SESSION['staff_business_id'] = $row['business_id'];

					header("Location: ./staff_timetable.php");
					exit;
				}
			}
			// incorrect password
			else {
				$errorMessage = "Incorrect details.";
			}
		}
		// email address is not in the DB
		else {
			$errorMessage = "Incorrect details.";
		}
	}
}
?>