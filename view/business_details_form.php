<p>
	<label for="formName">Business Name</label>
	<input type="text" name="name" id="formName" value="<?php echo isset($_POST['name']) ? $_POST['name'] : (isset($row['name']) ? $row['name'] : ""); ?>">
</p>
<p>
	<label for="formAddressLine1">Address Line 1</label>
	<input type="text" name="address_line_1" id="formAddressLine1" value="<?php echo isset($_POST['address_line_1']) ? $_POST['address_line_1'] : (isset($row['address_line_1']) ? $row['address_line_1'] : ""); ?>">
</p>
<p>
	<label for="formAddressLine2">Address Line 2</label>
	<input type="text" name="address_line_2" id="formAddressLine2" value="<?php echo isset($_POST['address_line_2']) ? $_POST['address_line_2'] : (isset($row['address_line_2']) ? $row['address_line_2'] : ""); ?>">
</p>
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
	<label for="formContactName">Contact Name</label>
	<input type="text" name="contact_name" id="formContactName" value="<?php echo isset($_POST['contact_name']) ? $_POST['contact_name'] : (isset($row['contact_name']) ? $row['contact_name'] : ""); ?>">
</p>
<p>
	<label for="formContactPhone">Contact Phone</label>
	<input type="text" name="contact_phone" id="formContactPhone" value="<?php echo isset($_POST['contact_phone']) ? $_POST['contact_phone'] : (isset($row['contact_phone']) ? $row['contact_phone'] : ""); ?>">
</p>
<p>
	<label for="formContactEmail">Contact Email</label>
	<input type="text" name="contact_email" id="formContactEmail" value="<?php echo isset($_POST['contact_email']) ? $_POST['contact_email'] : (isset($row['contact_email']) ? $row['contact_email'] : ""); ?>">
</p>
<div>
	<label for="formBusinessTypes">Business Type(s)<br><br></label>
	<select name="business_types[]" id="formBusinessTypes" size="5" multiple>
		<?php
		$sql = "SELECT * FROM type
				ORDER BY name";
		$types = $mysqli->query($sql);
		?>
		<?php while ($type = $types->fetch_assoc()) { ?>
			<?php
			$sql = "SELECT * FROM business_type
					WHERE business_id = '{$_SESSION['staff_business_id']}'
					AND type_id = '{$type['type_id']}'";
			$result = $mysqli->query($sql);
			$total = $result->num_rows;

			if (isset($_POST['business_types']) && array_search($type['type_id'], $_POST['business_types']) !== false) {
				$selected = "selected";
			}
			elseif (!isset($_POST['update_business_details']) && $total > 0) {
				$selected = "selected";
			}
			else {
				$selected = "";
			}
			?>
			<option value="<?php echo $type['type_id']; ?>" <?php echo $selected; ?>>
				<?php echo $type['name']; ?>
			</option>
		<?php } ?>
	</select>
	<div class="left">
		<em>Hold CTRL and click to select multiple</em>
	</div>
</div>