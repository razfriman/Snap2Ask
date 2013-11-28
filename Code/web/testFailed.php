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

if(isset($_POST['submit'])) {
	if ($_POST['submit'] == 'Retake Now' && isset($_GET['category'])) {
		$category_id = $_GET['category'];
		header(sprintf('Location: subjectTest.php?category_id=%s', $category_id));
	} else if ($_POST['submit'] == 'Retake Later') {
		header('Location: index.php');
		exit;
	} else {
		header('Location: index.php');
		exit;
	}
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Snap-2-Ask | Test-Failed</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" type="image/x-icon" href="res/favicon.ico">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
	<?php include_once("ganalytics.php") ?>
	<header class="tall">
		<a href="index.php"> <img id="logoTall" src="res/logo.png" alt="Snap-2-Ask Logo"/> </a>
	</header>

	<div id="content">
	
		<p>You have failed the test. </p>
		<?php
		
		if (isset($_GET['correct']) && isset($_GET['total']) && isset($_GET['percentCorrect']) && isset($_GET['passingPercentage'])) {
			
			echo sprintf('You answered %s out of %s questions correctly, meaning your score was %s%%. However, the minimum passing percentage is %s%%.</p>', $_GET['correct'], $_GET['total'], $_GET['percentCorrect'], $_GET['passingPercentage']);
		}
		
		?>
			
		<p>You can choose to retake the test now or later.</p>
		
		<form id="testfailed" method="post" action="#">		
			<input class="decision_point button" type="submit" value="Retake Now" name="submit"/>
			<input class="decision_point button" type="submit" value="Retake Later" name="submit"/>
		</form>
		
	</div>
	
	<?php include('footer.php') ?>
</body>
</html>
