<?php
include_once 'db_connect.php';
include_once 'functions.php';

session_start(); //Secure session start
if (isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $login = -1;
    $login = login($username, $password, $mysqli);

    /*
     * ErrorCode(0) - Login Success
     * ErrorCode(3) - No User exists
     * ErrorCode(5) - Password Incorrect
     * 
     */
    if ($login == 0) {
        //Login success
        header('Location: ../index.php');
    } else if ($login == 4) {
        header('Location: ../login.php?error=4');
    } else if ($login == 3) {
        header('Location: ../login.php?error=3');
    } else {
        header('Location: ../login.php?error=' . $login);
    }
} else {
    // The correct POST variables were not setn to this page.
    echo 'Invalid Request';
}
?>
