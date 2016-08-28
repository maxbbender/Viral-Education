<?php
/**
 *  Login Page
 *
 * @Author: Max Bender
 */
session_start ();

?>
<html>
<head>
<meta charset="UTF-8">
<title>Viral Education - Login</title>
    <?php
		include_once 'includes/css_links.php';
	?>
</head>
<body>
	<!-- Main Nav -->

<?php include_once 'includes/main_nav.php'; ?>

<!-- Login Form -->
<div class="row">
    <div class="small-6 columns small-centered">
        <form action="includes/process_login.php" id="login_form" method="POST" data-abide>
            <fieldset>
                <legend>Login</legend>
                <div class="row">
                    <div class="small-4 columns">
                        <label for="username" class="right inline">Username: </label>
                    </div>
                    <div class="small-8 columns">
                        <input type="text" id="username" placeholder="Username" name="username" require
                               pattern="alpha_numeric">
                        <small class="error">Username can contain only digits, upper and lowercase letters</small>
                    </div>
                </div>
                <div class="row">
                    <div class="small-4 columns">
                        <label for="password" class="right inline">Password: </label>
                    </div>
                    <div class="small-8 columns">
                        <input type="password" id="password" name="password" required pattern="valid_password">
                        <small class="error">Passwords must contain at least one upper case letter, one lower case
                            letter and one number
                        </small>
                    </div>
                </div>
            </fieldset>
            
            <?php 
    		/*
	    	 * Error List 
	    	 * 0 - Login Successful
	    	 * 1 - Undefined?
	    	 * 2 - Undefined?
	    	 * 3 - No user exists
	    	 * 4 - Account is locked
	    	 * 5 - Password Incorrect
	    	 * 
	    	 */
            if (isset($_GET['error'])) {
            	if ($_GET['error'] != 0){
            		$type = "";
            		$reason = "";
            		$errorID = $_GET['error'];
            		if ($errorID == 5) {
            			$type = "alert";
            			$reason = "Password is incorrect, please try again";
            		} else if ($errorID == 4) {
            			$type = "alert";
            			$reason = "Your account is locked due to too many failed login attempts, please try later";
            		} else if ($errorID == 3) {
            			$type = "warning";
            			$reason = "User account does not exists, register <a href='register.php'>here</a>";
            		}
            		
            		echo '
    			<div data-alert class="alert-box ' . $type . ' radius">
					' . $reason . '
				  <a href="#" class="close">&times;</a>
				</div>';
            	}
            }
            ?>
            
            <div class="row">
                <div class="small-12 columns text-center">
                    <input type="submit" class="button right radius small" value="Log In">
                </div>
            </div>
        </form>
    </div>
</div>

	<!-- Register Link -->
	<div class="row">
		<div class="small-4 columns small-centered">
			<a href="register.php" class="text-center">Don't have an account?
				Register Here!</a>
		</div>
	</div>
	<?php require 'includes/javascript_basic.php'?>
	<script type="text/javascript" src="js/sha512.js"></script>
	<script>
    $(document).foundation({
        abide: {
            patterns: {
                valid_password: /(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/
            }
        }
    });
</script>
</body>
</html>
