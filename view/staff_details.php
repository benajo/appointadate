<?php
$edit = preg_match("/(edit)/i", $_SERVER['PHP_SELF']) ? true : false;

if ($edit) {
	$staffId = isset($_GET['staff']) && !empty($_GET['staff']) ? $mysqli->real_escape_string($_GET['staff']) : $_SESSION['staff_id'];

	$sql = "SELECT * FROM staff
			WHERE staff_id = {$staffId}";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
}
else {
	unset($row);
}

// admin radio options
$adminYes = $adminNo = "";

if (isset($_POST['admin']) && $_POST['admin'] == 1) {
	$adminYes = "checked";
}
elseif (!isset($_POST['admin']) && isset($row['admin']) && $row['admin'] == 1) {
	$adminYes = "checked";
}

if (isset($_POST['admin']) && $_POST['admin'] == 0) {
	$adminNo = "checked";
}
elseif (!isset($_POST['admin']) && isset($row['admin']) && $row['admin'] == 0) {
	$adminNo = "checked";
}
elseif (!isset($_POST['admin']) && !isset($row['admin'])) {
	$adminNo = "checked";
}
?>
<div id="staff-details">
	<h1><?php echo $edit ? "Edit Staff" : "Add Staff"; ?></h1>


	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
		<?php include "./view/staff_details_form.php"; ?>

		<p><input type="submit" name="<?php echo $edit ? "edit_details" : "add_details"; ?>" value="Submit"></p>
	</form>
</div>