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
	<title>Snap-2-Ask | Test-Choice</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" type="image/x-icon" href="res/favicon.ico">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="js/populateList.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
	<form id="testchoice" method="post" action="./api/index.php/testChoices">
	<p>Our records indicate you are a first time tutor. In order to start answering questions and get paid, you have to pass a test. You can choose to take it now or later.</p>
	<select name="category">
		<option value="Select Category">Select Category</option>
		<!-- Populate menu -->
		<?php
			$categ = getCategories();

			for ($a = 0; $a < sizeof($categ); $a++){
				echo "<option value='" . $categ[$a]["id"] . "|" . $categ[$a]["name"] . "'>" . $categ[$a]["name"] . "</option>";
			}
		?>
	</select>
	<input  class="decision_point button" type="submit" value="Take Now" name="testChoice"/>
	<input class="decision_point button" type="submit" value="Take Later" name="testChoice"/>
	</form>
</body>
</html>
