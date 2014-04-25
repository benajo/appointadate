<?php
// only allow these changes when a customer is logged in
if (isset($_SESSION['customer_logged_in'])) {
	// add favourite business to database
	if (isset($_GET['add_favourite_business'])) {
		$sql = "INSERT INTO customer_pref_business SET
				business_id = {$mysqli->real_escape_string($_GET['add_favourite_business'])},
				customer_id = {$_SESSION['customer_id']}";
		$result = $mysqli->query($sql);

		if ($result) {
			$message = "The business has been added to your favourite list.";
		}
		else {
			$errorMessage = "There has been an unexpected error, please try again.";
		}
	}
	// remove favourite business from database
	elseif (isset($_GET['remove_favourite_business'])) {
		$sql = "DELETE FROM customer_pref_business
				WHERE business_id = {$mysqli->real_escape_string($_GET['remove_favourite_business'])}
				AND customer_id = {$_SESSION['customer_id']}";
		$result = $mysqli->query($sql);

		if ($result) {
			$message = "The business has been removed from your favourite list.";
		}
		else {
			$errorMessage = "There has been an unexpected error, please try again.";
		}
	}
}
?>