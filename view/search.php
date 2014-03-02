<div id="front-search">
	<h1>Search</h1>

	<form action = "search.php" method = "post">
		<p>
			<h3>By Business Name</h3>
			<label for = "businessName">Name</label>
			<input type = "text" name = "businessName" id = "businessName" value = "">
			<input type = "submit" name = "searchByName" value = "Go">
		</p>
	</form>
	<form action = "search.php" method = "post">
		<p>
			<h3>By Business Type</h3>
			<label for = "businessType">Type</label>
			<select name = "businessType">
				<option value = "hairSalon">Hair Salon</option>
				<option value = "nailSalon">Nail Salon</option>
				<option value = "barber">Barber</option>
				<option value = "yoga">Yoga</option>
				<option value = "generalPractitioners">General Practitioners</option>
				<option value = "physiotherapist">Physiotherapist</option>
			</select>
			<input type = "submit" name = "searchByType" value = "Go">
		</p>
	</form>
	<form action = "search.php" method = "post">
		<p>
			<h3>By Location</h3>
			<label for = "postcode">Postcode</label>
			<input type = "text" name = "postcode" id = "postcode" value = "">
			<label for = "range">Within:</label>
			<select name = "range">
				<option value = "1">1 mile</option>
				<option value = "2">3 miles</option>
				<option value = "5">5 miles</option>
				<option value = "10">10 miles</option>
			</select>
			<input type = "submit" name = "searchByLocation" value = "Go">
 		</p>
 	</form>
</div>