<div id="front-businesses">
	<?php if (isset($_GET['business']) && !empty($_GET['business'])) { ?>

		<?php $business_id = $mysqli->real_escape_string($_GET['business']); ?>

		<div>
			<?php
			$sql = "SELECT * FROM business
					WHERE business_id = '{$business_id}'";
			$result = $mysqli->query($sql);
			$business = $result->fetch_assoc();
			?>
			<h1 class="title"><?php echo $business['name']; ?></h1>

			<?php echo favourite_business($business['business_id']); ?>

			<div>
				<?php
				$sql = "SELECT * FROM business_timetable
						WHERE business_id = '{$business_id}'";
				$result = $mysqli->query($sql);
				$timetable = $result->fetch_assoc();
				$days = array("Monday" => "mon", "Tuesday" => "tue", "Wednesday" => "wed", "Thursday" => "thu", "Friday" => "fri", "Saturday" => "sat", "Sunday" => "sun");
				?>
				<div class="timetable">
					<table>
						<?php foreach ($days as $k => $v) { ?>
							<tr>
								<td><?php echo $k; ?></td>
								<td>
									<?php
									if ($timetable["{$v}_off"] == 1) {
										echo "Closed";
									}
									else {
										echo substr_replace($timetable["{$v}_start"], ":", -2, 0);
										echo " - ";
										echo substr_replace($timetable["{$v}_end"], ":", -2, 0);
									}
									?>
								</td>
							</tr>
						<?php } ?>
					</table>
				</div>

				<?php business_types_html($business['business_id']); ?>

				<p><?php echo str_replace("\n", "<br>", $business['description']); ?></p>
			</div>

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

					var infowindow<?php echo $business['business_id']; ?> = new google.maps.InfoWindow({
						content: '<div>' + '<?php echo $business['name']; ?>' + '</div>'
					});

					var marker<?php echo $business['business_id']; ?> = new google.maps.Marker({
						position: new google.maps.LatLng(<?php echo $business['latitude']; ?>, <?php echo $business['longitude']; ?>),
						map: map,
						title: '<?php echo $business['name']; ?>'
					});

					google.maps.event.addListener(marker<?php echo $business['business_id']; ?>, 'click', function() {
						infowindow<?php echo $business['business_id']; ?>.open(map, marker<?php echo $business['business_id']; ?>);
					});
				}

				google.maps.event.addDomListener(window, 'load', initialize);
			</script>

			<p id="map-canvas" style="width: 1000px; height: 300px;"></p>
		</div>

		<div class="left-content-half">
			<div id="noticeboard" class="noticeboard">
				<h2>Noticeboard</h2>
				<?php
				$limit = 2;
				$page = isset($_GET['noticepage']) ? $_GET['noticepage'] : 1;
				$from = ($page-1) * $limit;

				$sql = "SELECT * FROM noticeboard
						WHERE business_id = '{$business_id}'";
				$result = $mysqli->query($sql);
				$total = $result->num_rows;

				$sql = "SELECT * FROM noticeboard
						WHERE business_id = '{$business_id}'
						ORDER BY noticeboard_id DESC
						LIMIT {$from}, {$limit}";
				$result = $mysqli->query($sql);
				?>

				<?php if ($result && $result->num_rows > 0) { ?>

					<?php pagination($limit, $from, $page, $total, "businesses.php", "noticepage", "noticeboard", array("business")); ?>

					<?php while ($notice = $result->fetch_assoc()) { ?>
						<div class="notice">
							<h3><?php echo $notice['title']; ?></h3>
							<time datetime="<?php echo date("Y-m-d H:i", strtotime($notice['created'])); ?>"><?php echo date("D, d M Y", strtotime($notice['created'])); ?></time>
							<p><?php echo str_replace("\n", "<br>", $notice['content']); ?></p>
						</div>
					<?php } ?>

					<?php pagination($limit, $from, $page, $total, "businesses.php", "noticepage", "	noticeboard", array("business")); ?>

				<?php } ?>

			</div>
		</div>

		<div class="right-content-half">
			<div id="reviews" class="reviews">
				<h2>Reviews</h2>

				<?php
				$limit = 2;
				$page = isset($_GET['reviewpage']) ? $_GET['reviewpage'] : 1;
				$from = ($page-1) * $limit;

				$sql = "SELECT * FROM review r
						JOIN appointment a ON r.appointment_id = a.appointment_id
						WHERE a.business_id = '{$business_id}'";
				$result = $mysqli->query($sql);
				$total = $result->num_rows;

				$sql = "SELECT * FROM review r
						JOIN appointment a ON r.appointment_id = a.appointment_id
						WHERE a.business_id = '{$business_id}'
						ORDER BY review_id DESC
						LIMIT {$from}, {$limit}";
				$result = $mysqli->query($sql);
				?>
				<?php if ($result && $result->num_rows > 0) { ?>
					<?php pagination($limit, $from, $page, $total, "businesses.php", "reviewpage", "reviews", array("business")); ?>

					<?php while ($review = $result->fetch_assoc()) { ?>
						<div class="review">
							<time datetime="<?php echo date("Y-m-d H:i", strtotime($review['created'])); ?>"><?php echo date("D, d M Y", strtotime($review['created'])); ?></time>
							<p>Rating <?php echo $review['rating']; ?>/5</p>
							<p><?php echo str_replace("\n", "<br>", $review['review']); ?></p>
						</div>
					<?php } ?>

					<?php pagination($limit, $from, $page, $total, "businesses.php", "reviewpage", "reviews", array("business")); ?>

				<?php } ?>
			</div>
		</div>

	<?php } else { ?>

		<h1>Businesses</h1>

		<form action = "businesses.php" method = "get" id = "businesses-search">
			<fieldset>
				<legend>Search Businesses</legend>

				<table>
					<tr>
						<td>
							<label for = "keywords">Keywords</label>
							<input type = "text" name = "keywords" id = "keywords"
							value = "<?php echo isset($_GET['keywords']) && !empty($_GET['keywords']) ? $_GET['keywords'] : "" ?>">
						</td>
						<td>
							<label for = "postcode">Postcode</label>
							<input type = "text" name = "postcode" id = "postcode"
							value = "<?php echo isset($_GET['postcode']) && !empty($_GET['postcode']) ? $_GET['postcode'] : "" ?>">
						</td>
					</tr>
					<tr>
						<td>
							<label for = "businessType">Business Type</label>
							<select name = "businessType" id = "businessType">
								<option value="">Please select...</option>
								<?php
								$sql = "SELECT * FROM type ORDER BY name";
								$result = $mysqli->query($sql);
								?>
								<?php if ($result && $result->num_rows > 0) { ?>
									<?php while ($row = $result->fetch_assoc()) { ?>
										<option value="<?php echo $row['type_id']; ?>"
										<?php echo isset($_GET['businessType']) && $_GET['businessType'] == $row['type_id'] ? "selected" : ""; ?>><?php echo $row['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</td>
						<td>
							<label for = "range">Within</label>
							<select name = "range" id = "range">
								<option value ="">Please select...</option>

								<?php $ranges = array(1, 3, 5, 10); ?>

								<?php foreach ($ranges as $v) { ?>
									<option value = "<?php echo $v; ?>"
									<?php echo isset($_GET['range']) && $_GET['range'] == $v ? "selected" : ""; ?>><?php echo $v; ?> mile<?php echo $v > 1 ? "s" : ""; ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
				</table>
	 			<div>
	 				<input type = "submit" value = "Search">

	 				<?php if (isset($_GET['keywords']) || isset($_GET['businessType']) || isset($_GET['postcode']) || isset($_GET['range'])) { ?>
	 					<a href="businesses.php">Cancel search</a>
	 				<?php } ?>
	 			</div>
	 		</fieldset>
 		</form>

		<?php
		$limit = 2;
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$from = ($page-1) * $limit;

		list($businesses, $total) = search_businesses($from, $limit);
		?>
		<?php if ($businesses->num_rows) { ?>
			<?php while ($business = $businesses->fetch_assoc()) { ?>
				<div class="business-item">
					<h2 class="title"><a href="businesses.php?business=<?php echo $business['business_id']; ?>"><?php echo $business['name']; ?></a></h2>

					<?php echo favourite_business($business['business_id']); ?>

					<?php if (isset($business['distance'])) { ?>
						<p>Distance: <?php echo round($business['distance'], 2); ?> miles</p>
					<?php } ?>

					<?php business_types_html($business['business_id']); ?>

					<?php if (strlen($business['description']) > 0) { ?>
						<p><?php echo substr($business['description'], 0, 250); ?>...</p>
					<?php } ?>
				</div>
			<?php } ?>

			<?php pagination($limit, $from, $page, $total, "businesses.php", "page", "", array("keywords", "postcode", "businessType", "range")); ?>
		<?php } else { ?>
			<p>There are no businesses yet.</p>
		<?php } ?>
	<?php } ?>
</div>