<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/7/14
 * Time: 4:06 PM
 */
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
session_start();
if (isset($_GET['classID'])) {
    if (checkTeacher($_GET['classID'], $mysqli)) {
        $query = "
            INSERT INTO assigned_texts
            (text_id, class_id, creator_id, date_created)
            VALUES (?,?,?,?)
        ";
        if ($stmt = $mysqli->prepare($query)) {
            $now = time();
            $stmt->bind_param("iiii", $_GET['textID'], $_GET['classID'], $_SESSION['user_id'], $now);
            $stmt->execute();
            header("Location: view_class.php?class_id=" . $_GET['classID']);
        }
    } else {
        echo 'You are not the teacher';
    }
} else {
    echo 'Wrong parameters set';
}
