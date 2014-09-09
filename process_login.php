<?php
include_once 'db_connect.php';
include_once 'functions.php';

session_start(); //Secure session start
$root = $_SERVER['DOCUMENT_ROOT'];
if (isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = hash('sha512', $_POST['password']); //hashed password

    if (login($email, $password, $mysqli)) {
        //Login success
        header('Location: index.php'); //TO DO CHANGE TO PROFILE PAGE WITH TEXTS
    } else {
        //Login failed
        header('Location: login.php?error=1'); //TO DO: CHANGE WITH ERROR SAYING INVALID LOGIN
    }
} else {
    // The correct POST variables were not setn to this page.
    echo 'Invalid Requst';
}
?>
