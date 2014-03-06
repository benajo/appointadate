<?php
if (!$_SESSION['staff_logged_in'] || !$_SESSION['staff_id'] || !$_SESSION['staff_admin'] || !$_SESSION['staff_business_id']) {
	session_destroy();
	unset($_SESSION);

	header("Location: ./login.php");
	exit;
}

// used in view/header.php to show the correct navigation
define("STAFF_SECTION", true);
?>