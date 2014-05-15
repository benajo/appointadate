<?php
$searchClause = "";

if (isset($_GET['keywords'])) {
	$terms = explode(" ", trim($_GET['keywords']));

	$clauses = array();

	foreach ($terms as $term) {
		$term = $mysqli->real_escape_string($term);

		$clauses[] = " (c.first_name LIKE '%{$term}%' OR c.last_name LIKE '%{$term}%' OR c.email LIKE '%{$term}%') ";
	}

	$searchClause = " AND (".implode(" OR ", $clauses).") ";
}
?>
<div id="staff-customers">
	<h1>Customers</h1>

	<form action="staff_customers.php" method="get">
		<p>
			<input type="text" name="keywords" value="<?php echo isset($_GET['keywords']) ? $_GET['keywords'] : null; ?>">
			<input type="submit" value="Search">
		</p>
	</form>

	<?php
	$limit = 20;
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	$from = ($page-1) * $limit;

	$sql = "SELECT * FROM customer c
			JOIN appointment a ON c.customer_id = a.customer_id
			WHERE a.business_id = {$_SESSION['staff_business_id']}
			{$searchClause}
			GROUP BY c.customer_id
			ORDER BY c.first_name";
	$result = $mysqli->query($sql);
	$total = $result->num_rows;

	$sql .= " LIMIT {$from}, {$limit}";
	$result = $mysqli->query($sql);
	?>
	<?php if ($result->num_rows) { ?>
		<table>
			<tr>
				<th>Name</th>
				<th>Phone</th>
				<th>Email</th>
				<th>Actions</th>
			</tr>
			<?php while ($row = $result->fetch_assoc()) { ?>
				<tr>
					<td><?php echo $row['first_name']." ".$row['last_name']; ?></td>
					<td><?php echo strlen($row['phone']) ? $row['phone'] : "-"; ?></td>
					<td><a href="mailto:<?php echo $row['email']; ?>"><?php echo $row['email']; ?></a></td>
					<td><a href="staff_create_appointment.php?customer=<?php echo $row['customer_id']; ?>">New Appointment</a></td>
				</tr>
			<?php } ?>
		</table>

		<?php pagination($limit, $page, $total, "staff_customers.php", "page", "", array("keywords")); ?>

	<?php } else { ?>
		<p>You have no appointments.</p>
	<?php } ?>
</div>