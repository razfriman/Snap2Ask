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
	<title>Snap-2-Ask | Test</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" type="image/x-icon" href="res/favicon.ico">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="js/testQuestions.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

	<header class="tall">
		<a href="index.php"> <img id="logoTall" src="res/logo.png" alt="Snap-2-Ask Logo"/> </a>
	</header>

	<div id="content">
	
		<div id="subjectTestContainer">
		
		<?php
			$name = $_SESSION['test_category_name'];
	
			echo "<h1 id='title'>Tutor Validation Test for " . $name . "</h1>"
		?>
		
			<form id="Test" method="post" action="./api/index.php/validateTest">
				<!---Population test questions here.-->
			</form>
		</div>
	</div>
	
	<?php include('footer.php') ?>
</body>
</html>
