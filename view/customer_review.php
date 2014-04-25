<?php
$ratings = array(1, 2, 3, 4, 5);

$appointment_id = $mysqli->real_escape_string($_GET['appointment']);

$sql = "SELECT * FROM review
		WHERE appointment_id = '{$appointment_id}'";
$result = $mysqli->query($sql);

if ($result->num_rows) {
	$row = $result->fetch_assoc();
}

$activeRating = isset($_POST['rating']) ? $_POST['rating'] : (isset($row['rating']) ? $row['rating'] : 0);
?>
<div id="customer-review">
	<h1>Review</h1>

	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
		<p>
			<label for="rating">Rating</label>
			<select name="rating" id="rating">
				<option value="">Select...</option>}
				<?php foreach ($ratings as $v) { ?>
					<option value="<?php echo $v; ?>" <?php echo $v == $activeRating ? "selected" : ""; ?>><?php echo $v; ?></option>}
				<?php } ?>
			</select>
		</p>

		<p>
			<label for="review">Review</label>
			<textarea name="review" id="review" class="full-width"><?php echo isset($_POST['review']) ? $_POST['review'] : (isset($row['review']) ? $row['review'] : ""); ?></textarea>
		</p>

		<p><input type="submit" name="update_review" value="Submit"></p>
	</form>
</div>