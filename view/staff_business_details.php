<?php
$sql = "SELECT * FROM business
		WHERE business_id = {$_SESSION['staff_business_id']}";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();
?>
<div id="staff-business-details">
	<h1>Business Details</h1>

	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
		<div class="left-content-half">
			<fieldset>
				<legend>Business Information</legend>

				<?php include "./view/business_details_form.php"; ?>
			</fieldset>
		</div>

		<div class="right-content-half">
			<fieldset>
				<legend>Business Location</legend>

				<?php include "./view/business_location_form.php"; ?>
			</fieldset>
		</div>

		<div class="clear-both">
			<fieldset>
				<legend>Business Description</legend>

				<?php include "./view/business_description_form.php"; ?>
			</fieldset>

			<p><input type="submit" name="update_business_details" value="Submit"></p>
		</div>

	</form>
</div>