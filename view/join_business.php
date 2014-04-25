<?php
$edit = false;
?>
<div id="front-business-join">
	<h1>Business Join</h1>

	<form action="join_business.php" method="post">
		<div class="left-content-half">
			<fieldset>
				<legend>Business Information</legend>

				<?php include "./view/business_details_form.php"; ?>
			</fieldset>

			<fieldset>
				<legend>Your Information</legend>

				<?php include "./view/staff_details_form.php"; ?>

				<p><input type="submit" name="add_business" value="Submit"></p>
			</fieldset>
		</div>

		<div class="right-content-half">
			<fieldset>
				<legend>Business Location</legend>

				<?php include "./view/business_location_form.php"; ?>
			</fieldset>
		</div>
	</form>
</div>