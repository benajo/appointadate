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
	<input type="text" name="email" id="formEmail" value="<?php echo isset($_POST['email']) ? $_POST['email'] : (isset($row['email']) ? $row['email'] : ""); ?>">
</p>
<p>
	<label for="formPassword1"><?php echo $edit ? "New " : ""; ?>Password</label>
	<input type="password" name="password1" id="formPassword1" value="">
</p>
<p>
	<label for="formPassword2">Confirm Password</label>
	<input type="password" name="password2" id="formPassword2" value="">
</p>
<p>
	<label for="formPassHint">Password Hint</label>
	<input type="text" name="pass_hint" id="formPassHint" value="<?php echo isset($_POST['pass_hint']) ? $_POST['pass_hint'] : (isset($row['pass_hint']) ? $row['pass_hint'] : ""); ?>">
</p>
<?php if ($_SERVER['PHP_SELF'] != "/join_business.php") { // so this doesn't show on busniess join page ?>
	<p>
		<span class="label">Admin?</span>

		<input type="radio" name="admin" id="formAdminYes" value="1" <?php echo $adminYes; ?>>
		<label for="formAdminYes" class="inline">Yes</label>

		<input type="radio" name="admin" id="formAdminNo" value="0" <?php echo $adminNo; ?>>
		<label for="formAdminNo" class="inline">No</label>
	</p>
<?php } ?>