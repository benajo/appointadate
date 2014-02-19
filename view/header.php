<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo PAGE_TITLE; ?></title>

	<meta charset="utf-8">

	<meta name="keywords" content="<?php echo PAGE_KEYWORDS; ?>">
	<meta name="description" content="<?php echo PAGE_DESCRIPTION; ?>">

	<link type="text/css" rel="stylesheet" href="./style/normalize.css">
	<link type="text/css" rel="stylesheet" href="./style/jquery-ui.min.css">
	<link type="text/css" rel="stylesheet" href="./style/default.css">
	<link type="text/css" rel="stylesheet" href="./style/front.css">
</head>
<body>
<div id="page-container">
	<div id="page-header">
		<h1>AppointADate</h1>
		<a href="customer/index.php">Customer section</a> |
		<a href="business/index.php">Business section</a>
	</div>
	<div id="page-nav">
		<ul>
			<li><a href="index.php">Home</a></li>
			<li><a href="about.php">About</a></li>
			<li><a href="contact.php">Contact</a></li>
			<li><a href="businesses.php">Businesses</a></li>
			<li><a href="search.php">Search</a></li>
			<li><a href="login.php">Login</a></li>
			<li><a href="customer_join.php">Customer Join</a></li>
			<li><a href="business_join.php">Business Join</a></li>
		</ul>

		<?php if (isset($_SESSION['customer_logged_in']) && $_SESSION['customer_logged_in'] == true) { ?>
			<br>
			<br>
			<ul>
				<li><a href="appointments.php">Appointments</a></li>
				<li><a href="create_appointment.php">Create Appointment</a></li>
				<li><a href="favourite_businesses.php">Favourite Businesses Noticeboard</a></li>
				<li><a href="#">Ratings and Reviews</a></li>
				<li><a href="edit_details.php">Edit details</a></li>
				<?php
				$sql = "SELECT first_name, last_name FROM customer
						WHERE customer_id = {$_SESSION['customer_id']}";
				$result = $mysqli->query($sql);
				$row = $result->fetch_assoc();
				?>

				<li>Logged in as <?php echo $row['first_name']." ".$row['last_name']; ?> (<a href="?logout">logout</a>)</li>
			</ul>
		<?php } ?>
	</div>
	<?php if (!empty($message) || !empty($errorMessage)) { ?>
		<div id="page-message">
			<?php echo !empty($message) ? "Message:<br>{$message}" : null; ?>
			<?php echo !empty($errorMessage) ? "<span class=\"error\">Error(s):<br>{$errorMessage}</span>" : null; ?>
		</div>
	<?php } ?>
	<div id="page-content">