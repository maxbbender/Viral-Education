<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/7/14
 * Time: 4:34 PM
 */
session_start();
include_once 'includes/db_connect.php';
if (isset($_POST['readerID'])) {
    $query = "
        INSERT INTO stats_text
        (text_id, reader_id, word, defined, date_created, class_id)
        VALUES (?,?,?,?,?,?)
    ";
    $word = preg_replace("/(?![.=$'€%-])\p{P}/u", "", $_POST['word']);
    $defined = preg_replace("/(?![.=$'€%-])\p{P}/u", "", $_POST['defined']);
    if ($stmt = $mysqli->prepare($query)) {
        $now = time();
        if (!isset($_POST['classID'])) {
            $classID = 0;
        } else {
            $classID = $_POST['classID'];
        }
        $stmt->bind_param("iissii", $_POST['textID'], $_POST['readerID'], htmlentities($word), htmlentities($defined), $now, $classID);
        $stmt->execute();
        echo $stmt->error;
    }
}