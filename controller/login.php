<?php
/**
* @author Vlad Marchis, Ben Jovanic
* @version 2014-03-02
*/
if (isset($_POST['login'])) {
	$errorMessage  = validate_form($_POST['formEmail'], "req", "Email");
	$errorMessage .= validate_form($_POST['formEmail'], "email", "Email");
	$errorMessage .= validate_form($_POST['formPassword'], "req", "Password");

	if (empty($errorMessage)) {
		$post = escape_post_data($_POST);

		$customer = $_POST['formType'] == "customer" ? true : false; // determines if a customer or staff is loggin in

		if ($customer) { // if it's a customer logging in
			$sql = "SELECT customer_id, password FROM customer
					WHERE email = '{$post['formEmail']}'";
		}
		else { // if it's a staff loggin in
			$sql = "SELECT staff_id, password FROM staff
					WHERE email = '{$post['formEmail']}'";
		}

		$result = $mysqli->query($sql);

		if ($result && $result->num_rows > 0){
			$row = $result->fetch_assoc();

			$id = $row['customer_id'];

			if (password_verify($_POST['formPassword'], $row['password'])) {
				if ($customer) {
					$_SESSION['customer_logged_in'] = true;
					$_SESSION['customer_id'] = $row['customer_id'];

					header("Location: ./appointments.php");
					exit;
				}
				else {
					$_SESSION['staff_logged_in'] = true;
					$_SESSION['staff_id'] = $row['staff_id'];

					header("Location: ");
					exit;
				}
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