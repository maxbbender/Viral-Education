<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/4/14
 * Time: 7:37 PM
 */
$error = $inviteID = "";
session_start();
include_once 'includes/db_connect.php';
require 'includes/functions.php';
$classExists = FALSE;
if (isset($_POST['inviteID'])) {
    $inviteID = $_POST['inviteID'];
    if ($stmt = $mysqli->prepare("SELECT id FROM classes WHERE invite_id = ?")) {
        $stmt->bind_param("s", $_POST['inviteID']);
        $stmt->execute();
        $stmt->bind_result($classID);
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->fetch();
            $classExists = TRUE;
        } else {
            $classExists = FALSE;
        }
        $stmt->free_result();
    }
    $query = "
        INSERT INTO class_members
        (class_id,user_id)
        VALUES (?,?)
    ";
    if ($classExists) {
    	if (!isAMemberofClass($_SESSION['user_id'], $classID, $mysqli)) {
    		if ($stmt = $mysqli->prepare($query)) {
    			$stmt->bind_param("ii", $classID, $_SESSION['user_id']);
    			$stmt->execute();
    			header("Location: my_classes.php");
    		}
    	} else {
    		$error = '<div data-alert class="alert-box alert">You are already a member of this class!<a href="#" class="close">&times;</a></div>';
    	}
       
    } else {
        $error = '<div data-alert class="alert-box alert">The class you have tried to join does not exist. Please check invite code.<a href="#" class="close">&times;</a></div>';
    }

}
?>
<html>
<head>
    <title>Viral Education - Join Class</title>
    <?php include_once 'includes/css_links.php'; ?>
</head>
<body>
<?php include_once 'includes/main_nav.php'; ?>
<div class="row text-center">
    <h1>Join Class</h1>
</div>
<div class="row">
    <div class="small-6 columns small-centered">
        <?php echo $error; ?>
        <div class="row panel">
            <h3 class="subheader">Info</h3>
            You can get the Invite ID for the class from the teacher of the class if you don't have it. If you do just
            put it in the box and click "Join Class" and it will add you to the class.
        </div>
        <br>

        <form action="join_class.php" method="POST">
            <div class="row">
                <div class="small-8 columns">
                    <label>Invite ID
                        <input type="text" name="inviteID" value="<?php echo $inviteID; ?>" placeholder="Invite ID">
                    </label>
                </div>
                <div class="small-4 columns">
                    <input type="submit" class="button radius" value="Join Class">
                </div>
            </div>
        </form>
    </div>
</div>
<?php include_once 'includes/javascript_basic.php'; ?>
</body>
</html>
