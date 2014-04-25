<?php
// page settings
require "./inc/session.php";
require "./inc/secure_staff.php";
require "./inc/db.php";
require "./inc/global_vars.php";
require "./inc/functions.php";

// page controllers
include "./controller/staff_noticeboard.php";

// page header
include "./view/header.php";

// page views
include "./view/staff_noticeboard.php";

// page footer
include "./view/footer.php";
?>