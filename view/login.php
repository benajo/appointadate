<div id="front-login">
	<h1>Login</h1>

	<form action="login.php" method="post">
		<p>
			<label for="loginEmail">Email</label>
			<input type="text" name="loginEmail" id="loginEmail" value="<?php echo isset($_POST['loginEmail']) ? $_POST['loginEmail'] : ""; ?>">
		</p>
		<p>
			<label for="loginPassword">Password</label>
			<input type="password" name="loginPassword" id="loginPassword" value="">
		</p>
		<p>
			<input type="radio" name="loginType" id="loginTypeCustomer" value="customer"
			<?php echo isset($_POST['loginType']) && $_POST['loginType'] == "customer" ? "checked" : (!isset($_POST['loginType']) ? "checked" : ""); ?>
			>

			<label for="loginTypeCustomer">Customer</label>
			<input type="radio" name="loginType" id="loginTypeStaff" value="staff"
			<?php echo isset($_POST['loginType']) && $_POST['loginType'] == "staff" ? "checked" : ""; ?>
			>
			<label for="loginTypeStaff">Staff</label>
		</p>

		<p><input type="submit" name="login" value="Submit"> <a href="reset_password.php">Forgot password?</a></p>
	</form>
</div>