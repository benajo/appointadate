<?php
// if (customer OR staff is set) AND code is set
if (((isset($_GET['customer']) && !empty($_GET['customer'])) || (isset($_GET['staff']) && !empty($_GET['staff']))) && isset($_GET['code']) && !empty($_GET['code'])) {
		$showPasswordReset = true;
}
else {
	$showPasswordReset = false;
}
?>
<div id="front-reset-password">
	<?php if ($showPasswordReset) { ?>
		<h1>Enter a new password</h1>

		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<p>
				<label for="formPassword1">New Password</label>
				<input type="password" name="formPassword1" id="formPassword1" value="<?php echo isset($_POST['formPassword1']) ? $_POST['formPassword1'] : ""; ?>">
			</p>
			<p>
				<label for="formPassword2">Confirm Password</label>
				<input type="password" name="formPassword2" id="formPassword2" value="<?php echo isset($_POST['formPassword2']) ? $_POST['formPassword2'] : ""; ?>">
			</p>
			<p>
				<input type="submit" name="reset_password_step2" value="Submit"> -
				<a href="reset_password.php">Cancel</a>
			</p>
		</form>
	<?php } else { ?>
		<h1>Reset Password</h1>

		<form action="reset_password.php" method="post">
			<p>
				<label for="formEmail">Email</label>
				<input type="text" name="formEmail" id="formEmail" value="<?php echo isset($_POST['formEmail']) ? $_POST['formEmail'] : ""; ?>">
			</p>
			<p>
				<span class="label">Account Type</span>

				<input type="radio" name="formType" id="formTypeStaff" value="staff" <?php echo isset($_POST['formType']) && $_POST['formType'] == "staff" ? "checked" : (!isset($_POST['formType']) ? "checked" : ""); ?>>
				<label for="formTypeStaff" class="inline">Staff</label>

				<input type="radio" name="formType" id="formTypeCustomer" value="customer" <?php echo isset($_POST['formType']) && $_POST['formType'] == "customer" ? "checked" : ""; ?>>
				<label for="formTypeCustomer" class="inline">Customer</label>
			</p>
			<p>
				<input type="submit" name="reset_password_step1" value="Submit">
				<a href="login.php">Back to login</a>
			</p>
		</form>
	<?php } ?>
</div>