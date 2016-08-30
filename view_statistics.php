<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/7/14
 * Time: 4:49 PM
 */
session_start();
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
$statsList = $title = $studentCheck = '';
$title = FALSE;

if (isset($_GET['type'])) {

    //Define the type for ease of use
    $type = $_GET['type'];

    if ($type == 'student') {
        //If student: we need the class in which the student is a part of
        if (isset($_GET['classID'], $_GET['studentID'])) {

            $classID = $_GET['classID'];
            $studentID = $_GET['studentID'];

            //Is the user the teacher of the class in question?
            if (checkTeacher($classID, $mysqli)) {

                //Is the student in the class in question?
                if (checkStudentInClass($classID, $studentID, $mysqli)) {
                    $query = "
                        SELECT stats_text.word, stats_text.defined, stats_text.text_id, texts.title, members.fname, members.lname, classes.class_name
                        FROM stats_text
                        INNER JOIN members
                          ON stats_text.reader_id = members.id
                        INNER JOIN classes
                          ON stats_text.class_id = classes.id
                        INNER JOIN texts
                          ON stats_text.text_id = texts.id
                        WHERE stats_text.reader_id = ? AND stats_text.class_id = ?
                        ORDER BY stats_text.text_id
                    ";
                    if ($stmt = $mysqli->prepare($query)) {
                        $stmt->bind_param("ii", $studentID, $classID);
                        $stmt->execute();
                        $stmt->bind_result($word, $defined, $textID, $textTitle, $fname, $lname, $className);
                        $stmt->store_result();
                        echo $stmt->error;
                        if ($stmt->num_rows > 0) {
                            $currentID = 0;
                            while ($stmt->fetch()) {
                                if ($textID == $currentID) {
                                    $statsList .= '
                                        <tr>
                                            <td> ' . html_entity_decode($word) . '</td>
                                            <td> ' . html_entity_decode($defined) . '</td>
                                        </tr>
                                    ';
                                } else if ($currentID == 0) {
                                    $title = $fname . ' ' . $lname . ' - ' . $className;
                                    $statsList .= '
                                        <dl class="accordion" data-accordion>
                                            <dd class="accordion-navigation">
                                                <a href="#panel' . $textID . '">' . $textTitle . '</a>
                                                <div id="panel' . $textID . '" class="content active">
                                                    <h3 class="subheader"><a href="view_text.php?textID= ' . $textID . '">View Text</a></h3>
                                                    <table>
                                                        <thead>
                                                            <tr>
                                                                <th>Looked up Word</th>
                                                                <th>Defined word</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td> ' . html_entity_decode($word) . '</td>
                                                                <td> ' . html_entity_decode($defined) . '</td>
                                                            </tr>
                                    ';


                                    $currentID = $textID;
                                } else {
                                    $statsList .= '
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </dd>
                                            <dd class="accordion-navigation">
                                                <a href="#panel' . $textID . '">' . $textTitle . '</a>
                                                <div id="panel' . $textID . '" class="content">
                                                    <h3 class="subheader"><a href="view_text?textID= ' . $textID . '">View Text</a></h3>
                                                    <table>
                                                        <thead>
                                                            <tr>
                                                                <th>Looked up Word</th>
                                                                <th>Defined Word</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td> ' . html_entity_decode($word) . '</td>
                                                                <td> ' . html_entity_decode($defined) . '</td>
                                                            </tr>
                                    ';
                                    $currentID = $textID;
                                }
                            }
                            $statsList .= '

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </dd>
                                        </dl>
                            ';
                        }
                    } else {
                        echo 'Nope';
                    }
                } else {
                    echo 'Student is not in class';
                }
            } else {
                echo 'You are not the teacher of this class';
            }
        } else {
            echo 'No Class id for the student requested';
        }
    }
    if ($type == 'class') {

        //Check if Class ID was sent
        if (isset($_GET['classID'])) {

            //Check whether or user is the teacher of the class
            if (checkTeacher($_GET['classID'], $mysqli)) {

                //Check whether it is for just one text
                if (isset($_GET['textID'])) {
                    $query = "
                        SELECT stats_text.word, stats_text.defined, texts.title, COUNT(stats_text.word) as itemcount
                        FROM stats_text
                        INNER JOIN texts
                          ON stats_text.text_id = texts.id
                        WHERE stats_text.class_id= ? AND stats_text.text_id = ?
                        GROUP BY stats_text.word
                        ORDER BY itemcount DESC
                    ";
                    if ($stmt = $mysqli->prepare($query)) {
                        $stmt->bind_param("ii", $_GET['classID'], $_GET['textID']);
                        $stmt->execute();
                        $stmt->bind_result($rawWord, $definedWord, $textTitle, $wordCount);
                        $stmt->store_result();
                        $statsList .= '
                            <table>
                                <thead>
                                    <tr>
                                        <th>Word</th>
                                        <th>Defined Word</th>
                                        <th>Number of Clicks</th>
                                    </tr>
                                </thead>
                                <tbody>
                        ';
                        if ($stmt->num_rows > 0) {
                            while ($stmt->fetch()) {
                                $statsList .= '
                                    <tr>
                                        <td>' . html_entity_decode($rawWord). '</td>
                                        <td>' . $definedWord . '</td>
                                        <td>' . $wordCount . '</td>
                                    </tr>
                                ';
                            }
                            $statsList .= '
                                </tbody>
                            </table>
                            ';
                        } else {
                            $statsList .= '
                                <tr>
                                    <td style="color:red">There is no statistics for this text in this class</td>
                                </tr>
                            ';
                        $stmt->free_result();
                        }
                    } else {
                        echo 'Query Error';
                    }
                    $query = "
                        SELECT class_members.user_id, members.fname, members.lname
                        FROM class_members
                        INNER JOIN members
                          ON class_members.user_id = members.id
                        WHERE class_members.class_id = ?
                    ";
                    if ($stmt = $mysqli->prepare($query)) {
                        $stmt->bind_param("i", $_GET['classID']);
                        $stmt->execute();
                        $stmt->bind_result($id, $fname, $lname);
                        $stmt->store_result();
                        $studentList .= '
                            <table>
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Read Text?</th>
                                    </tr>
                                </thead>
                                <tbody>
                        ';
                        if($stmt->num_rows > 0){
                            while ($stmt->fetch()) {
                                $studentList .= '
                                    <tr>
                                        <td> ' . $fname . ' ' . $lname . '</td>
                                ';
                                if (checkStudentRead($_GET['textID'], $_GET['classID'], $id, $mysqli)) {
                                    $studentList .= '
                                        <td style="background:green;width:100px;"></td>
                                    ';
                                } else {
                                    $studentList .= '
                                        <td style="background:red;width:50px;"></td>
                                    ';
                                }
                                $studentList .= '
                                    </tr>';
                            }
                        }
                        $studentList .= '
                            </tbody>
                        </table>
                        ';
                    } else {
                        echo 'Query Error';
                    }
                } else {
                    $query = "
                        S
                    ";
                }

            }
        }
    }
} else {
    echo 'Wrong parameters submitted';
}
?>
<html>
<head>
    <title>Viral Education - Student Stats</title>
    <meta charset="UTF-8">
    <?php include_once 'includes/css_links.php'; ?>
</head>
<body>
<?php include_once 'includes/main_nav.php'; ?>
<div class="row text-center">
    <h1>Statistics: <?php echo $title; ?></h1>
    <hr>
</div>
<div class="row text-center">
    <div class="small-5 columns">
        <div class="row">
            <h3 class="subheader">Word Stats</h3>
        </div>
        <?php echo $statsList; ?>
    </div>
    <div class="small-7 columns">
        <div class="row">
            <h3 class="subheader">Student Stats</h3>
        </div>
        <?php echo $studentList; ?>
    </div>
</div>
<?php include_once 'includes/javascript_basic.php'; ?>
</body>
</html>