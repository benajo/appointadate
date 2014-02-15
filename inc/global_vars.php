<?php
$sql = "SELECT * FROM page_meta WHERE page = '{$_SERVER['PHP_SELF']}'";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();

define("PAGE_TITLE", $row['title']);
define("PAGE_KEYWORDS", $row['keywords']);
define("PAGE_DESCRIPTION", $row['description']);
?>