<?php
if (isset($_POST['reset_password'])) {
	$errorMessage  = validate_form($_POST['formEmail'], "req", "Email Address");
	$errorMessage .= validate_form($_POST['formEmail'], "email", "Email Address");

	if (empty($errorMessage)) {
		$post = escape_post_data($_POST);

		$sql = "SELECT customer_id, first_name, last_name FROM customer
				WHERE email = '{$post['formEmail']}'";
		$result = $mysqli->query($sql);

		if ($result && $result->num_rows > 0){
			$row = $result->fetch_assoc();

			$id = $row['customer_id'];
			$code = random_code(32);

			$to = $_POST['formEmail'];
			$subject = "AppointADate password reset";

			$body  = "Hello {$row['first_name']} {$row['last_name']},\r\n\r\n";
			$body .= "You have requested to reset your password. Please follow the link below:\r\n\r\n";
			$body .= "appointadate.local/reset_password.php?i={$row['customer_id']}&c={$code}";

			$headers = "";//From: <reset@appointadate.com>";

			// $mail = mail($to, $subject, $body, $headers);
			$mail = mail("ben@jovanic.co.uk", "subject", "body", "");

			var_dump($mail);

			if ($mail) {
				$message = "An email has been sent to you with information about how to reset your password. Please ensure you check your spam/junk folder.";
			}
			else {
				$errorMessage = "There has been an unexpected error, please try agian.";
			}
		}
		else {
			$errorMessage = "Email address is not in our database.";
		}
	}
}
?>