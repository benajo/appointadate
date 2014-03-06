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


/**
* @author Vlad-Tudor Marchis
* @version 2014-03-03
*/
function search_business($name, $type, $location, $range)
{
	global $mysqli;

	if (empty($name) && empty($type) && empty($location) && empty($range)) {
		return "SELECT * FROM business
				ORDER BY name";
	}

	$sql ="";
	$where1 ="";
	$where2 ="";
	$having ="";
	$order = "";

	if (!empty($location) && !empty($range)) {
		$location = urlencode($location);
		$url = "http://maps.googleapis.com/maps/api/geocode/xml?address={$location}&sensor=false";
		$xml = simplexml_load_string(file_get_contents($url));

		$lat = $xml->result->geometry->location->lat;
		$long = $xml->result->geometry->location->lng;

		$sql = "SELECT *,
				(( 6371 * acos( cos( radians({$lat}) ) * cos( radians( `latitude` ) ) * cos( radians( `longitude` ) - radians({$long}) ) + sin( radians({$lat}) ) * sin( radians( `latitude` ) ) ) ) * 0.621371192) AS distance ";

		$having = " HAVING distance <= {$range}
				   ORDER BY distance ASC";
	}
	else {
		$sql = "SELECT * ";
	}

	if (!empty($type)) {
		$sql .= " FROM business b
				JOIN business_type bt ON b.business_id = bt.business_id ";
	}
	else {
		$sql .= " FROM business ";
	}

	$and = false;

	if (!empty($type))
	{
		$where1 =" WHERE ( bt.type_id = {$type} )";
		$and = true;
	}

	if (!empty($name))
	{
		$keywords = explode(' ', trim($name));

		if (empty($location) || empty($range)) {
			$order = " ORDER BY ";
		}
		elseif (!empty($location) && !empty($range)) {
			$order = " , ";
		}

		if ($and) {
			$where2 .= " AND ( ";
		}
		else {
			$where2 .= " WHERE ( ";
		}

		for ($i = 0; $i < count($keywords); $i++)
		{
			$key = $keywords[$i];
			$where2 .= "name LIKE '%$key%'";

			if ($i < 1){
				$order .= " case when `name` like '%$key%' then 1 else 0 end ";
			}
			else {
				$order .= " + case when `name` like '%$key%' then 1 else 0 end ";
			}

			if ($i < count($keywords) - 1) {
				$where2 .= " OR ";
			}
			else {
				$order .= " DESC ";
			}
		}

		$where2 .=") ";
		//$sql .= $where1;
		//$and = true;
		//echo $sql;
		//$result = $mysqli->query($sql);
	}

	$sql .= $where1;
	$sql .= $where2;
	$sql .= $having;
	$sql .= $order;

	return $sql;

}
?>