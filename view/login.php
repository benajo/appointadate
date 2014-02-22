<div id="front-login">
	<h1>Customer Login</h1>

	<form action="login.php" method="post">
		<p>
			<label for="customerEmail">Email</label>
			<input type="text" name="customerEmail" id="customerEmail" value="<?php echo isset($_POST['customerEmail']) ? $_POST['customerEmail'] : ""; ?>">
		</p>
		<p>
			<label for="customerPassword">Password</label>
			<input type="password" name="customerPassword" id="customerPassword" value="">
		</p>

		<p><input type="submit" name="customer_login" value="Login"> <a href="reset_password.php">Forgot password?</a></p>
	</form>

	<h1>Staff Login</h1>

	<form action="login.php" method="post">
		<p>
			<label for="staffEmail">Email</label>
			<input type="text" name="staffEmail" id="staffEmail" value="<?php echo isset($_POST['staffEmail']) ? $_POST['staffEmail'] : ""; ?>">
		</p>
		<p>
			<label for="staffPassword">Password</label>
			<input type="password" name="staffPassword" id="staffPassword" value="">
		</p>

		<p><input type="submit" name="business_login" value="Login"> <a href="reset_password.php">Forgot password?</a></p>
	</form>
</div>