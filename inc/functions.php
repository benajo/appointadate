<?php
/**
 * @author Ben Jovanic
 * @version 2014-02-22
 *
 * Validates HTML form fields and returns an error if there is one.
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
			case "email": // validates input is an email address
				if (!filter_var($data, FILTER_VALIDATE_EMAIL)) {
					return $invalid;
				}
				break;

			case "name": // validates the input is a person's name
				if (!preg_match("/^([a-z\'\-\s]+)$/i", $data)) {
					return $invalid;
				}
				break;

			case "alpha": // alphabetic characters only
				if (!preg_match("/^([a-z]+)$/i", $data)) {
					return $invalid;
				}
				break;

			case "alnum": // alphabetic and number characters only
				if (!preg_match("/^([a-z0-9]+)$/i", $data)) {
					return $invalid;
				}
				break;

			case "num": // number characters only
				if (!preg_match("/^([0-9]+)$/i", $data)) {
					return $invalid;
				}
				break;

			case "decimal": // decimal inputs only
				if (!preg_match("/^([0-9.-]+)$/i", $data)) {
					return $invalid;
				}
				break;

			case "alpha_s": // same as "alpha", plus spaces
				if (!preg_match("/^([a-z\s]+)$/i", $data)) {
					return $invalid;
				}
				break;

			case "alnum_s": // same as "alnum", plus spaces
				if (!preg_match("/^([a-z0-9\s]+)$/i", $data)) {
					return $invalid;
				}
				break;

			case "num_s": // same as "num", plus spaces
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
 *
 * Ensures a user's password is not empty, both passwords match, and
 * the length is 8 or more characters long.
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
 *
 * Applies mysqli_real_escape_string() to the _POST data to combat SQL injection
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
 *
 * Checks if an email is already in use by another customer.
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
 *
 * Checks if an email is already in use by another staff member.
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
 *
 * Generates a random string of letters and numbers to the specified length.
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
 *
 * Creates the pagination links for some MySQL result set, such as the businesses listed on the businesses page.
 *
 * @param $limit   - This is the max amount of results to be in 1 page.
 * @param $page    - current page
 * @param $total   - total number of results in the query
 * @param $url     - the current url of the page
 * @param $pageVar - the get variable name of the page
 * @param $hash    - applies a "$#$hash" to the URL
 * @param $get     - a list of get variables that need to be catered for when using pagination (such as business search)
 */
function pagination($limit, $page, $total, $url, $pageVar="page", $hash="", $get=array())
{
	// calculate how many pages are needed
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
*
* Function that retrieves the businesses in the database that correspond to the given search terms.
*/
function search_businesses($from, $limit)
{
	global $mysqli;

	// Acquire all the non-empty search terms
	$name     = isset($_GET['keywords'])     && !empty($_GET['keywords'])     ? $_GET['keywords']     : "";
	$type     = isset($_GET['businessType']) && !empty($_GET['businessType']) ? $_GET['businessType'] : "";
	$location = isset($_GET['postcode'])     && !empty($_GET['postcode'])     ? $_GET['postcode']     : "";
	$range    = isset($_GET['range'])        && !empty($_GET['range'])        ? $_GET['range']        : "";

	// If all of the fields are empty select all of the businesses
	if (empty($name) && empty($type) && empty($location) && empty($range)) {
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

	// If the location and range fields are not empty
	if (!empty($location) && !empty($range)) {
		$location = urlencode($location);

		// Using the google api to get the corresponding latitude and longitude of a postcode
		$url = "http://maps.googleapis.com/maps/api/geocode/xml?address={$location}&sensor=false";
		$xml = simplexml_load_string(file_get_contents($url));

		$lat = $xml->result->geometry->location->lat;
		$long = $xml->result->geometry->location->lng;

		// Selecting all of the businesses as well as using math functions provided
		// by sql to compute the straight-line distance between the location determined
		// by the latitude and longitude attributes of the input postcode and the
		// latitude and longitude of all of the other businesses
		$sql = "SELECT *,
				(( 6371 * acos( cos( radians({$lat}) ) * cos( radians( `latitude` ) ) * cos( radians( `longitude` ) - radians({$long}) ) + sin( radians({$lat}) ) * sin( radians( `latitude` ) ) ) ) * 0.621371192) AS distance ";

		// After computing the distance, restrict the results to those that have the distance
		// smaller than or equal to the range in which we are searching
		$having = " HAVING distance <= {$range}
				   ORDER BY distance ASC";
	}

	// If one of the fields is empty, select everything, ignore distance
	else {
		$sql = "SELECT * ";
	}

	// If the type attribute is not empty, join the business and business_type tables
	if (!empty($type)) {
		$sql .= " FROM business b
				JOIN business_type bt ON b.business_id = bt.business_id ";
	}

	// Otherwise, ignore
	else {
		$sql .= " FROM business ";
	}

	// Boolean attribute that tells us whether an 'AND' should precede 'WHERE' clause
	$and = false;

	// If the type attribute is not empty, the first 'WHERE' clause is specified, to select
	// businesses of a certain type. Also, the 'and' attribute is now indicating that the
	// next 'WHERE' clause will be preceded by an 'AND'
	if (!empty($type))
	{
		$where1 =" WHERE ( bt.type_id = {$type} )";
		$and = true;
	}

	// If the name field is not empty
	if (!empty($name))
	{

		// Trim any extra whitespaces and split the keywords using ' ' as a divider
		$keywords = explode(' ', trim($name));

		// If the location or range fields were empty, there is not yet an 'ORDER' clause.
		// Create one
		if (empty($location) || empty($range)) {
			$order = " ORDER BY ";
		}

		// Otherwise, if they are both non-empty, simply add a comma as there is already
		// an 'ORDER' clause
		elseif (!empty($location) && !empty($range)) {
			$order = " , ";
		}

		// Determine whether this is the first 'WHERE' clause or not and formulate it accordingly
		if ($and) {
			$where2 .= " AND ( ";
		}
		else {
			$where2 .= " WHERE ( ";
		}

		// For each of the keywords add a 'LIKE' clause
		for ($i = 0; $i < count($keywords); $i++)
		{
			$key = $keywords[$i];
			$where2 .= "name LIKE '%$key%'";

			// Order the business names by relevance
			// If this is the first one, don't add a '+', otherwise, do.
			// In that way, the business with the most keywords in its name gets the highest score
			if ($i < 1){
				$order .= " case when `name` like '%$key%' then 1 else 0 end ";
			}
			else {
				$order .= " + case when `name` like '%$key%' then 1 else 0 end ";
			}

			// Add an 'OR' after every 'LIKE' clause except the last one
			if ($i < count($keywords) - 1) {
				$where2 .= " OR ";
			}

			// If it is the last one, order the results descending by the score obtained above
			else {
				$order .= " DESC ";
			}
		}

		$where2 .=") ";
	}

	// Putting it all together
	$sql .= $where1;
	$sql .= $where2;
	$sql .= $having;
	$sql .= $order;

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
*
* Displays the "add to favourites" or "remove from favourites" links beside a business name for a logged in customer.
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
 *
 * Generates the HTML for a business' types. Displays on the businesses page and index page.
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
 *
 * Generates the "create new appointment" link on the business page for logged in customers.
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
*
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
*
* Function that splits the gaps in a staff member's schedule according to possible appointment types
*/
function splitGaps($available, $type, $endTime, $business = 0)
{
	global $mysqli;

	// As we are using the same algorithm to schedule appointments both for customers and staff,
	// we need a way to differentiate between the degrees of freedom that each have.
	// We use the variable below to keep a record of whether a staff is making the appointment or
	// a customer is.
	$isStaff = false;

	// If a business variable was not passed (the default value was not changed),
	// it means a staff is requesting the appointment.
	if ($business == 0)
	{
		// The session variable for staff_business_id is set (in the login controller) tells
		// us the corresponding business of the staff requesting the appointment.
		$business = $_SESSION['staff_business_id'];
		$isStaff = true;
	}

	// SQL for retrieving the appointment types and their lengths corresponding to the business variable
	$sql = "SELECT appointment_type_id, length
			FROM appointment_type
			WHERE business_id = {$business}";

	$result = $mysqli->query($sql);

	// If the query returns at least one result
	if ($result->num_rows)
	{
		$types = array();
		$length = 0;

		// Take each result and put the length in an array
		while ($row = $result->fetch_assoc())
		{
			$types[] = $row['length'];

			// If the current appointment type corresponds to the requested appointment's
			// retain the length attribute for use later.
			if ($row['appointment_type_id'] == $type)
			{
				$length = $row['length'];
			}
		}
	}

	// Sort the appointment types in descending order.
	rsort($types);
	$newGap = true;
	$gapSize = 0;
	$minutesStart = 00;

	// Iterating through the array hour by hour and...
	foreach ($available as $hour => $minutes)
	{
		// ...minute by minute.
		foreach ($minutes as $minute => $value)
		{
			// Checks if it is a new gap that has been found or it is inside of a gap.
			if ($newGap)
			{
				// Alters the start hour of the gap if it is a new one.
				$hourStart = $hour;
			}

			// If the slot is available and it is not the end of the day.
			if (($value == 1) && (!endOfDay($hour, $minute, $endTime)))
			{
				// Again, checking whether the gap is new
				if ($newGap)
				{
					// Altering the start minute of the gap
					$minutesStart = $minute;
					$newGap = false;
				}

				// Increasing the size of the gap
				$gapSize += 5;
			}
			else
			// If the slot is not available, the gap is closed
			{

				// Set the end hour and minute
				$hourEnd = $hour;
				if (endOfDay($hour, $minute, $endTime))
					$minutesEnd = $minute + 5;
				else
					$minutesEnd = $minute;

				// Set the increment to 5 (minutes)
				$increment = 5;

				// If the gap is big enough to fit the new appointment
				if ($gapSize >= $length)
				{

					// If it is not a staff member that is requesting the appointment
					if (!$isStaff)
						// Calculate the largest appointment that would fit in that gap along with the new one
						for ($i = 0; $i < count($types); $i++)
						{
							if ($types[$i] + $length <= $gapSize)
							{
								// Set the increment to the afore mentioned appointment length
								$increment = $types[$i];
								break;
							}
						}

					// In what follows, the gap is iterated through and slots are made unavailable according
					// to the set increment.
					// Every time the loop has iterated and reached the size of the increment a new one is calculated
					// with regards to the remaining size of the gap
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
    						{
    							$i = 1;
    							$increment = 5;
    							if (!$isStaff)
									for ($j = 0; $j < count($types); $j++)
									{
										if ($types[$j] + $length <= $gapSize)
										{

											$increment = $types[$j];
											break;
										}
									}
	    						$gapSize -= 5;
    						}

    					}
    				}
    				else
    				{
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
    								{
    									$i = 1;
    									$increment = 5;
    									if (!$isStaff)
											for ($j = 0; $j < count($types); $j++)
											{
												if ($types[$j] + $length <= $gapSize)
												{

													$increment = $types[$j];
													break;
												}
											}
    								}
    								$gapSize -= 5;
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
    								{
    									$i = 1;
    									$increment = 5;
    									if (!$isStaff)
											for ($j = 0; $j < count($types); $j++)
											{
												if ($types[$j] + $length <= $gapSize)
												{

													$increment = $types[$j];
													break;
												}
											}
    								}
    								$gapSize -= 5;
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
    								{
    									$i = 1;
    									$increment = 5;
    									if (!$isStaff)
											for ($j = 0; $j < count($types); $j++)
											{
												if ($types[$j] + $length <= $gapSize)
												{

													$increment = $types[$j];
													break;
												}
											}
    								}
    								$gapSize -= 5;
    							}
    						}
    					}
					}
				}
				elseif ($gapSize >= $increment)
				{
					if ($hourStart == $hourEnd)
    				{
    					for ($m = (int)$minutesStart; $m < $minutesEnd; $m += 5)
    					{
    						$available[$hourStart][$m < 10 ? "0".$m : $m] = 0;
    					}
    				}
    				else
    				{
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

				// All of the processing for one gap has finished, set the new gap variable to true
				$newGap = true;
				$gapSize = 0;
			}

		}
	}

	// Return the updated array to the calling function
	return $available;
}

/**
* @author Vlad-Tudor Marchis
* @version 2014-03-21
*
* Function that finds the availability of a certain member of staff on a certain day
* taking into account their timetable and booked appointments
*/
function findAvailableTimes($date, $type, $staff_id, $business = 0)
{
	global $mysqli;

	$day = $date;

	// Change the format of the date to query the exceptions table
	$date = DateTime::createFromFormat("D, d F Y", $date);
	$date = $date->format("Y-m-d");

	// Querying the exceptions table to check for any differences from the normal timetable
	$sqlException = "SELECT start, end, off
					FROM staff_exception
					WHERE staff_id = {$staff_id}
					AND date = '{$date}'";
	$resultHours = $mysqli->query($sqlException);

	// If an exception is found
	if ($resultHours->num_rows) {
		$row = $resultHours->fetch_assoc();

		// If the exception is not that the staff is off prepare to get the start and end times
		// of the day by assigning the column names to variables
		if ($row['off'] == 0)
		{
			$start = 'start';
			$end = 'end';
		}

		// Else return an appropriate message (handled in the view)
		else
			return 'off';
	}

	// If an exception is not found
	else
	{
		// Turn the date into day of the week format
		$day = strftime("%a",strtotime($day));
		$day = strtolower($day);

		// Prepare to query the table by creating column names from the day variable
		// e.g: mon_start; mon_end; mon_off
		$start = $day.'_start';
		$end = $day.'_end';
		$off = $day.'_off';

		// Query the timetable for the selected staff to get their start time, end time
		// and whether or not they are off
		$sqlHours = "SELECT {$start}, {$end}, {$off}
					FROM staff_timetable
					WHERE staff_id = {$staff_id}";
		$resultHours = $mysqli->query($sqlHours);

		// If an entry is found
		if ($resultHours->num_rows)
		{
			$row = $resultHours->fetch_assoc();

			// If the staff is off return an appropriate message (handled in the view)
			if ($row[$off] != 0)
				return 'off';
		}
	}

	// Otherwise, carry on.
	// Create an array (map) to store the available times and retrieve the start and end times
	// either from the exceptions or the timetable tables.
	// The structure of the map is:
	// key - hour of format hh00 - e.g 1200
	// value - another map detailed below
	$availableHours = array();
	$start = $row[$start];
	$end = $row[$end];

	// Formatting to make sure that the start and end hours are format hh00
	if ($start < 1000)
		$startHour = substr($start, 0, 1)."00";
	else
		$startHour = substr($start, 0, 2)."00";
	if ($end < 1000)
		$endHour = substr($end, 0, 1)."00";
	else
		$endHour = substr($end, 0, 2)."00";

	// Create an array (map) to store the available minutes of each hour.
	// Structure of the map:
	// key - minute of format mm - e.g. 05
	// value - 0 or 1 - 1 for available, 0 for unavailable
	// This map is used as a value for the 'availableHours' one above
	$availableMinutes = array();

	// For minutes of an hour that is not a business hour in its entirety
	// i.e. start hour is 10:15 - make minutes from 00 to 10 unavailable
	// make the rest available. At this point, we are not taking into account
	// any of the booked appointments. We just assume the whole day is available.
	for ($i = (int)substr($startHour, -2); $i < substr($start, -2) ;$i += 5)
	{

		// Formatting to make sure the minutes are in mm format
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

	// Adding the map as a value to the map holding the hours with the key corresponding
	// to the start hour
	$availableHours[$startHour] = $availableMinutes;

	// Repeating the process for each hour until the end hour
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
			// If we get to the end hour, we want to find out if it is available in its entirety
			// or ends at some point before the next hour, i.e: 16:45.
			// Handled as above.
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

	// Create limits for where to search for appointments using the date passed by the user
	$datetimeStart = $date." "."00:00";
	$datetimeStart = DateTime::createFromFormat("Y-m-d H:i", $datetimeStart);
	$datetimeStart = $datetimeStart->format("Y-m-d H:i:s");

	$datetimeEnd = $date." "."23:59";
	$datetimeEnd = DateTime::createFromFormat("Y-m-d H:i", $datetimeEnd);
	$datetimeEnd = $datetimeEnd->format("Y-m-d H:i:s");

	// Query the appointment table and find all of the appointments for the specified date
	// booked with the specified staff
	$sqlApp = "SELECT
				a.datetime,
				b.length
			   FROM appointment a
			   JOIN appointment_type b ON a.appointment_type_id = b.appointment_type_id
			   WHERE a.staff_id = {$staff_id}
			   AND a.datetime >= '{$datetimeStart}'
			   AND a.datetime <= '{$datetimeEnd}'
			   AND a.cancelled = 0";


    $resultApp = $mysqli->query($sqlApp);

    // If any are found
    if ($resultApp->num_rows)
    {
    	//Take all of the results in the table on by one
    	while ($row = $resultApp->fetch_assoc())
    	{
    		// Get the time and length of the appointment
    		$time = $row['datetime'];
    		$length = $row['length'];

    		// Get the hours and minutes for the start and end of each appointment
    		$minutesStart = substr($time, 14, 2);
    		$minutesEnd = $minutesStart + $length;

    		$hourStart = substr($time, 11, 2);
    		$hourStart = (int)$hourStart;
    		$hourEnd = (int)($minutesEnd/60)+ $hourStart;
    		$hourStart *= 100;
    		$hourEnd *= 100;

    		// Formatting the end minute of an appointment to be between 0 and 60
    		if ($minutesEnd >= 60)
    			$minutesEnd %= 60;

    		// In what follows, we make all of the times that the appointment spans unavailable
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
    	}
    }

    // After the array has been updated to account for timetable and appointments, the gaps in it are split
    $availableHours = splitGaps($availableHours, $type, $end, $business);
	return $availableHours;
}
?>