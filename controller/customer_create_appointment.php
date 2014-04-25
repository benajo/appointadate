<?php
if (isset($_POST['create_appointment'])) {

	$datetime = $_POST['date']." ".$_POST['time'];
	$datetime = DateTime::createFromFormat("D, d F Y H:i", $datetime);
	$datetime = $datetime->format("Y-m-d H:i:s");

	$post = escape_post_data($_POST);

	$sql = "INSERT INTO appointment SET
			business_id         = '{$post['business']}',
			customer_id         = '{$_SESSION['customer_id']}',
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
?>