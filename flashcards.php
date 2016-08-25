<?php
/**
 *  Project Talos - Learning Language Platform
 *
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


//get title of text used for flashcards
	
	$queryTitle = "
        SELECT title, content
        FROM texts
        WHERE id = ?
    ";
		if($stmt = $mysqli->prepare($queryTitle)){
			
        $stmt->bind_param("i", $_GET['textID']);
        $stmt->execute();
        $stmt->bind_result($title, $content);
        $stmt->store_result();
		
        if ($stmt->num_rows > 0) {
			
            $stmt->fetch();
            $returnArray = array();
            $returnArray[0] = $title;
            $returnArray[1] = $content;
			
            
			
        } else {
			
            return FALSE;
        }
    } else {
		
        return FALSE;
    }

}



?>
<html>
<head>
   
    <?php include_once 'includes/css_links.php'; ?>
	<?php include 'css/app.css'; ?>
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
<div class="row text-center">
    <h1>Review Cards  - <?php echo $returnArray[0] ?></h1><hr>
    <?php if ($error != NULL) {
        echo $error;
    } ?>
</div>
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
 					
						
 						' . $innerArray [0] . ' </div>';
						
						
							echo '
				<div class="back hidden" id = "flashcardb' . $count . '">
 					
 						' . $innerArray [1] . ' </div>';
							$count ++;
							$inc ++;
						}
						echo '
				<div class="front hidden" id = "doneCard">
					You are done with all the cards.
					</div>';
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
	<div class="row">

		<div class="small-12 columns text-center">
			<a id=done_button class="button small"> Done With this Card</a> 
			
		</div>

	</div>

<?php include_once 'includes/javascript_basic.php'; ?>

<script >

	$(document).ready(function(){
		
		var allDone = 0;
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
			if (curCard< 0){
						curCard =  <?php echo $numWords-1?>
					}
					while( $('#flashcardf'+curCard).hasClass('done')){
						curCard--;
						if (curCard< 0){
						curCard = <?php echo $numWords-1?>
					}
						console.log('got here');
					}
					
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
	
	
	
	
	
	
	$( "#next_button").click(function() {
	
	
			if ($('#next_button,#done_button').is('[disabled=disabled]')){
				console.log('is next disabled'+$('#next_button').prop('disabled'));
			}
			else{
				
					
						if ($('.card').hasClass('flipped')){
							$('.card').toggleClass('flipped');
						}
						
						 $('#next_button').attr("disabled", true);
						  $('#pre_button').attr("disabled", true);
						   $('#done_button').attr("disabled", true);
						  
						
						
						
					console.log('is next disabled'+$("#next_button").is(":disabled"));
					$( "#flashcardb"+curCard).fadeOut( "slow", function() {
						
								
						curCard++;
						if (curCard> <?php echo $numWords-1?>){
							curCard = 0
						}
						while( $('#flashcardf'+curCard).hasClass('done')){
							curCard++;
							if (curCard> <?php echo $numWords-1?>){
							curCard = 0
						}
							console.log('got here');
						}
						
						
						
					
						
						if (curCard> <?php echo $numWords-1?>){ 
							curCard= 0;
							$( "#flashcardf"+curCard).fadeIn( "slow", function(){});
							$( "#flashcardb"+curCard).fadeIn( "slow", function(){});
							 $('#next_button').attr("disabled", false);
							  $('#pre_button').attr("disabled", false);
							   $('#done_button').attr("disabled", false);
								
					
						}
						else {
						$( "#flashcardf"+curCard).fadeIn( "slow", function(){});
						$( "#flashcardb"+curCard).fadeIn( "slow", function(){});
						 $('#next_button').attr("disabled", false);
						  $('#pre_button').attr("disabled", false);
						   $('#done_button').attr("disabled", false);
							
						}
			  });
					$( "#flashcardf"+curCard).fadeOut( "slow", function() {
				// Animation complete.
			  });
		}
	});

	$( "#flip_button" ).click(function() {
		if ($('#flip_button').is('[disabled=disabled]')){
				console.log('is next disabled'+$('#next_button').prop('disabled'));
			}
		else {
			$('.card').toggleClass('flipped');
		}
		
	})
	
	
	
	$( "#done_button" ).click(function() {
	 
			 $("#flashcardf"+curCard).addClass('done');
			 $("#flashcardb"+curCard).addClass('done');
			 if ($('#done_button').is('[disabled=disabled]')){
			console.log('is done disabled'+$('#next_button').prop('disabled'));
				}
			else{
				 allDone++;
			
			if(allDone==<?php echo $numWords?>){
					 $("#flashcardf"+curCard).addClass('done');
					 $("#flashcardb"+curCard).addClass('done');
					 $('#next_button').attr("disabled", true);
					 $('#pre_button').attr("disabled", true);
					 $('#done_button').attr("disabled", true);
					 $('#flip_button').attr("disabled", true);
					 $( "#flashcardb"+curCard).fadeOut( "slow");
					 $( "#flashcardf"+curCard).fadeOut( "slow", function(){
						$( "#doneCard").fadeIn( "slow");
						 })
				}
			else{
				if ($('.card').hasClass('flipped')){
							$('.card').toggleClass('flipped');
						}
						
						 $('#next_button').attr("disabled", true);
						  $('#pre_button').attr("disabled", true);
						   $('#done_button').attr("disabled", true);
						  
						
						
						
					console.log('is next disabled'+$("#next_button").is(":disabled"));
					$( "#flashcardb"+curCard).fadeOut( "slow", function() {
						
								
						curCard++;
						if (curCard> <?php echo $numWords-1?>){
							curCard = 0
						}
						while( $('#flashcardf'+curCard).hasClass('done')){
							curCard++;
							if (curCard> <?php echo $numWords-1?>){
							curCard = 0
						}
							console.log('got here');
						}
						
						
						
					
						
						if (curCard> <?php echo $numWords-1?>){ 
							curCard= 0;
							$( "#flashcardf"+curCard).fadeIn( "slow", function(){});
							$( "#flashcardb"+curCard).fadeIn( "slow", function(){});
							 $('#next_button').attr("disabled", false);
							  $('#pre_button').attr("disabled", false);
							   $('#done_button').attr("disabled", false);
								
					
						}
						else {
						$( "#flashcardf"+curCard).fadeIn( "slow", function(){});
						$( "#flashcardb"+curCard).fadeIn( "slow", function(){});
						 $('#next_button').attr("disabled", false);
						  $('#pre_button').attr("disabled", false);
						   $('#done_button').attr("disabled", false);
							
						}
			  });
					$( "#flashcardf"+curCard).fadeOut( "slow", function() {
				// Animation complete.
			  });
		}
	}});

				
				
			});	
</script>
</body>
</html>
