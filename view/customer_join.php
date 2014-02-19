<div id="front-customer-join">
	<h1>Customer Join</h1>

	<form action="customer_join.php" method="post">
		<p>
			<label for="formFirstName">First Name</label>
			<input type="text" name="first_name" id="formFirstName" value="<?php echo isset($_POST['first_name']) ? $_POST['first_name'] : ""; ?>">
		</p>
		<p>
			<label for="formLastName">Last Name</label>
			<input type="text" name="last_name" id="formLastName" value="<?php echo isset($_POST['last_name']) ? $_POST['last_name'] : ""; ?>">
		</p>
		<p>
			<label for="formEmail">Email</label>
			<input type="email" name="email" id="formEmail" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ""; ?>">
		</p>
		<p>
			<label for="formPhone">Phone</label>
			<input type="text" name="phone" id="formPhone" value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : ""; ?>">
		</p>
		<p>
			<label for="formAddLine1">Address Line 1</label>
			<input type="text" name="address_line_1" id="formAddLine1" value="<?php echo isset($_POST['address_line_1']) ? $_POST['address_line_1'] : ""; ?>">
		</p>
		<p>
			<label for="formAddLine2">Address Line 2</label>
			<input type="text" name="address_line_2" id="formAddLine2" value="<?php echo isset($_POST['address_line_2']) ? $_POST['address_line_2'] : ""; ?>">
		</p>
		<p>
			<label for="formCity">City</label>
			<input type="text" name="city" id="formCity" value="<?php echo isset($_POST['city']) ? $_POST['city'] : ""; ?>">
		</p>
		<p>
			<label for="formCounty">County</label>
			<input type="text" name="county" id="formCounty" value="<?php echo isset($_POST['county']) ? $_POST['county'] : ""; ?>">
		</p>
		<p>
			<label for="formPostcode">Postcode</label>
			<input type="text" name="postcode" id="formPostcode" value="<?php echo isset($_POST['postcode']) ? $_POST['postcode'] : ""; ?>">
		</p>
		<p>
			<label for="formPassword1">Password</label>
			<input type="password" name="password1" id="formPassword1" value="">
		</p>
		<p>
			<label for="formPassword2">Confirm Password</label>
			<input type="password" name="password2" id="formPassword2" value="">
		</p>
		<p>
			<label for="formPassHint">Password Hint</label>
			<input type="text" name="pass_hint" id="formPassHint" value="<?php echo isset($_POST['pass_hint']) ? $_POST['pass_hint'] : ""; ?>">
		</p>
		<p><input type="submit" name="customer_join" value="Submit"></p>
	</form>
</div>