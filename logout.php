<?php
// Reference: https://www.php.net/manual/en/book.session.php
require_once('connection.php');
session_start();
    if (!(isset($_SESSION['loggedin'])) && $_SESSION['loggedin'] != true){
        // if user is not logged in, redirect them to the login page.
        header("location: login.php");
        exit;
    }
    session_unset(); 
    session_destroy();
    header("Location: login.php");
    exit; 
?>