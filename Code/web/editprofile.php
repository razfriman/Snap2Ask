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
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="js/validateEditProfile.js" type="text/javascript"></script>
	
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
            <h1>EDIT PROFILE</H1>
			<!--POPULATE PROFILE INFORMATION HERE-->
			<form id="edittutorprofile" action="#" method="post">
			
<?php
// Echo the information using sprintf
// Escape special html characters to enhance XSS security
echo sprintf("<label>First Name</label><input type='text' name='first_name' value='%s'>", htmlspecialchars($responseObj['first_name']));
echo sprintf("<label>Last Name</label><input type='text'  name='last_name' value='%s'>", htmlspecialchars($responseObj['last_name']));
?>
				<input type="submit" value="Save Changes">
				<a href="deact.php">Deactivate account</a>
			</form>
		</div>

	</div>

	<?php include('footer.php') ?>

</body>
</html>
