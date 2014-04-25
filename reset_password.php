<?php
// page settings
require "./inc/session.php";
require "./inc/db.php";
require "./inc/global_vars.php";
require "./inc/functions.php";
require "./inc/password.php";
require "./inc/phpmailer/PHPMailerAutoload.php";

// page controllers
include "./controller/reset_password.php";

// page header
include "./view/header.php";

// page views
include "./view/reset_password.php";

// page footer
include "./view/footer.php";
?>