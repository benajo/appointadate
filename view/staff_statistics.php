<?php
$freq_times = array();
$by_staff   = array();
$app_period = array();

$from = isset($_POST['from']) ? strtotime($_POST['from']) : 0;
$to   = isset($_POST['to']) ? strtotime($_POST['to']) : 0;

if (isset($_POST['freq_times'])) {
	$sql = "SELECT COUNT(*), CONCAT(s.first_name, ' ', s.last_name), DATE(a.datetime) FROM appointment a
			JOIN staff s ON a.staff_id = s.staff_id
			WHERE a.business_id = '{$_SESSION['staff_business_id']}'
			AND (a.datetime BETWEEN '".(date("Y-m-d", $from))." 0:0:0' AND '".(date("Y-m-d", $to))." 23:59:59')
			GROUP BY DATE(a.datetime), a.staff_id
			ORDER BY a.datetime";
	$result = $mysqli->query($sql);

	if ($result && $result->num_rows > 0) {
		while ($row = $result->fetch_row()) {
			$freq_times[$row[2]][$row[1]] = $row[0];
		}
	}
}
elseif (isset($_POST['by_staff'])) {

	$staff_list = array();

	$sql = "SELECT CONCAT(first_name, ' ', last_name) AS staff_name FROM staff
			WHERE business_id = '{$_SESSION['staff_business_id']}'
			ORDER BY staff_name";
	$result = $mysqli->query($sql);

	if ($result && $result->num_rows > 0) {
		while ($row = $result->fetch_row()) {
			$staff_list[] = $row[0];
		}
	}

	$sql = "SELECT COUNT(*), CONCAT(s.first_name, ' ', s.last_name) AS staff_name, DATE(a.datetime) FROM appointment a
			JOIN staff s ON a.staff_id = s.staff_id
			WHERE a.business_id = '{$_SESSION['staff_business_id']}'
			AND (a.datetime BETWEEN '".(date("Y-m-d", $from))." 0:0:0' AND '".(date("Y-m-d", $to))." 23:59:59')
			GROUP BY DATE(a.datetime), a.staff_id
			ORDER BY a.datetime";
	$result = $mysqli->query($sql);

	if ($result && $result->num_rows > 0) {
		while ($row = $result->fetch_row()) {
			$by_staff[date("d M Y", strtotime($row[2]))][$row[1]] = $row[0];
		}
	}
}
elseif (isset($_POST['app_period'])) {

	$sql = "SELECT COUNT(*), DATE(datetime) FROM appointment a
			WHERE a.business_id = '{$_SESSION['staff_business_id']}'
			AND (a.datetime BETWEEN '".(date("Y-m-d", $from))." 0:0:0' AND '".(date("Y-m-d", $to))." 23:59:59')
			GROUP BY DATE(a.datetime)
			ORDER BY a.datetime";
	$result = $mysqli->query($sql);

	if ($result && $result->num_rows > 0) {
		while ($row = $result->fetch_row()) {
			$app_period[date("d M Y", strtotime($row[1]))] = $row[0];
		}
	}
}


?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<div id="staff-statistics">
	<h1>Statistics</h1>


	<div>
		<h2>Most frequently booked times</h2>
		<p><em>Most frequently booked times and day for appointments</em></p>

		<form action="staff_statistics.php" method="post">
			<p>
				<label for="freq_times_from" class="inline">From</label>
				<input type="text" name="from" id="freq_times_from" class="datepicker" value="<?php echo isset($_POST['from']) ? $_POST['from'] : ""; ?>">

				<label for="freq_times_to" class="inline">To</label>
				<input type="text" name="to" id="freq_times_to" class="datepicker" value="<?php echo isset($_POST['to']) ? $_POST['to'] : ""; ?>">

				<input type="submit" name="freq_times" value="Go">
			</p>
		</form>

		<?php //echo "<PRE>";print_r($freq_times);echo "</PRE>"; ?>
	</div>

	<div>
		<h2>Appointments by staff</h2>
		<p><em>Graph of how many appointments each staff has taken in a given time period</em></p>

		<form action="staff_statistics.php" method="post">
			<p>
				<label for="by_staff_from" class="inline">From</label>
				<input type="text" name="from" id="by_staff_from" class="datepicker" value="<?php echo isset($_POST['from']) ? $_POST['from'] : ""; ?>">

				<label for="by_staff_to" class="inline">To</label>
				<input type="text" name="to" id="by_staff_to" class="datepicker" value="<?php echo isset($_POST['to']) ? $_POST['to'] : ""; ?>">

				<input type="submit" name="by_staff" value="Go">
			</p>
		</form>

		<?php if (count($by_staff) > 0) { ?>

			<?php //echo "<PRE>";print_r($staff_list);echo "</PRE>"; ?>
			<?php //echo "<PRE>";print_r($by_staff);echo "</PRE>"; ?>

			<script type="text/javascript">
				google.load("visualization", "1", { packages:["corechart"] });

				google.setOnLoadCallback(drawChart);

				function drawChart() {
					var data = google.visualization.arrayToDataTable([
						['Day', "<?php echo implode('","', $staff_list) ?>"],

						<?php foreach ($by_staff as $day => $staff) { ?>
							['<?php echo $day; ?>',

							<?php foreach ($staff_list as $value) { ?>
								<?php echo isset($staff[$value]) ? $staff[$value] : 0; ?>,
							<?php } ?>

							],
						<?php } ?>
					]);

					var options = {
						hAxis: {
							title: 'Day',
						},
						vAxis: {
							title: 'Count',
						},
						isStacked: true
					};

					var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));

					chart.draw(data, options);
				}
			</script>

			<div id="chart_div" style="width: 1000px; height: 500px;"></div>

		<?php } ?>
	</div>

	<div>
		<h2>Total appointments during time period</h2>
		<p><em>Total appointments during a specified time period</em></p>

		<form action="staff_statistics.php" method="post">
			<p>
				<label for="app_period_from" class="inline">From</label>
				<input type="text" name="from" id="app_period_from" class="datepicker" value="<?php echo isset($_POST['from']) ? $_POST['from'] : ""; ?>">

				<label for="app_period_to" class="inline">To</label>
				<input type="text" name="to" id="app_period_to" class="datepicker" value="<?php echo isset($_POST['to']) ? $_POST['to'] : ""; ?>">

				<input type="submit" name="app_period" value="Go">
			</p>
		</form>

		<?php if (count($app_period) > 0) { ?>

			<?php //echo "<PRE>";print_r($app_period);echo "</PRE>"; ?>

			<script type="text/javascript">
				google.load("visualization", "1", { packages:["corechart"] });

				google.setOnLoadCallback(drawChart);

				function drawChart() {
					var data = google.visualization.arrayToDataTable([
						['Day', 'Count'],

						<?php foreach ($app_period as $day => $count) { ?>
							['<?php echo $day; ?>', <?php echo $count; ?>],
						<?php } ?>
					]);

					var options = {
						hAxis: {
							title: 'Day',
						},
						vAxis: {
							title: 'Count',
						},
						legend: { position: 'none' },
					};

					var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));

					chart.draw(data, options);
				}
			</script>

			<div id="chart_div" style="width: 1000px; height: 500px;"></div>

		<?php } ?>

	</div>
</div>

<?php
// $start = strtotime("2014-05-01 08:00:00");
// $end   = strtotime("2014-05-31 00:00:00");

// for ($i=$start; $i < $end; $i+=1800) {

// 	if (date("H", $i) > 20) {
// 		$i = strtotime("+12 hours", $i);
// 		continue;

// 	}

// 	if (rand(0,2)) {
// 		continue;
// 	}

// 	echo $sql = "
// 	INSERT INTO `web14-appoint`.`appointment` VALUES (NULL , '1', '".rand(1,9)."', '".rand(1,7)."', '1', '".date("Y-m-d H:i:s", $i)."', '1', '0', NOW( ) , NOW( ));

// 	";

// 	$mysqli->query($sql);

// 	echo "<BR>";
// }
?>