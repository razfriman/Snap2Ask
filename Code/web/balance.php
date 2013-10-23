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
	<title>Snap-2-Ask | Balance</title>
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
				<li class="selected"><a href="balance.php" >Balance</a></li>
				<li><a href="profile.php" >Profile</a></li>
			</ul>
		</div>
	
		
	
		<div id="mainContent">
			<!--POPULATE BALANCE INFO HERE-->
			<!--Validate input values-->
<?php

// Echo the information using sprintf
// Escape special html characters to enhance XSS security
echo sprintf("<label>Balance</label><input readonly='YES' value='%s'>", htmlspecialchars($responseObj['balance']));

?>
		<form name="addtutorfunds" id="addtutorfunds" action="#" method="put">
		<input type="submit" value="Add funds">
		</form>
		<form name="withdrawfunds" id="withdrawfunds" action="#" method="put">
		<input type="submit" value="Add funds">
		</form>
		</div>
	
	</div>
	


	<?php include('footer.php') ?>

</body>
</html>
