<div id="front-businesses">
	<h1>Businesses</h1>

	<?php
	$sql = "SELECT * FROM business
			ORDER BY name";
	$businesses = $mysqli->query($sql);
	?>
	<?php if ($businesses->num_rows) { ?>
		<?php while ($business = $businesses->fetch_assoc()) { ?>
			<div style="border: 1px solid #000;">
				<h2><?php echo $business['name']; ?></h2>

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
</div>