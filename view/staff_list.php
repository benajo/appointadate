<div id="staff-list">
	<h1>Staff List</h1>

	<p><a href="staff_add_details.php">Add new staff member</a></p>

	<?php
	$sql = "SELECT * FROM staff
			WHERE business_id = '{$_SESSION['staff_business_id']}'
			ORDER BY first_name";
	$result = $mysqli->query($sql);
	?>
	<?php if ($result->num_rows) { ?>
		<table>
			<tr>
				<th>Name</th>
				<th>Email</th>
				<th>Admin</th>
				<th>Actions</th>
			</tr>
			<?php while ($row = $result->fetch_assoc()) { ?>
				<tr>
					<td><?php echo $row['first_name']." ".$row['last_name']; ?></td>
					<td><a href="mailto:<?php echo $row['email']; ?>"><?php echo $row['email']; ?></a></td>
					<td><?php echo $row['admin'] == 1 ? "Yes" : "No"; ?></td>
					<td>
						<a href="staff_edit_details.php?staff=<?php echo $row['staff_id']; ?>">edit</a>
					</td>
				</tr>
			<?php } ?>
		</table>
	<?php } else { ?>
		<p>You have no appointments.</p>
	<?php } ?>
</div>