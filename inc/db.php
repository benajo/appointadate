<?php
// $mysqli = new mysqli('176.32.230.14', 'web14-appoint', 'nomorejsp', 'web14-appoint');
$mysqli = new mysqli('localhost', 'root', 'password', 'web14-appoint');

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
?>