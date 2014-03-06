<div id="front-businesses">
	<?php if (isset($_GET['business']) && !empty($_GET['business'])) { ?>
		<?php
		$sql = "SELECT * FROM business
				WHERE business_id = '{$mysqli->real_escape_string($_GET['business'])}'";
		$result = $mysqli->query($sql);
		$business = $result->fetch_assoc();
		?>
		<h1><?php echo $business['name']; ?></h1>

	<?php } else { ?>
		<h1>Businesses</h1>

		<form action = "businesses.php" method = "post">
			<p>
				<label for = "keywords">Keywords</label>
				<input type = "text" name = "keywords" id = "keywords"
				value = "<?php echo isset($_POST['keywords']) && !empty($_POST['keywords']) ? $_POST['keywords'] : "" ?>">
			</p>
			<p>
				<label for = "businessType">Business Type</label>
				<select name = "businessType">
					<option value="">Please select...</option>
					<?php
					$sql = "SELECT * FROM type ORDER BY name";
					$result = $mysqli->query($sql);
					?>
					<?php if ($result && $result->num_rows > 0) { ?>
						<?php while ($row = $result->fetch_assoc()) { ?>
							<option value="<?php echo $row['type_id']; ?>"
							<?php echo isset($_POST['businessType']) && $_POST['businessType'] == $row['type_id'] ? "selected" : ""; ?>><?php echo $row['name']; ?></option>
						<?php } ?>
					<?php } ?>
				</select>
			</p>
			<p>
				<label for = "postcode">Postcode</label>
				<input type = "text" name = "postcode" id = "postcode"
				value = "<?php echo isset($_POST['postcode']) && !empty($_POST['postcode']) ? $_POST['postcode'] : "" ?>">

				<label for = "range">Within:</label>
				<select name = "range">
					<option value ="">Please select...</option>

					<?php $ranges = array(1, 3, 5, 10); ?>

					<?php foreach ($ranges as $v) { ?>
						<option value = "<?php echo $v; ?>"
						<?php echo isset($_POST['range']) && $_POST['range'] == $v ? "selected" : ""; ?>><?php echo $v; ?> mile<?php echo $v > 1 ? "s" : ""; ?></option>
					<?php } ?>
				</select>
 			</p>
 			<p>
 				<input type = "submit" name = "search" value = "Go">
 			</p>
 		</form>

		<?php
		$sql = search_business(
			isset($_POST['keywords']) && !empty($_POST['keywords']) ? $_POST['keywords'] : "",
			isset($_POST['businessType']) && !empty($_POST['businessType']) ? $_POST['businessType'] : "",
			isset($_POST['postcode']) && !empty($_POST['postcode']) ? $_POST['postcode'] : "",
			isset($_POST['range']) && !empty($_POST['range']) ? $_POST['range'] : ""
		);
		$businesses = $mysqli->query($sql);
		?>
		<?php if ($businesses->num_rows) { ?>
			<?php while ($business = $businesses->fetch_assoc()) { ?>
				<div style="border: 1px solid #000;">
					<h2><a href="businesses.php?business=<?php echo $business['business_id']; ?>"><?php echo $business['name']; ?></a></h2>

					<?php if (isset($business['distance'])){ ?>
						<p><?php echo $business['distance']; ?></p>
					<?php } ?>

					<?php
					$sql = "SELECT * FROM business_type bt
							JOIN type t ON bt.type_id = t.type_id
							WHERE business_id = {$business['business_id']}
							ORDER BY name";
					$types = $mysqli->query($sql);
					?>
					<?php if ($types->num_rows) { ?>
						<?php while ($type = $types->fetch_assoc()) { ?>
							<?php echo $type['name']; ?><br>
						<?php } ?>
					<?php } ?>
				</div>
			<?php } ?>
		<?php } else { ?>
			<p>You have no businesses.</p>
		<?php } ?>
	<?php } ?>
</div>