<?php
/**
 *  Registration Page
 *
 * @Author: Max Bender
 */
//include_once 'includes/register.inc.php';
include_once 'includes/functions.php';
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Viral Education - Registration</title>
    <?php include_once 'includes/css_links.php'; ?>
    <script src='https://www.google.com/recaptcha/api.js'></script>

</head>
<body>
<!-- Main Nav -->
<?php include_once 'includes/main_nav.php'; ?>
<div class="row">
    <div class="small-8 columns small-centered">
        <h2 class="text-center">Register for Viral Education</h2><hr>

        <!-- Displays error messages -->
        <?php
        if (!empty($error_msg)) {
            echo $error_msg;
        }
        ?>
    </div>
</div>
<div class="row">

</div>
<div class="row">
    <div class="small-6 columns">
        <?php
            if (isset($_GET['error'])) {
                if($_GET['error'] == 1){

                } else if($_GET['error'] == 1){

                } else if($_GET['error'] == 1){

                } else if($_GET['error'] == 1){

                } else if($_GET['error'] == 5){
                    $error_msg = "The Captcha Code you have entered is incorrect, please try again";
                }
                echo '
                    <div data-alert class="row alert-box alert radius">
                        ' . $error_msg . '
                        <a href="#" class="close">&times;</a>
                    </div><br>
                ';
            }
        ?>
        <form action="includes/register2.inc.php" method="post" id="registration_form" data-abide>
            <div class="row">
                <div class="small-6 columns">
                    <div class="row">
                        <div class="small-6 columns">
                            <label for="fname" class="right inline">First Name:
                                <small>required</small>
                            </label>
                        </div>
                        <div class="small-6 columns">
                            <input type="text" placeholder="First Name" id="fname" name="fname" value = "<?php echo $_GET['fname'] ?>" required>
                        </div>
                    </div>
                </div>
                <div class="small-6 columns">
                    <div class="row">
                        <div class="small-6 columns">
                            <label for="fname" class="right inline">Last Name:
                                <small>required</small>
                            </label>
                        </div>
                        <div class="small-6 columns">
                            <input value = "<?php echo $_GET['lname'] ?>" type="text" placeholder="Last Name" id="lname" name="lname" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="small-3 columns">
                    <label for="username" class="right inline">Username:
                        <small>required</small>
                    </label>
                </div>
                <div class="small-9 columns">
                    <input type="text" id="username" name="username" placeholder="Username" value = "<?php echo $_GET['username'] ?>" pattern="alpha_numeric" required>
                    <small class="error">Username can contain only digits, upper and lowercase letters</small>
                </div>
            </div>
            <div class="row">
                <div class="small-3 columns">
                    <label for="email" class="right inline">Email:
                        <small>required</small>
                    </label>
                </div>
                <div class="small-9 columns">
                    <input type="email" value = "<?php echo $_GET['email'] ?>" id="email" name="email" placeholder="Email" pattern="email" required>
                    <small class="error">Please put in a valid email</small>
                </div>
            </div>
            <div class="row">
                <div class="small-3 columns">
                    <label for="password" class="right inline">Password:
                        <small>required</small>
                    </label>
                </div>
                <div class="small-9 columns">
                    <input type="password" id="password" name="password" pattern="valid_password" required>
                    <small class="error">Passwords must contain at least one upper case letter, one lower case letter
                        and one number
                    </small>
                </div>
            </div>
            <div class="row">
                <div class="small-3 columns">
                    <label for="confirmpwd" class="right inline">Confirm Password:</label>
                </div>
                <div class="small-9 columns">
                    <input type="password" id="confirmpwd" name="confirmpwd" data-equalto="password">
                    <small class="error">The password did not match</small>
                </div>
            </div>
            <div class="row">
                <?php
                  require_once('includes/recaptchalib.php');
                  $publickey = "6LdbCgoUAAAAAKYt0kYWEGkaHYrQQqZML1f787d5"; // you got this from the signup page
                  echo recaptcha_get_html($publickey);
                ?>
                <!-- <div class="g-recaptcha" data-sitekey="6LdbCgoUAAAAAKYt0kYWEGkaHYrQQqZML1f787d5">
                    
                </div> -->
                <!-- <div class="small-6 columns">
                    <label>Captcha confirm (Anti Spam Bots)
                    <img id="captcha" src="/securimage/securimage_show.php" alt="CAPTCHA Image" /><br>
                        <input type="text" name="captcha_code" size="10" maxlength="6" />
                    </label>
                    <a href="#" onclick="document.getElementById('captcha').src = '/securimage/securimage_show.php?' + Math.random(); return false">[ Different Image ]</a>
                </div> -->
            </div>
            <div class="row">
                <div class="small-12 columns small-centered">
                    <input type="submit" class="button right small radius" value="Register">
                </div>
            </div>
        </form>
    </div>
    <div class="small-6 columns">
        <div class="panel callout">
            <ul>
                <li>Usernames may contain only digits, upper and lower case letters</li>
                <li>Emails must have a valid email format</li>
                <li>Passwords must be at least 6 characters long</li>
                <li>Passwords must contain
                    <ul>
                        <li>At least one upper case letter (A..Z)</li>
                        <li>At least one lower case letter (a..z)</li>
                        <li>At least one number (0..9)</li>
                    </ul>
                </li>
                <li>Your password confirmation must match exactly</li>
            </ul>
        </div>
    </div>
</div>
<?php include_once 'includes/javascript_basic.php'; ?>
</body>
</html>
			
			