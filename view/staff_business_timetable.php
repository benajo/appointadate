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

	<form action="staff_business_timetable.php" method="post">
		<?php $timetable->output(); ?>

		<p><input type="submit" name="update_business_timetable" value="Update"></p>
	</form>
</div>