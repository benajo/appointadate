<div id="customer-appointments">
	<h1>Appointments List</h1>

	<?php
	$limit = 20;
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	$from = ($page-1) * $limit;

	$sql = "SELECT
				a.appointment_id,
				b.name AS business_name,
				CONCAT(s.first_name, ' ', s.last_name) AS staff_name,
				a.datetime AS appointment_datetime,
				at.name AS appointment_type,
				at.length AS appointment_length,
				a.accepted AS appointment_accepted,
				a.cancelled AS appointment_cancelled,
				a.created AS appointment_created,
				review_id
			FROM appointment a
			JOIN business b ON a.business_id = b.business_id
			JOIN staff s ON a.staff_id = s.staff_id
			JOIN appointment_type at ON a.appointment_type_id = at.appointment_type_id
			LEFT JOIN review r ON a.appointment_id = r.appointment_id
			WHERE a.customer_id = {$_SESSION['customer_id']}
			ORDER BY a.datetime DESC";
	$result = $mysqli->query($sql);
	$total = $result->num_rows;

	$sql .= " LIMIT {$from}, {$limit}";
	$result = $mysqli->query($sql);
	?>
	<?php if ($result->num_rows) { ?>
		<table>
			<tr>
				<th>Business</th>
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
					<td><?php echo $row['business_name']; ?></td>
					<td><?php echo strlen($row['staff_name']) > 0 ? $row['staff_name'] : "---"; ?></td>
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
					<td><?php echo date("d-m-Y", strtotime($row['appointment_created'])); ?></td>
					<td>
						<?php if (strtotime($row['appointment_datetime']) < time()) { ?>
							<a href="customer_review.php?appointment=<?php echo $row['appointment_id']; ?>">
								<?php echo empty($row['review_id']) ? "<strong>review</strong>" : "review"; ?>
							</a>
						<?php } else { ?>
							<a href="customer_appointments.php?delete_appointment=<?php echo $row['appointment_id']; ?>" class="confirm-delete">delete</a>
						<?php } ?>
					</td>
				</tr>
			<?php } ?>
		</table>

		<?php pagination($limit, $page, $total, "customer_appointments.php"); ?>

	<?php } else { ?>
		<p>You have no appointments.</p>
	<?php } ?>
</div>