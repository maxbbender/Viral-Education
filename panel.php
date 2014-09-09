<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 7/30/14
 * Time: 3:51 PM
 */
include_once 'includes/db_connect.php';
session_start();
$createClass = "";
/* -- CREATE CLASS -- */
if ($_SESSION['teacher'] == TRUE) {
    $createClass = '
        <div class="row text-center">
            <h1>Teacher Panel</h1><hr>
        </div>
        <div class="row">
            <h3>Create a New Class</h3>
        </div>
        <div class="row panel">
            <form method="POST" action="create_class.php">
                <div class="row">
                    <div class="small-4 columns">
                        <label>Class Name <small>required</small>
                            <input id="class_name" type="text" name="class_name" placeholder="Class Name" required>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="small-6 columns">
                        <label>Class Description
                            <textarea cols="5" id="description" name="description"></textarea>
                        </label>
                    </div>
                </div>
                <input type="submit" value="Create Class" class="button small radius">
            </form>
        </div>
    ';
    /* -- CURRENT CLASSES -- */
    $query = "
        SELECT id, class_name
        FROM classes
        WHERE class_teacher = ?
    ";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $stmt->store_result();

        //Bind results
        $stmt->bind_result($id, $className);
        $currentClasses .= '<div class="row"><h3>Classes You Teach</h3></div>';
        if ($stmt->num_rows > 0) {
            //Teacher currently has classes

            //Begin List
            $currentClasses .= '<div class="row panel"><ul>';
            while ($stmt->fetch()) {
                $currentClasses .= '
                    <li><a href="view_class.php?class_id=' . $id . '">' . $className . '</a></li>';
            }

            //End List
            $currentClasses .= '</ul></div>';
        } else {
            $currentClasses .= '<div class="row"><span style="color:red">You have no current classes. Try creating one above</span></div>';
        }
    }
}
?>
<html>
<head>
    <title>Viral Education - Teacher Panel</title>
    <?php include_once 'includes/css_links.php'; ?>
</head>
<body>
<?php
include_once 'includes/main_nav.php';
//Create A Class
echo $createClass;
//See Current Classes
echo $currentClasses;
include_once 'includes/javascript_basic.php';?>
</body>
</html>