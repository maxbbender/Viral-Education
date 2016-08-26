<?php
require '../includes/db_connect.php';
require '../includes/functions.php';
header('Content-Type: application/json');

if (isset($_GET['word'])) {
	$word = $_GET['word'];
	$photos = getPhotos($word, $mysqli);
	if ($photos != false) {
		echo json_encode($photos);
	} else {
		echo json_encode([]);
	}
} else {
	echo 'GET word not defined';
}