<?php
// page settings
require "../inc/session.php";
require "../inc/settings.php";
require "../inc/global_vars.php";
require "../inc/functions.php";

// page controllers
include "./controller/favourite_businesses.php";

// page header
include "./view/header.php";

// page views
include "./view/favourite_businesses.php";

// page footer
include "./view/footer.php";
?>