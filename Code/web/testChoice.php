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
	<title>Snap-2-Ask | Test-Choice</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" type="image/x-icon" href="res/favicon.ico">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="js/testQuestions.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body id="test-choice">
	<form id="taketest" method="post" action="">
	<p id="test-title">Our records indicate you are a first time tutor. In order to start answering questions and get paid, you have to pass a test. You can choose to take it now or later.</p>
	<div id="buttons">
		<input class="button" type="submit" value="Take Now" id="take" onClick="javascript:console.log("hello"); window.location.href='subjectTest.php';"/>
		<input class="button" type="submit" value="Take Later" id="notake"/>
	</div>
	</form>
</body>
</html>
