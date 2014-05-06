<?php
if (isset($_POST['create_appointment'])) {

	// ensure all the variables are set and not empty
	if (isset($_POST['customer']) && !empty($_POST['customer']) && isset($_POST['staff']) && !empty($_POST['staff']) && isset($_POST['type']) && !empty($_POST['type'])) {

		// convert the date and time to a format the DB can read
		$datetime = $_POST['date']." ".$_POST['time'];
		$datetime = DateTime::createFromFormat("D, d F Y H:i", $datetime);
		$datetime = $datetime->format("Y-m-d H:i:s");

		$post = escape_post_data($_POST);

		$sql = "INSERT INTO appointment SET
				business_id         = '{$_SESSION['staff_business_id']}',
				customer_id         = '{$post['customer']}',
				staff_id            = '{$post['staff']}',
				appointment_type_id = '{$post['type']}',
				datetime            = '{$datetime}',
				accepted            = 0,
				cancelled           = 0,
				created             = NOW(),
				updated             = NOW()";
		$result = $mysqli->query($sql);

		if ($result) {
			$message = "Appointment Request successful!";
		}
	}
	else {
		$errorMessage = "There has been an unexpected error, please try again.";
	}

}
?>