<?php
if (isset($_POST['update_business_details'])) {

	$errorMessage  = validate_form($_POST['name'], "req", "Business Name");
	$errorMessage .= validate_form($_POST['address_line_1'], "req", "Address Line 1");
	$errorMessage .= validate_form($_POST['address_line_1'], "alnum_s", "Address Line 1");
	$errorMessage .= validate_form($_POST['address_line_2'], "alnum_s", "Address Line 2");
	$errorMessage .= validate_form($_POST['city'], "req", "City");
	$errorMessage .= validate_form($_POST['city'], "alpha_s", "City");
	$errorMessage .= validate_form($_POST['county'], "req", "County");
	$errorMessage .= validate_form($_POST['county'], "alpha_s", "County");
	$errorMessage .= validate_form($_POST['postcode'], "req", "Postcode");
	$errorMessage .= validate_form($_POST['postcode'], "alnum_s", "Postcode");
	$errorMessage .= validate_form($_POST['latitude'], "req", "Latitude");
	$errorMessage .= validate_form($_POST['latitude'], "num", "Latitude");
	$errorMessage .= validate_form($_POST['longitude'], "req", "Longitude");
	$errorMessage .= validate_form($_POST['longitude'], "num", "Longitude");
	$errorMessage .= validate_form($_POST['contact_name'], "req", "Contact Name");
	$errorMessage .= validate_form($_POST['contact_name'], "name", "Contact Name");
	$errorMessage .= validate_form($_POST['contact_phone'], "req", "Contact Phone");
	$errorMessage .= validate_form($_POST['contact_phone'], "num_s", "Contact Phone");
	$errorMessage .= validate_form($_POST['contact_email'], "req", "Contact Email");
	$errorMessage .= validate_form($_POST['contact_email'], "email", "Contact Email");
	$errorMessage .= validate_form((isset($_POST['business_types']) ? count($_POST['business_types']) : ""), "req", "Business Types");

	if (empty($errorMessage)) {
		$post = escape_post_data();

		$sql = "UPDATE business SET
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
				description    = '{$post['description']}',
				updated        = NOW()
				WHERE business_id = '{$_SESSION['staff_business_id']}'";
		$result = $mysqli->query($sql);

		if ($result) {
			$message = "Business details have been updated.";

			// remove all of the businesses types, as we add all the currently selected ones back in
			$sql = "DELETE FROM business_type
					WHERE business_id = '{$_SESSION['staff_business_id']}'";
			$mysqli->query($sql);

			foreach ($_POST['business_types'] as $k => $v) {
				$sql = "INSERT INTO business_type SET
						type_id = '{$v}',
						business_id = '{$_SESSION['staff_business_id']}'";
				$mysqli->query($sql);
			}

			unset($_POST);
		}
		else {
			$errorMessage = "There has been an unexpected error, please try again.";
		}
	}
}
elseif (isset($_POST['update_business_timetable'])) {
	$days = array(
		"mon" => "Monday",
		"tue" => "Tuesday",
		"wed" => "Wednesday",
		"thu" => "Thursday",
		"fri" => "Friday",
		"sat" => "Saturday",
		"sun" => "Sunday"
	);

	$errorMessage = "";

	// loop through each day of the week to validate the users input
	foreach ($days as $k => $v) {
		// creates a time in the format: 800 for 8am, 2130 for 9:30pm
		$start = (int)($_POST["{$k}_start_h"].($_POST["{$k}_start_m"] < 10 ? "0" : "").$_POST["{$k}_start_m"]);
		$end   = (int)($_POST["{$k}_end_h"].  ($_POST["{$k}_end_m"]   < 10 ? "0" : "").$_POST["{$k}_end_m"]);

		$errorMessage .= $_POST["{$k}_start_h"] < 0 || $_POST["{$k}_start_m"] < 0 ? "{$days[$k]} start is required.<br>" : "";
		$errorMessage .= $_POST["{$k}_end_h"] < 0   || $_POST["{$k}_end_m"] < 0   ? "{$days[$k]} end is required.<br>" : "";
		$errorMessage .= $start >= $end ? "{$days[$k]} end time must be after the start time.<br>" : "";
	}

	if (empty($errorMessage)) {
		$data = array();
		$post = escape_post_data();

		foreach ($days as $k => $v) {
			// only change the time if it is set
			if ($post["{$k}_start_h"] > -1) {
				$data[] = "{$k}_start = '".$post["{$k}_start_h"] . ($post["{$k}_start_m"] < 10 ? "0" : "") . $post["{$k}_start_m"]."'";
			}

			// only change the time if it is set
			if ($post["{$k}_end_h"] > -1) {
				$data[] = "{$k}_end = '".$post["{$k}_end_h"] . ($post["{$k}_end_m"] < 10 ? "0" : "") . $post["{$k}_end_m"]."'";
			}

			$data[] = "{$k}_off = '".(isset($post["{$k}_off"]) ? 1 : 0)."'";
		}

		$sql = "UPDATE business_timetable SET
				".implode(",", $data)."
				WHERE business_id = {$_SESSION['staff_business_id']}";
		$result = $mysqli->query($sql);

		if ($result) {
			$message = "Business timetable has been updated.";
		}
		else {
			$errorMessage = "There has been an unexpected error, please try again.";
		}
	}
}
elseif (isset($_POST['add_appointment_type']) || isset($_POST['edit_appointment_type'])) {

	$errorMessage  = validate_form($_POST['appointment_type_name'], "req", "Type Name");
	$errorMessage .= validate_form($_POST['appointment_type_name'], "alnum_s", "Type Name");
	$errorMessage .= validate_form($_POST['appointment_type_length'], "req", "Type Length");
	$errorMessage .= validate_form($_POST['appointment_type_length'], "num", "Type Length");

	$errorMessage .= (int)$_POST['appointment_type_length'] < 5 || (int)$_POST['appointment_type_length'] % 5 != 0 ? "Type Length must >= 5 and <= 240, and must be a multiple of 5.<br>" : "";

	if (empty($errorMessage)) {
		$post = escape_post_data();

		$post['appointment_type_length'] = (int)$post['appointment_type_length'];

		// if for creating a new appointment type
		if (isset($_POST['add_appointment_type'])) {
			$sql = "INSERT INTO appointment_type SET
					business_id = '{$_SESSION['staff_business_id']}',
					name        = '{$post['appointment_type_name']}',
					length      = '{$post['appointment_type_length']}',
					created     = NOW(),
					updated     = NOW()";
			$result = $mysqli->query($sql);

			if ($result) {
				$message = "Appointment type has been created.";

				unset($_POST);
			}
			else {
				$errorMessage = "There has been an unexpected error, please try again.";
			}
		}
		// else for updating an appointment type
		else {
			$appointment_type = $mysqli->real_escape_string($_GET['appointment_type']);

			$sql = "SELECT * FROM appointment
					WHERE appointment_type_id = '{$appointment_type}'
					LIMIT 1";
			$result = $mysqli->query($sql);
			$num = $result->num_rows;

			// ensure there are no appointments for this appointment type, because they cannot be changed afterwords
			// if an appointment for 30 minutes have been created, the type cannot be changed to something else like 20 minutes
			if ($num == 0) {
				$sql = "UPDATE appointment_type SET
						name    = '{$post['appointment_type_name']}',
						length  = '{$post['appointment_type_length']}',
						updated = NOW()
						WHERE appointment_type_id = '{$appointment_type}'";
				$result = $mysqli->query($sql);

				if ($result) {
					$message = "Appointment type has been updated.";

					unset($_POST);
				}
				else {
					$errorMessage = "There has been an unexpected error, please try again.";
				}
			}
			else {
				$errorMessage = "This appointment type cannot be changed because appointments are already using it.";
			}
		}
	}
}
elseif (isset($_GET['remove_appointment_type'])) {
	$appointment_type = $mysqli->real_escape_string($_GET['remove_appointment_type']);

	$sql = "SELECT * FROM appointment
			WHERE appointment_type_id = '{$appointment_type}'
			AND business_id = '{$_SESSION['staff_business_id']}'
			LIMIT 1";
	$result = $mysqli->query($sql);
	$num = $result->num_rows;

	if ($num == 0) {
		$sql = "DELETE FROM appointment_type
				WHERE appointment_type_id = {$appointment_type}
				AND business_id = '{$_SESSION['staff_business_id']}'";
		$result = $mysqli->query($sql);

		if ($result) {
			$message = "Appointment type has been removed.";
		}
		else {
			$errorMessage = "There has been an unexpected error, please try again.";
		}
	}
	else {
		$errorMessage = "This appointment type cannot be removed because appointments are already using it.";
	}
}
elseif (isset($_POST['add_timetable_exception'])) {

	$errorMessage  = validate_form($_POST['exception_date'], "req", "Exception Date");

	$errorMessage .= $_POST["exception_start_hour"] < 0 || $_POST["exception_start_minute"] < 0 ? "Exception Start is required.<br>" : "";
	$errorMessage .= $_POST["exception_end_hour"] < 0   || $_POST["exception_end_minute"] < 0   ? "Exception End is required.<br>" : "";

	if (empty($errorMessage)) {

		$post = escape_post_data();

		$date = date("Y-m-d H:i:s", strtotime($post['exception_date']));

		$sql = "SELECT * FROM business_exception
				WHERE business_id = '{$_SESSION['staff_business_id']}'
				AND   `date` = '{$date}'";
		$result = $mysqli->query($sql);
		$num = $result->num_rows;

		if ($num == 0) {

			$start = (int)$post["exception_start_hour"] . ($post["exception_start_minute"] < 10 ? "0" : "") . $post["exception_start_minute"];
			$end   = (int)$post["exception_end_hour"] . ($post["exception_end_minute"] < 10 ? "0" : "") . $post["exception_end_minute"];

			$sql = "INSERT INTO business_exception SET
					business_id = '{$_SESSION['staff_business_id']}',
					`date`      = '{$date}',
					start       = '{$start}',
					end         = '{$end}'";
			$result = $mysqli->query($sql);

			if ($result) {
				$message = "Timetable exception has been created.";

				unset($_POST);
			}
			else {
				$errorMessage = "There has been an unexpected error, please try again.";
			}
		}
		else {
			$errorMessage = "You already have an exception for this day.";
		}
	}
}
elseif (isset($_GET['remove_exception'])) {
	$sql = "DELETE FROM business_exception
			WHERE `date` = '{$mysqli->real_escape_string($_GET['remove_exception'])}'
			AND business_id = '{$_SESSION['staff_business_id']}'";
	$result = $mysqli->query($sql);

	if ($result) {
		$message = "Exception has been removed.";
	}
	else {
		$errorMessage = "There has been an unexpected error, please try again.";
	}
}
?>