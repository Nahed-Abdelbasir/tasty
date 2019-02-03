<?php

include_once "connect.php";

$tmp  = "includes/templates/";
$func = "includes/functions/";
$lang = "includes/languages/";
$css  = "layout/css/";
$js  = "layout/js/";

//includes important files

include_once $func."functions.php";
include_once $lang."english.php";
include_once $tmp."header.php";

if(!isset($nonavbar)){
    include_once $tmp."navbar.php";
}

?>