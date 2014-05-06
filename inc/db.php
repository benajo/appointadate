<?php
// if the website is running on the herokuapp website then use the remote DB
if (preg_match("/(herokuapp)/i", $_SERVER['HTTP_HOST'])) {
	$mysqli = new mysqli('176.32.230.14', 'web14-appoint', 'nomorejsp', 'web14-appoint');
}
// use the local development DB on a devs laptop when not on the heroku app website
// this is because the university proxy wouldn't allow a connection to a remove DB
else {
	$mysqli = new mysqli('localhost', 'root', 'password', 'web14-appoint');
}

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
?>