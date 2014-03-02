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

		<?php
		$sql = "SELECT * FROM business
				ORDER BY name";
		$businesses = $mysqli->query($sql);
		?>
		<?php if ($businesses->num_rows) { ?>
			<?php while ($business = $businesses->fetch_assoc()) { ?>
				<div style="border: 1px solid #000;">
					<h2><a href="businesses.php?business=<?php echo $business['business_id']; ?>"><?php echo $business['name']; ?></a></h2>

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