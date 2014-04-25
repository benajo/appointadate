<div id="front-index">
	<div class="left-content-big">
		<h1>Welcome</h1>

		<p>Appoint-a-Date is a web oriented scheduling system which allows users to organise appointments and provides a resource of structured information to effectively manage their business. Our system gives business owners the ability to purchase their own licensed product, from where they can then manage their staff and statistics. It offers an frontend platform for both administrative use and customer use. From their accounts, owners and staff can easily update their details. Customers have easy access to this as well as options to alter their availability and a range of other preferences. Additionally, stored information would be able to generate useful statistics based on client data.</p>

		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDi-00pKaEBMGigG3wG7uWfQsYymvqbSX4&amp;sensor=false"></script>
		<script type="text/javascript">
			/*
			 * Code modified from:
			 * https://developers.google.com/maps/documentation/javascript/tutorial
			 */
			function initialize() {
				var mapOptions = {
					center: new google.maps.LatLng(57.165140, -2.104861),
					zoom: 13
				};

				var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

				<?php
				$sql = "SELECT business_id, name, latitude, longitude FROM business";
				$result = $mysqli->query($sql);
				?>
				<?php if ($result->num_rows) { ?>
					<?php while ($row = $result->fetch_assoc()) { ?>
						var infowindow<?php echo $row['business_id']; ?> = new google.maps.InfoWindow({
							content: '<div>' + '<?php echo $row['name']; ?>' + '</div>'
						});

						var marker<?php echo $row['business_id']; ?> = new google.maps.Marker({
							position: new google.maps.LatLng(<?php echo $row['latitude']; ?>, <?php echo $row['longitude']; ?>),
							map: map,
							title: '<?php echo $row['name']; ?>'
						});

						google.maps.event.addListener(marker<?php echo $row['business_id']; ?>, 'click', function() {
							infowindow<?php echo $row['business_id']; ?>.open(map, marker<?php echo $row['business_id']; ?>);
						});
					<?php } ?>
				<?php } ?>
			}

			google.maps.event.addDomListener(window, 'load', initialize);
		</script>

		<div id="map-canvas" style="width: 680px; height: 300px;"></div>
	</div>

	<div class="right-content-small">
		<h1>New Businesses</h1>

		<?php
		$sql = "SELECT * FROM business
				ORDER BY created DESC
				LIMIT 3";
		$businesses = $mysqli->query($sql);
		?>
		<?php if ($businesses->num_rows) { ?>
			<?php while ($business = $businesses->fetch_assoc()) { ?>
				<div>
					<h2><a href="businesses.php?business=<?php echo $business['business_id']; ?>"><?php echo $business['name']; ?></a></h2>

					<?php
					$sql = "SELECT * FROM business_type bt
							JOIN type t ON bt.type_id = t.type_id
							WHERE business_id = {$business['business_id']}
							ORDER BY name";
					$types = $mysqli->query($sql);
					?>
					<?php if ($types->num_rows) { ?>
						<ul>
							<?php while ($type = $types->fetch_assoc()) { ?>
								<li><?php echo $type['name']; ?></li>
							<?php } ?>
						</ul>
					<?php } ?>
				</div>
			<?php } ?>
		<?php } else { ?>
			<p>You have no businesses.</p>
		<?php } ?>
	</div>
</div>