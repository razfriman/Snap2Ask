<?php
// Start the named session
session_name('loginSession');
session_start();

// Allow the included files to be executed
define('inc_file', TRUE);

// if (!isset($_SESSION['user_id'])) {
// 	// The user is not logged in
// 	header('Location: index.php');
// 	exit;
// }

// Require the functions file
require_once('functions.php');

$responseObj = getUserInfo(true);

?>

<!DOCTYPE html>
<html>

<head>
	<title>Snap-2-Ask | Test-Failed</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" type="image/x-icon" href="res/favicon.ico">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="js/testQuestions.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
	<form id="testfailed" method="post" action="./api/index.php/testChoices">
	<p>You have failed the test. The minimum passing percentage is 70%. You can choose to retake the test now or later.</p>
	<input class="decision_point button" type="submit" value="Retake Now" name="testChoice"/>
	<input class="decision_point button" type="submit" value="Retake Later" name="testChoice"/>
	</form>
</body>
</html>
