<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/5/14
 * Time: 5:23 PM
 */
header("Access-Control-Allow-Origin: *");
session_start();
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
$class = 0;
if (isset($_GET['textID'])) {
    if (isset($_GET['class'])) {
        $class = $_GET['class'];

        //Records that student has read text
        recordStudentRead($_GET['textID'], $_GET['class'], $_SESSION['user_id'], $mysqli);
    }

    $query = "
        SELECT title, content
        FROM texts
        WHERE id = ?
    ";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $_GET['textID']);
        $stmt->execute();
        $stmt->bind_result($title, $content);
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->fetch();
            $content = '
                <div class="row text-center">
                    <h2> ' . html_entity_decode($title) . '</h2>
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
            var s = window.getSelection();
            s.modify('extend', 'backward', 'word');
            var b = s.toString();

            s.modify('extend', 'forward', 'word');
            var a = s.toString();
            s.modify('move', 'forward', 'character');
            var i = b + a;
            var c = i.toLowerCase();
			//alert(c);
			//var n = s.indexOf(' ');
			c = c.split(" ")[0];
			c = c.replace(/[\.,-\/#!$%\^&\*;:{}=\-_`~()]/g, "");
			//alert(c);
			//c = c.replace(/\s+/g, '');
			/*var s = window.getSelection();
			
			s = s.substring(0, s.indexOf('&nbsp;');
			
			var c = s.replace(/\s+/g, '');*/
			//alert("yo");
            if (hasSpace(c)) {
                DefineElement(c);
            } else {
                document.getElementById("googleTranslate").innerHTML = "You can not select phrases";
                document.getElementById("wordReferenceTranslate").innerHTML = "You can not select phrases";
                //alert(c);
            }
        });
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
        function hasSpace(s) {
            return s.indexOf(' ') === -1;
        }
        function typeCheck(s){
            if (s.indexOf("v") >= 0){
                return 'Verb';
            }
        }
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