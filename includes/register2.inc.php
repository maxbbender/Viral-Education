<?php
include_once 'db_connect.php';
include_once 'psl-config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/securimage/securimage.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/recaptchalib.php';

// $securimage = new Securimage();
$error_msg = 0;



if (isset($_POST['username'], 
          $_POST['email'], 
          $_POST['password'], 
          $_POST['fname'], 
          $_POST['lname'])) {

       
        $privatekey = "6LdbCgoUAAAAABcYymj6TZa8NCZNums1MIW2B3da";
        $resp = recaptcha_check_answer ($privatekey,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);

        if (!$resp->is_valid) {
        // What happens when the CAPTCHA was entered incorrectly
            $error_msg = 5;
        } else {
        // Your code here to handle a successful verification
        }

    // Let's check if Captcha is correct

    // Sanitize and validate the data passed in
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
    $lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);

    // Username validity and password validity have been checked client side.
    // This should should be adequate as nobody gains any advantage from
    // breaking these rules.
    //

    $prep_stmt = "SELECT id FROM members WHERE email = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);

    // check existing email
    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            // A user with this email address already exists
            $error_msg = 1;
            $stmt->close();
        }
        $stmt->close();
    } else {
        $error_msg = 2;
        $stmt->close();
    }

    // check existing username
    $prep_stmt = "SELECT id FROM members WHERE username = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);

    if ($stmt) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            // A user with this username already exists
            $error_msg = 3;
            $stmt->close();
        }
        $stmt->close();
    } else {
        $error_msg = 4;
        $stmt->close();
    }

    // //Check captcha code and make sure it was correct
    // if ($securimage->check($_POST['captcha_code']) == false){
    //     //code is incorrect
    //     $error_msg = 5;
    // }
    // TODO: 
    // We'll also have to account for the situation where the user doesn't have
    // rights to do registration, by checking what type of user is attempting to
    // perform the operation.

    if ($error_msg == 0) {
        // Create a random salt
        //$random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE)); // Did not work
        $random_salt = hash('sha512', uniqid(10, true));

        // Create salted password 
        $password = hash('sha512', $_POST['password'] . $random_salt);

        // Insert the new user into the database 
        if ($insert_stmt = $mysqli->prepare("INSERT INTO members (username, email, password, salt, fname, lname) VALUES (?, ?, ?, ?,?,?)")) {
            $insert_stmt->bind_param('ssssss', $username, $email, $password, $random_salt, $fname, $lname);
            // Execute the prepared query.
            if (!$insert_stmt->execute()) {
                header('Location: register.php');
            }
            header('Location: ../index.php');
        } else {
            echo 'Query Error';
        }

        echo 'yay:)';
    } else {
        if($error_msg == 5){
            $addon = "&username=" . $username ."&email=" . $email . "&fname=" . $fname . "&lname=" . $lname;
        }
        header('Location: ../register.php?error=' . $error_msg . $addon);
        echo 'aww:(';
    }
} else {
    echo 'params not set';
}