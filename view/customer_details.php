<?php
$join = preg_match("/(join)/i", $_SERVER['PHP_SELF']) ? true : false; // determines whether to display the join page or edit details

if (!$join) {
	$sql = "SELECT * FROM customer
			WHERE customer_id = {$_SESSION['customer_id']}";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
}
else {
	unset($row);
}
?>
<div id="customer-details">
	<h1><?php echo $join ? "Customer Join" : "Edit Details"; ?></h1>

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<div class="left-content-half">
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
				<label for="formPhone">Phone</label>
				<input type="text" name="phone" id="formPhone" value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : (isset($row['phone']) ? $row['phone'] : ""); ?>">
			</p>
			<p>
				<label for="formAddLine1">Address Line 1</label>
				<input type="text" name="address_line_1" id="formAddLine1" value="<?php echo isset($_POST['address_line_1']) ? $_POST['address_line_1'] : (isset($row['address_line_1']) ? $row['address_line_1'] : ""); ?>">
			</p>
			<p>
				<label for="formAddLine2">Address Line 2</label>
				<input type="text" name="address_line_2" id="formAddLine2" value="<?php echo isset($_POST['address_line_2']) ? $_POST['address_line_2'] : (isset($row['address_line_2']) ? $row['address_line_2'] : ""); ?>">
			</p>
			<p><input type="submit" name="<?php echo $join ? "join_customer" : "edit_details"; ?>" value="Submit"></p>
		</div>

		<div class="right-content-half">
			<p>
				<label for="formCity">City</label>
				<input type="text" name="city" id="formCity" value="<?php echo isset($_POST['city']) ? $_POST['city'] : (isset($row['city']) ? $row['city'] : ""); ?>">
			</p>
			<p>
				<label for="formCounty">County</label>
				<input type="text" name="county" id="formCounty" value="<?php echo isset($_POST['county']) ? $_POST['county'] : (isset($row['county']) ? $row['county'] : ""); ?>">
			</p>
			<p>
				<label for="formPostcode">Postcode</label>
				<input type="text" name="postcode" id="formPostcode" value="<?php echo isset($_POST['postcode']) ? $_POST['postcode'] : (isset($row['postcode']) ? $row['postcode'] : ""); ?>">
			</p>
			<p>
				<label for="formPassword1"><?php echo !$join ? "New " : ""; ?>Password</label>
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
		</div>
	</form>
</div>