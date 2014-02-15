<?php
// page settings
require "../inc/session.php";
require "../inc/settings.php";
require "../inc/global_vars.php";
require "../inc/functions.php";

// page controllers
include "./controller/edit_details.php";

// page header
include "./view/header.php";

// page views
include "./view/edit_details.php";

// page footer
include "./view/footer.php";
?>