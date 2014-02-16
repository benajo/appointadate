<?php
if (isset($_GET['remove_favourite_business'])) {
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
?>