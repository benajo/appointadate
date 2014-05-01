<div id="customer-create-appointment">
	<?php if (isset($_GET['business']) && !empty($_GET['business']) && isset($_GET['staff']) && !empty($_GET['staff']) && isset($_GET['date']) && !empty($_GET['date']) && isset($_GET['type']) && !empty($_GET['type'])) { ?>

		<h1>Create Appointment - Select a Time</h1>

		<form action="customer_create_appointment.php" method="post">
			<input type="hidden" name="business" value="<?php echo $_GET['business']; ?>">
			<input type="hidden" name="date" value="<?php echo $_GET['date']; ?>">
			<input type="hidden" name="staff" value="<?php echo $_GET['staff']; ?>">
			<input type="hidden" name="type" value="<?php echo $_GET['type']; ?>">

			<?php $availableTimes = findAvailableTimes($_GET['date'], $_GET['type'], $_GET['staff'], $_GET['business']);
			//echo "<pre>";print_r($availableTimes);echo "</pre>";
			if ($availableTimes == 'off') {
				echo '<br> There are no appointments available for the current selection.';
			}
			else {
				?>
				<table class="column-highlighter">
					<thead>
						<tr>
							<td>&nbsp;</td>
							<?php for ($i=0; $i < 60; $i+=5) { ?>
								<td class = "td-highlight"><?php echo ($i < 10 ? "0" : "").$i; ?></td>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($availableTimes as $hour => $minutes) { ?>
							<tr class="trhighlight">
								<td>
									<?php echo substr_replace(($hour < 1000 ? "0" : "").$hour, ":", 2, 0); ?>
								</td>
								<?php foreach ($minutes as $minute => $value) { ?>
									<td class = "td-highlight radio">
										<?php if ($value == 1){ ?>
											<input name = "time" type="radio" value ="<?php echo substr($hour, 0, ($hour < 1000 ? 1 : 2)).":".$minute ?>">
										<?php } else { ?>
											&nbsp;
										<?php } ?>
									</td>
								<?php } ?>
							</tr>

						<?php } ?>
					</tbody>
				</table>

				<p><input type="submit" name="create_appointment" value="Submit"> <a href="customer_create_appointment.php?business=<?php echo $_GET['business']; ?>&amp;date=<?php echo $_GET['date']; ?>&amp;staff=<?php echo $_GET['staff'];?>">Back</a></p>
				<?php
			}
			?>
		</form>

	<?php } elseif (isset($_GET['business']) && !empty($_GET['business']) && isset($_GET['staff']) && !empty($_GET['staff']) && isset($_GET['date']) && !empty($_GET['date'])) { ?>

		<h1>Create Appointment - Select Appointment Type</h1>

		<form action = "customer_create_appointment.php" method = "get">
			<input type="hidden" name="business" value="<?php echo $_GET['business']; ?>">
			<input type="hidden" name="date" value="<?php echo $_GET['date']; ?>">
			<input type="hidden" name="staff" value="<?php echo $_GET['staff']; ?>">
			<p>
				<label for = "formType">Type</label>
				<select name = "type" id = "formType">
					<option value = "">Please select...</option>
					<?php
					$sql = "SELECT * FROM appointment_type
							WHERE business_id = {$mysqli->real_escape_string($_GET['business'])}
							ORDER BY length";
					$result = $mysqli->query($sql);
					?>
					<?php if ($result -> num_rows) { ?>
						<?php while ($row = $result->fetch_assoc()) { ?>
						<option value="<?php echo $row['appointment_type_id']?>"><?php echo $row['name'].' ('.$row['length'].' minutes)'?> </option>
						<?php } ?>
					<?php } ?>
				</select>
			</p>

			<p><input type = "submit" value ="Submit"> <a href="customer_create_appointment.php?business=<?php echo $_GET['business']; ?>&amp;date=<?php echo $_GET['date']; ?>">Back</a></p>

	<?php } elseif (isset($_GET['business']) && !empty($_GET['business']) && isset($_GET['date']) && !empty($_GET['date'])) { ?>
		<h1>Create Appointment - Select Staff</h1>

		<form action="customer_create_appointment.php" method="get">
			<input type="hidden" name="business" value="<?php echo $_GET['business']; ?>">
			<input type="hidden" name="date" value="<?php echo $_GET['date']; ?>">

			<p>
				<label for="formStaff">Staff</label>
				<select name="staff" id="formStaff">
					<option value="">Please select...</option>
					<!-- <option value="anyone">Anyone</option> -->
					<?php
					$sql = "SELECT * FROM staff
							WHERE business_id = {$mysqli->real_escape_string($_GET['business'])}
							ORDER BY first_name";
					$result = $mysqli->query($sql);
					?>
					<?php if ($result->num_rows) { ?>
						<?php while ($row = $result->fetch_assoc()) { ?>
							<option value="<?php echo $row['staff_id']; ?>"><?php echo $row['first_name']." ".$row['last_name']; ?></option>
						<?php } ?>
					<?php } ?>
				</select>
			</p>

			<p><input type="submit" value="Submit"> <a href="customer_create_appointment.php?business=<?php echo $_GET['business']; ?>">Back</a></p>
		</form>
	<?php } elseif (isset($_GET['business']) && !empty($_GET['business'])) { ?>
		<h1>Create Appointment - Select a Date</h1>

		<form action="customer_create_appointment.php" method="get">
			<input type="hidden" name="business" value="<?php echo $_GET['business']; ?>">

			<p>
				<label for="date">Day</label>
				<input type="text" name="date" id="date" class="datepicker-no-past">
			</p>

			<p><input type="submit" value="Submit"> <a href="customer_create_appointment.php">Back</a></p>
		</form>
	<?php } else { ?>
		<h1>Create Appointment - Select Business</h1>

		<form action="customer_create_appointment.php" method="get">
			<p>
				<label for="formBusiness">Business</label>
				<select name="business" id="formBusiness">
					<option value="">Please select...</option>
					<?php
					$sql = "SELECT * FROM business ORDER BY name";
					$result = $mysqli->query($sql);
					?>
					<?php if ($result && $result->num_rows > 0) { ?>
						<?php while ($row = $result->fetch_assoc()) { ?>
							<option value="<?php echo $row['business_id']; ?>"><?php echo $row['name']; ?></option>
						<?php } ?>
					<?php } ?>
				</select>
			</p>

			<p><input type="submit" value="Submit"></p>
		</form>
	<?php } ?>
</div>