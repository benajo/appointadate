<div id="staff-appointments">
	<h1>Appointments List</h1>

	<?php
	$sql = "SELECT
				a.appointment_id,
				b.name AS business_name,
				CONCAT(c.first_name, ' ', c.last_name) AS customer_name,
				a.datetime AS appointment_datetime,
				at.name AS appointment_type,
				at.length AS appointment_length,
				a.accepted AS appointment_accepted,
				a.cancelled AS appointment_cancelled,
				a.created AS appointment_created
			FROM appointment a
			JOIN business b ON a.business_id = b.business_id
			LEFT JOIN customer c ON a.customer_id = c.customer_id
			JOIN appointment_type at ON a.appointment_type_id = at.appointment_type_id
			WHERE a.staff_id = {$_SESSION['staff_id']}
			ORDER BY a.datetime";
	$result = $mysqli->query($sql);
	?>
	<?php if ($result->num_rows) { ?>
		<table>
			<tr>
				<th>Business</th>
				<th>Customer</th>
				<th>Date</th>
				<th>Time</th>
				<th>Type</th>
				<th>Length</th>
				<th>Status</th>
				<th>Created</th>
				<th>Actions</th>
			</tr>
			<?php while ($row = $result->fetch_assoc()) { ?>
				<tr>
					<td><?php echo $row['business_name']; ?></td>
					<td><?php echo strlen($row['customer_name']) > 0 ? $row['customer_name'] : "---"; ?></td>
					<td><?php echo date("d M Y", strtotime($row['appointment_datetime'])); ?></td>
					<td><?php echo date("H:i", strtotime($row['appointment_datetime'])); ?></td>
					<td><?php echo $row['appointment_type']; ?></td>
					<td><?php echo $row['appointment_length']; ?> minutes</td>
					<td>
						<?php if ($row['appointment_accepted'] == 1) { ?>
							Accepted
						<?php } elseif ($row['appointment_cancelled'] == 1) { ?>
							Cancelled
						<?php } else { ?>
							Pending
						<?php } ?>
					</td>
					<td><?php echo date("d M Y", strtotime($row['appointment_created'])); ?></td>
					<td><a href="">edit</a> - <a href="staff_appointments.php?delete_appointment=<?php echo $row['appointment_id']; ?>" class="confirm-delete">delete</a></td>
				</tr>
			<?php } ?>
		</table>
	<?php } else { ?>
		<p>You have no appointments.</p>
	<?php } ?>
</div>