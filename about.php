<?php
// page settings
require "./inc/session.php";
require "./inc/db.php";
require "./inc/global_vars.php";
require "./inc/functions.php";

// page controllers
// include "./controller/about.php";

// page header
include "./view/header.php";

// page views
include "./view/about.php";

// page footer
include "./view/footer.php";
?>