<div id="customer-edit-details">
	<h1>Edit Details</h1>

	<form action="edit_details.php" method="post">
		<p>
			<label for="formFirstName">First Name</label>
			<input type="text" name="first_name" id="formFirstName" value="<?php echo isset($_POST['first_name']) ? $_POST['first_name'] : $row['first_name']; ?>">
		</p>
		<p>
			<label for="formLastName">Last Name</label>
			<input type="text" name="last_name" id="formLastName" value="<?php echo isset($_POST['last_name']) ? $_POST['last_name'] : $row['last_name']; ?>">
		</p>
		<p>
			<label for="formEmail">Email</label>
			<input type="text" name="email" id="formEmail" value="<?php echo isset($_POST['email']) ? $_POST['email'] : $row['email']; ?>">
		</p>
		<p>
			<label for="formPhone">Phone</label>
			<input type="text" name="phone" id="formPhone" value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : $row['phone']; ?>">
		</p>
		<p>
			<label for="formAddLine1">Address Line 1</label>
			<input type="text" name="address_line_1" id="formAddLine1" value="<?php echo isset($_POST['address_line_1']) ? $_POST['address_line_1'] : $row['address_line_1']; ?>">
		</p>
		<p>
			<label for="formAddLine2">Address Line 2</label>
			<input type="text" name="address_line_2" id="formAddLine2" value="<?php echo isset($_POST['address_line_2']) ? $_POST['address_line_2'] : $row['address_line_2']; ?>">
		</p>
		<p>
			<label for="formCity">City</label>
			<input type="text" name="city" id="formCity" value="<?php echo isset($_POST['city']) ? $_POST['city'] : $row['city']; ?>">
		</p>
		<p>
			<label for="formCounty">County</label>
			<input type="text" name="county" id="formCounty" value="<?php echo isset($_POST['county']) ? $_POST['county'] : $row['county']; ?>">
		</p>
		<p>
			<label for="formPostcode">Postcode</label>
			<input type="text" name="postcode" id="formPostcode" value="<?php echo isset($_POST['postcode']) ? $_POST['postcode'] : $row['postcode']; ?>">
		</p>
		<p>
			<label for="formPassword1">New Password</label>
			<input type="password" name="password1" id="formPassword1" value="">
		</p>
		<p>
			<label for="formPassword2">Confirm New Password</label>
			<input type="password" name="password2" id="formPassword2" value="">
		</p>
		<p>
			<label for="formPassHint">Password Hint</label>
			<input type="text" name="pass_hint" id="formPassHint" value="<?php echo isset($_POST['pass_hint']) ? $_POST['pass_hint'] : $row['pass_hint']; ?>">
		</p>
		<p><input type="submit" name="edit_details" value="Submit"></p>
	</form>
</div>