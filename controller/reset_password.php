<?php
// validate the get variables to ensure they match
if (((isset($_GET['customer']) && !empty($_GET['customer'])) || (isset($_GET['staff']) && !empty($_GET['staff']))) && isset($_GET['code']) && !empty($_GET['code'])) {
	$id   = $mysqli->real_escape_string(isset($_GET['customer']) ? $_GET['customer'] : $_GET['staff']);
	$code = $mysqli->real_escape_string($_GET['code']);

	if (isset($_GET['customer'])) {
		$sql = "SELECT * FROM customer
				WHERE customer_id = '$id'
				AND   code = '$code'";
	}
	else {
		$sql = "SELECT * FROM staff
				WHERE staff_id = '$id'
				AND   code = '$code'";
	}

	$result = $mysqli->query($sql);

	// if false then return customer to first step: entering their email address
	if (!$result->num_rows) {
		header("Location: reset_password.php");
		exit;
	}

}

if (isset($_POST['reset_password_step1'])) {
	$errorMessage  = validate_form($_POST['formEmail'], "req", "Email Address");
	$errorMessage .= validate_form($_POST['formEmail'], "email", "Email Address");

	if (empty($errorMessage)) {
		$post = escape_post_data();

		if ($post['formType'] == "customer") {
			$sql = "SELECT customer_id AS id, first_name, last_name FROM customer
					WHERE email = '{$post['formEmail']}'";
		}
		else {
			$sql = "SELECT staff_id AS id, first_name, last_name FROM staff
					WHERE email = '{$post['formEmail']}'";
		}

		$result = $mysqli->query($sql);

		if ($result && $result->num_rows > 0){
			$row = $result->fetch_assoc();

			$id = $row['id'];
			$code = random_code(32);

			$resetURL = $_SERVER['HTTP_HOST']."/reset_password.php?{$post['formType']}={$id}&code={$code}";

			$emailMessage  = "<p>Hello {$row['first_name']} {$row['last_name']},</p>";
			$emailMessage .= "<p>You have requested to reset your password. Please follow the link below:</p>";
			$emailMessage .= "<p><a href='http://{$resetURL}'>{$resetURL}</a></p>";

			$mail = new PHPMailer;

			$mail->isSMTP();
			// $mail->SMTPDebug  = 2;
			$mail->SMTPAuth   = true;
			$mail->SMTPSecure = 'tsl';
			$mail->Host       = 'mail.jovanic.co.uk';
			$mail->Port       = 587;
			// $mail->Username   = 'appointadate@gmail.com';
			// $mail->Password   = '1tWoxsdN5V1U1OT8qcwIxqtE';
			$mail->Username   = 'ben@jovanic.co.uk';
			$mail->Password   = '1$VN!hv5KN90Ww^I';

			$mail->setFrom('reset@appointadate.com', 'Appoint-A-Date Password Reset');
			$mail->addReplyTo('noreply@appointadate.com');

			$mail->addAddress($_POST['formEmail']);
			$mail->isHTML(true);

			$mail->Subject = "Appoint-A-Date password reset";
			$mail->Body    = $emailMessage;

			if ($mail->send()) {
				$message = "An email has been sent to you with information about how to reset your password.<br>Please ensure you check your spam/junk folder.";

				if ($_POST['formType'] == "customer") {
					$sql = "UPDATE customer SET
							code = '{$mysqli->real_escape_string($code)}'
							WHERE customer_id = '{$id}'";
					$mysqli->query($sql);
				}
				else {
					$sql = "UPDATE staff SET
							code = '{$mysqli->real_escape_string($code)}'
							WHERE staff_id = '{$id}'";
					$mysqli->query($sql);
				}

				unset($_POST);
			}
			else {
				$errorMessage = "There has been an unexpected error, please try agian.";
				// $mail->ErrorInfo;
			}
		}
		else {
			$errorMessage = "Email address is not in our database.";
		}
	}
}
elseif (isset($_POST['reset_password_step2'])) {
	$errorMessage = validate_password($_POST['formPassword1'], $_POST['formPassword2']);

	$hash = password_hash($_POST['formPassword1'], PASSWORD_DEFAULT);

	$errorMessage .= !password_verify($_POST['formPassword1'], $hash) ? "Password hashing error, please try again.<br>" : "";

	if (empty($errorMessage)) {
		$post = escape_post_data();

		$id   = $mysqli->real_escape_string(isset($_GET['customer']) ? $_GET['customer'] : $_GET['staff']);
		$code = $mysqli->real_escape_string($_GET['code']);

		if (isset($_GET['customer'])) {
			$sql = "SELECT customer_id AS id, first_name, last_name, email FROM customer
					WHERE customer_id = '{$id}'
					AND   code        = '{$code}'";
		}
		else {
			$sql = "SELECT staff_id AS id, first_name, last_name, email FROM staff
					WHERE staff_id = '{$id}'
					AND   code     = '{$code}'";
		}

		$result = $mysqli->query($sql);

		if ($result && $result->num_rows > 0){
			$row = $result->fetch_assoc();

			$id = $row['id'];
			$code = random_code(32);

			$loginURL = $_SERVER['HTTP_HOST']."/login.php";

			$emailMessage  = "<p>Hello {$row['first_name']} {$row['last_name']},</p>";
			$emailMessage .= "<p>Your password has been reset. Please follow the link below to login:</p>";
			$emailMessage .= "<p><a href='http://{$loginURL}'>{$loginURL}</a></p>";

			$mail = new PHPMailer;

			$mail->isSMTP();
			// $mail->SMTPDebug  = 2;
			$mail->SMTPAuth   = true;
			$mail->SMTPSecure = 'ssl';
			$mail->Host       = 'smtp.gmail.com';
			$mail->Port       = 465;
			$mail->Username   = 'appointadate@gmail.com';
			$mail->Password   = '1tWoxsdN5V1U1OT8qcwIxqtE';

			$mail->setFrom('reset@appointadate.com', 'Appoint-A-Date Password Reset');
			$mail->addReplyTo('noreply@appointadate.com');

			$mail->addAddress($row['email']);
			$mail->isHTML(true);

			$mail->Subject = "Appoint-A-Date password reset";
			$mail->Body    = $emailMessage;

			if ($mail->send()) {
				$message = "Your new password has been set and you should not be able to login.";

				if (isset($_GET['customer'])) {
					$sql = "UPDATE customer SET
							password = '{$hash}',
							code     = '{$mysqli->real_escape_string($code)}'
							WHERE customer_id = '{$id}'";
					$mysqli->query($sql);
				}
				else {
					$sql = "UPDATE staff SET
							password = '{$hash}',
							code     = '{$mysqli->real_escape_string($code)}'
							WHERE staff_id = '{$id}'";
					$mysqli->query($sql);
				}

				unset($_POST, $_GET);
			}
			else {
				$errorMessage = "There has been an unexpected error, please try agian.";
				// $mail->ErrorInfo;
			}
		}
		else {
			$errorMessage = "There has been an unexpected error, please try agian.";
		}
	}
}
?>