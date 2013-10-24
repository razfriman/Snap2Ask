<?php

// Start the named session
session_name('loginSession');
session_start();

// Allow the included files to be executed
define('inc_file', TRUE);


if (!isset($_SESSION['user_id'])) {
	// The user is not logged in
	header('Location: index.php');
	exit;
}

// Require the functions file
require_once('functions.php');

$responseObj = getUserInfo(true);


?>

<!DOCTYPE html>
<html>

<head>
	<title>Snap-2-Ask | Profile</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" type="image/x-icon" href="res/favicon.ico">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

	<?php include('header.php') ?>

	<div id="container">
	
		<div id="linksNav">
			<ul>
				<li><a href="browse.php" >Browse</a></li>
				<li><a href="balance.php" >Balance</a></li>
				<li class="selected" ><a href="profile.php" >Profile</a></li>
			</ul>
		</div>
	
		
	
		<div id="mainContent">

		<h1>VIEW PROFILE</h1>

			<!--POPULATE PROFILE INFORMATION HERE-->
            <form id="tutorprofile" action="editprofile.php" method="get">
			
<?php
// Echo the information using sprintf
// Escape special html characters to enhance XSS security
echo sprintf("<div class='profileItem'><label>First Name:</label><p>%s</p></div>", htmlspecialchars($responseObj['first_name']));
echo sprintf("<div class='profileItem'><label>Last Name:</label><p>%s</p></div>", htmlspecialchars($responseObj['last_name']));
echo sprintf("<div class='profileItem'><label>Email:</label><p>%s</p></div>", htmlspecialchars($responseObj['email']));
?>
		<div class='profileItem'><label>Password:</label><p id="password">(hidden) <a href="reset.php">Reset</a></p></div>
		<input type="submit" value="Edit Profile">
		<a href="deact.php">Deactivate account</a>
            	</form>
        <h3>Click on Edit Profile to Edit your Preferred Subjects</h3>
        <ol>
        Your 5 High School Subjects:
        <li>Subject 1</l1>
        <li>Subject 2</l1>
        <li>Subject 3</l1>
        <li>Subject 4</l1>
        <li>Subject 5</l1>
        </ol>
        <ol>
        Your 5 College Subjects:
        <li>Subject 1</l1>
        <li>Subject 2</l1>
        <li>Subject 3</l1>
        <li>Subject 4</l1>
        <li>Subject 5</l1>
        </ol>
		</div><!--end container div-->
	</div>

	<?php include('footer.php') ?>

</body>
</html>
