<?php
if (preg_match("/(herokuapp)/i", $_SERVER['HTTP_HOST'])) {
	$mysqli = new mysqli('176.32.230.14', 'web14-appoint', 'nomorejsp', 'web14-appoint');
}
else {
	// $mysqli = new mysqli('176.32.230.14', 'web14-appoint', 'nomorejsp', 'web14-appoint');
	$mysqli = new mysqli('localhost', 'root', 'password', 'web14-appoint');
}

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
?>