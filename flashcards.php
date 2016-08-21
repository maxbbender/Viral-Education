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
// }
// /* entire container, keeps perspective */
// .flip-container {
	// perspective: 1000px;
	// text-align: center;
	// position: absolute;
	// left: 50%;
	// transform: translateX(-50%);
// }

// .flip-container, .front, .back {
	// width: 480px;
	// height: 288px;
	// position: center;
// }

// /* flip speed goes here */
// .flipper {
	// transition: 0.6s;
	// transform-style: preserve-3d;
	// position: relative;
// }

// /* hide back of pane during swap */
// .front, .back {
	// position: absolute;
	// top: 0;
	// left: 0;
	// backface-visibility: hidden;
	// -webkit-backface-visibility: hidden;
// }

// /* front pane, placed above back */
// .front {
	// z-index: 1;
	// background-image:
		// url("https://upload.wikimedia.org/wikipedia/commons/2/2e/Notecard.jpg");
// }

// /* back, initially hidden pane */
// .back {
	// transform: rotateX(-180deg);
	// background-image:
		// url("https://upload.wikimedia.org/wikipedia/commons/2/2e/Notecard.jpg");
	// animation: toFront 0.3s linear normal forwards;
// }

// .vertical.flip-container {
	// position: relative;
// }

// .vertical.flip-container:hover .back {
	// animation-delay: 0.3s;
	// animation: toBack 0.3s linear normal forwards;
// }

// @
// keyframes toBack { 0% {
	// z-index: 0;
// }

// 100%
// {
// z-index
// :
// 1;
// }
// }
// @
// keyframes toFront { 0% {
	// z-index: 1;
// }

// 100%
// {
// z-index
// :
// 0;
// }
// }
// .vertical.flip-container .flipper {
	// transform-origin: 100% 144px; /* half of height */
// }

// .vertical.flip-container:hover .flipper {
	// transform: rotateX(-180deg);
// }




/* ---------------------------------------------------------*/

.container {
    width: 200px;
    height: 260px;
    position: center;
    -webkit-perspective: 800px;
    -moz-perspective: 800px;
    -o-perspective: 800px;
    perspective: 800px;
	
}
.card {
    width: 240%;
    height: 100%;
    position: absolute;
    -webkit-transition: -webkit-transform 1s;
    -moz-transition: -moz-transform 1s;
    -o-transition: -o-transform 1s;
    transition: transform 1s;
    -webkit-transform-style: preserve-3d;
    -moz-transform-style: preserve-3d;
    -o-transform-style: preserve-3d;
    transform-style: preserve-3d;
    -webkit-transform-origin: 50% 50%;
}
.card div {
   
    height: 100%;
    width: 100%;
    line-height: 260px;
    color: black;
    text-align: center;
    font-weight: bold;
    font-size: 30px;
    position: absolute;
    -webkit-backface-visibility: hidden;
    -moz-backface-visibility: hidden;
    -o-backface-visibility: hidden;
    backface-visibility: hidden;
	
}
.card .front {
  background-image: url("https://upload.wikimedia.org/wikipedia/commons/2/2e/Notecard.jpg");
  background-position:50% 50%;
}
.card .back {
     background-image: url("https://upload.wikimedia.org/wikipedia/commons/2/2e/Notecard.jpg");
    -webkit-transform: rotateY( 180deg );
    -moz-transform: rotateY( 180deg );
    -o-transform: rotateY( 180deg );
    transform: rotateY( 180deg );
	background-position:50% 50%;
}
.card.flipped {
    -webkit-transform: rotateY( 180deg );
    -moz-transform: rotateY( 180deg );
    -o-transform: rotateY( 180deg );
    transform: rotateY( 180deg );
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
<br>
<center>
    <div class="row">

		<!-- dive for the card -->
	<div class="container ">
			<div class="card" >
    	<?php		
		
					$numWords = count ( $wordArray );
					$count = 0;
					
					if ($numWords > 0) {
					
						
						foreach ( $wordArray as $innerArray ) {
							
							echo '
 					<div class="front hidden" id = "flashcardf' . $count . '" >
 						Front Side
						
 						' . $innerArray [0] . ' </div>';
						
						
							echo '
					<div class="back hidden" id = "flashcardb' . $count . '">
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
	<br>
	<br>
		<div class="large-12 columns">
			<a id=pre_button class="button small">Previous Word</a> 
			<a id=flip_button class="button small" >Flip Card</a>
			<a id=next_button class="button small">Next Word</a>

		</div>

	</div>
</center>
	</div>


	</div>

	</div>

	</div>

<?php include_once 'includes/javascript_basic.php'; ?>

<script>
	$(document).ready(function(){
		var curCard = 0;
    $("#flashcardf0").show();
	$("#flashcardb0").show();
	$( "#pre_button" ).click(function() {
		$( "#flashcardb"+curCard).fadeOut( "slow", function() {
			
			
			curCard--;
			if (curCard<0){ 
				curCard= <?php echo $numWords -1 ?>;
				$( "#flashcardf"+curCard).fadeIn( "slow", function(){});
				$( "#flashcardb"+curCard).fadeIn( "slow", function(){});
			}
			else {
			$( "#flashcardf"+curCard).fadeIn( "slow", function(){});
			$( "#flashcardb"+curCard).fadeIn( "slow", function(){});
			}
  });
		$( "#flashcardf"+curCard).fadeOut( "slow", function() {
    // Animation complete.
  });
	});
	$( "#next_button" ).click(function() {
		$( "#flashcardb"+curCard).fadeOut( "slow", function() {
			
			curCard++;
			if (curCard> <?php echo $numWords-1?>){ 
				curCard= 0;
				$( "#flashcardf"+curCard).fadeIn( "slow", function(){});
				$( "#flashcardb"+curCard).fadeIn( "slow", function(){});
			}
			else {
			$( "#flashcardf"+curCard).fadeIn( "slow", function(){});
			$( "#flashcardb"+curCard).fadeIn( "slow", function(){});
			}
  });
		$( "#flashcardf"+curCard).fadeOut( "slow", function() {
    // Animation complete.
  });
	});

	$( "#flip_button" ).click(function() {
    $('.card').toggleClass('flipped');
	})
});	
</script>
</body>
</html>
