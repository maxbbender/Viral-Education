<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 7/31/14
 * Time: 6:50 PM
 */
$assign = $createNews = "";
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
if (isset($_GET['class_id'])) {

    //Initiate whether or not user is a teacher of this class/Class exists
    $teacher = $exists = FALSE;

    //Initiate Variables
    $className = $classNews = "";

    $error = NULL;

    //Check if class exists
    $existCheck = checkClassExists($_GET['class_id'], $mysqli);
    if ($existCheck > 0) {
        //Check whether or not user is a teacher of this class
        $exists = TRUE;
        if (checkTeacher($_GET['class_id'], $mysqli)) {
            $teacher = TRUE;
        }
    }
    //Get teacher's current text list for assigning
    if ($teacher) {
        $myTexts = getCollection($_SESSION['user_id'], $mysqli);
		$assigned_texts = getAssigned($_GET['class_id'], $mysqli);
        $assign = '
            <form action="assign_text.php" method="GET" id="assign_form">
                <div class="row">
                    <div class="small-8 columns">
                        <select name="textID">

        ';
        foreach ($myTexts as $texts) {
            $explodeText = explode("#", $texts);
            $assign .= '
                <option value="' . $explodeText[1] . '">' . $explodeText[0] . '</option>
            ';
        }
        $assign .= '
                        </select>
						
                    </div>
                    <div class="small-4 columns">
        				<input name="date" id="dateTimePicker" type="text">
					

		
		
                        <input type="submit" value="Assign Text" class="button radius tiny" id="assign_button">
                    </div>
                    <input type="hidden" name="classID" value="' . $_GET['class_id'] . '">
                </div><hr>
            </form>
        ';
		$assign .= '<div>
		
		</div>';
 

	

		
		//get assigned texts to be removed 
		$assign .= '
            <form  action="remove_text.php" method="GET" id="remove_form">
                <div class="row">
                    <div class="small-8 columns">
                        <select name="textID" id="del_select">

        ';
        foreach ($assigned_texts as $texts) {
            $explodeText = explode("#", $texts);
            $assign .= '
                <option value="' . $explodeText[1] . '">' . $explodeText[0] . '</option>
            ';
        }
        $assign .= '
                        </select>
                    </div>
                    <div class="small-4 columns">
                        <input type="button" value="Remove Text" class="button radius tiny" id="remove_button">
                    </div>
                    <input type="hidden" name="classID" value="' . $_GET['class_id'] . '">
                </div><hr>
            </form>
        ';
		
		
		
    }
    if ($exists) {
        //Populate Information SideBar
        $query = "
            SELECT classes.invite_id, classes.class_name, classes.class_description, classes.date_created, members.fname, members.lname
            FROM classes
            LEFT JOIN members
              ON classes.class_teacher = members.id
            WHERE classes.id = ?
        ";
        $classInfo = '
            <div class="row text-center">
                <h2 class="subheader">Class Information</h3>
            </div>
        ';
        /* -- General Info -- */
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("i", $_GET['class_id']);
            $stmt->execute();
            $stmt->bind_result($inviteID, $className, $classDescription, $classCreated, $teacherFName, $teacherLName);
            $stmt->store_result();
            $stmt->fetch();


            $classInfo .= '
                <dl class="accordion" data-accordion>
                    <dd class="accordion-navigation">
                        <a href="#panel1">General Information</a>
                        <div id="panel1" class="content active">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>Teacher:</td>
                                        <td>' . $teacherFName . ' ' . $teacherLName . '
                                    </tr>
                                    <tr>
                                        <td>Invite Code:</td>
                                        <td>' . $inviteID . '</td>
                                    </tr>
                                    <tr>
                                        <td>Class Created:</td>
                                        <td>' . date("m.d.y", $classCreated) . '
                                    </tr>
                                    <tr>
                                        <td>Class Description:</td>
                                        <td>' . $classDescription . '
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </dd>
            ';
            $stmt->free_result();
        }

        /* -- Assigned Texts -- */
        $query = "
            SELECT assigned_texts.text_id, texts.title
            FROM assigned_texts
            LEFT JOIN texts
              ON assigned_texts.text_id = texts.id
            WHERE assigned_texts.class_id = ? AND (NOW() < assignment_due OR assignment_due='0000-00-00 00:00:00')
        ";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("i", $_GET['class_id']);
            $stmt->execute();
            $stmt->bind_result($textID, $textTitle);
            $stmt->store_result();

            $classInfo .= '
                <dd class="accordion-navigation">
                <a href="#panel2">Assigned Texts</a>
                <div id="panel2" class="content">
                ' . $assign . '
                    <table>
                        <thead>
                            <tr>
                                <th>Text Title</th>
            ';
            if($teacher){
                $classInfo .= '<th>Statistics</th>';
            }
            $classInfo .= '</tr></thead>';
            if ($stmt->num_rows > 0) {
                while ($stmt->fetch()) {
                    $classInfo .= '
                        <tr>
                            <td><a href="view_text.php?textID=' . $textID . '&class=' . $_GET['class_id'] . '"> ' . $textTitle . '</a></td>
                    ';
                    if($teacher){
                        $classInfo .= '<td><a href="view_statistics.php?type=class&classID=' . $_GET['class_id'] . '&textID=' . $textID .'">View Statistics</a></td> ';
                    }
                    $classInfo .= '</tr>';
                }
            } else {
                $classInfo .= '
                    <tr>
                        <td style="color:red">There is currently no assigned texts</td>
                    </tr>
                ';

            }
            $classInfo .= '
                            </tbody>
                        </table>
                    </div>
                </dd>
            ';
            $stmt->free_result();
        }

        /* -- Current Students -- */
        $classInfo .= '
            <dd class="accordion-navigation">
                <a href="#panel3">Class Roster</a>
                <div id="panel3" class="content">
                    <table>
                        <thead>
                            <tr>
                                <th>Student Name</th>
                            </tr>
                        </thead>
                        <tbody>
        ';

        $query = "
            SELECT members.fname, members.lname, members.id
            FROM class_members
            LEFT JOIN members
              ON class_members.user_id = members.id
            WHERE class_members.class_id = ?
        ";

        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("i", $_GET['class_id']);
            $stmt->execute();
            $stmt->bind_result($fname, $lname, $memberID);
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                while ($stmt->fetch()) {
                    if ($teacher) {
                        $classInfo .= '
                            <tr>
                                <td><a href="view_statistics.php?type=student&classID=' . $_GET['class_id'] . '&studentID=' . $memberID . '">' . $fname . ' ' . $lname . '</a></td>
                            </tr>
                        ';
                    } else {
                        $classInfo .= '
                            <tr>
                                <td>' . $fname . ' ' . $lname . '</td>
                            </tr>

                         ';
                    }
                }
            } else {
                $classInfo .= '
                    <tr>
                        <td style="color:red">There is no current students in this class</td>
                    </tr>
                ';
            }
            $classInfo .= '
                </tbody>
                </table>
                </div>
                </dd>
            ';
        }

        /* -- Get Class News -- */
        $query2 = "
            SELECT class_news.id, class_news.date_created, class_news.news_title, class_news.news_content, members.fname, members.lname
            FROM class_news
            LEFT JOIN members
              ON class_news.creator_id = members.id
            WHERE class_news.class_id = ?
            ORDER BY class_news.date_created DESC
            LIMIT 3
        ";
        if ($stmt = $mysqli->prepare($query2)) {
            $stmt->bind_param("i", $_GET['class_id']);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($newsID, $dateCreated, $newsTitle, $newsContent, $creatorFName, $creatorLName);
            if ($teacher) {
                $createNews = ' - <a href="create_news.php?type=class&class_id=' . $_GET['class_id'] . '" class="button radius small">Create News</a>';
            }
            $classNews .= '
                <div class="row text-center">
                    <h2 class="subheader">Class News/Updates' . $createNews . '</h2>

                </div>
            ';

            if ($stmt->num_rows > 0) {
                while ($stmt->fetch()) {
                    if($teacher){
                        $classNews .= '<a href="edit_news.php?newsID=' . $newsID .'&classID=' . $_GET['class_id'] . '">';
                    }
                    $classNews .= '
                        <div class="row panel">
                            <div class="row">
                                <div class="small-9 columns">
                                    <h3 class="subheader">' . $newsTitle . '</h3>
                                </div>
                                <div class="small-3 columns">
                                    <h3 class="subheader">' . date("m.d.y", $dateCreated) . '</h3>
                                </div>
                            </div>
                            <div class="row">
                                ' . $newsContent . '
                            </div>
                            <div class="row">
                                <br> ~' . $creatorFName . ' ' . $creatorLName . '
                            </div>
                        </div>
                    ';
                    if($teacher){
                        $classNews .= '</a>';
                    }
                }
            } else {
                $classNews .= '
                    <div class="row text-center">
                        <span style="color:red">There is no news for this class</span>
                    </div>
                ';
            }
        }
    } else {
        $error = 'Class does not exist';
    }
} else {
    $error = 'Wrong parameters sent to page. Please go back and try again';
}
?>
<html>
<head>
    <title>Viral Education - View Class</title>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">

    <?php include_once 'includes/css_links.php'; ?>
    <link rel="stylesheet" type="text/css" href="/datetimepicker-master/jquery.datetimepicker.css"/ >
</head>
<body>
<?php include_once 'includes/main_nav.php'; ?>
<div class="row text-center">
    <h1>View Class - <?php echo $className; ?></h1><hr>
    <?php if ($error != NULL) {
        echo $error;
    } ?>
</div>
<div class="row">
    <div class="small-8 columns">
        <?php echo $classNews; ?>
    </div>
    <div class="small-4 columns">
        <?php echo $classInfo; ?>
    </div>
</div>
<?php include_once 'includes/javascript_basic.php'; ?>
<!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>-->
   <script src="/datetimepicker-master/jquery.js"></script>
  <script src="/datetimepicker-master/build/jquery.datetimepicker.full.min.js"></script>
 
  

 <script>

	
$(document).ready(function () {
	var dt = new Date();
	var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
	$('#dateTimePicker').datetimepicker( {
		 datepicker: true,
		 format:'Y-m-d H:i',
		 minDate:0,//yesterday is minimum date(for today use 0 or -1970/01/01)
	});

	
   $("#remove_button").click(function(){
		if (confirm("Are you sure you want to delete: "+ $( "#del_select option:selected" ).text()+". \n\nClick Ok to confirm this action.")){
			$("form#remove_form").submit();
		}
		  
	});
});



  
 
  </script>

</script>
</body>
</html>