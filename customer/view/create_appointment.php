<div id="customer-create-appointment">
	<?php if (isset($_GET['business']) && !empty($_GET['business']) && isset($_GET['staff']) && !empty($_GET['staff']) && isset($_GET['date']) && !empty($_GET['date'])) { ?>
		<h1>Create Appointment - Select a Tme</h1>

		<form action="create_appointment.php" method="post">
			<input type="hidden" name="business" value="<?php echo $_GET['business']; ?>">
			<input type="hidden" name="staff" value="<?php echo $_GET['staff']; ?>">
			<input type="hidden" name="date" value="<?php echo $_GET['date']; ?>">

			<label for="formTime">Select a time</label>

			<table>
				<thead>
					<tr>
						<td>&nbsp;</td>
						<?php for ($i=0; $i < 60; $i+=5) { ?>
							<td><?php echo ($i < 10 ? "0" : "").$i; ?></td>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php for ($i=0; $i < 24; $i++) { ?>
						<tr>
							<td><?php echo ($i < 10 ? "0" : "").$i; ?>:00</td>
							<?php for ($j=0; $j < 60; $j+=5) { ?>
								<td><input type="checkbox"></td>
							<?php } ?>
						</tr>
					<?php } ?>
				</tbody>
			</table>

			<p><input type="submit" name="create_appointment" value="Submit"> <a href="create_appointment.php?business=<?php echo $_GET['business']; ?>&amp;staff=<?php echo $_GET['staff']; ?>">Back</a></p>
		</form>
	<?php } elseif (isset($_GET['business']) && !empty($_GET['business']) && isset($_GET['staff']) && !empty($_GET['staff'])) { ?>
		<h1>Create Appointment - Select a Date</h1>

		<form action="create_appointment.php" method="get">
			<input type="hidden" name="business" value="<?php echo $_GET['business']; ?>">
			<input type="hidden" name="staff" value="<?php echo $_GET['staff']; ?>">

			<p>
				<label for="date">Day</label>
				<input type="text" name="date" id="date" class="datepicker">
			</p>

			<p><input type="submit" value="Submit"> <a href="create_appointment.php?business=<?php echo $_GET['business']; ?>">Back</a></p>
		</form>
	<?php } elseif (isset($_GET['business']) && !empty($_GET['business'])) { ?>
		<h1>Create Appointment - Select Staff</h1>

		<form action="create_appointment.php" method="get">
			<input type="hidden" name="business" value="<?php echo $_GET['business']; ?>">

			<p>
				<label for="formStaff">Staff</label>
				<select name="staff" id="formStaff">
					<option value="">Please select...</option>
					<option value="">Anyone</option>
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

			<p><input type="submit" value="Submit"> <a href="create_appointment.php">Back</a></p>
		</form>
	<?php } else { ?>
		<h1>Create Appointment - Select Business</h1>

		<form action="create_appointment.php" method="get">
			<p>
				<label for="formBusiness">Business</label>
				<select name="business" id="formBusiness">
					<option value="">Please select...</option>
					<?php
					$sql = "SELECT * FROM business ORDER BY name";
					$result = $mysqli->query($sql);
					?>
					<?php if ($result->num_rows) { ?>
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