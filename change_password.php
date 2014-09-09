<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 7/30/14
 * Time: 3:07 PM
 */
session_start();
function change_password($password, $newPassword, $mysqli)
{
    $user_id = $_SESSION['user_id'];
    if ($stmt = $mysqli->prepare('SELECT password, salt
									  FROM members
									  WHERE id = ?')
    ) {
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $stmt->store_result();

        $stmt->bind_result($db_password, $db_salt);
        $stmt->fetch();

        //Hash password
        $current_hash = hash('sha512', $password . $db_salt);
        //Compare to $db_password
        if ($current_hash == $db_password) {
            $new_hash = hash('sha512', $newPassword . $db_salt);
            $stmt->close();
            if ($stmt = $mysqli->prepare('UPDATE members
											  SET password = ?
											  WHERE id = ?')
            ) {
                $stmt->bind_param('si', $new_hash, $user_id);
                $stmt->execute();

                //Password updated
                return 0;
            } else {
                //stmt2 error, PW failed to update
                return 1;
            }
        } else {
            //Old password do not match, PW failed to update
            return 2;
        }
    } else {
        //stmt error, PW failed to update
        return 3;
    }
}

include_once 'includes/db_connect.php';
$pwChange = change_password($_POST['currentPassword'], $_POST['newPassword'], $mysqli);
header("Location: account.php?pwerror=" . $pwChange);