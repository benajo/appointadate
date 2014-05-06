<?php
if (isset($_POST['contact'])) {
	// validate the contact form fields
	$errorMessage  = validate_form($_POST['formName'], "req", "Name");
	$errorMessage .= validate_form($_POST['formEmail'], "req", "Email");
	$errorMessage .= validate_form($_POST['formEmail'], "email", "Email");
	$errorMessage .= validate_form($_POST['formSubject'], "req", "Subject");
	$errorMessage .= validate_form($_POST['formQuery'], "req", "Query");

	if (empty($errorMessage)) {
		// add in the line breaks for the email
		$query = str_replace('\n', '<br>', $_POST['formQuery']);

		$emailMessage  = "<p><b>Name</b>: {$_POST['formName']}</p>";
		$emailMessage .= "<p><b>Email</b>: <a href=\"mailto:{$_POST['formEmail']}\">{$_POST['formEmail']}</a></p>";
		$emailMessage .= "<p><b>Subject</b>: {$_POST['formSubject']}</p>";
		$emailMessage .= "<p><b>Query</b>:<br>{$query}</p>";

		$mail = new PHPMailer;

		$mail->isSMTP();
		// $mail->SMTPDebug  = 2;
		$mail->SMTPAuth   = true;
		$mail->SMTPSecure = 'ssl';
		$mail->Host       = 'smtp.gmail.com';
		$mail->Port       = 465;
		$mail->Username   = 'appointadate@gmail.com';
		$mail->Password   = '1tWoxsdN5V1U1OT8qcwIxqtE';

		$mail->setFrom($_POST['formEmail']);
		$mail->addReplyTo($_POST['formEmail']);

		$mail->addAddress("appointadate@gmail.com");
		$mail->isHTML(true);

		$mail->Subject = "Appoint-A-Date contact query";
		$mail->Body    = $emailMessage;

		if ($mail->send()) {
			$message = "Your contact query has been sent to Appoint-A-Date.";

			unset($_POST);
		}
		else {
			$errorMessage = "There has been an unexpected error, please try agian.";
			// $mail->ErrorInfo;
		}
	}
}
?>