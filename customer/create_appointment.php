<?php
// page settings
require "../inc/session.php";
require "../inc/settings.php";
require "../inc/global_vars.php";
require "../inc/functions.php";

// page controllers
include "./controller/create_appointment.php";

// page header
include "./view/header.php";

// page views
include "./view/create_appointment.php";

// page footer
include "./view/footer.php";
?>