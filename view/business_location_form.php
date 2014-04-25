<?php
$latitude = isset($_POST['latitude']) ? $_POST['latitude'] : (isset($row['latitude']) ? $row['latitude'] : "57.165140");
$longitude = isset($_POST['longitude']) ? $_POST['longitude'] : (isset($row['longitude']) ? $row['longitude'] : "-2.104861");
?>
<p>
	<label for="formLatitude">Latitude</label>
	<input type="text" name="latitude" id="formLatitude" value="<?php echo $latitude; ?>">
</p>
<p>
	<label for="formLongitude">Longitude</label>
	<input type="text" name="longitude" id="formLongitude" value="<?php echo $longitude; ?>">
</p>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDi-00pKaEBMGigG3wG7uWfQsYymvqbSX4&amp;sensor=false"></script>
<script type="text/javascript">
	function initialize() {
		var latlng = new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>);

		var mapOptions = {
			center: latlng,
			zoom: 13
		};

		var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

		var marker = new google.maps.Marker({
			position: latlng,
			map: map,
			draggable: true
		});

		$("input#formLatitude").val(latlng.lat().toFixed(5));
		$("input#formLongitude").val(latlng.lng().toFixed(5));

		google.maps.event.addListener(marker, 'dragend', function() {
			var point = marker.getPosition();
			map.panTo(point);
			$("input#formLatitude").val(point.lat().toFixed(5));
			$("input#formLongitude").val(point.lng().toFixed(5));
		});

		google.maps.event.addListener(map, 'center_changed', function() {
			var point = map.getCenter();
			marker.setPosition(point);
			$("input#formLatitude").val(point.lat().toFixed(5));
			$("input#formLongitude").val(point.lng().toFixed(5));
		});

	}

	google.maps.event.addDomListener(window, 'load', initialize);
</script>

<p><em>Move the marker to location of your business.</em></p>

<div id="map-canvas" style="width: 100%; height: 450px;"></div>