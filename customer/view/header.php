<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo PAGE_TITLE; ?></title>

	<meta charset="utf-8">

	<meta name="keywords" content="<?php echo PAGE_KEYWORDS; ?>">
	<meta name="description" content="<?php echo PAGE_DESCRIPTION; ?>">

	<link type="text/css" rel="stylesheet" href="../style/normalize.css">
	<link type="text/css" rel="stylesheet" href="../style/jquery-ui.min.css">
	<link type="text/css" rel="stylesheet" href="./style/customer.css">
</head>
<body>
<div id="page-container">
	<div id="page-header">
		<h1>AppointADate - Customer Section</h1>

		<?php
		$sql = "SELECT * FROM customer
				WHERE customer_id = {$_SESSION['customer_id']}";
		$result = $mysqli->query($sql);
		$row = $result->fetch_assoc();
		?>

		Logged in as <?php echo $row['first_name']." ".$row['last_name']; ?> (<a href="?logout">logout</a>)
	</div>
	<div id="page-nav">
		<ul>
			<li><a href="appointments.php">Appointments</a></li>
			<li><a href="create_appointment.php">Create Appointment</a></li>
			<li><a href="#">Favourite Businesses Noticeboard</a></li>
			<li><a href="#">Ratings and Reviews</a></li>
			<li><a href="edit_details.php">Edit details</a></li>
		</ul>
	</div>
	<?php if (!empty($message) || !empty($errorMessage)) { ?>
		<div id="page-message">
			<?php echo !empty($message) ? "Message:<br>{$message}" : null; ?>
			<?php echo !empty($errorMessage) ? "<span class=\"error\">Error(s):<br>{$errorMessage}</span>" : null; ?>
		</div>
	<?php } ?>
	<div id="page-content">