<?php
if (isset($_GET['appointment'])) {
	$appointment_id = $mysqli->real_escape_string($_GET['appointment']);

	$sql = "SELECT * FROM appointment
			WHERE appointment_id = '{$appointment_id}'
			AND   customer_id    = '{$_SESSION['customer_id']}'
			AND   datetime       < NOW()";
	$result = $mysqli->query($sql);

	// if the appointment id is invalid then return customer to appointments list
	if ($result->num_rows != 1) {
		header("Location: ./customer_appointments.php");
		exit;
	}
}
else {
	header("Location: ./customer_appointments.php");
	exit;
}

if (isset($_POST['update_review'])) {
	$errorMessage  = validate_form($_POST['rating'], "req", "Rating");
	$errorMessage .= validate_form($_POST['review'], "req", "Review");

	if (empty($errorMessage)) {
		$appointment_id = $mysqli->real_escape_string($_GET['appointment']);

		$post = escape_post_data();

		$sql = "SELECT * FROM review WHERE appointment_id = '{$appointment_id}'";
		$result = $mysqli->query($sql);

		if ($result->num_rows > 0) {
			$sql = "UPDATE review SET
					rating         = '{$post['rating']}',
					review         = '{$post['review']}',
					updated        = NOW()
					WHERE appointment_id = '{$appointment_id}'";
		}
		else {
			$sql = "INSERT INTO review SET
					appointment_id = '{$appointment_id}',
					rating         = '{$post['rating']}',
					review         = '{$post['review']}',
					created        = NOW(),
					updated        = NOW()";
		}

		$result = $mysqli->query($sql);

		if ($result) {
			$message = "Your review has been saved.";

			unset($_POST);
		}
		else {
			$errorMessage = "There has been an unexpected error, please try again.";
		}
	}
}
?>