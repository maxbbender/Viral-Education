<?php
	require '../includes/db_connect.php';
	require '../includes/functions.php';
	if (isset($_GET['word'], $_GET['context_url'], $_GET['thumbnail_url'])) {
		$word = $_GET['word'];
		$context = $_GET['context_url'];
		$thumbnail = $_GET['thumbnail_url'];
		
		if (insertPhotos($word, $context, $thumbnail, $mysqli)) {
			return json_encode(array('success' => 'true'), JSON_FORCE_OBJECT);
		} else {
			return json_encode(array('success' => 'false'), JSON_FORCE_OBJECT);
		}
	}
?>