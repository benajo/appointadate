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
					<td><a href="customer_favourite_businesses.php?remove_favourite_business=<?php echo $row['business_id']; ?>" class="confirm-delete">remove</a></td>
				</tr>
			<?php } ?>
		</table>
	<?php } else { ?>
		<p>You have no favourite businesses.</p>
	<?php } ?>

	<hr>

	<h1 id="notices">Aggregated Favourite Businesses Noticeboards</h1>

	<?php
	$limit = 5;
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	$from = ($page-1) * $limit;

	$sql = "SELECT bn.title AS title, b.name AS name, bn.created AS created, bn.content AS content
			FROM noticeboard bn
			JOIN business b ON bn.business_id = b.business_id
			JOIN customer_pref_business cpb ON bn.business_id = cpb.business_id
			WHERE cpb.customer_id = '{$_SESSION['customer_id']}'
			ORDER BY bn.created DESC";
	$result = $mysqli->query($sql);
	$total = $result->num_rows;

	$sql .= " LIMIT {$from}, {$limit}";
	$result = $mysqli->query($sql);
	?>
	<?php if ($result->num_rows) { ?>

		<?php pagination($limit, $page, $total, "customer_favourite_businesses.php", "page", "notices"); ?>

		<?php while ($row = $result->fetch_assoc()) { ?>
			<h2><?php echo $row['title']; ?> (<?php echo $row['name']; ?>)</h2>

			<time datetime="<?php echo date("Y-m-d H:i", strtotime($row['created'])); ?>"><?php echo date("D, d M Y", strtotime($row['created'])); ?></time>

			<p><?php echo str_replace("\n", "<br>", $row['content']); ?></p>
		<?php } ?>

		<?php pagination($limit, $page, $total, "customer_favourite_businesses.php", "page", "notices"); ?>

	<?php } else { ?>
		<p>There are no notices.</p>
	<?php } ?>
</div>