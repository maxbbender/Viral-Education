<?php
header ( "Access-Control-Allow-Origin: *" );
session_start ();
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
$class = 0;
/*
 * For this we need the textID to load as well as the class
 * in which we are loading this text for
 */
if (isset ( $_GET ['textID'] )) { // is the textID set in the HTTP GET header
	if (isset ( $_GET ['class'] )) { // is the class set in the HTTP GET header
		$class = $_GET ['class'];
		// Records that student has read text
		recordStudentRead ( $_GET ['textID'], $_GET ['class'], $_SESSION ['user_id'], $mysqli );
	}
	
	// Get the content/title of the text
	$query = "
        SELECT title, content
        FROM texts
        WHERE id = ?
    ";
	
	if ($stmt = $mysqli->prepare ( $query )) {
		$stmt->bind_param ( "i", $_GET ['textID'] );
		$stmt->execute ();
		$stmt->bind_result ( $title, $content );
		$stmt->store_result ();
		
		// if the text has returned rows
		if ($stmt->num_rows > 0) {
			$stmt->fetch ();
			$spannedWords = "";
			$re = "/(<p[^>]*>)(.*)(<\\/p>)/";
			preg_match_all($re, $content, $matches);
			
			for ($i = 0; $i <= count($matches[1]); $i++) {
				$spannedWords = $spannedWords . $matches[1][$i];
				$explodedContent = explode (" ", $matches[2][$i] );
				foreach ( $explodedContent as $word ) {
					$spannedWords = $spannedWords . "<span class='clickable'>" . $word . " </span>";
				}
				$spannedWords = $spannedWords . $matches[3][$i];
			}
			
			$data = '
                <div class="row text-center">
                    <h2> ' . html_entity_decode ( $title ) . '</h2>
                </div><hr>
                <div class="row">
                    <div id="content" style="height:600px; overflow:scroll;" class="small-8 columns panel">
                      	' . $spannedWords . '
                    </div>
                    <div id="translate" class="small-4 columns">
                        <div class="row panel callout">
                            <h3 class="subheader">Translations</h3>
                            <span>Google Translate : </span><span id="googleTranslate" class="">No Word Selected</span><br><br>
                        	<span>Word Reference : </span><span id="wordReferenceTranslate" class="">No Word Selected</span>
                            <br><br>
                      		<div class="row">
                                <div class="large-12 columns">
                                    <a href="#" data-options="align:right;is_hover:true" data-dropdown="wordReferenceUsage" class="button small">See Usages</a>
                                    <ul id="wordReferenceUsage" data-dropdown-content class="medium f-dropdown" aria-hidden="true" tabindex="-1">
                                        <li>No Word Selected/No Available Usage</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
						<div class="row panel callout">
                      		<h3>Text to Speech (TTS)</h3>
                      		<div style="display:none;" id="ttsLoading" class="small-12 columns text-center">
                      			<object type="image/svg+xml" data="/img/loading.svg">
								  Your browser does not support SVG
								</object>
                      		</div>
                      		<div id="ttsAudioOuterDiv" style="display:none;" class="small-12 columns small-centered text-center">
                      			<div id ="ttsWordWithAudio" class="row">
                      				<div class="small-12 columns text-center">
                      					<strong><h3 id=""></h3></strong>
                      				</div>
                      			</div>
                      			<div class="row">
                      				<div class="small-12 columns">
                      					<div id="ttsAudioDiv" style="display:none;" >
                      						<embed id="ttsAudio" src="" type="audio/ogg">
                      					</div>
                      				</div>
                      			</div>
                      		</div>
                      		<div id="initalTTS">
	                      		<div style ="vertical-align: middle;" class="small-8 columns text-center">
                      				<h3 style ="line-height: 70px; vertical-align: middle;" id="ttsText"></h3>
                      			</div>
                      			<div style ="vertical-align: middle;" id="ttsClick" class="small-4 columns">
                      				<img style="height:70px" src="/img/tts.png">
                      			</div>
                      		</div>
                      	</div>
						<div class="row panel callout">
                            <h3 class="subheader">Word Review</h3>
                            <br>
                            <div class="row">
                                <div class="large-12 columns">
                                    <a href="flashcards.php?textID=' . $_GET ['textID'] . '" data-options="align:right;" class="button small">Review Cards</a>   
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div id = "pictures" class="row ">
                    <div class="small-8 columns small-centered">
                    	<div id="picSliderDiv" class="picSlider"></div>
               		</div>    
               </div>
            ';
		}
	}
}
?>
<html>
<head>
<title>View Text - <?php echo $title; ?></title>
<!-- <meta charset="UTF-8"> -->
    <?php include_once 'includes/css_links.php'; ?>
    <link rel="stylesheet" type="text/css"
	href="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css" />
<style>
#pictures {
	height: 500px;
}
</style>

</head>
<body>
<?php include_once 'includes/main_nav.php'; ?> 
<div class="row">
    <?php echo $data; ?>
</div>
<?php include_once 'includes/javascript_basic.php'; ?>
<script type="text/javascript"
		src="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js"></script>
	<script>
	$(document).ready( function(){
		var currentTTSWord = "";
        $('.picSlider').slick({
        	autoplay : true,
        	arrows : false,
        	slidesToShow: 2,
        	slidesToScroll: 1
        });

        
                
        
        $(".clickable").unbind().click( function () {
            
            var wordClicked = $(this).html();
            if (wordClicked != '1') {

                // Clean word
                wordClicked = checkForHTMLTags(wordClicked);
    			wordClicked = cleanWord(wordClicked);
    			wordClicked = wordClicked.toLowerCase();
    		
                DefineElement(wordClicked);
    			getPhotos(wordClicked);
				currentTTSWord = wordClicked;
				
    			// Populate the TTS field
    			$('#ttsText').html(wordClicked);
    			
    			if ($('#initalTTS').css('display') == 'none') {
    				$('#ttsAudioDiv').fadeOut();
        			$('#ttsWordWithAudio').fadeOut(function() {
        				$("#initalTTS").fadeIn();
        			});
    			}
            }
        });

        $('#ttsClick').click(function () {
            if (currentTTSWord != "") {
            	$('#initalTTS').fadeOut(function() {
                    $('#ttsLoading').fadeIn(function() {
                    	$.ajax({
                            type: "GET",
                            url: "/helpers/getTTSAudio.php",
                        	data: {word : currentTTSWord},
                           	success: function(result){
                               	$('#ttsAudioDiv').empty();
                               	$('#ttsAudioDiv').append('<embed style="max-height:40px;" src="data:audio/ogg;base64,' + result + '" type="audio/ogg">');
                               	$('#ttsWordWithAudio h3').html(currentTTSWord);	
                               	$('#ttsLoading').fadeOut(function() {
                                   	$('#ttsAudioDiv').fadeIn();
                                	$('#ttsAudioOuterDiv').fadeIn();
                                	$('#ttsWordWithAudio').fadeIn();
                                   	
                               	});
                           		
                      		}
                    	});
                    });
                });
            } 
            
        });

        /* Defines the element from all translation engines. This is where we do 
         * all the API requests with "element" as the word to define.
         */
        function DefineElement(element) {
            var cleanedElement = element.replace(/[\.,-\/#!$%\^&\*;:{}=\-_`~()]/g, "");
            //Google Translate
            $.ajax({
                type: "GET",
                url: "https://www.googleapis.com/language/translate/v2",
                data: {key: "AIzaSyAJgQycIa3vSLEuC48VND-ZhkOHfxBRcSM", source: "es", target: "en", q: cleanedElement},
                success: function (results) {
                    // alert(results.data.translations[0].translatedText);
                    document.getElementById("googleTranslate").innerHTML = cleanedElement + ": " + results.data.translations[0].translatedText;
                    var definedWord = results.data.translations[0].translatedText;

                    //Word Reference
                    $.getJSON('http://api.wordreference.com/0.8/36c73/json/esen/' + cleanedElement + '?callback=?',
                        function alertcall(data){
                            var tense = "";
                            var verb = "";
                            var inc = 0;
                            //alert(data.term0.PrincipalTranslations[inc].FirstTranslation['term']);
                            /*if(data[0].PrincipalTranslations[inc].FirstTranslation['term'] === undefined){
                             $(wordReferenceTranslate).html(data.term1.PrincipalTranslations[inc].OriginalTerm['term'] + ': ' + data.term1.PrincipalTranslations[inc].FirstTranslation['term']);
                             }  else {
                             $(wordReferenceTranslate).html(data.term0.PrincipalTranslations[inc].OriginalTerm['term'] + ': ' + data.term0.PrincipalTranslations[inc].FirstTranslation['term']);
                             }*/
                            try {
                                //verb = verbCheck(data.term0.PrincipalTranslations[inc].OriginalTerm['POS']);
                                $(wordReferenceTranslate).html(data.term0.PrincipalTranslations[inc].OriginalTerm['term'] + ': ' + verb + data.term0.PrincipalTranslations[inc].FirstTranslation['term']);
                            } catch(err) {
                                try {
                                    // verb = verbCheck(data.term1.PrincipalTranslations[inc].OriginalTerm['POS']);
                                    $(wordReferenceTranslate).html(data.term1.PrincipalTranslations[inc].OriginalTerm['term'] + ': ' + verb + data.term1.PrincipalTranslations[inc].FirstTranslation['term']);
                                } catch(err) {
                                    try {
                                        //  verb = verbCheck(data.term2.PrincipalTranslations[inc].OriginalTerm['POS']);
                                        $(wordReferenceTranslate).html(data.term2.PrincipalTranslations[inc].OriginalTerm['term'] + ': ' + verb + data.term2.PrincipalTranslations[inc].FirstTranslation['term']);
                                    } catch(err) {
                                        try {
                                            //    verb = verbCheck(data.term3.PrincipalTranslations[inc].OriginalTerm['POS']);
                                            $(wordReferenceTranslate).html(data.term3.PrincipalTranslations[inc].OriginalTerm['term'] + ': ' + verb + data.term3.PrincipalTranslations[inc].FirstTranslation['term']);
                                        } catch(err) {
                                            try {
                                                //      verb = verbCheck(data.term4.PrincipalTranslations[inc].OriginalTerm['POS']);
                                                $(wordReferenceTranslate).html(data.term4.PrincipalTranslations[inc].OriginalTerm['term'] + ': ' + verb + data.term4.PrincipalTranslations[inc].FirstTranslation['term']);
                                            } catch(err) {

                                            }
                                        }
                                    }
                                }
                            }

                            //alert(JSON.stringify(data));
                            //alert('http://api.wordreference.com/0.8/36c73/json/esen/' + cleanedElement + '?callback=?');
                            //Usages
                            $(wordReferenceUsage).empty();
                            var check = 1;
                            var compounds = data.original.Compounds;

							$.each(compounds, function() {
								$("#wordReferenceUsage").append("<li><strong>" + this.OriginalTerm.term + " : </strong>" + this.FirstTranslation.term + "</li>");
							});

                        });

                	// post the word searched to our databases
                    $.ajax({
                        type: "POST",
                        url: "read_text.php",
                        data: {textID: <?php echo $_GET['textID'] ?>, readerID: <?php echo $_SESSION['user_id'] ?>, defined: definedWord, word: cleanedElement, classID: <?php if(isset($_GET['class'])){ echo $_GET['class']; } else {echo 0;} ?>},
                        dataType: "html",
                        success: function (result) {
                            //alert(result);
                        }
                    });
                }
            });
        }

        // Does var "s" have a space in it?
        function hasSpace(s) {
            return s.indexOf(' ') === -1;
        }

        // Is this a verb?
        function typeCheck(s){
            if (s.indexOf("v") >= 0){
                return 'Verb';
            }
        }

        // What tense?
        function verbCheck(s){
            var tense = typeCheck(data.term0.PrincipalTranslations[inc].OriginalTerm['POS']);
            if (tense == 'Verb'){
                return 'to ';
            }
        }

        function checkForHTMLTags(stringToCheck) {
		    var re = /<\w+>\s*(\w+)\s*<\/\w+>/; 
		    var m;
		     
		    if ((m = re.exec(stringToCheck)) !== null) {
		        if (m.index === re.lastIndex) {
		            re.lastIndex++;
		        }
		        return m[1];
		    } else {
			    return stringToCheck;
		    }
        }

        function cleanWord(stringToClean) {
            var re = /\W*([\wραινσϊό]+)\W*/ ; 
            var m;
             
            if ((m = re.exec(stringToClean)) !== null) {
                if (m.index === re.lastIndex) {
                    re.lastIndex++;
                }

                return m[1];
            } else {
                return stringToClean;
            }
        }

        function populatePhotos(word) {
        	var imageSearchApiKey ="AIzaSyAJgQycIa3vSLEuC48VND-ZhkOHfxBRcSM";
     		var imageSearchEngineID = "004858954618909365061:lujb8xyl_ui";
     	    var imageSearchBaseURL = "https://www.googleapis.com/customsearch/v1?searchType=image";
     		var imageSearchFullURL = imageSearchBaseURL + "&key=" + imageSearchApiKey + "&cx=" + imageSearchEngineID;
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
                            
                         }
                             
                     });
     			});
     			getPhotos(word);
     		});
        }
        
		function fillData(results) {
			$('.picSlider').slick('unslick');
            $('#picSliderDiv').empty();
            $('#picSliderDiv').fadeOut(function() {
            	$.each(results, function(index, value) {
                   $newDiv = '<div><img class="sliderPics" src="' + value[0] + '"></div>';
                	$('#picSliderDiv').append($newDiv);
                });
            	$('.picSlider').slick({
            		centerPadding : '50px',
                	autoplay : true,
                	arrows : false,
                	slidesToShow: 2,
                	slidesToScroll: 1,
            });
            	$('#picSliderDiv').fadeIn();
            });
		}
		
        function getPhotos(word) {
            if (word != '1') {
        	 $.ajax({
                 type: "GET",
                 url: "/helpers/getPhotos.php",
                 data: {word:word},
                 success: function (results) {
                     if (results.length === 0) {
                    	 populatePhotos(word);
                     } else {
                    	 fillData(results);
                     }
                 }
             });
            }
        }
            
    });
</script>
</body>
</html>