<?php
session_start();
include_once 'includes/db_connect.php';
if ($_SESSION['logged'] == TRUE) {
    $query = "
        SELECT username, email, admin, teacher, fname, lname, phone
        FROM members
        WHERE id = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();

        $stmt->store_result();
        $stmt->bind_result($username, $email, $admin, $teacher, $fname, $lname, $phone);
        $stmt->fetch();
    }
    
    
    $bodyFill = "WAT";
    if ($_GET['edit'] == TRUE) {
    	// Details var
        $details = '
            <div class="row text-center">
                <h1>Edit Account Details</h1><hr>
            </div>
            <form action="update_account.php" method="POST">
                <div class="row">
                    <div class="small-6 columns small-centered">
                        <div class="row">
                            <div class="small-6 columns">
                                <label>First Name
                                    <input type="text" value="' . $fname . '" name="fname">
                                </label>
                            </div>
                            <div class="small-6 columns">
                                <label>Last Name
                                    <input type="text" value="' . $lname . '" name="lname">
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="small-6 columns">
                                <label>Email
                                    <input type="email" value=" ' . $email . '" name="email">
                                </label>
                            </div>
                            <div class="small-6 columns">
                                <label>Phone
                                    <input type="tel" value="' . $phone . '" name="phone">
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <br><div class="small-3 columns small-centered">
                                <input type="submit" class="button radius" value="Edit">
                            </div>
                        </div>
                    </div>
                </div>
            </form>';
    } else {
        $details = '
            <div class="row text-center">
                <h1>My Account</h1>
            </div><hr>
            <div class="row">
                <div class="small-10 columns"><h2>Account Details:</h2></div><div class="small-2 columns"><a href="account.php?edit=1" class="button radius">Edit Account</a></div>
            </div>
            <div class="row">
                <div class="small-12 columns">
                    <h3 class="subheader">Name: ' . $fname . ' ' . $lname . '</h4>
                </div>
            </div>
            <div class="row">
                <div class="small-12 columns">
                    <h3 class="subheader">Email: ' . $email . '<h4>
                </div>
            </div>
            <div class="row">
                <div class="small-12 columns">
                    <h3 class="subheader">Phone Number: ' . $phone . '</h4>
                </div>
            </div>';
    }
    $stmt->free_result();
    //$query2 = ""
    //if($stmt = $mysqli)
}
?>
<html>
<head>
    <title>Viral Education - My Account</title>
    <?php include_once 'includes/css_links.php'; ?>
</head>
<body>
<?php include_once 'includes/main_nav.php';
echo $details;
echo '<br><div class="row"><h2>Change Password</h2></div>';
if (isset($_GET['pwerror'])) {
    if ($_GET['pwerror'] == 0) {
        echo '<div class="row">
                            <div data-alert class="small-8 columns small-centered alert-box success radius">
                                Your password has been successfully changed
                                <a href="#" class="close">&times;</a>
                            </div>
                          </div>';
    } else if ($_GET['pwerror'] == 1) {
        echo '<div class="row">
                            <div data-alert class="small-8 columns small-centered alert-box alert radius">
                                Stmt2 error, please contact an admin. Thanks.
                                <a href="#" class="close">&times;</a>
                            </div>
                          </div>';
    } else if ($_GET['pwerror'] == 2) {
        echo '<div class="row">
                            <div data-alert class="small-8 columns small-centered alert-box alert radius">
                                Your old password did not match
                                <a href="#" class="close">&times;</a>
                            </div>
                          </div>';
    } else if ($_GET['pwerror'] == 3) {
        echo '<div class="row">
                            <div data-alert class="small-8 columns small-centered alert-box alert radius">
                                Stmt error, please contact an admin. Thanks.
                                <a href="#" class="close">&times;</a>
                            </div>
                          </div>';
    } else {
        echo '<div class="row">
                            <div data-alert class="small-8 columns small-centered alert-box alert radius">
                                ' . $_GET['pwerror'] . '
                                <a href="#" class="close">&times;</a>
                            </div>
                          </div>';
    }

}
?>
<div class="row">
    <form data-abide novalidate="novalidate" method="POST" action="change_password.php">
        <div class="row ">
            <div class="large-12 columns">
                <label for="Currentpassword">Current Password
                    <small>required</small>
                    <input type="password" id="Currentpassword" placeholder="Current Password" name="currentPassword"
                           required="" pattern="alpha_numeric">
                </label>
                <small class="error">Passwords must contain only letters and numbers.</small>
            </div>
        </div>
        <div class="row">
            <div class="large-12 columns">
                <label for="Newpassword">New Password
                    <small>required</small>
                    <input type="password" id="NewPassword" placeholder="New Password" name="newPassword" required=""
                           pattern="alpha_numeric">
                </label>
                <small class="error">Passwords must contain only letters and numbers.</small>
            </div>
        </div>
        <div class="row">
            <div class="large-12 columns">
                <label for="NewPassword">Confirm Password
                    <small>required</small>
                    <input type="password" id="confirmPassword" placeholder="New Password" name="newPasswordConfirm"
                           required="" pattern="alpha_numeric" data-equalto="NewPassword">
                </label>
                <small class="error">Passwords must match.</small>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
                <button type="submit" class="medium button green">Change Password</button>
            </div>
        </div>
    </form>
</div>
<?php
include_once 'includes/javascript_basic.php';?>

</body>
</html>