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

if (isset($responseObj['error'])) {
	// Invalid user
	header('Location: logout.php');
	exit;
}

?>

<!DOCTYPE html>
<html>

<head>
	<title>Snap-2-Ask | Test-Passed</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" type="image/x-icon" href="res/favicon.ico">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="js/testQuestions.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
	<?php include_once("ganalytics.php") ?>
	<header class="tall">
		<a href="index.php"> <img id="logoTall" src="res/logo.png" alt="Snap-2-Ask Logo"/> </a>
	</header>

	<div id="content">
	
		<form id="testpassed" method="post" action="index.php">
			<p>Congratulations, you have passed the test and are ready to start answering questions!</p>
			<input class="decision_point" type="submit" value="Continue" name="testChoice"/>
		</form>
	</div>
	
	<?php include('footer.php') ?>
</body>
</html>
