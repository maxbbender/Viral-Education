<?php
/**
 *  Project Talos - Learning Language Platform
 *
 *  User's will be able to upload texts and read them. This is the homepage where you
 *  have to login to view statistics, and see texts that are assigned to you
 *
 * @Author: Dom Rossillo
 * @Version: 1.0
 */

// Start Session
session_start ();
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
$wordArray = array (
		array () 
);

$inc = 0;
if (isset ( $_GET ['textID'] )) { // is the textID set in the HTTP GET header
                                  // Get the flash card words
	$query = "
        SELECT stats_text.word, stats_text.defined, stats_text.text_id
        FROM stats_text
        WHERE stats_text.text_id = 24 AND stats_text.reader_id = 43
    ";
	if ($stmt = $mysqli->prepare ( $query )) {
		//$stmt->bind_param ( "ii", $_GET ['textID'], $_SESSION ['user_id'] );
		$stmt->execute ();
		$stmt->bind_result ( $word, $define, $textID );
		$stmt->store_result ();
		
		if ($stmt->num_rows > 0) {
			while ( $stmt->fetch () ) {
				$wordArray [$inc] [0] = $word;
				$wordArray [$inc] [1] = $define;
				$wordArray [$inc] [2] = $textID;
				
				$inc ++;
			}
		}
	} else {
		error_log ( "MySQL error : " > $mysqli->error );
	}
}



?>
<html>
<head>
   
    <?php include_once 'includes/css_links.php'; ?>
    <style>
.hidden {
	display: none;
}

#titleText {
	font-family: 'Questrial', sans-serif;
	text-align: center;
	position: relative;
	top: 10%
}

#main {
	font-family: 'Open Sans', sans-serif;
	font-size: 400%;
}

.sub {
	font-family: 'Questrial', sans-serif;
	font-size: 200%;
}

.sub2 {
	font-family: 'Questrial', sans-serif;
	font-size: 300%;
}

.quest {
	font-family: 'Questrial', sans-serif;
}

#title {
	height: 500px;
	background-image: url('/img/green_dust_scratch.png');
}

.helper {
	display: inline-block;
	height: 100%;
	vertical-align: middle;
}

img {
	vertical-align: middle;
	max-height: 128px;
}
/* entire container, keeps perspective */
.flip-container {
	perspective: 1000px;
	text-align: center;
	position: absolute;
	left: 50%;
	transform: translateX(-50%);
}

.flip-container, .front, .back {
	width: 480px;
	height: 288px;
	position: center;
}

/* flip speed goes here */
.flipper {
	transition: 0.6s;
	transform-style: preserve-3d;
	position: relative;
}

/* hide back of pane during swap */
.front, .back {
	position: absolute;
	top: 0;
	left: 0;
	backface-visibility: hidden;
	-webkit-backface-visibility: hidden;
}

/* front pane, placed above back */
.front {
	z-index: 1;
	background-color: red;
	background-image:
		url("https://upload.wikimedia.org/wikipedia/commons/2/2e/Notecard.jpg");
}

/* back, initially hidden pane */
.back {
	transform: rotateX(-180deg);
	background-image:
		url("https://upload.wikimedia.org/wikipedia/commons/2/2e/Notecard.jpg");
	animation: toFront 0.3s linear normal forwards;
}

.vertical.flip-container {
	position: relative;
}

.vertical.flip-container:hover .back {
	animation-delay: 0.3s;
	animation: toBack 0.3s linear normal forwards;
}

@
keyframes toBack { 0% {
	z-index: 0;
}

100%
{
z-index
:
1;
}
}
@
keyframes toFront { 0% {
	z-index: 1;
}

100%
{
z-index
:
0;
}
}
.vertical.flip-container .flipper {
	transform-origin: 100% 144px; /* half of height */
}

.vertical.flip-container:hover .flipper {
	transform: rotateX(-180deg);
}
</style>
<link href='http://fonts.googleapis.com/css?family=Questrial'
	rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Luckiest+Guy'
	rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:600italic'
	rel='stylesheet' type='text/css'>
</head>
<body>
	<!-- Navigation Bar -->
<?php include_once 'includes/main_nav.php'; ?>


    <div class="row">

		<!-- dive for the card -->
		<div class="vertical flip-container"
			ontouchstart="this.classList.toggle('hover');">
			<div class="flipper">
    	<?php		
		
					$numWords = count ( $wordArray );
					$count = 0;
					echo 'got here';
					if ($numWords > 0) {
						echo 'got there';
						
						foreach ( $wordArray as $innerArray ) {
							
							echo '
 					<div class="front" id = "flashcard' . $count . '">
 						Front Side
						
 						' . $innerArray [0] . ' </div>';
						
						
							echo '
					<div class="back" id = "flashcard' . $count . '">
 						Back Side
 						' . $innerArray [1] . ' </div>';
							$count ++;
							$inc ++;
						}
					} else {
						error_log ( "Dom" );
					}
					
					
					?>
		
		

		
    </div>
		</div>
		<div class="large-12 columns">
			<a value=--$inc class="button small">Previous Word</a> <a value=++$inc
				class="button small">Next Word</a>

		</div>

	</div>
	</div>


	</div>

	</div>

	</div>
<?php include_once 'includes/javascript_basic.php'; ?>
<script>
	
</script>
</body>
</html>
