<?php
$edit = isset($_GET['notice']) ? true : false;

if ($edit) {
	$sql = "SELECT * FROM noticeboard
			WHERE noticeboard_id = {$mysqli->real_escape_string($_GET['notice'])}";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
}
else {
	unset($row);
}
?>
<div id="staff-noticeboard">
	<h1><?php echo $edit ? "Edit" : "Create"; ?> Notice</h1>

	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
		<p>
			<label for="notice_title">Title</label>
			<input type="text" name="notice_title" id="notice_title" class="full-width" maxlength="255" value="<?php echo isset($_POST['notice_title']) ? $_POST['notice_title'] : (isset($row['title']) ? $row['title'] : "") ?>">
		</p>
		<p>
			<label for="notice_content">Content</label>
			<textarea name="notice_content" id="notice_content" class="full-width"><?php echo isset($_POST['notice_content']) ? $_POST['notice_content'] : (isset($row['content']) ? $row['content'] : "") ?></textarea>
		</p>

		<p><input type="submit" name="<?php echo $edit ? "edit_notice" : "add_notice"; ?>" value="Submit"></p>
	</form>

	<h1 id="notices">Notices</h1>

	<?php
	$limit = 10;
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	$from = ($page-1) * $limit;

	$sql = "SELECT *, CONCAT(s1.first_name, ' ', s1.last_name) AS staff1, CONCAT(s2.first_name, ' ', s2.last_name) AS staff2
			FROM noticeboard bn
			JOIN staff s1 ON bn.created_by = s1.staff_id
			JOIN staff s2 ON bn.updated_by = s2.staff_id
			WHERE bn.business_id = '{$_SESSION['staff_business_id']}'
			ORDER BY bn.created DESC";
	$result = $mysqli->query($sql);
	$total = $result->num_rows;

	$sql .= " LIMIT {$from}, {$limit}";
	$result = $mysqli->query($sql);
	?>
	<?php if ($result->num_rows) { ?>
		<table>
			<tr>
				<th>Title</th>
				<th>Created By</th>
				<th>Created</th>
				<th>Updated By</th>
				<th>Updated</th>
				<th>Actions</th>
			</tr>
			<?php while ($row = $result->fetch_assoc()) { ?>
				<tr>
					<td><?php echo $row['title']; ?></td>
					<td><?php echo $row['staff1']; ?></td>
					<td><?php echo date("d M Y", strtotime($row['created'])); ?></td>
					<td><?php echo $row['staff2']; ?></td>
					<td><?php echo date("d M Y", strtotime($row['updated'])); ?></td>
					<td>
						<a href="staff_noticeboard.php?notice=<?php echo $row['noticeboard_id']; ?>">edit</a> -
						<a href="staff_noticeboard.php?remove_notice=<?php echo $row['noticeboard_id']; ?>" class="confirm-delete">remove</a>
					</td>
				</tr>
			<?php } ?>
		</table>

		<?php pagination($limit, $page, $total, "staff_noticeboard.php", "page", "notices"); ?>

	<?php } else { ?>
		<p>There are no notices.</p>
	<?php } ?>
</div>