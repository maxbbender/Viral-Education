<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/19/14
 * Time: 2:34 PM
 */
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
session_start();
$newsTitle = $newsContent = "";
$creatorID = $newsID = $classID = 0;
if (isset($_GET['newsID'], $_GET['classID'])) {
    $newsID = $_GET['newsID'];
    $classID = $_GET['classID'];
    $query = "
        SELECT creator_id, news_title, news_content
        FROM class_news
        WHERE id = ?
    ";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $_GET['newsID']);
        $stmt->execute();
        $stmt->bind_result($creatorID, $newsTitle, $newsContent);
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->fetch();
        }
        if ($creatorID != $_SESSION['user_id']) {
            $newsTitle = $newsContent = "";
        }
    }
}
if (isset($_POST['title'], $_POST['content'], $_POST['newsID'], $_POST['classID'])) {
    $returnUpdate = FALSE;
    $returnUpdate = updateNews($_POST['newsID'], $_POST['title'], $_POST['content'], $mysqli);
    if($returnUpdate){
        header("Location: view_class.php?class_id=" . $_POST['classID']);
        //header("Location: edit_news.php?newsID=" . $_POST['newsID'] . "&classID=". $_POST['classID']. "&error=". $returnUpdate);
    } else {
        header("Location: edit_news.php?newsID=" . $_POST['newsID'] . "&classID=". $_POST['classID']. "&error=". $returnUpdate);
    }
}
?>
<html>
<head>
    <title>Viral Education - Edit News</title>
    <?php include_once 'includes/css_links.php'; ?>
    <script src="ckeditor/ckeditor.js"></script>
</head>
<body>
    <?php include_once 'includes/main_nav.php'; ?>
    <div class="row">
        <h1 class="text-center">Edit News</h1><hr>
        <form action="edit_news.php" method="POST">
            <div class="row">
                <div class="small-6 columns">
                    <label>News Title
                        <input type="text" name="title" placeholder="News Title" value="<?php echo $newsTitle; ?>">
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="small-8 columns">
                    <label>News Content
                        <textarea id="textContent" name="content" placeholder="News Content"><?php echo $newsContent; ?></textarea>
                    </label>
                </div>
            </div>
            <input type="hidden" value="<?php echo $newsID; ?>" name="newsID">
            <input type="hidden" value="<?php echo $classID; ?>" name="classID">
            <div class="row">
                <div class="small-4 columns">
                    <input type="submit" value="Edit News" class="small button radius">
                </div>
            </div>
        </form>
    </div>
    <?php include_once 'includes/javascript_basic.php'; ?>
    <script>
        CKEDITOR.replace('textContent');
    </script>
</body>
</html>