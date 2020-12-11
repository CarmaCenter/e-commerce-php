<?php
include 'connect.php';
//Routes

$tpl = "includes/templates/"; //template Directory
$lang = "includes/languages/"; //lang Directory
$func = "includes/functions/"; //Functions Directory
$css = "layout/css/" ;  //Css Directory
$js = "layout/js/" ;  //js Directory

//include Important Files
include $func . "functions.php";
include $lang ."en.php";
include $tpl. "header.php";

// Include Navebar On All Pages Expect The One With $noNavebar variable
if(!isset($noNavbar)){
    include $tpl ."navbar.php";
}
