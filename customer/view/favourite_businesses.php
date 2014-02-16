<div id="favourite-businesses">
	<h1>Favourite Businesses</h1>

	<?php
	$sql = "SELECT * FROM business b
			JOIN customer_pref_business cpb ON b.business_id = cpb.business_id
			WHERE cpb.customer_id = {$_SESSION['customer_id']}";
	$result = $mysqli->query($sql);
	?>
	<?php if ($result->num_rows) { ?>
		<table>
			<tr>
				<th>Business</th>
				<th>Actions</th>
			</tr>
			<?php while ($row = $result->fetch_assoc()) { ?>
				<tr>
					<td><?php echo $row['name']; ?></td>
					<td><a href="favourite_businesses.php?remove_favourite_business=<?php echo $row['business_id']; ?>" class="confirm-delete">remove</a></td>
				</tr>
			<?php } ?>
		</table>
	<?php } else { ?>
		<p>You have no favourite businesses.</p>
	<?php } ?>
</div>