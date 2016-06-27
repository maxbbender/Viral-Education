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
			$content = '
                <div class="row text-center">
                    <h2> ' . html_entity_decode ( $title ) . '</h2>
                </div><hr>
                <div class="row">
                    <div id="content" style="height:600px; overflow:scroll;" class="small-8 columns panel">
                        ' . $content . '
                    </div>
                    <div id="translate" class="small-4 columns">
                        <div class="row panel callout">
                            <h3 class="subheader">Google Translate</h3>
                            <span id="googleTranslate" class="">No Word Selected</span>
                        </div>
                        <div class="row panel callout">
                            <h3 class="subheader">Word Reference</h3>
                            <div class="row">
                                <div class="large-12 columns">
                                    <span id="wordReferenceTranslate" class="">No Word Selected</span>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="large-12 columns">
                                    <a href="#" data-options="align:right;is_hover:true" data-dropdown="wordReferenceUsage" class="button small">See Usages</a>
                                    <ul id="wordReferenceUsage" data-dropdown-content class="medium f-dropdown" aria-hidden="true" tabindex="-1">
                                        <li>No Word Selected/No Available Usage</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
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
</head>
<body>
<?php include_once 'includes/main_nav.php'; ?> 
<div class="row">
    <?php echo $content; ?>
</div>
<?php include_once 'includes/javascript_basic.php'; ?>
<script>
    $( document).ready(function(){
        $(content).click(function () {
            var wordClicked = window.getSelection();
            wordClicked.modify('extend', 'backward', 'word');

            // This is testing for "phrases" and removing words to the right and left
            // of the word
            var wordClickedModified1 = wordClicked.toString();

            wordClicked.modify('extend', 'forward', 'word');

			/* testing for "phrases" to the left of the word */
            var wordClickedModified1 = s.toString();
            s.modify('move', 'forward', 'character');
            var i = wordClickedModified2 + wordClickedModified1;
            var wordFinal = i.toLowerCase();
			wordFinal = wordFinal.split(" ")[0];
			wordFinal = wordFinal.replace(/[\.,-\/#!$%\^&\*;:{}=\-_`~()]/g, "");
			
            if (hasSpace(wordFinal)) {
                DefineElement(wordFinal);
            } else {
                document.getElementById("googleTranslate").innerHTML = "You can not select phrases";
                document.getElementById("wordReferenceTranslate").innerHTML = "You can not select phrases";
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
                            try{
                                while(check == 1){
                                    $("<li><strong>" + data.original.Compounds[inc].OriginalTerm['term'] + ":</strong> " + data.original.Compounds[inc].FirstTranslation['term'] + "</li>").appendTo(wordReferenceUsage);
                                    try {
                                        var temp = data.original.Compounds[inc+1].OriginalTerm['term'];
                                        inc++;
                                    } catch (err){
                                        check = 0;
                                    }
                                }
                            } catch(err){

                            }

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
    });
</script>
</body>
</html>