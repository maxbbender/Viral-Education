<?php
/**
 *  Helper Functions
 *
 * @Author: Max
 */
include_once 'psl-config.php';
session_start();
//Starts a secure session
function sec_session_start()
{
    $session_name = 'sec_session_id';
    $secure = SECURE;
    $httponly = true;
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: error.php?err=Could not initate a safe session");
        exit();
    }
    // Gets current cookies params
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"],
        $cookieParams["domain"],
        $secure,
        $httponly
    );
    session_name($session_name);
    session_start();
    session_regenerate_id();
}

function login($username, $password, $mysqli)
{
    //Prepared Statement to prevent SQL injection
    if ($stmt = $mysqli->prepare("SELECT id, username, password, salt, admin, teacher, fname, lname
									FROM members
									WHERE username = ?
									LIMIT 1")
    ) {
        $stmt->bind_param('s', $username); //Binds $username to prepared statement
        $stmt->execute();
        $stmt->store_result();

        //Get variables from result
        $stmt->bind_result($user_id, $user, $db_password, $salt, $admin, $teacher, $fname, $lname);
        $stmt->fetch();

        //Has the password with unique salt
        $password = hash('sha512', $password . $salt);

        if ($stmt->num_rows == 1) {
            //If user exists we check if the account is locked
            //from to many login attempts

            if (checkbrute($user_id, $mysqli) == true) {
                echo 'account locked';
                //Account is locked
                /**
                 *  TO DO. SEND EMAIL TO USER SAYING THEIR ACCOUNT IS LOCKED
                 */
                return 4;
            } else {
                //Check if the password matches the one in the databases
                if ($db_password == $password) {
                    //Password is correct
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];
                    // XSS protection
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                    $_SESSION['user_id'] = $user_id;
                    // XSS protection
                    $username = preg_replace("/[^a-zA-Z0-9_\-]", "", $username);
                    $_SESSION['username'] = $user;
                    $_SESSION['login_string'] = hash('sha512', $password . $user_browser);
                    $_SESSION['logged'] = TRUE;
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['name'] = $fname . ' ' . $lname;
                    //Check Admin Status
                    if ($admin == TRUE) {
                        $_SESSION ['admin'] = TRUE;
                    }
                    if ($teacher == TRUE) {
                        $_SESSION ['teacher'] = TRUE;
                    }
                    //Login successful
                    return 0;
                } else {
                    echo 'password incorrect';
                    //Password is incorrect
                    //We record attempt in login_attempts database.
                    $now = time();
                    $mysqli->query("INSERT INTO login_attempts(user_id, time) VALUES ('$user_id', '$now')");
                    return 5;
                }
            }
        } else {
            //No user exists.
            echo 'No user exists';
            return 3;
        }
    }
}

function checkbrute($user_id, $mysqli)
{
    //Timestamp
    $now = time();

    //All login attempts are counted from the past 2 hours
    $valid_attempts = $now - (2 * 60 * 60);

    if ($stmt = $mysqli->prepare("SELECT time
										FROM login_attempts
										WHERE user_id = ?
										AND time > '$valid_attempts'")
    ) {
        $stmt->bind_param('i', $user_id);

        //Execute query
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 5) {
            return true;
        } else {
            return false;
        }
    }
}

function login_check($mysqli)
{
    //Check if all session variables are set
    if (isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];

        //Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];

        if ($stmt = $mysqli->prepare("SELECT password
										 FROM members
										 WHERE id = ? LIMIT 1")
        ) {
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                //If the user exists get variables from result
                $stmt->bind_result($password);
                $stmt->fetch();
                $login_check = hash('sha512', $password . $user_browser);

                if ($login_check == $login_string) {
                    //Logged In
                    return true;
                } else {
                    //Not logged in
                    return false;
                }
            } else {
                //Not logged in
                return false;
            }
        } else {
            //Not logged in
            return false;
        }
    } else {
        //Not logged in
        return false;
    }
}

function esc_url($url)
{
    if ('' == $url) {
        return $url;
    }

    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
    +

    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string)$url;

    $count = 1;
    while ($count) {
        $url = str_replace($stript, '', $url, $count);
    }

    $url = str_replace(';//', '://', $url);

    $url = htmlentities($url);

    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);

    if ($url[0] !== '/') {
        // We're only interested in relative links from $_SERVER['PHP_SELF']
        return '';
    } else {
        return $url;
    }
}

function generateRandomString($length = 10)
{
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}


function checkClassExists($class_id, $mysqli)
{
    $query = "
        SELECT id
        FROM classes
        WHERE id = ?
    ";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $class_id);
        $stmt->execute();
        $stmt->store_result();

        $rows = $stmt->num_rows;
        return $rows;
    }
}

function submitClassNews($newsTitle, $newsContent, $mysqli, $classID)
{
    $query = "
        INSERT INTO class_news
        (class_id, creator_id, date_created, news_title, news_content)
        VALUES (?,?,?,?,?)
    ";
    if ($stmt = $mysqli->prepare($query)) {
        $now = time();
        $stmt->bind_param("iiiss", $classID, $_SESSION['user_id'], $now, $newsTitle, $newsContent);
        $stmt->execute();
        return TRUE;
    } else {
        return FALSE;
    }
}

function insert_text($textTitle, $textContent, $mysqli)
{
    $query = "
        INSERT INTO texts
        (title, content, creator_id)
        VALUES (?,?,?)
    ";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("ssi", $textTitle, $textContent, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->store_result();
        if (addCollection($stmt->insert_id, $mysqli, $_SESSION['user_id'])) {
            return TRUE;
        }
    } else {
        return FALSE;
    }
}

function getCollection($user_id, $mysqli)
{
    $query = "
    SELECT texts_collection.text_id, texts.title
    FROM texts_collection
    LEFT JOIN texts
      ON texts_collection.text_id = texts.id
    WHERE texts_collection.user_id = ?
";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($id, $title);
        $stmt->store_result();
        $texts = array();
        $inc = 0;
        if ($stmt->num_rows > 0) {
            while ($stmt->fetch()) {
                $texts[$inc] = $title . '#' . $id;
                $inc++;


            }
        }
    }
    return $texts;
}

function getAssigned($class_id, $mysqli)
{
    $query = "
    SELECT assigned_texts.text_id, texts.title
    FROM assigned_texts
    LEFT JOIN texts
    ON assigned_texts.text_id = texts.id
    WHERE assigned_texts.class_id = ?
";
    if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("i", $_GET['class_id']);
            $stmt->execute();
            $stmt->bind_result($textID, $textTitle);
            $stmt->store_result();
			$texts= array();
			$inc = 0;
			  if ($stmt->num_rows > 0) {
            while ($stmt->fetch()) {
                $texts[$inc] = $textTitle . '#' . $textID;
                $inc++;

			}
            }
        }
			
    return $texts;
}

function addCollection($textID, $mysqli, $user_id)
{
    $query = "
        INSERT INTO texts_collection
        (text_id, user_id)
        VALUES (?,?)
    ";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("ii", $textID, $user_id);
        $stmt->execute();
        return TRUE;
    } else {
        return FALSE;
    }
}

function checkStudentInClass($classID, $studentID, $mysqli)
{
    $query = "
        SELECT id
        FROM class_members
        WHERE user_id = ? AND class_id = ?
    ";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("ii", $studentID, $classID);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
}

function checkStudentRead($textID, $classID, $studentID, $mysqli){
    $query = "
        SELECT id
        FROM text_read
        WHERE text_id = ? AND member_id = ? AND class_id = ?
    ";
    if($stmt = $mysqli->prepare($query)){
        $stmt->bind_param("iii", $textID, $studentID, $classID);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows > 0){
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
function recordStudentRead($textID, $classID, $studentID, $mysqli)
{
    if(!checkStudentRead($textID, $classID, $studentID, $mysqli)){
        $query = "
        INSERT INTO text_read
        (class_id, text_id, member_id, date_created)
        VALUES (?,?,?,?)
    ";
        if ($stmt = $mysqli->prepare($query)) {
            $now = time();
            $stmt->bind_param("iiii", $classID, $textID, $studentID, $now);
            $stmt->execute();
            if ($stmt->error == '') {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

}
function checkNewsTeacher($newsID, $mysqli){
    $query = "
        SELECT class_id
        FROM class_news
        WHERE id = ?
    ";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $newsID);
        $stmt->execute();
        $stmt->bind_result($classID);
        $stmt->fetch();
        $stmt->store_result();
        $stmt->free_result();
        $teacher = checkTeacher($classID, $mysqli);
        if ($teacher) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
function checkTeacher($class_id, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT class_teacher FROM classes WHERE id = ?")) {
        $stmt->bind_param("i", $class_id);
        $stmt->execute();
        $stmt->bind_result($classTeacher);
        $stmt->fetch();

        if ($classTeacher == $_SESSION['user_id']) {
            return TRUE;
        } else {
            //return $classTeacher . $_SESSION['user_id'];
            return FALSE;
        }
    } else {
        return FALSE;
    }
}
function updateNews($newsID, $newsTitle, $newsContent, $mysqli)
{
    $teacher = checkNewsTeacher($newsID, $mysqli);
    if($teacher == 2){
        $query = "
            UPDATE class_news
            SET news_title = ?, news_content = ?
            WHERE id = ?
        ";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("ssi", $newsTitle, $newsContent, $newsID);
            $stmt->execute();
            $stmt->store_result();
            $error = $stmt->error;
            if ($error == "") {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    } else {
        return FALSE;
    }
}
function checkTextOwner($textID, $userID, $mysqli){
    $query = "
        SELECT creator_id
        FROM texts
        WHERE id = ?
    ";
    if($stmt = $mysqli->prepare($query)){
        $stmt->bind_param("i", $textID);
        $stmt->execute();
        $stmt->bind_result($creatorID);
        $stmt->store_result();

        if($stmt->num_rows > 0){
            $stmt->fetch();
            if ($creatorID === $userID) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    } else {
        return FALSE;
    }
}
function getTextInfo($textID, $mysqli){
    $query = "
        SELECT title, content
        FROM texts
        WHERE id = ?
    ";
    if($stmt = $mysqli->prepare($query)){
        $stmt->bind_param("i", $textID);
        $stmt->execute();
        $stmt->bind_result($title, $content);
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->fetch();
            $returnArray = array();
            $returnArray[0] = $title;
            $returnArray[1] = $content;
            return $returnArray;
        } else {
            return FALSE;
        }
    } else {
        return FALSE;
    }
}

function createOA($title, $desc, $classID, $mysqli){
    $query = '
        INSERT INTO open_assignment
        (oa_teacherID, oa_classID, oa_title, oa_description, oa_timeSubmitted)
        VALUES (?, ?, ?, ?, ?)
    ';
    $now = time();
    $teacherID = $_SESSION['user_id'];
    if($stmt = $mysqli->prepare($query)){
        $stmt->bind_param("iissi", $teacherID, $classID, $title, $desc, $now);
        $stmt->execute();
        if ($stmt->error() == ""){
            return TRUE;
        } else {
            return FALSE;
        }
    }
}

function getClassName($class_id, $mysqli){
    $class_name = "";
    $query = '
        SELECT class_name
        FROM classes
        WHERE id = ?
    ';
    if ($stmt = $mysqli->prepare($query)){
        $stmt->bind_param("i", $class_id);
        $stmt->execute();
        $stmt->bind_result($class_name);
        $stmt->store_result();
        if ($stmt->num_rows > 0){
            $stmt->fetch();
            return $class_name;
        } else {
            return "#NULL";
        }
    } else {
        echo "#Query";
    }
}