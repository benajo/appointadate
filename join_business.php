<?php
// page settings
require "./inc/session.php";
require "./inc/db.php";
require "./inc/global_vars.php";
require "./inc/functions.php";
require "./inc/password.php";

// page controllers
include "./controller/join_business.php";

// page header
include "./view/header.php";

// page views
include "./view/join_business.php";

// page footer
include "./view/footer.php";
?>