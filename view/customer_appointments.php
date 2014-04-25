<div id="customer-appointments">
	<h1>Appointments List</h1>

	<?php
	$sql = "SELECT
				a.appointment_id,
				b.name AS business_name,
				CONCAT(s.first_name, ' ', s.last_name) AS staff_name,
				a.datetime AS appointment_datetime,
				at.name AS appointment_type,
				at.length AS appointment_length,
				a.accepted AS appointment_accepted,
				a.cancelled AS appointment_cancelled,
				a.created AS appointment_created
			FROM appointment a
			JOIN business b ON a.business_id = b.business_id
			LEFT JOIN staff s ON a.staff_id = s.staff_id
			JOIN appointment_type at ON a.appointment_type_id = at.appointment_type_id
			WHERE a.customer_id = {$_SESSION['customer_id']}
			ORDER BY a.datetime DESC";
	$result = $mysqli->query($sql);
	?>
	<?php if ($result->num_rows) { ?>
		<table>
			<tr>
				<th>Business</th>
				<th>Staff</th>
				<th>Date</th>
				<th>Type</th>
				<th>Length</th>
				<th>Status</th>
				<th>Created</th>
				<th>Actions</th>
			</tr>
			<?php while ($row = $result->fetch_assoc()) { ?>
				<tr>
					<td><?php echo $row['business_name']; ?></td>
					<td><?php echo strlen($row['staff_name']) > 0 ? $row['staff_name'] : "---"; ?></td>
					<td><?php echo date("H:i d-m-Y", strtotime($row['appointment_datetime'])); ?></td>
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
					<td><?php echo date("d-m-Y", strtotime($row['appointment_created'])); ?></td>
					<td>
						<?php if (strtotime($row['appointment_datetime']) < time()) { ?>
							<a href="customer_review.php?appointment=<?php echo $row['appointment_id']; ?>">review</a> -
						<?php } ?>
						<a href="customer_appointments.php?delete_appointment=<?php echo $row['appointment_id']; ?>" class="confirm-delete">delete</a>
					</td>
				</tr>
			<?php } ?>
		</table>
	<?php } else { ?>
		<p>You have no appointments.</p>
	<?php } ?>
</div>