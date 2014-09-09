<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 7/30/14
 * Time: 3:46 PM
 */
session_start();
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
if (isset($_POST['class_name'])) {
    $query = "
        INSERT INTO classes
        (invite_id, creator_id, class_name, class_description, class_teacher, date_created)
        VALUES
        (?,?,?,?,?,?)
    ";
    if ($stmt = $mysqli->prepare($query)) {
        $classInvite = generateRandomString(5);
        $now = time();
        $stmt->bind_param("sissii", $classInvite, $_SESSION['user_id'], $_POST['class_name'], $_POST['description'], $_SESSION['user_id'], $now);
        $stmt->execute();
        if ($stmt->error != NULL) {
            header("Location: panel.php?error=" . $stmt->error);
        } else {
            header("Location: panel.php");
        }
    } else {
        header("Loaction: error.php");
    }
}
?>
