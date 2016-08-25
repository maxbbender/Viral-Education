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
        SELECT DISTINCT stats_text.word, stats_text.defined, stats_text.text_id
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



/* ---------------------------------------------------------*/

.container {
    width: 200px;
    height: 260px;
    -webkit-perspective: 800px;
    -moz-perspective: 800px;
    -o-perspective: 800px;
    perspective: 800px;
	
}
.card {
	
    width: 240%;
    height: 100%;
    
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
  

  
}

.card .back {
     background-image: url("https://upload.wikimedia.org/wikipedia/commons/2/2e/Notecard.jpg");
    -webkit-transform: rotateY( 180deg );
    -moz-transform: rotateY( 180deg );
    -o-transform: rotateY( 180deg );
    transform: rotateY( 180deg );
	
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

    <div class="row">
    	<div class="small-5 columns small-centered text-center">
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
    	</div>
	</div>	
	<br>
	<br>
	<div class="row">

		<div class="small-12 columns text-center">
			<a id=pre_button class="button small">Previous Word</a> 
			<a id=flip_button class="button small" >Flip Card</a>
			<a id=next_button class="button small">Next Word</a>
		</div>

	</div>

<?php include_once 'includes/javascript_basic.php'; ?>

<script >

	$(document).ready(function(){
		
			
		var curCard = 0;
    $("#flashcardf0").show();
	$("#flashcardb0").show();
	$( "#pre_button" ).click(function() {
		if ($('#pre_button').is('[disabled=disabled]')){
			console.log('is next disabled'+$('#next_button').prop('disabled'));
}
		else{
			
			 $('#pre_button').attr("disabled", true);
			 $('#next_button').attr("disabled", true);
			 

		$( "#flashcardb"+curCard).fadeOut( "slow", function() {
			
			
			curCard--;
			if (curCard<0){ 
				curCard= <?php echo $numWords -1 ?>;
				$( "#flashcardf"+curCard).fadeIn( "slow", function(){});
				$( "#flashcardb"+curCard).fadeIn( "slow", function(){});
				$('#pre_button').attr("disabled", false);
				$('#next_button').attr("disabled", false);
				
			}
			else {
			$( "#flashcardf"+curCard).fadeIn( "slow", function(){});
			$( "#flashcardb"+curCard).fadeIn( "slow", function(){});
			$('#pre_button').attr("disabled", false);
			$('#next_button').attr("disabled", false);
			
			}
  });
		$( "#flashcardf"+curCard).fadeOut( "slow", function() {
    // Animation complete.
  });
		}});
	
	
	
	
	
	
	
	$( "#next_button" ).click(function() {
		if ($('#next_button').is('[disabled=disabled]')){
			console.log('is next disabled'+$('#next_button').prop('disabled'));
}
		else{
			if ($('.card').hasClass('flipped')){
				$('.card').toggleClass('flipped');
			}
			
			 $('#next_button').attr("disabled", true);
			  $('#pre_button').attr("disabled", true);
			  
			
			
			
		console.log('is next disabled'+$("#next_button").is(":disabled"));
		$( "#flashcardb"+curCard).fadeOut( "slow", function() {
			
						
			
			curCard++;
			if (curCard> <?php echo $numWords-1?>){ 
				curCard= 0;
				$( "#flashcardf"+curCard).fadeIn( "slow", function(){});
				$( "#flashcardb"+curCard).fadeIn( "slow", function(){});
				 $('#next_button').attr("disabled", false);
				  $('#pre_button').attr("disabled", false);
				  	
		
			}
			else {
			$( "#flashcardf"+curCard).fadeIn( "slow", function(){});
			$( "#flashcardb"+curCard).fadeIn( "slow", function(){});
			 $('#next_button').attr("disabled", false);
			  $('#pre_button').attr("disabled", false);
			  	
			}
  });
		$( "#flashcardf"+curCard).fadeOut( "slow", function() {
    // Animation complete.
  });
	}});

	$( "#flip_button" ).click(function() {
    $('.card').toggleClass('flipped');
	})
});	
</script>
</body>
</html>
