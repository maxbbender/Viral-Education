<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/30/14
 * Time: 4:29 PM
 */
$textArray = array();
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
session_start();
if (isset($_GET['textID'])) {
    if (checkTextOwner($_GET['textID'], $_SESSION['user_id'], $mysqli)) {
        $textArray = getTextInfo($_GET['textID'], $mysqli);
        $textTitle = $textArray[0];
        $textContent = $textArray[1];
    } else {
        //echo 'error';
    }
} else {
    //echo 'error';
}
?>
<html>
<head>
    <title>Edit Text- Viral Education</title>
    <?php include_once 'includes/css_links.php'; ?>
    <script src="ckeditor/ckeditor.js"></script>
</head>
<body>
<?php include_once 'includes/main_nav.php'; ?>
<h1 class="text-center">Edit Text - <?php echo $textTitle; ?></h1>

<div class="row">
    <form action="edit_text.php" method="POST">
        <div class="row">
            <input name="textTitle" type="text" placeholder="Text Title" value="<?php echo $textTitle; ?>">
        </div>
        <div class="row">
            <textarea name="textContent" placeholder="Text Content" value="<?php echo $textContent; ?>"></textarea>
        </div>
        <div class="row">
            <input type="submit" class="button radius">
        </div>
    </form>
</div>
<?php include_once 'includes/javascript_basic.php'; ?>
</body>
</html>