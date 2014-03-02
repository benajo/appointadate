<?php
// page settings
require "./inc/session.php";
require "./inc/secure.php";
require "./inc/db.php";
require "./inc/global_vars.php";
require "./inc/functions.php";

// page controllers
include "./controller/customer_favourite_businesses.php";

// page header
include "./view/header.php";

// page views
include "./view/customer_favourite_businesses.php";

// page footer
include "./view/footer.php";
?>