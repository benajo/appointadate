<?php
class StaffTimetable
{
	private $business, $staff;

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

		$sql = "SELECT * FROM staff_timetable
				WHERE staff_id = '{$_SESSION['staff_id']}'";
		$result = $mysqli->query($sql);
		$this->staff = $result->fetch_assoc();
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
			elseif (!isset($_POST[$name]) && isset($this->staff[$name]) && $this->staff[$name] == 1) {
				$checked = "checked";
			}
			else {
				$checked = "";
			}

			echo "<td>";

			echo "<label for=\"{$name}\">Off</label>&nbsp;";
			echo "<input type=\"checkbox\" name=\"{$name}\" id=\"{$name}\" value=\"1\" {$checked}></td>";

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

		$r .= "<label for=\"{$hourName}\">".($startEnd == "start" ? "Start" : "End")."</label>&nbsp;";

		$r .= "<select name=\"{$hourName}\" id=\"{$hourName}\">";
			$r .= "<option value=\"-1\">Hour</option>";

			for ($i=substr($this->business["{$day}_start"], 0, -2); $i<=substr($this->business["{$day}_end"], 0, -2); $i++) {

				if (isset($_POST[$hourName]) && $_POST[$hourName] == $i) {
					$selected = "selected";
				}
				elseif (!isset($_POST[$hourName]) && isset($this->staff["{$day}_{$startEnd}"]) && substr($this->staff["{$day}_{$startEnd}"], 0, -2) == $i) {
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
				elseif (!isset($_POST[$minName]) && isset($this->staff["{$day}_{$startEnd}"]) && substr($this->staff["{$day}_{$startEnd}"], -2) == $i) {
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

$timetable = new StaffTimetable;
?>
<div id="staff-timetable">
	<h1>Staff Timetable</h1>

	<form action="staff_timetable.php" method="post">
		<?php $timetable->output(); ?>

		<p><input type="submit" name="update_staff_timetable" value="Update"></p>
	</form>
</div>

<hr>

<div id="staff-timetable-exceptions">
	<h1>Create New Exception</h1>

	<form action="staff_timetable.php" method="post">
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

	<h1>Your Exceptions</h1>

	<?php
	$sql = "SELECT * FROM staff_exception
			WHERE staff_id = '{$_SESSION['staff_id']}'
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
					<td><?php echo substr_replace($row['start'], ":", -2, 0); ?></td>
					<td><?php echo substr_replace($row['end'], ":", -2, 0); ?></td>
					<td>
						<a href="staff_timetable.php?remove_exception=<?php echo $row['date']; ?>" class="confirm-delete">remove</a>
					</td>
				</tr>
			<?php } ?>
		</table>
	<?php } else { ?>
		<p>There are no exceptions.</p>
	<?php } ?>
</div>