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
			<span class="label">Account Type</span>

			<input type="radio" name="loginType" id="loginTypeStaff" value="staff"
			<?php echo isset($_POST['loginType']) && $_POST['loginType'] == "staff" ? "checked" : (!isset($_POST['loginType']) ? "checked" : ""); ?>
			>
			<label for="loginTypeStaff" class="inline">Staff</label>

			<input type="radio" name="loginType" id="loginTypeCustomer" value="customer"
			<?php echo isset($_POST['loginType']) && $_POST['loginType'] == "customer" ? "checked" : ""; ?>
			>
			<label for="loginTypeCustomer" class="inline">Customer</label>
		</p>

		<p><input type="submit" name="login" value="Submit"> <a href="reset_password.php">Forgot password?</a></p>
	</form>
</div>