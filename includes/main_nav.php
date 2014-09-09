<?php
/**
 *  Main Navigation Bar
 *
 * @Author: Max Bender
 */
session_start();
echo '
	<nav class="top-bar" data-topbar>
		<ul class="title-area">
			<li class="name">
				<h1><a href="index.php">Viral Education</a></h1>
			</li>
		</ul>
		
		<section class="top-bar-section">
		
		<ul class="right">';
			//<li class=""><a href="#">How it Works</a></li>
			//<li class=""><a href="#">About Us</a></li>
   // ';
if ($_SESSION['teacher'] == TRUE) {
    echo '<li class=""><a href="panel.php">Teacher Panel</a></li>';
}
if (isset($_SESSION['logged'])) {
    if ($_SESSION['logged'] == TRUE) {
        echo '
            <li class="has-dropdown">
                <a href="#">Student</a>
                <ul class="dropdown">
                    <li class="has-dropdown not-click">
                        <a href="#">Classes</a>
                        <ul class="dropdown">
                            <li><label>Classes</label></li>
                            <li><a href="my_classes.php">My Classes</a></li>
                            <li><a href="join_class.php">Join Class</a></li>
                        </ul>
                    </li>
                    <li class=""><a href="my_texts.php?userID= ' . $_SESSION['user_id'] . '">My Readings</a></li>
                    <li class=""><a href="create_text.php">Create Text</a></li>
                </ul>
            </li>
            <li class="has-dropdown">
                <a href="#">' . $_SESSION['name'] . '</a>
                <ul class="dropdown">
                    <li class=""><a href="account.php">My Account</a></li>
                    <li class=""><a href="logout.php">Logout</a></li>
                </ul>
            </li>


                   ';
    }
} else {
    echo '
                <li class=""><a href="login.php">Login</a></li>
                <li class=""><a href="register.php">Register</a></li>';
}

echo '
    	</ul>
    </nav>';

?>