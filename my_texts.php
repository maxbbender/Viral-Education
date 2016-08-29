<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/6/14
 * Time: 7:36 PM
 */
$content = "";
session_start();
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
$texts = getCollection($_SESSION['user_id'], $mysqli);

foreach ($texts as $text) {
    //$replacedText = str_replace("#", " ", $text);
    $explodedText = explode("#", $text);
//     $content .= '
//                 <li>' . $explodedText[0] . ' - <a href="view_text.php?textID=' . $explodedText[1] . '">View Text</a></li>
//             ';
		$content .= '
				<div id="textDivContainer" class="row">
					<div id="textDiv' . $explodedText[1] . '" class="row">
						<h3>Title : <span id="textDiv' . $explodedText[1] . 'title"></span></h3>
					</div>
				</div>
				
				'
}

?>
<html>
<head>
    <title>Viral Education - My Texts</title>
    <?php include_once 'includes/css_links.php'; ?>
</head>
<body>
<?php include_once 'includes/main_nav.php'; ?>
<div class="row text-center">
    <h1>Your Collection</h1>
</div>
<div class="row">
    <div class="small-6 columns panel">
        <ul>
            <?php echo $content; ?>
        </ul>
    </div>
</div>
<?php include_once 'includes/javascript_basic.php'; ?>
</body>
</html>