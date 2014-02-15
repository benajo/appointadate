<?php
error_reporting(E_ALL);
session_start();

preg_match("/^\/([a-z]+)\//i", $_SERVER['PHP_SELF'], $folder);

$_SESSION['customer_logged_in'] = true;
$_SESSION['customer_id'] = 1;

switch ($folder[1]) {
	case "customer":
		if (!$_SESSION['customer_logged_in'] || !$_SESSION['customer_id']) {
			session_destroy();
			unset($_SESSION);

			header("Location: ../login.php");
			exit;
		}
		break;

	case "business":
		if (!$_SESSION['business_logged_in'] || !$_SESSION['staff_id']) {
			session_destroy();
			unset($_SESSION);

			header("Location: ../login.php");
			exit;
		}
		break;
}
?>