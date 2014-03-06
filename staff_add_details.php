<?php
// page settings
require "./inc/session.php";
require "./inc/secure_staff.php";
require "./inc/db.php";
require "./inc/global_vars.php";
require "./inc/functions.php";
require "./inc/password.php";

// page controllers
include "./controller/staff_details.php";

// page header
include "./view/header.php";

// page views
include "./view/staff_details.php";

// page footer
include "./view/footer.php";
?>