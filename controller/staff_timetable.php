<?php
if (isset($_POST['update_staff_timetable'])) {
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

		$sql = "UPDATE staff_timetable SET
				".implode(",", $data)."
				WHERE staff_id = {$_SESSION['staff_id']}";
		$result = $mysqli->query($sql);

		if ($result) {
			$message = "Your timetable has been updated.";

			unset($_POST);
		}
		else {
			$errorMessage = "There has been an unexpected error, please try again.";
		}
	}
}
elseif (isset($_POST['add_timetable_exception'])) {

	$errorMessage  = validate_form($_POST['exception_date'], "req", "Exception Date");

	$errorMessage .= $_POST["exception_start_hour"] < 0 || $_POST["exception_start_minute"] < 0 ? "Exception Start is required.<br>" : "";
	$errorMessage .= $_POST["exception_end_hour"] < 0   || $_POST["exception_end_minute"] < 0   ? "Exception End is required.<br>" : "";

	if (empty($errorMessage)) {

		$post = escape_post_data();

		$date = date("Y-m-d H:i:s", strtotime($post['exception_date']));

		$sql = "SELECT * FROM staff_exception
				WHERE staff_id = '{$_SESSION['staff_id']}'
				AND   `date` = '{$date}'";
		$result = $mysqli->query($sql);
		$num = $result->num_rows;

		if ($num == 0) {

			$start = (int)$post["exception_start_hour"] . ($post["exception_start_minute"] < 10 ? "0" : "") . $post["exception_start_minute"];
			$end   = (int)$post["exception_end_hour"] . ($post["exception_end_minute"] < 10 ? "0" : "") . $post["exception_end_minute"];

			$sql = "INSERT INTO staff_exception SET
					staff_id = '{$_SESSION['staff_id']}',
					`date`   = '{$date}',
					start    = '{$start}',
					end      = '{$end}'";
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
	$sql = "DELETE FROM staff_exception
			WHERE `date` = '{$mysqli->real_escape_string($_GET['remove_exception'])}'
			AND staff_id = '{$_SESSION['staff_id']}'";
	$result = $mysqli->query($sql);

	if ($result) {
		$message = "Exception has been removed.";
	}
	else {
		$errorMessage = "There has been an unexpected error, please try again.";
	}
}
?>