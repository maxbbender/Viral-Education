<?php
/**
 * Created by PhpStorm.
 * User: TH3M45T3R
 * Date: 2/9/2015
 * Time: 3:46 PM
 */
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
session_start();
if(isset ($_GET['class_id'])){
    $classID = $_GET['class_id'];
} else if (isset ($_POST['class_id'])){
    $classID = $_POST['class_id'];
}

if (isset($_POST['oaTitle'], $_POST['oaDescription'], $_POST['class_id'])){
    if(checkTeacher($classID, $mysqli)) {
        if (createOA($_POST['oaTitle'], $_POST['oaDescription'], $_POST['class_id'], $mysqli)){
            header("Location: http://viraleducation.com/view_class.php?class_id=" . $_POST['class_id']);
        }

    }
}
?>
<html>
<head>
    <title>TALOS - Open Assignment</title>
    <?php include_once 'includes/css_links.php'; ?>
</head>
<body>
    <?php include_once 'includes/main_nav.php'; ?>
    <?php if (checkTeacher($classID, $mysqli)){ ?>
        <div class="row">
            <h1 class="text-center">
                Create a New Assignment for <?php echo(getClassName($classID, $mysqli)); ?>
            </h1>
            <form id="openAssignment" action="create_open_assignment.php" method="POST">
                <div class="row">
                    <div class="small-4 columns">
                        <label>Assignment Title
                            <input type="text" name="oaTitle" placeholder="Assignment Title">
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="small-6 columns">
                        <label>Assignment Description/Instructions
                            <textarea name="oaDescription"></textarea>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <input type="submit" class="button small radius" value="Create Assignment">
                </div>
                <input type="hidden" name="class_id" value="<?php echo $_GET['class_id']; ?>">
            </form>
        </div>
    <?php } else { ?>
        <div class="row alert error">You are not the leader for this class so you can not assign an open assignment. If you believe that this is an error please Report it to the system administrators <a href="report.php">here</a></div>
    <?php } ?>
</body>
</html>