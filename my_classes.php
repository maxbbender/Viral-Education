<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/8/14
 * Time: 1:57 AM
 */
session_start();
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
$content = "";
$query = "
    SELECT classes.class_name, class_members.class_id
    FROM class_members
    LEFT JOIN classes
      ON class_members.class_id = classes.id
    WHERE class_members.user_id = ?
";
if ($stmt = $mysqli->prepare($query)) {
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($className, $classID);
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        while ($stmt->fetch()) {
            $content .= '
                <li><a href="view_class.php?class_id=' . $classID . '">' . $className . '</a></li>
            ';
        }
    } else {
        $content .= '
            <li style="color:red">You are not a part of any classes </li>
        ';
    }
}
?>
<html>
<head>
    <title>Viral Education - My Classes</title>
    <?php include_once 'includes/css_links.php'; ?>
</head>
<body>
<?php include_once 'includes/main_nav.php'; ?>
<div class="row text-center">
    <h1>My Classes</h1>
    <hr>
</div>
<div class="row">
    <ul>
        <?php echo $content; ?>
    </ul>
</div>
<?php include_once 'includes/javascript_basic.php'; ?>
</body>
</html>
