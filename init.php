<?php

// error reporting

ini_set("display_errors", "On");
error_reporting(E_ALL);

include "admin/connect.php";

$tmp  = "includes/templates/";
$func = "includes/functions/";
$lang = "includes/languages/";
$css  = "layout/css/";
$js  = "layout/js/";

//includes important files

include_once $func."functions.php";
include_once $lang."english.php";
include_once $tmp."header.php";



?>