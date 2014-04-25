<?php
if (isset($_POST['update_business_timetable'])) {
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

	foreach ($days as $k => $v) {
		$errorMessage .= $_POST["{$k}_start_h"] < 0 || $_POST["{$k}_start_m"] < 0 ? "{$days[$k]} start is required.<br>" : "";
		$errorMessage .= $_POST["{$k}_end_h"] < 0   || $_POST["{$k}_end_m"] < 0   ? "{$days[$k]} end is required.<br>" : "";
	}

	if (empty($errorMessage)) {
		$data = array();
		$post = escape_post_data();

		foreach ($days as $k => $v) {
			if ($post["{$k}_start_h"] > -1) {
				$data[] = "{$k}_start = '".$post["{$k}_start_h"] . ($post["{$k}_start_m"] < 10 ? "0" : "") . $post["{$k}_start_m"]."'";
			}

			if ($post["{$k}_end_h"] > -1) {
				$data[] = "{$k}_end = '".$post["{$k}_end_h"]   . ($post["{$k}_end_m"] < 10 ? "0" : "")   . $post["{$k}_end_m"]."'";
			}
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
?>