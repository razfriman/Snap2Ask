<?php


// Allow the included files to be executed
define('inc_file', TRUE);

// Start the named session
session_name('loginSession');

// Making the cookie live for 2 weeks
session_set_cookie_params(2*7*24*60*60);

// Start the session
session_start();


// Require the functions file
require_once('functions.php');


?>

<!DOCTYPE html>
<html>

<head>
	<title>Snap-2-Ask | Reset Password</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" type="image/x-icon" href="res/favicon.ico">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

	<header>

		<h1><img src="res/new_logo.png" alt="Snap-2-Ask Logo" id="logo"/></h1>

		<!-- content -->
		<div id="slogan">
				<h2>Snap. Ask. Done.</h2>
		</div>

	</header>
   
    <div id="mainContainer">

		<div class="divider"></div>

		<div id="loginContainer" >
		<form action='resetemailsent.php' name="resetpass" id="resetpass" method= "post">

			<label>Enter your E-mail Address</label><input type='text' name='toemail'>
			<input type="submit" id="submitButton" value="Send New Password">
			<p><a href="index.php">Return to Main Login page</a>
		</form>
				
		</div>

		<div class="divider"></div>
<?php include('footer.php');?>
	</div>
</body>
</html>
