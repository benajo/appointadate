<?php
/**
* @author Vlad Marchis
* @date 2014-02-19
*/
if (isset($_POST['customer_login'])) {
	$errorMessage  = validate_form($_POST['customerEmail'], "req", "Email Address");
	$errorMessage .= validate_form($_POST['customerEmail'], "email", "Email Address");
	$errorMessage .= validate_form($_POST['customerPassword'], "req", "Password");

	if (empty($errorMessage)) {
		$post = escape_post_data($_POST);

		$sql = "SELECT customer_id, password FROM customer
				WHERE email = '{$post['customerEmail']}'";
		$result = $mysqli->query($sql);

		if ($result && $result->num_rows > 0){
			$row = $result->fetch_assoc();
			$id = $row['customer_id'];
			$pass = $row['password'];

			if (password_verify($_POST['customerPassword'], $pass)) {
				$_SESSION['customer_logged_in'] = true;
				$_SESSION['customer_id'] = $id;

				header("Location: ./appointments.php");
				exit;
			}
			else {
				$errorMessage = "Incorrect password.";
			}
		}
		else {
			$errorMessage = "Email address is not in our database.";
		}
	}
}

if (isset($_POST['business_login'])) {
	$errorMessage  = validate_form($_POST['staffEmail'], "req", "Email Address");
	$errorMessage .= validate_form($_POST['staffEmail'], "email", "Email Address");
	$errorMessage .= validate_form($_POST['staffPassword'], "req", "Password");

	if (empty($errorMessage)) {
		$post = escape_post_data($_POST);

		$sql = "SELECT staff_id, password FROM staff
				WHERE email = '{$post['staffEmail']}'";
		$result = $mysqli->query($sql);

		if ($result && $result->num_rows > 0){
			$row = $result->fetch_assoc();
			$id = $row['staff_id'];
			$pass = $row['password'];

			if (password_verify($_POST['staffPassword'], $pass)) {
				$_SESSION['staff_logged_in'] = true;
				$_SESSION['staff_id'] = $id;

				header("Location: ");
				exit;
			}
			else {
				$errorMessage = "Incorrect password.";
			}
		}
		else {
			$errorMessage = "Email address is not in our database.";
		}
	}
}
?>