<?php
unset($row);

$edit = preg_match("/(edit)/i", $_SERVER['PHP_SELF']) ? true : false;

if ($edit) {
	$staffId = isset($_GET['staff']) && !empty($_GET['staff']) ? $mysqli->real_escape_string($_GET['staff']) : $_SESSION['staff_id'];

	$sql = "SELECT * FROM staff
			WHERE staff_id = {$staffId}";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
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
	<h1><?php $edit ? "Edit Staff" : "Add Staff"; ?></h1>


	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
		<p>
			<label for="formFirstName">First Name</label>
			<input type="text" name="first_name" id="formFirstName" value="<?php echo isset($_POST['first_name']) ? $_POST['first_name'] : (isset($row['first_name']) ? $row['first_name'] : ""); ?>">
		</p>
		<p>
			<label for="formLastName">Last Name</label>
			<input type="text" name="last_name" id="formLastName" value="<?php echo isset($_POST['last_name']) ? $_POST['last_name'] : (isset($row['last_name']) ? $row['last_name'] : ""); ?>">
		</p>
		<p>
			<label for="formEmail">Email</label>
			<input type="email" name="email" id="formEmail" value="<?php echo isset($_POST['email']) ? $_POST['email'] : (isset($row['email']) ? $row['email'] : ""); ?>">
		</p>
		<p>
			<label for="formPassword1"><?php echo $edit ? "New " : ""; ?>Password</label>
			<input type="password" name="password1" id="formPassword1" value="">
		</p>
		<p>
			<label for="formPassword2">Confirm <?php echo $edit ? "New " : ""; ?>Password</label>
			<input type="password" name="password2" id="formPassword2" value="">
		</p>
		<p>
			<label for="formPassHint">Password Hint</label>
			<input type="text" name="pass_hint" id="formPassHint" value="<?php echo isset($_POST['pass_hint']) ? $_POST['pass_hint'] : (isset($row['pass_hint']) ? $row['pass_hint'] : ""); ?>">
		</p>
		<p>
			Admin?

			<input type="radio" name="admin" id="formAdminYes" value="1" <?php echo $adminYes; ?>>
			<label for="formAdminYes">Yes</label>

			<input type="radio" name="admin" id="formAdminNo" value="0" <?php echo $adminNo; ?>>
			<label for="formAdminNo">No</label>

		</p>
		<p><input type="submit" name="<?php echo $edit ? "edit_details" : "add_details"; ?>" value="Submit"></p>
	</form>
</div>