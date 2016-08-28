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
            (text_id, class_id, creator_id, date_created,assignment_due)
            VALUES (?,?,?,?,?)
        ";
        if ($stmt = $mysqli->prepare($query)) {
            $now = time();
			$day= substr("". $_GET['date']." ". $_GET['time'],0,2);
			$month= substr("". $_GET['date']." ". $_GET['time'], 3, 2);
			$year= substr("". $_GET['date']." ". $_GET['time'], 6, 4);
			$timestamp =strtotime("". $year ."-".$month."-".$day." ".$_GET['time']);
			$dt  =  date("Y-m-d H:i:s", $timestamp);
            $stmt->bind_param("iiiis", $_GET['textID'], $_GET['classID'], $_SESSION['user_id'], $now, $dt);
            $stmt->execute();
			//echo $day;
			//echo $month;
			//echo $year;
			echo $timestamp;
			//echo $dt;
			//echo $now;
           // header("Location: view_class.php?class_id=" . $_GET['classID']);
        }
    } else {
        echo 'You are not the teacher';
    }
} else {
    echo 'Wrong parameters set';
}
