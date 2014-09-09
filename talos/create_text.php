<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/5/14
 * Time: 4:23 PM
 */
session_start();
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

if (isset($_POST['textTitle'])) {
    $check = insert_text($_POST['textTitle'], $_POST['textContent'], $mysqli);
    if ($check == TRUE) {
        header("Location: my_texts.php");
    } else {
        header("Location: create_text.php");
    }

}
?>
<html>
<head>
    <title>Viral Education - Create Text</title>
    <?php include_once 'includes/css_links.php'; ?>
    <script src="ckeditor/ckeditor.js"></script>
</head>
<body>
<?php include_once 'includes/main_nav.php'; ?>
<div class="row text-center">
    <h1>Add a Text to Your Collection</h1>
</div>
<div class="row">
    <div class="small-8 columns small-centered">
        <form action="create_text.php" method="POST">
            <div class="row">
                <div class="small-6 columns">
                    <label>Text Title
                        <input type="text" name="textTitle">
                    </label>
                </div>
            </div>
            <div class="row">
                <label>Text Content
                    <textarea rows="20" cols="40" id="textContent" name="textContent"
                              placeholder="Copy and Paste Text Here"></textarea>
                </label>
            </div>
            <div class="row">
                <input type="submit" class="button" value="Add Text">
            </div>
        </form>
    </div>
</div>
<?php include_once 'includes/javascript_basic.php'; ?>
<script>
    CKEDITOR.replace('textContent');
</script>
</body>
</html>