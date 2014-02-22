<?php
/**
 * @author Ben Jovanic
 * @version 2014-02-22
 */
function validate_form($data, $type, $name)
{
	$invalid = "Invalid characters in {$name}.<br>";

	if (empty($data)) {
		if ($type == "req") {
			return "{$name} required.<br>";
		}
	}
	else {
		switch ($type) {
			case "email":
				if (!filter_var($data, FILTER_VALIDATE_EMAIL)) {
					return $invalid;
				}
				break;

			case "name":
				if (!preg_match("/^([a-z\'\-]+)$/i", $data)) {
					return $invalid;
				}
				break;

			case "alpha":
				if (!preg_match("/^([a-z]+)$/i", $data)) {
					return $invalid;
				}
				break;

			case "alnum":
				if (!preg_match("/^([a-z0-9]+)$/i", $data)) {
					return $invalid;
				}
				break;

			case "num":
				if (!preg_match("/^([0-9]+)$/i", $data)) {
					return $invalid;
				}
				break;

			case "alpha_s":
				if (!preg_match("/^([a-z\s]+)$/i", $data)) {
					return $invalid;
				}
				break;

			case "alnum_s":
				if (!preg_match("/^([a-z0-9\s]+)$/i", $data)) {
					return $invalid;
				}
				break;

			case "num_s":
				if (!preg_match("/^([0-9\s]+)$/i", $data)) {
					return $invalid;
				}
				break;
		}
	}
}

/**
 * @author Ben Jovanic
 * @version 2014-02-15
 */
function validate_password($p1, $p2)
{
	if (empty($p1) || empty($p2)) {
		return "Complete both password fields.<br>";
	}

	$error = "";

	if ($p1 != $p2) {
		$error .= "Passwords do not match.<br>";
	}

	if (strlen($p1) < 8) {
		$error .= "Password length must be greater than 8.<br>";
	}

	return $error;
}

/**
 * @author Ben Jovanic
 * @version 2014-02-15
 */
function escape_post_data($p)
{
	global $mysqli;

	$escaped = array();

	foreach ($p as $k => $v) {
		$escaped[$k] = $mysqli->real_escape_string($v);
	}

	return $escaped;
}

/**
 * @author Ben Jovanic
 * @version 2014-02-22
 */
function validate_customer_email($email, $customer_id=null)
{
	global $mysqli;

	if (!$email) {
		return;
	}

	$sql = "SELECT * FROM customer
			WHERE email = '{$mysqli->real_escape_string($email)}'
			".(!is_null($customer_id) ? "AND customer_id != {$customer_id}" : "");
	$result = $mysqli->query($sql);

	return $result->num_rows ? "Email address is already in use.<br>" : "";
}

/**
 * @author Ben Jovanic
 * @version 2014-02-22
 */
function validate_staff_email($email, $staff_id=null)
{
	global $mysqli;

	if (!$email) {
		return;
	}

	$sql = "SELECT * FROM staff
			WHERE email = '{$mysqli->real_escape_string($email)}'
			".(!is_null($staff_id) ? "AND staff_id != {$staff_id}" : "");
	$result = $mysqli->query($sql);

	return $result->num_rows ? "Email address is already in use.<br>" : "";
}

/**
 * @author Ben Jovanic
 * @version 2014-02-22
 */
function random_code($length)
{
	$code = "";

	$chars = array(
		"A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z",
		"a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z",
		"0","1","2","3","4","5","6","7","8","9"
	);

	$charsLength = count($chars) - 1;

	for ($i=0; $i < $length; $i++) {
		$code .= $chars[rand(1, $charsLength)];
	}

	return $code;
}