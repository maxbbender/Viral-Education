<?php
require '../includes/db_connect.php';
require '../includes/functions.php';
if (isset($_GET['word'])) {
	$word = $_GET['word'];
	
	$photos = getPhotos($word, $mysqli);
	header('Content-Type: application/json');
	echo json_encode($photos);
}