<div id="front-login">
	<h1>Login</h1>

	<form action="login.php" method="post">
		<p>
			<label for="formEmail">Email</label>
			<input type="text" name="formEmail" id="formEmail" value="<?php echo isset($_POST['formEmail']) ? $_POST['formEmail'] : ""; ?>">
		</p>
		<p>
			<label for="formPassword">Password</label>
			<input type="password" name="formPassword" id="formPassword" value="">
		</p>
		<p>
			<input type="radio" name="formType" id="formTypeCustomer" value="customer" <?php echo isset($_POST['formType']) && $_POST['formType'] == "customer" ? "checked" : (!isset($_POST['formType']) ? "checked" : ""); ?>>
			<label for="formTypeCustomer">Customer</label>
			<input type="radio" name="formType" id="formTypeStaff" value="staff" <?php echo isset($_POST['formType']) && $_POST['formType'] == "staff" ? "checked" : ""; ?>>
			<label for="formTypeStaff">Staff</label>
		</p>

		<p><input type="submit" name="login" value="Submit"> <a href="reset_password.php">Forgot password?</a></p>
	</form>
</div>