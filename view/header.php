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

		<?php
		// $sql = "SELECT first_name, last_name FROM customer
		// 		WHERE customer_id = {$_SESSION['customer_id']}";
		// $result = $mysqli->query($sql);
		// $row = $result->fetch_assoc();
		?>

		Logged in as <?php //echo $row['first_name']." ".$row['last_name']; ?> (<a href="?logout">logout</a>)
	</div>
	<div id="page-nav">
		<ul>
			<li><a href="customer/index.php">Customer section</a></li>
			<li><a href="business/index.php">Business section</a></li>
		</ul>
		<ul>
			<li><a href="index.php">Home</a></li>
			<li><a href="about.php">About</a></li>
			<li><a href="contact.php">Contact</a></li>
			<li><a href="businesses.php">Businesses</a></li>
			<li><a href="search.php">Search</a></li>
			<li><a href="login.php">Login</a></li>
			<li><a href="join_customer.php">Customer Join</a></li>
			<li><a href="join_business.php">Business Join</a></li>
		</ul>
	</div>
	<div id="page-content">