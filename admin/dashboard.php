<?php 
    session_start();

    if(isset($_SESSION['Username']))
    {
        $pageTitle = 'Dashboard';
        include 'init.php';
  
    } 
    else 
    {
        
        header('Location: index.php');

        exit();
    }
    ?>