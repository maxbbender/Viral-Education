<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/23/14
 * Time: 1:59 PM
 */
/*function grab_xml_definition ($word, $ref, $key)
{	$uri = "http://www.dictionaryapi.com/api/v1/references/" . urlencode($ref) . "/xml/" .
    urlencode($word) . "?key=" . urlencode($key);
    return file_get_contents($uri);
}
$xdef = grab_xml_definition("voy", "spanish", "42b58b48-c3e2-4e8e-9ebe-88b9b564dca4");
//echo $xdef;
//echo '<hr>';
//$xml = simplexml_load_file("testxml.xml");

//$xml2 = simplexml_load_string($xdef);
//print_r($xml);
//echo $xml2;
//print_r($xml2);
//$xml2->formatOutput = true;
//echo $xml2->saveXML();
//echo '<br><br>' . $xml2->attributes.version;
//echo $xml2->entry[0].

/*$parser = xml_parser_create();
xml_parse_into_struct($parser, $xdef, $values);
xml_parser_free($parser);

//echo 'index array';
///print_r($index);

//echo "<br>Vals aray<br>";
print_r($values);

foreach($values as $value){
    //echo $value['tag'] . '<br>';
    if($value['tag'] == 'FL'){
        echo $value['value'] . '<br>';
    } else if ($value['tag'] == 'SN'){
        echo $value['value'] . ': ';
    } else if ($value['tag'] == 'REF-LINK'){
        echo  $value['value'] . '<br>';
    }

}
$inc = 0;
//while($)*/


?>
<html>
<head>

</head>
<body>
    <!--<a href="#" onclick="getJSON2('http://api.wordreference.com/0.8/36c73/json/esen/libro')">Click Me</a>-->
    <a href="#" onclick="getJSON()">Click Me</a>
    <div id="container"></div>
    <?php include_once 'includes/javascript_basic.php'; ?>
    <script>
        function DefineElement(element) {
            var cleanedElement = element.replace(/[\.,-\/#!$%\^&\*;:{}=\-_`~()]/g, "");
            $.ajax({
                type: "GET",
                url: "http://api.wordreference.com/0.8/36c73/json/esen/libro",
                //data: {key: "AIzaSyAJgQycIa3vSLEuC48VND-ZhkOHfxBRcSM", source: "es", target: "en", q: cleanedElement},
                success: function (results) {
                    // alert(results.data.translations[0].translatedText);
                    //document.getElementById("googleDefine").innerHTML = cleanedElement + ": " + results.data.translations[0].translatedText;
                    //var definedWord = results.data.translations[0].translatedText;
                    alert(results);

                    /*$.ajax({
                        type: "POST",
                        url: "read_text.php",
                        data: {textID: <?php echo $_GET['textID'] ?>, readerID: <?php echo $_SESSION['user_id'] ?>, defined: definedWord, word: cleanedElement, classID: <?php if(isset($_GET['class'])){ echo $_GET['class']; } else {echo 0;} ?>},
                        dataType: "html",
                        success: function (result) {
                            //alert(result);
                        }
                    });*/
                }
            });
        }
        //DefineElement('libro');
        function load_home(){
            document.getElementById("container").innerHTML='<object type="type/html" data="http://api.wordreference.com/0.8/36c73/json/esen/libro"></object>';
            var myInnerHTML = document.getElementById("container")
        }
        function getJSON(){
            $.getJSON('http://api.wordreference.com/0.8/36c73/json/esen/libro?callback=?',
                function alertcall(data){
                    alert(data.term0.PrincipalTranslations[0].FirstTranslation['term']);
                });
        }
        function getJSON2(href){
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.open("get", href, false);
            xmlhttp.send();
            alert (xmlhttp.responseText);
        }


    </script>
</body>
</html>
