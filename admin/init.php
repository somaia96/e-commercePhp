<?php
include 'connect.php';
// ROUTES
$tpl = 'includes/templates/';
$lang ='includes/languages/';
$func ='includes/functions/';
$css = 'layout/css/';
$js = 'layout/js/';

// include important files 

include $func . 'functions.php';
include $lang . 'english.php';
include $tpl . 'header.php';

// include navbar to all pages exept the one with $noNavbar variable

if(!isset($noNavbar)) {include $tpl . 'navbar.php';}
