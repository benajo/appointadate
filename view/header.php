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
		<div style="height: 78px; font-size: 38pt; float: left;">Appoint-A-Date</div>

		<div style="width: 500px; height: 50px; float:right;text-align: right">
			<?php if (isset($_SESSION['customer_logged_in']) && $_SESSION['customer_logged_in'] == true) { ?>
				<?php
				$sql = "SELECT first_name, last_name FROM customer
						WHERE customer_id = {$_SESSION['customer_id']}";
				$result = $mysqli->query($sql);
				$row = $result->fetch_assoc();
				?>
				Logged in as <?php echo $row['first_name']." ".$row['last_name']; ?> (<a href="logout.php">logout</a>)<br>

				<a href="appointments.php">My Account</a>
			<?php } elseif ($_SERVER['PHP_SELF'] != "/login.php") { ?>
				<form action="login.php" method="post">
					<div>
						<label for="formEmail">Email</label>
						<input type="text" name="formEmail" id="formEmail" value="<?php echo isset($_POST['formEmail']) ? $_POST['formEmail'] : ""; ?>">
					</div>
					<div>
						<label for="formPassword">Password</label>
						<input type="password" name="formPassword" id="formPassword" value="<?php echo isset($_POST['formPassword']) ? $_POST['formPassword'] : ""; ?>">
					</div>
					<div>
						<input type="radio" name="formType" id="formTypeCustomer" value="customer" <?php echo isset($_POST['formType']) && $_POST['formType'] == "customer" ? "checked" : (!isset($_POST['formType']) ? "checked" : ""); ?>>
						<label for="formTypeCustomer">Customer</label>
						<input type="radio" name="formType" id="formTypeStaff" value="staff" <?php echo isset($_POST['formType']) && $_POST['formType'] == "staff" ? "checked" : ""; ?>>
						<label for="formTypeStaff">Staff</label>

						<input type="submit" name="login" value="Login">
					</div>
				</form>
			<?php } ?>
		</div>
	</div>
	<div id="page-nav">
		<ul>
			<li><a href="index.php">Home</a></li>

			<?php if (defined("SECURE_PAGE") && SECURE_PAGE == true) { ?>
				<li><a href="appointments.php">Appointments</a></li>
				<li><a href="create_appointment.php">Create Appointment</a></li>
				<li><a href="favourite_businesses.php">Favourite Businesses</a></li>
				<li><a href="#">Reviews</a></li>
				<li><a href="edit_details.php">Edit details</a></li>
			<?php } else { ?>
				<li><a href="about.php">About</a></li>
				<li><a href="contact.php">Contact</a></li>
				<li><a href="businesses.php">Businesses</a></li>
				<li><a href="customer_join.php">Customer Join</a></li>
				<li><a href="business_join.php">Business Join</a></li>
				<li class="search">
					<form action="search.php" method="post">
						<input type="text" name="search" value="Search businesses">
						<input type="image" src="images/search-icon.png">
					</form>
				</li>
			<?php } ?>
		</ul>
	</div>
	<?php if (!empty($message) || !empty($errorMessage)) { ?>
		<div id="page-message">
			<?php echo !empty($message) ? "Message:<br>{$message}" : null; ?>
			<?php echo !empty($errorMessage) ? "<span class=\"error\">Error(s):<br>{$errorMessage}</span>" : null; ?>
		</div>
	<?php } ?>
	<div id="page-content">