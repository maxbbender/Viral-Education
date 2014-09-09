<?php
include_once 'includes/functions.php';
session_start();

//Unset all session values
$_SESSION = array();

//Get session parameters
$params = session_get_cookie_params();

// Delete the cookie
setcookie(session_name(),
    '', time() - 42000,
    $params["path"],
    $params["domain"],
    $params["secure"],
    $params["httponly"]);

// Destroy session
session_destroy();
echo '<script type="text/javascript">location.replace("http://talos.benderdesigns.org/index.php");</script>';
?>