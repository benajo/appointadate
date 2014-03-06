<div id="front-index">
	<div class="left-content">
		<h1>Welcome</h1>

		<p>Lorem ipsum Ea labore sunt proident et reprehenderit eiusmod ea eiusmod. Lorem ipsum Aliquip commodo ad dolor minim aliqua dolor laboris Duis nisi exercitation dolore irure dolor Excepteur Duis incididunt cillum incididunt pariatur in quis dolore laborum nostrud cillum fugiat eiusmod pariatur pariatur ad pariatur aliquip elit exercitation Duis in adipisicing exercitation aute irure ex reprehenderit dolor Duis eiusmod enim nisi Duis culpa consectetur fugiat irure proident incididunt commodo in do proident ullamco fugiat deserunt velit laboris qui mollit amet laborum in magna pariatur nulla laborum pariatur ullamco nulla elit Excepteur ex in quis qui amet velit officia adipisicing incididunt occaecat ut culpa elit dolore in in Duis ut sint veniam exercitation aute do ex non ut sit sit adipisicing et nulla irure incididunt Excepteur pariatur eu ex quis enim ex Excepteur ex exercitation dolore enim fugiat ut ex Ut labore ullamco elit voluptate officia deserunt labore in aute aute minim amet sit laborum ea laborum exercitation aliqua.</p>

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

	<div class="right-content">
		<h1>Recent Businesses</h1>

		<?php
		$sql = "SELECT * FROM business
				ORDER BY created DESC
				LIMIT 3";
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
						Types:
						<ul>
							<?php while ($type = $types->fetch_assoc()) { ?>
								<li><?php echo $type['name']; ?></li>
							<?php } ?>
						</ul>
					<?php } ?>

					Created: <?php echo $business['created']; ?>
				</div>
			<?php } ?>
		<?php } else { ?>
			<p>You have no businesses.</p>
		<?php } ?>
	</div>
</div>