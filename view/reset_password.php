<div id="front-reset-password">
	<h1>Reset Password</h1>

	<form action="reset_password.php" method="post">
		<p>
			<label for="formEmail">Email</label>
			<input type="text" name="formEmail" id="formEmail" value="<?php echo isset($_POST['formEmail']) ? $_POST['formEmail'] : ""; ?>">
		</p>
		<p>
			<input type="radio" name="formType" id="formTypeCustomer" value="customer" <?php echo isset($_POST['formType']) && $_POST['formType'] == "customer" ? "checked" : (!isset($_POST['formType']) ? "checked" : ""); ?>>
			<label for="formTypeCustomer">Customer</label>
			<input type="radio" name="formType" id="formTypeStaff" value="staff" <?php echo isset($_POST['formType']) && $_POST['formType'] == "staff" ? "checked" : ""; ?>>
			<label for="formTypeStaff">Staff</label>
		</p>

		<p><input type="submit" name="reset_password" value="Submit"> <a href="login.php">Back to login</a></p>
	</form>
</div>