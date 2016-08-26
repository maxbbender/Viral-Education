<?php
	header('Content-Type: application/json');
	require '../includes/db_connect.php';
	require '../includes/functions.php';
	if (isAdmin($_SESSION['user_id'], $mysqli)) {
		if (isset($_GET['reportID'])) {
			if(markReportDone($_GET['reportID'], $mysqli)) {
				$returnArray = array('success' => 'success');
			} else {
				$returnArray = array('error' => 'Error marking the report as done');
			}
		} else {
			$returnArray = array('error' => 'Report ID not specified');
		}
	} else {
		$returnArray = array('error' => 'AuthError');
	}
	
	echo json_encode($returnArray, JSON_FORCE_OBJECT);
?>