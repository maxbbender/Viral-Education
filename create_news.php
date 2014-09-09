<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/4/14
 * Time: 5:34 PM
 */
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
$teacher = FALSE;

$newsTitle = $newsForm = "";


if (isset($_GET['type'])) {

    /* -- Title -- */
    if ($_GET['type'] == 'class') {
        if (isset($_GET['class_id'])) {
            $newsType = $_GET['class_id'];
            /* Check to see if user is teacher of class */
            if (checkTeacher($_GET['class_id'], $mysqli)) {
                $teacher = TRUE;
            }
            if ($teacher) {
                $query = "
                    SELECT class_name
                    FROM classes
                    WHERE id = ?
                ";
                if ($stmt = $mysqli->prepare($query)) {
                    $stmt->bind_param("i", $_GET['class_id']);
                    $stmt->execute();
                    $stmt->bind_result($className);
                    $stmt->store_result();
                    $stmt->fetch();
                    $stmt->free_result();
                    $newsTitle = "<h1>Create News - " . $className . "</h1>";
                }
            }
        }
    }

    /* -- News Form -- */
    $newsForm = '
            <form action="create_news.php" method="POST">
                <div class="row">
                    <label>Title
                        <input name="news_title" type="text" placeholder="Title">
                    </label>
                </div>
                <div class="row">
                    <label>Content
                        <textarea id="add_news" rows="6" name="news_content" placeholder="Content"></textarea>
                    </label>
                </div>
                <div class="row">
                    <input type="hidden" name="type" value="' . $newsType . '">
                </div>
                <div class="row"><br>
                    <input type="submit" class="button small radius" value="Submit News">
                </div>
            </form>
        ';

    /* -- Parse Content -- */
    if ($_GET['type'] == 'class') {
        if ($teacher) {
            $fullContent = '
                <div class="row text-center">
                    ' . $newsTitle . '
                </div>
                <div class="row">
                    <div class="small-6 columns small-centered">
                        ' . $newsForm . '
                    </div>
                </div>
            ';
        }
    }
}

if (isset($_POST['news_title'])) {
    if ($_POST['type'] > 0) {
        if (submitClassNews($_POST['news_title'], $_POST['news_content'], $mysqli, $_POST['type'])) {
            header("Location: view_class.php?class_id=" . $_POST['type']);
        }
    }

}

?>
<html>
<head>
    <title>Viral Education - Create News</title>
    <?php include_once 'includes/css_links.php' ?>
    <script src="ckeditor/ckeditor.js"></script>
</head>
<body>
<?php
include_once 'includes/main_nav.php';
echo $fullContent;
include_once 'includes/javascript_basic.php';
?>
<script>
    CKEDITOR.replace('add_news');
</script>
</body>
</html>




