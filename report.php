<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 9/17/14
 * Time: 3:24 PM
 */
    session_start();
    include_once 'includes/db_connect.php';
    $alert = "";
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if (isset($_POST['content'], $_POST['url'], $_POST['page_title'])) {
            $query = "
                INSERT INTO reports
                (report_content, report_url, report_page, member_id)
                VALUES (?,?,?,?)
            ";
            if ($stmt = $mysqli->prepare($query)){
                $stmt->bind_param("sssi", $_POST['content'], $_POST['url'], $_POST['page_title'], $_SESSION['user_id']);
                $stmt->execute();
                if($stmt->error == ""){
                    $check = TRUE;
                }
            }
            if($check){
                $alert = '
                    <div class="row">
                        <div data-alert class="small-6 rows alert-box success">
                            Your report has been successfully submitted
                            <a href="#" class="close">&times;</a>
                        </div>
                    </div>
                ';
            } else {
                $alert = '
                    <div class="row">
                        <div data-alert class="small-6 rows alert-box alert">
                            Your report was unable to be submitted, please try again.<br>
                            ' . $stmt->error . '
                            <a href="#" class="close">&times;</a>
                        </div>
                    </div>
                ';
            }
        }
    }

?>
<html>
<head>
    <title>Viral Education - Report</title>
    <?php include_once 'includes/css_links.php'; ?>
</head>
<body>
    <?php include_once 'includes/main_nav.php'; ?>
    <div class="row text-center">
        <h1>Submit a Report</h1><hr>
    </div>
    <?php echo $alert; ?>
    <div class="row">
        <form action="report.php" method="POST">
            <div class="row">
                <div class="small-6 columns">
                    <label>Description of the Issue <small>required</small>
                        <textarea name="content"></textarea>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="small-6 columns">
                    <label>URL of Page
                        <input type="text" name="url" placeholder="What is the URL of the page you are report (if there is one)">
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="small-5 columns">
                    <lable>Page Title
                        <input type="text" name="page_title" placeholder="Title of the page in quesion (if there is one)">
                    </lable>
                </div>
            </div>
            <div class="row">
                <input type="submit" class="button radius" value="Submit Report">
            </div>
        </form>
    </div>
    <?php include_once 'includes/javascript_basic.php'; ?>
</body>
</html>