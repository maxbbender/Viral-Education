<?php
	require 'includes/functions.php';
	require 'includes/db_connect.php';
?>
<html>
<head>
	<title>Viral Education - View Reports</title>
	<?php
		require 'includes/css_links.php';
	?>
</head>
<body>
	
	<?php 
	require 'includes/main_nav.php'; 
	echo '<div class="row text-center">
		<h1>View Reports</h1>
	</div>';
	if (!isAdmin($_SESSION['user_id'], $mysqli)) {
	?>
	<div data-alert class="row alert-box alert radius">
		You do not have access to this page, please go back
	  <a href="#" class="close">&times;</a>
	</div>
	<?php 
	} else {
		echo '<div class="row"><div class="small-8 columns small-centered">';
		$reports = getReports($_SESSION['user_id'], $mysqli);
		foreach ($reports as $report) {
			echo '
				<div id ="report' . $report[0] . '" class="row panel">
					<h3>Submitted by : ' . $report[4] . '<h3>
					Content : ' . $report[1] . '
					<br>
					URL : ' . $report[2] . '
					<br>
					Page : ' . $report[3] . '
					<br><br>
					<a href="#" class="reportButton button radius" id ="reportButton' . $report[0] . '" value="Mark Done">Done</a>
				</div><br>
			';
		}
		echo '</div></div>';
	}
	
	require 'includes/javascript_basic.php';
	?>
	<script>
	$(document).ready(function() {
		$('.reportButton').click(function(event) {
			var id = event.target.id;
			var reportID = id.charAt(12);
			$.ajax({
                type: "GET",
                url: "/helpers/reportDone.php",
                data: {reportID : reportID},
                success: function (results) {
                    if (results.hasOwnProperty('success')) {
                        $('#report' + reportID).fadeOut();
                    } else {
                        $(this).append('<div data-alert class="row alert-box alert radius">Error : ' + results.error + '<a href="#" class="close">&times;</a></div>');
                	}
                }
            });
		});
	});
	
		
	</script>
</body>
</html>