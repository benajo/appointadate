<?php
if (!$_SESSION['customer_logged_in'] || !$_SESSION['customer_id']) {
	session_destroy();
	unset($_SESSION);

	header("Location: ./login.php");
	exit;
}

// used in view/header.php to show the correct navigation
define("CUSTOMER_SECTION", true);
?>