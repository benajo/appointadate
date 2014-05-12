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
				if (!preg_match("/^([a-z\'\-\s]+)$/i", $data)) {
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

			case "decimal":
				if (!preg_match("/^([0-9.-]+)$/i", $data)) {
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
		if (!is_array($v)) {
			$escaped[$k] = $mysqli->real_escape_string(trim($v));
		}
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
 * @author Ben Jovanic
 * @version 2014-04-28
 */
function pagination($limit, $from, $page, $total, $url, $pageVar="page", $hash="", $get=array())
{
	$pages = ceil($total / $limit);

	$getVars = "";

	if (count($get)) {
		foreach ($get as $val) {
			if (isset($_GET[$val])) {
				$getVars .= "{$val}={$_GET[$val]}&amp;";
			}
		}
	}

	$newUrl = "{$url}?{$getVars}{$pageVar}=PAGENUMBER".(strlen($hash) > 0 ? "#{$hash}" : "");

	if ($pages > 1) {
		?>
		<p class="pagination">
			<?php if ($page > 1) { ?>
				<a href="<?php echo str_replace("PAGENUMBER", 1, $newUrl); ?>">First</a>
				<a href="<?php echo str_replace("PAGENUMBER", $page - 1, $newUrl); ?>">Prev</a>
			<?php } ?>

			<?php for ($i=($page - 2); $i <= ($page + 2); $i++) { ?>
				<?php if (($i >= 1) && ($i <= $pages)) { ?>
					<?php if ($page == $i) { ?>
						[<?php echo $i; ?>]
					<?php } else { ?>
						<a href="<?php echo str_replace("PAGENUMBER", $i, $newUrl); ?>"><?php echo $i; ?></a>
					<?php } ?>
				<?php } ?>
			<?php } ?>

			<?php if ($page < $pages) { ?>
				<a href="<?php echo str_replace("PAGENUMBER", $page + 1, $newUrl); ?>">Next</a>
				<a href="<?php echo str_replace("PAGENUMBER", $pages, $newUrl); ?>">Last</a>
			<?php } ?>
		</p>
		<?php
	}
}


/**
* @author Vlad-Tudor Marchis
* @version 2014-03-03
*/
function search_businesses($from, $limit)
{
	global $mysqli;

	$keywords = isset($_GET['keywords'])     && !empty($_GET['keywords'])     ? $mysqli->real_escape_string($_GET['keywords'])     : "";
	$type     = isset($_GET['businessType']) && !empty($_GET['businessType']) ? $mysqli->real_escape_string($_GET['businessType']) : "";
	$location = isset($_GET['postcode'])     && !empty($_GET['postcode'])     ? $mysqli->real_escape_string($_GET['postcode'])     : "";
	$range    = isset($_GET['range'])        && !empty($_GET['range'])        ? $mysqli->real_escape_string($_GET['range'])        : "";

	if (empty($keywords) && empty($type) && empty($location) && empty($range)) {
		$sql = "SELECT * FROM business
				ORDER BY name";
		$result = $mysqli->query($sql);
		$total = $result->num_rows;

		$sql = "SELECT * FROM business
				ORDER BY name
				LIMIT {$from}, {$limit}";
		$result = $mysqli->query($sql);

		return array($result, $total);
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

	if (!empty($keywords))
	{
		$keywords = explode(' ', trim($keywords));

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
	}

	$sql .= $where1;
	$sql .= $where2;
	$sql .= $having;
	$sql .= $order;

	// echo $sql;

	// get total number of results
	$result = $mysqli->query($sql);
	$total = $result->num_rows;

	// get limited to pagination results
	$result = $mysqli->query("{$sql} LIMIT {$from}, {$limit}");

	return array($result, $total);

}

/**
* @author Ben Jovanic
* @version 2014-03-03
*/
function favourite_business($business_id)
{
	global $mysqli;

	$html = "";

	if (isset($_SESSION['customer_logged_in'])) {
		$sql = "SELECT * FROM customer_pref_business
				WHERE customer_id = '{$_SESSION['customer_id']}'
				AND   business_id = '{$business_id}'";
		$result = $mysqli->query($sql);

		// if entry exists, they have already favourited the business, so allow them to remove the business
		if ($result->num_rows) {
			$html .= "<div class=\"favourite\"> - <a href=\"businesses.php?remove_favourite_business={$business_id}\">Remove from favourites</a></div>";
		}
		// otherwise, allow them to add the business as a favourite
		else {
			$html .= "<div class=\"favourite\"> - <a href=\"businesses.php?add_favourite_business={$business_id}\">Add to favourites</a></div>";
		}
	}

	return $html;
}

/**
 * @author Ben Jovanic
 * @version 2014-04-28
 */
function business_types_html($business_id)
{
	global $mysqli;

	$sql = "SELECT * FROM business_type bt
			JOIN type t ON bt.type_id = t.type_id
			WHERE business_id = {$business_id}
			ORDER BY t.name";
	$types = $mysqli->query($sql);

	if ($types->num_rows) {
		?>
		<p class="types">
			Business type(s):
			<?php
			$typesHTML = array();

			while ($type = $types->fetch_assoc()) {
				$typesHTML[] = "<a href=\"businesses.php?businessType={$type['type_id']}\">{$type['name']}</a>";
			}

			echo implode(", ", $typesHTML);
			?>
		</p>
		<?php
	}
}

/**
 * @author Ben Jovanic
 * @version 2014-05-12
 */
function create_appointment_html($business_id)
{
	global $mysqli;

	$html = "";

	if (isset($_SESSION['customer_logged_in'])) {
		$html .= "<div class=\"create-appointment\"> - <a href=\"customer_create_appointment.php?business={$business_id}\">Create new apppointment</a></div>";
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
		if (($hour == $endHour - 100) && ($minute == 55)) {
			return true;
		}
	}
	else
	{
		if (($hour == $endHour) && ($minute == $endMinute - 5)) {
			return true;
		}
	}

}

/**
* @author Vlad-Tudor Marchis
* @version 2014-04-17
*/
function splitGaps($available, $type, $endTime, $business = 0)
{
	global $mysqli;

	$isStaff = false;
	if ($business == 0)
	{
		$business = $_SESSION['staff_business_id'];
		$isStaff = true;
	}

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
	$minutesStart = 00;
	foreach ($available as $hour => $minutes)
	{
		// $minutesStart = 0;
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
			else
			{
				$hourEnd = $hour;
				if (endOfDay($hour, $minute, $endTime))
					$minutesEnd = $minute + 5;
				else
					$minutesEnd = $minute;
				$increment = 5;
				// echo $gapSize."<br>";
				if ($gapSize >= $length)
				{
					// print_r($types);
					if (!$isStaff)
						for ($i = 0; $i < count($types); $i++)
						{
							if ($types[$i] + $length <= $gapSize)
							{

								$increment = $types[$i];
								break;
							}
						}
					// echo $increment."<br>";
					// echo $hourStart." ";
					// echo $minutesStart."<br>";
					// echo $hourEnd." ";
					// echo $minutesEnd."<br>";

					if ($hourStart == $hourEnd)
    				{
    					// echo "you are here";
    					$i = 1;
    					for ($m = (int)$minutesStart+5; $m < $minutesEnd; $m += 5)
    					{
    						// echo $m."<br>";
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
    								// echo $m."<br>";
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
				elseif ($gapSize >= $increment)
				{
					// echo $hourStart." ";
					// echo $minutesStart."<br>";
					// echo $hourEnd." ";
					// echo $minutesEnd."<br>";

					if ($hourStart == $hourEnd)
    				{
    					for ($m = (int)$minutesStart; $m < $minutesEnd; $m += 5)
    					{
    						$available[$hourStart][$m < 10 ? "0".$m : $m] = 0;
    					}
    				}
    				else
    				{
    					//echo $hourStart."<br> ";
						//echo $hourEnd." ";
    					for ($h = $hourStart; $h <= $hourEnd; $h += 100)
    					{
    						if ($h == $hourStart)
    						{
    							for ($m = (int)$minutesStart; $m < 60; $m += 5)
    							{
									$available[$h][$m < 10 ? "0".$m : $m] = 0;
    							}
    						}
    						elseif ($h == $hourEnd)
    						{
    							for ($m = 0; $m < $minutesEnd; $m += 5)
    							{
    								$available[$h][$m < 10 ? "0".$m : $m] = 0;
    							}
    						}
    						else
    						{
    							for ($m = 0; $m < 60; $m += 5)
    							{
									$available[$h][$m < 10 ? "0".$m : $m] = 0;
    							}
    						}
    					}
					}
				}

				$newGap = true;
				$gapSize = 0;
				// echo "Inside For".$newGap."<br>";
			}

		}
	}

	return $available;
}

/**
* @author Vlad-Tudor Marchis
* @version 2014-03-21
*/
function findAvailableTimes($date, $type, $staff_id, $business = 0)
{
	// echo $date."<br>";
	// echo $type."<br>";
	// echo $staff_id;
	global $mysqli;
	$day = $date;
	$date = DateTime::createFromFormat("D, d F Y", $date);
	$date = $date->format("Y-m-d");

	$sqlException = "SELECT start, end, off
					FROM staff_exception
					WHERE staff_id = {$staff_id}
					AND date = '{$date}'";
	$resultHours = $mysqli->query($sqlException);

	if ($resultHours->num_rows) {
		$row = $resultHours->fetch_assoc();
		if ($row['off'] == 0)
		{
			$start = 'start';
			$end = 'end';
		}
		else
			return 'off';
	}
	else
	{
		$day = strftime("%a",strtotime($day));
		$day = strtolower($day);

		$start = $day.'_start';
		$end = $day.'_end';
		$off = $day.'_off';

		$sqlHours = "SELECT {$start}, {$end}, {$off}
					FROM staff_timetable
					WHERE staff_id = {$staff_id}";
		$resultHours = $mysqli->query($sqlHours);
		if ($resultHours->num_rows)
		{
			$row = $resultHours->fetch_assoc();
			if ($row[$off] != 0)
				return 'off';
		}
	}

	$availableHours = array();
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
	// Altering the timetable based on previously booked appointments.

	$datetimeStart = $date." "."00:00";
	$datetimeStart = DateTime::createFromFormat("Y-m-d H:i", $datetimeStart);
	$datetimeStart = $datetimeStart->format("Y-m-d H:i:s");
	// $datetimeStart .= ".000";

	$datetimeEnd = $date." "."23:59";
	$datetimeEnd = DateTime::createFromFormat("Y-m-d H:i", $datetimeEnd);
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
    $availableHours = splitGaps($availableHours, $type, $end, $business);
	return $availableHours;
}
?>