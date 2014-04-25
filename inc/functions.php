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
function escape_post_data()
{
	global $mysqli;

	$escaped = array();

	foreach ($_POST as $k => $v) {
		$escaped[$k] = $mysqli->real_escape_string(trim($v));
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
function search_businesses()
{
	global $mysqli;

	$name     = isset($_POST['keywords']) && !empty($_POST['keywords']) ? $_POST['keywords'] : "";;
	$type     = isset($_POST['businessType']) && !empty($_POST['businessType']) ? $_POST['business;Type'] : "";;
	$location = isset($_POST['postcode']) && !empty($_POST['postcode']) ? $_POST['postcode'] : "";;
	$range    = isset($_POST['range']) && !empty($_POST['range']) ? $_POST['range'] : "";

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

/**
* @author Ben Jovanic
* @version 2014-03-0314-04-01
*/
function favourite_business($businessId)
{
	global $mysqli;

	$html = "";

	if (isset($_SESSION['customer_logged_in'])) {
		$sql = "SELECT * FROM customer_pref_business
				WHERE customer_id = '{$_SESSION['customer_id']}'
				AND   business_id = '{$businessId}'";
		$result = $mysqli->query($sql);

		// if entry exists, they have already favourited the business, so allow them to remove the business
		if ($result->num_rows) {
			$html .= "<p><a href=\"businesses.php?remove_favourite_business={$businessId}\">Remove from favourites</a></p>";
		}
		// otherwise, allow them to add the business as a favourite
		else {
			$html .= "<p><a href=\"businesses.php?add_favourite_business={$businessId}\">Add to favourites</a></p>";
		}
	}

	return $html;
}


/**
* @author Vlad-Tudor Marchis
* @version 2014-04-17
* Helper function that establishes whether or not the given hour and minute are at the end of a business day.
*/
function endOfDay($hour, $minute, $end)
{
	$endHour = ((int)substr($end, 0, 2))*100;
	$endMinute = ((int)substr($end, -2));
	if ($endMinute == 0)
	{
		if (($hour == $endHour - 100) && ($minute == 55))
			return true;
	}
	else
		if (($hour == $endHour) && ($minute == $endMinute - 5))
			return true;

}

/**
* @author Vlad-Tudor Marchis
* @version 2014-04-17
*/
function splitGaps($available, $type, $business, $endTime)
{
	global $mysqli;

	$sql = "SELECT appointment_type_id, length
			FROM appointment_type
			WHERE business_id = {$business}";

	$result = $mysqli->query($sql);

	if ($result->num_rows)
	{
		$types = array();
		$length = 0;
		while ($row = $result->fetch_assoc())
		{
			$types[] = $row['length'];
			if ($row['appointment_type_id'] == $type)
			{
				$length = $row['length'];
			}
		}
	}
	rsort($types);
	$newGap = true;
	$gapSize = 0;
	foreach ($available as $hour => $minutes)
	{

		foreach ($minutes as $minute => $value)
		{
				if ($newGap)
			{
				// echo $hour."<br>";
				$hourStart = $hour;
			}
			if (($value == 1) && (!endOfDay($hour, $minute, $endTime)))
			{
				if ($newGap)
				{
					$minutesStart = $minute;
					$newGap = false;
				}

				$gapSize += 5;
			}
			elseif ($gapSize >= $length)
			{
				$hourEnd = $hour;
				if (endOfDay($hour, $minute, $endTime))
					$minutesEnd = $minute + 5;
				else
					$minutesEnd = $minute;
				$increment = 5;
				if ($gapSize >= $length)
				{
					for ($i = 0; $i < count($types); $i++)
					{
						if ($types[$i] + $length <= $gapSize)
						{
							$increment = $types[$i];
							break;
						}
					}

					// echo $hourStart." ";
					// echo $minutesStart."<br>";
					// echo $hourEnd." ";
					// echo $minutesEnd."<br>";

					if ($hourStart == $hourEnd)
    				{
    					$i = 1;
    					for ($m = (int)$minutesStart+5; $m < $minutesEnd; $m += 5)
    					{
    						if ($m > $minutesEnd - $length)
    						{
    							$available[$hourStart][$m < 10 ? "0".$m : $m] = 0;
    						}
    						elseif ($i < $increment/5)
    						{
    							$available[$hourStart][$m < 10 ? "0".$m : $m] = 0;
    							$i++;
    						}
    						else
    							$i = 1;
    					}
    				}
    				else
    				{
    					//echo $hourStart."<br> ";
						//echo $hourEnd." ";
    					$i = 1;
    					for ($h = $hourStart; $h <= $hourEnd; $h += 100)
    					{
    						if ($h == $hourStart)
    						{
    							for ($m = (int)$minutesStart+5; $m < 60; $m += 5)
    							{
									if ($i < $increment/5)
									{
    									$available[$h][$m < 10 ? "0".$m : $m] = 0;
    									$i++;
    								}
    								else
    									$i = 1;
    							}
    						}
    						elseif ($h == $hourEnd)
    						{
    							for ($m = 0; $m < $minutesEnd; $m += 5)
    							{
    								if ($m > $minutesEnd - $length)
    								{
    									$available[$h][$m < 10 ? "0".$m : $m] = 0;
    								}
    								elseif ($i < $increment/5)
									{
    									$available[$h][$m < 10 ? "0".$m : $m] = 0;
    									$i++;
    								}
    								else
    									$i = 1;
    							}
    						}
    						else
    						{
    							for ($m = 0; $m < 60; $m += 5)
    							{
									if ($i < $increment/5)
									{
    									$available[$h][$m < 10 ? "0".$m : $m] = 0;
    									$i++;
    								}
    								else
    									$i = 1;
    							}
    						}
    					}
					}
				}
				$newGap = true;
				$gapSize = 0;
				// echo "Inside For".$newGap."<br>";
			}
			else
			{
				$newGap = true;
				$gapSize = 0;
			}
		}
	}

	return $available;
}

/**
* @author Vlad-Tudor Marchis
* @version 2014-03-21
*/
function findAvailableTimes($date, $type, $staff_id, $business)
{
	// echo $date."<br>";
	// echo $type."<br>";
	// echo $staff_id;
	global $mysqli;

	$day = strftime("%a",strtotime($date));
	$day = strtolower($day);

	$start = $day.'_start';
	$end = $day.'_end';

	$sqlHours = "SELECT {$start}, {$end}
				FROM staff_timetable
				WHERE staff_id = {$staff_id}";

	$availableHours = array();
	$resultHours = $mysqli->query($sqlHours);
	if ($resultHours->num_rows)
	{
		$row = $resultHours->fetch_assoc();
		$start = $row[$start];
		$end = $row[$end];
		if ($start < 1000)
			$startHour = substr($start, 0, 1)."00";
		else
			$startHour = substr($start, 0, 2)."00";
		if ($end < 1000)
			$endHour = substr($end, 0, 1)."00";
		else
			$endHour = substr($end, 0, 2)."00";
		$availableMinutes = array();
		// For minutes of an hour that is not a business hour in its entirety
		for ($i = (int)substr($startHour, -2); $i < substr($start, -2) ;$i += 5)
		{
			if ($i < 10)
				$availableMinutes["0".$i] = 0;
			else
				$availableMinutes[$i] = 0;
		}

		for ($i = (int)substr($start, -2); $i < 60; $i += 5){
			if ($i < 10)
				$availableMinutes["0".$i] = 1;
			else
				$availableMinutes[$i] = 1;
		}

		$availableHours[$startHour] = $availableMinutes;
		for ($h = $startHour+100; $h<=$endHour; $h += 100)
		{
			unset($availableMinutes);
			$availableMinutes = array();
			if ($h < $endHour)
			{
				for ($m = 0; $m < 60; $m += 5)
				{
					if ($m < 10)
						$availableMinutes["0".$m] = 1;
					else
						$availableMinutes[$m] = 1;
				}
				$availableHours[$h] = $availableMinutes;
			}
			else
			{
				// echo strcmp(substr($end, -2), "00");
				if (strcmp(substr($end, -2), "00") != 0)
				{
					for ($m = 0; $m < substr($end, -2); $m += 5)
					{
						if ($m < 10)
							$availableMinutes["0".$m] = 1;
						else
							$availableMinutes[$m] = 1;
					}
					for ($m = substr($end, -2); $m < 60; $m += 5)
					{
						if ($m < 10)
							$availableMinutes["0".$m] = 0;
						else
							$availableMinutes[$m] = 0;
					}
					$availableHours[$h] = $availableMinutes;
				}
			}
		}
	}
	// Altering the timetable based on previously booked appointments.

	$datetimeStart = $date." "."00:00";
	$datetimeStart = DateTime::createFromFormat("D, d F Y H:i", $datetimeStart);
	$datetimeStart = $datetimeStart->format("Y-m-d H:i:s");
	// $datetimeStart .= ".000";

	$datetimeEnd = $date." "."23:59";
	$datetimeEnd = DateTime::createFromFormat("D, d F Y H:i", $datetimeEnd);
	$datetimeEnd = $datetimeEnd->format("Y-m-d H:i:s");
	// $datetimeEnd .= ".000";

	$sqlApp = "SELECT
				a.datetime,
				b.length
			   FROM appointment a
			   JOIN appointment_type b ON a.appointment_type_id = b.appointment_type_id
			   WHERE a.staff_id = {$staff_id}
			   AND a.datetime >= '{$datetimeStart}'
			   AND a.datetime <= '{$datetimeEnd}'
			   AND a.cancelled = 0";

    // echo $sqlApp;

    $resultApp = $mysqli->query($sqlApp);

    if ($resultApp->num_rows)
    {
    	while ($row = $resultApp->fetch_assoc())
    	{
    		$time = $row['datetime'];
    		$length = $row['length'];

    		$minutesStart = substr($time, 14, 2);
    		$minutesEnd = $minutesStart + $length;

    		$hourStart = substr($time, 11, 2);
    		$hourStart = (int)$hourStart;
    		$hourEnd = (int)($minutesEnd/60)+ $hourStart;
    		$hourStart *= 100;
    		$hourEnd *= 100;

    		if ($minutesEnd >= 60)
    			$minutesEnd %= 60;

    		// echo "<br>".$hourStart." ";
    		// echo $minutesStart."<br>";
    		// echo $hourEnd." ";
    		// echo $minutesEnd."<br>";
    		if ($hourStart == $hourEnd)
    		{
    			for ($m = (int)$minutesStart; $m < $minutesEnd; $m += 5)
    			{
    				$availableHours[$hourStart][$m < 10 ? "0".$m : $m] = 0;
    			}
    		}
    		else
    		{
    			for ($h = $hourStart; $h <= $hourEnd; $h += 100)
    			{
    				if ($h == $hourStart)
    				{
    					for ($m = $minutesStart; $m < 60; $m += 5)
    					{
							$availableHours[$h][$m < 10 ? "0".$m : $m] = 0;
    					}
    				}
    				elseif ($h == $hourEnd)
    				{
    					for ($m = 0; $m < $minutesEnd; $m += 5)
    					{
							$availableHours[$h][$m < 10 ? "0".$m : $m] = 0;
    					}
    				}
    				else
    				{
    					for ($m = 0; $m < 60; $m += 5)
    					{
    						$availableHours[$h][$m < 10 ? "0".$m : $m] = 0;
    					}
    				}
    			}
			}
    		// echo $hourStart." ".$minutesStart."<br>";
    		// echo $hourEnd." ".$minutesEnd."<br>";
    	}
    }

    // echo $datetimeStart;
    // echo $datetimeEnd;
    $availableHours = splitGaps($availableHours, $type, $business, $end);
	return $availableHours;
}
?>