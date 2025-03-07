<?php
 

    include 'connect.php';

// Routes

    $tpl = 'includes/templates/';  // Template directory 
    $lang = 'includes/langugues/'; // Language directory
    $func = 'includes/functions/'; //function directory 
    $css = 'layout/css/';
    $js = 'layout/js/';
    

// Include the important files
    include $func . 'functions.php';
    include $lang . 'english.php';
    include $tpl . 'header.php';


// navbar in all pages exculde page nonavbar show in 

    if(!isset($noNavbar)){include $tpl . 'navbar.php';}


?>
