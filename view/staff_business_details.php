<?php
$sql = "SELECT * FROM business
		WHERE business_id = {$_SESSION['staff_business_id']}";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();
?>
<div id="staff-business-details">
	<h1>Business Details</h1>

	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
		<div class="left-content-half">
			<fieldset>
				<legend>Business Information</legend>

				<?php include "./view/business_details_form.php"; ?>
			</fieldset>
		</div>

		<div class="right-content-half">
			<fieldset>
				<legend>Business Location</legend>

				<?php include "./view/business_location_form.php"; ?>
			</fieldset>
		</div>

		<div class="clear-both">
			<fieldset>
				<legend>Business Description</legend>

				<?php include "./view/business_description_form.php"; ?>
			</fieldset>

			<p><input type="submit" name="update_business_details" value="Submit"></p>
		</div>

	</form>
</div>

<hr>

<?php
class BusinessTimetable
{
	private $business;

	private $days = array(
		"mon" => "Monday",
		"tue" => "Tuesday",
		"wed" => "Wednesday",
		"thu" => "Thursday",
		"fri" => "Friday",
		"sat" => "Saturday",
		"sun" => "Sunday"
	);

	public function __construct()
	{
		global $mysqli;

		$sql = "SELECT * FROM business_timetable
				WHERE business_id = '{$_SESSION['staff_business_id']}'";
		$result = $mysqli->query($sql);
		$this->business = $result->fetch_assoc();
	}

	public function output()
	{
		echo "<table>";

		foreach ($this->days as $k => $v) {
			echo "<tr>";

			echo self::generate_form($k, "start");
			echo self::generate_form($k, "end");

			$name = "{$k}_off";

			if (isset($_POST[$name]) && $_POST[$name] == 1) {
				$checked = "checked";
			}
			elseif (!isset($_POST[$name]) && isset($this->business[$name]) && $this->business[$name] == 1) {
				$checked = "checked";
			}
			else {
				$checked = "";
			}

			echo "<td>";

			echo "<label for=\"{$name}\">Closed</label>&nbsp;";
			echo "<input type=\"checkbox\" name=\"{$name}\" id=\"{$name}\" value=\"1\" {$checked}>";

			echo "</td>";

			echo "</tr>";
		}

		echo "</table>";
	}

	private function generate_form($day, $startEnd)
	{
		$hourName = "{$day}_{$startEnd}_h";
		$minName  = "{$day}_{$startEnd}_m";


		$r = "";

		if ($startEnd == "start") {
			$r .= "<td>{$this->days[$day]}</td>";
		}

		$r .= "<td>";

		$r .= "<label for=\"{$hourName}\">".($startEnd == "start" ? "Open" : "Close")."</label>&nbsp;";

		$r .= "<select name=\"{$hourName}\" id=\"{$hourName}\">";
			$r .= "<option value=\"-1\">Hour</option>";

			for ($i=0; $i<=24; $i++) {

				if (isset($_POST[$hourName]) && $_POST[$hourName] == $i) {
					$selected = "selected";
				}
				elseif (!isset($_POST[$hourName]) && isset($this->business["{$day}_{$startEnd}"]) && substr($this->business["{$day}_{$startEnd}"], 0, -2) == $i) {
					$selected = "selected";
				}
				else {
					$selected = "";
				}

				$r .= "<option value=\"{$i}\" {$selected}>".($i < 10 ? "0" : "")."{$i}</option>";
			}
		$r .= "</select>&nbsp;:&nbsp;";

		$r .= "<select name=\"{$day}_{$startEnd}_m\">";
			$r .= "<option value=\"-1\">Min</option>";

			for ($i=0; $i<60; $i+=5) {

				if (isset($_POST[$minName]) && $_POST[$minName] == $i) {
					$selected = "selected";
				}
				elseif (!isset($_POST[$minName]) && isset($this->business["{$day}_{$startEnd}"]) && substr($this->business["{$day}_{$startEnd}"], -2) == $i) {
					$selected = "selected";
				}
				else {
					$selected = "";
				}

				$r .= "<option value=\"{$i}\" {$selected}>".($i < 10 ? "0" : "")."{$i}</option>";
			}
		$r .= "</select>";

		$r .= "</td>";

		return $r;
	}
}

$timetable = new BusinessTimetable;
?>
<div id="staff-business-timetable">
	<h1>Business Timetable</h1>

	<form action="staff_business_details.php" method="post">
		<?php $timetable->output(); ?>

		<p><input type="submit" name="update_business_timetable" value="Update"></p>
	</form>
</div>

<div id="staff-business-timetable-exceptions">
	<h1>Create New Exception</h1>

	<form action="staff_business_details.php" method="post">
		<p>
			<label for="exception_date">Date</label>
			<input type="text" name="exception_date" id="exception_date" class="datepicker-no-past" value="<?php echo isset($_POST['exception_date']) ? $_POST['exception_date'] : ""; ?>">
		</p>

		<p>
			<label for="exception_start_hour">Start</label>

			<select name="exception_start_hour" id="exception_start_hour">
				<option value="-1">Hour</option>

				<?php for ($i=0; $i<24; $i++) { ?>
					<?php
					if (isset($_POST['exception_start_hour']) && $_POST['exception_start_hour'] == $i) {
						$selected = "selected";
					}
					else {
						$selected = "";
					}
					?>
					<option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo ($i < 10 ? "0" : ""); ?><?php echo $i; ?></option>
				<?php } ?>
			</select> :

			<select name="exception_start_minute">
				<option value="-1">Min</option>

				<?php for ($i=0; $i<60; $i+=5) { ?>
					<?php
					if (isset($_POST['exception_start_minute']) && $_POST['exception_start_minute'] == $i) {
						$selected = "selected";
					}
					else {
						$selected = "";
					}
					?>
					<option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo ($i < 10 ? "0" : ""); ?><?php echo $i; ?></option>
				<?php } ?>
			</select>
		</p>

		<p>
			<label for="exception_end_hour">End</label>

			<select name="exception_end_hour" id="exception_end_hour">
				<option value="-1">Hour</option>

				<?php for ($i=0; $i<24; $i++) { ?>
					<?php
					if (isset($_POST['exception_end_hour']) && $_POST['exception_end_hour'] == $i) {
						$selected = "selected";
					}
					else {
						$selected = "";
					}
					?>
					<option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo ($i < 10 ? "0" : ""); ?><?php echo $i; ?></option>
				<?php } ?>
			</select> :

			<select name="exception_end_minute">
				<option value="-1">Min</option>

				<?php for ($i=0; $i<60; $i+=5) { ?>
					<?php
					if (isset($_POST['exception_end_minute']) && $_POST['exception_end_minute'] == $i) {
						$selected = "selected";
					}
					else {
						$selected = "";
					}
					?>
					<option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo ($i < 10 ? "0" : ""); ?><?php echo $i; ?></option>
				<?php } ?>
			</select>
		</p>

		<p><input type="submit" name="add_timetable_exception" value="Create"></p>
	</form>

	<h1>Set Exceptions</h1>

	<?php
	$sql = "SELECT * FROM business_exception
			WHERE business_id = '{$_SESSION['staff_business_id']}'
			AND `date` > NOW()
			ORDER BY `date` DESC";
	$types = $mysqli->query($sql);
	?>
	<?php if ($types->num_rows) { ?>
		<table>
			<tr>
				<th>Date</th>
				<th>Start</th>
				<th>End</th>
				<th>Actions</th>
			</tr>
			<?php while ($row = $types->fetch_assoc()) { ?>
				<tr>
					<td><?php echo date("D, d M Y", strtotime($row['date'])); ?></td>
					<td><?php echo substr_replace(str_pad($row['start'], 4, "0", STR_PAD_LEFT), ":", -2, 0); ?></td>
					<td><?php echo substr_replace(str_pad($row['end'], 4, "0", STR_PAD_LEFT), ":", -2, 0); ?></td>
					<td>
						<a href="staff_business_details.php?remove_exception=<?php echo $row['date']; ?>" class="confirm-delete">remove</a>
					</td>
				</tr>
			<?php } ?>
		</table>
	<?php } else { ?>
		<p>There are no exceptions.</p>
	<?php } ?>
</div>

<hr>

<div id="staff-appointment-types">
	<?php
	$edit = isset($_GET['appointment_type']) ? true : false;

	if ($edit) {
		$sql = "SELECT * FROM appointment_type
				WHERE appointment_type_id = {$mysqli->real_escape_string($_GET['appointment_type'])}";
		$result = $mysqli->query($sql);
		$row = $result->fetch_assoc();
	}
	else {
		unset($row);
	}
	?>

	<h1><?php echo $edit ? "Edit" : "Create"; ?> Appointment Type</h1>

	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">

		<p>
			<label for="appointment_type_name">Type Name</label>
			<input type="text" name="appointment_type_name" id="appointment_type_name" maxlength="100" value="<?php echo isset($_POST['appointment_type_name']) ? $_POST['appointment_type_name'] : (isset($row['name']) ? $row['name'] : "") ?>">
		</p>

		<p>
			<label for="appointment_type_length">Type Length</label>
			<input type="text" name="appointment_type_length" id="appointment_type_length" maxlength="11" value="<?php echo isset($_POST['appointment_type_length']) ? $_POST['appointment_type_length'] : (isset($row['length']) ? $row['length'] : "") ?>">
			<em>in minutes</em>
		</p>

		<p>
			<input type="submit" name="<?php echo $edit ? "edit_appointment_type" : "add_appointment_type"; ?>" value="Submit">
			<?php if ($edit) { ?>
				<a href="staff_business_details.php#staff-appointment-types">Cancel</a>
			<?php } ?>
		</p>

	</form>

	<h1>Appointment Types</h1>

	<?php
	$sql = "SELECT * FROM appointment_type
			WHERE business_id = '{$_SESSION['staff_business_id']}'
			ORDER BY name";
	$types = $mysqli->query($sql);
	?>
	<?php if ($types->num_rows) { ?>
		<table>
			<tr>
				<th>Type Name</th>
				<th>Length</th>
				<th>Actions</th>
			</tr>
			<?php while ($row = $types->fetch_assoc()) { ?>
				<?php
				$sql = "SELECT * FROM appointment WHERE appointment_type_id = '{$row['appointment_type_id']}' LIMIT 1";
				$result = $mysqli->query($sql);
				$num = $result->num_rows;
				?>
				<tr>
					<td><?php echo $row['name']; ?></td>
					<td><?php echo $row['length']; ?> minutes</td>
					<td>
						<?php if ($num == 0) { ?>
							<a href="staff_business_details.php?appointment_type=<?php echo $row['appointment_type_id']; ?>#staff-appointment-types">edit</a> -
							<a href="staff_business_details.php?remove_appointment_type=<?php echo $row['appointment_type_id']; ?>" class="confirm-delete">remove</a>
						<?php } else { ?>
							-
						<?php } ?>
					</td>
				</tr>
			<?php } ?>
		</table>
	<?php } else { ?>
		<p>There are no appointment types.</p>
	<?php } ?>
</div>