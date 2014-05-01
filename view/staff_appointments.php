<div id="staff-appointments">
	<h1>Appointments List</h1>

	<?php
	$limit = 20;
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	$from = ($page-1) * $limit;

	$sql = "SELECT
				a.appointment_id,
				CONCAT(c.first_name, ' ', c.last_name) AS customer_name,
				CONCAT(s.first_name, ' ', s.last_name) AS staff_name,
				a.datetime AS appointment_datetime,
				at.name AS appointment_type,
				at.length AS appointment_length,
				a.accepted AS appointment_accepted,
				a.cancelled AS appointment_cancelled,
				a.created AS appointment_created
			FROM appointment a
			JOIN business b ON a.business_id = b.business_id
			JOIN customer c ON a.customer_id = c.customer_id
			JOIN staff s ON a.staff_id = s.staff_id
			JOIN appointment_type at ON a.appointment_type_id = at.appointment_type_id
			WHERE a.business_id = {$_SESSION['staff_business_id']}
			ORDER BY a.datetime DESC";
	$result = $mysqli->query($sql);
	$total = $result->num_rows;

	$sql .= " LIMIT {$from}, {$limit}";
	$result = $mysqli->query($sql);
	?>
	<?php if ($result->num_rows) { ?>
		<table>
			<tr>
				<th>Customer</th>
				<th>Staff</th>
				<th>Date/Time</th>
				<th>Type</th>
				<th>Length</th>
				<th>Status</th>
				<th>Created</th>
				<th>Actions</th>
			</tr>
			<?php while ($row = $result->fetch_assoc()) { ?>
				<tr>
					<td><?php echo $row['customer_name']; ?></td>
					<td><?php echo $row['staff_name']; ?></td>
					<td><?php echo date("d M Y @ H:i", strtotime($row['appointment_datetime'])); ?></td>
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
					<td>
						<?php if (strtotime($row['appointment_datetime']) > time()) { ?>
							<a href="staff_appointments.php?delete_appointment=<?php echo $row['appointment_id']; ?>" class="confirm-delete">delete</a>
						<?php } else { ?>
							-
						<?php } ?>
					</td>
				</tr>
			<?php } ?>
		</table>

		<?php pagination($limit, $from, $page, $total, "staff_appointments.php"); ?>

	<?php } else { ?>
		<p>You have no appointments.</p>
	<?php } ?>
</div>