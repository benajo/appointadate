<?php
session_start();

if (!isset($_SESSION['customer_logged_in'])) {
	$_SESSION['customer_logged_in'] = null;
}
if (!isset($_SESSION['customer_id'])) {
	$_SESSION['customer_id'] = null;
}

if (!isset($_SESSION['staff_logged_in'])) {
	$_SESSION['staff_logged_in'] = null;
}
if (!isset($_SESSION['staff_id'])) {
	$_SESSION['staff_id'] = null;
}
if (!isset($_SESSION['staff_admin'])) {
	$_SESSION['staff_admin'] = null;
}
if (!isset($_SESSION['staff_business_id'])) {
	$_SESSION['staff_business_id'] = null;
}
?>