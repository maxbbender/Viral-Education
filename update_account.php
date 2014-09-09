<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/4/14
 * Time: 6:45 PM
 */
session_start();
include_once 'includes/db_connect.php';
$query = "
        UPDATE members
        SET email=?,fname=?,lname=?,phone=?
        WHERE id =?
    ";
if ($stmt = $mysqli->prepare($query)) {
    $stmt->bind_param("ssssi", $_POST['email'], $_POST['fname'], $_POST['lname'], $_POST['phone'], $_SESSION['user_id']);
    $stmt->execute();
    header("Location: account.php");
}