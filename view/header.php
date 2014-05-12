<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo PAGE_TITLE; ?></title>

	<meta charset="utf-8">

	<meta name="keywords" content="<?php echo PAGE_KEYWORDS; ?>">
	<meta name="description" content="<?php echo PAGE_DESCRIPTION; ?>">

	<link type="text/css" rel="stylesheet" href="./style/normalize.css">
	<link type="text/css" rel="stylesheet" href="./style/jquery-ui.min.css">
	<link type="text/css" rel="stylesheet" href="./style/style.css">

	<link href="./images/favicon.ico" rel="icon" type="image/x-icon">
</head>
<body>
<div id="page-container">
	<div id="page-header">
		<div id="page-logo"><a href="index.php"><img src="images/appoint-a-date-logo.png" alt="Appoint-A-Date"></a></div>

		<div id="page-login">
			<?php if ($_SESSION['customer_logged_in']) { ?>

				<?php
				$sql = "SELECT first_name, last_name FROM customer
						WHERE customer_id = {$_SESSION['customer_id']}";
				$result = $mysqli->query($sql);
				$row = $result->fetch_assoc();
				?>
				Logged in as <?php echo $row['first_name']." ".$row['last_name']; ?> (<a href="logout.php">logout</a>)<br>

				<a href="customer_appointments.php">My Account</a>

			<?php } elseif ($_SESSION['staff_logged_in']) { ?>

				<?php
				$sql = "SELECT first_name, last_name FROM staff
						WHERE staff_id = {$_SESSION['staff_id']}";
				$result = $mysqli->query($sql);
				$row = $result->fetch_assoc();
				?>
				Logged in as <?php echo $row['first_name']." ".$row['last_name']; ?> (<a href="logout.php">logout</a>)<br>

				<a href="staff_timetable.php">My Account</a>

			<?php } elseif ($_SERVER['PHP_SELF'] != "/login.php") { ?>

				<form action="login.php" method="post">
					<div>
						<label for="loginEmail">Email</label>
						<input type="text" name="loginEmail" id="loginEmail" value="<?php echo isset($_POST['loginEmail']) ? $_POST['loginEmail'] : ""; ?>">
					</div>
					<div>
						<label for="loginPassword">Password</label>
						<input type="password" name="loginPassword" id="loginPassword" value="<?php echo isset($_POST['loginPassword']) ? $_POST['loginPassword'] : ""; ?>">
					</div>
					<div>
						<input type="radio" name="loginType" id="loginTypeStaff" value="staff" <?php echo isset($_POST['loginType']) && $_POST['loginType'] == "staff" ? "checked" : (!isset($_POST['loginType']) ? "checked" : ""); ?>>
						<label for="loginTypeStaff">Staff</label>

						<input type="radio" name="loginType" id="loginTypeCustomer" value="customer" <?php echo isset($_POST['loginType']) && $_POST['loginType'] == "customer" ? "checked" : ""; ?>>
						<label for="loginTypeCustomer">Customer</label>

						<input type="submit" name="login" value="Login">
					</div>
				</form>

			<?php } ?>
		</div>
	</div>
	<div id="page-nav">
		<ul>
			<li><a href="index.php">Home</a></li>

			<?php if (defined("CUSTOMER_SECTION") && CUSTOMER_SECTION == true) { ?>

				<li><a href="customer_appointments.php">Appointments</a></li>
				<li><a href="customer_create_appointment.php">Create Appointment</a></li>
				<li><a href="customer_favourite_businesses.php">Favourite Businesses</a></li>
				<li><a href="customer_edit_details.php">Edit details</a></li>

			<?php } elseif (defined("STAFF_SECTION") && STAFF_SECTION == true) { ?>

				<li><a href="staff_timetable.php">Timetable</a></li>
				<li><a href="staff_appointments.php">Appointments</a></li>
				<!-- <li><a href="staff_create_appointment.php">Create Appointment</a></li> -->
				<li><a href="staff_statistics.php">Statistics</a></li>
				<li><a href="staff_list.php">Staff List</a></li>
				<li><a href="staff_customers.php">Customers</a></li>
				<li><a href="staff_noticeboard.php">Noticeboard</a></li>
				<li><a href="staff_business_details.php">Business Details</a></li>

			<?php } else { ?>

				<li><a href="about.php">About</a></li>
				<li><a href="businesses.php">Businesses</a></li>

				<?php if (!($_SESSION['customer_logged_in'] || $_SESSION['staff_logged_in'])) { ?>
					<li><a href="join_customer.php">Customer Join</a></li>
					<li><a href="join_business.php">Business Join</a></li>
				<?php } ?>

				<li><a href="contact.php">Contact</a></li>
				<li class="search">
					<form action="businesses.php" method="post">
						<input type="text" name="keywords" value="">
						<input type="image" src="images/search-icon.png" alt="Search">
					</form>
				</li>

			<?php } ?>
		</ul>
	</div>
	<?php if (!empty($message) || !empty($errorMessage)) { ?>
		<div id="page-message">
			<table>
				<tr>
					<td>
						<img src="./images/<?php echo !empty($message) ? "tick" : "cross"; ?>.png" alt="">
					</td>
					<td>
						<?php echo !empty($message) ? "{$message}" : "<span class=\"error\">{$errorMessage}</span>"; ?>
					</td>
				</tr>
			</table>
		</div>
	<?php } ?>
	<div id="page-content">