<html>
	<head>
		<title>Testing</title>
		<?php include_once 'includes/css_links.php' ?>
		
	</head>
	<body>
	<table>
<!-- 		<tbody id = "p"> -->
<!-- 		</tbody> -->
	<span id = "p"></span>
	</table>
	<?php include_once 'includes/functions.php' ?>
	<?php include_once 'includes/db_connect.php' ?>
	<?php include_once 'includes/javascript_basic.php' ?>
	<script>
	$(document).ready(function() {
// 		alert("gothere");
		var imageSearchApiKey ="AIzaSyD75qLbuILR5M-RlF4FHgjKg2sQ2Yh8byg";
		var imageSearchEngineID = "004858954618909365061:lujb8xyl_ui";
		var imageSearchBaseURL = "https://www.googleapis.com/customsearch/v1?searchType=image";
		var imageSearchFullURL = imageSearchBaseURL + "&key=" + imageSearchApiKey + "&cx=" + imageSearchEngineID;
		var word = '<?php echo $_GET['word']; ?>';
// 		alert (word);
		<?php if (!alreadyHasPhotos($_GET['word'], $mysqli)) {?>
		$.getJSON(imageSearchFullURL + "&q=" + word, function (data) {
			var items = data.items;
			$.each(items, function() {
				image = this.image;
				$.ajax({
                    type: "GET",
                    url: "helpers/insertPic.php",
                    data: {word : word, context_url : this.link, thumbnail_url: image.thumbnailLink},
                    dataType: "JSON",
                    success: function (result) {
                        if (result.success == 'true') {
                            alert ('it worked!');
                        } else {
                            alert ('it failed!');
                    	}
                    }
                        
                });
			});
		});
		<?php } else { echo '$("#p").html("photos already here");'; }?>
	});
	</script>
	</body>
</html>