<?php

// Start the named session
session_name('loginSession');
session_start();

// Allow the included files to be executed
define('inc_file', TRUE);

if (isset($_SESSION['user_id'])) {
	// User cannot reset a forgotten password if they are already logged in
	header('Location: index.php');
	exit;
}

// Require the functions file
require_once('functions.php');

if (!isset($_GET['user_id']) || !isset($_GET['token']))
{
	// Only allow to updat
	header('Location: index.php');
	exit;
}

$userData = getUserData($_GET['user_id']);

if ($userData['password_reset_token'] != $_GET['token'])
{
	header('Location: index.php');
	exit;
}


if (isset($_POST['submit']))
{
	// UPDATE THE PASSWORD IN THE DB
	// REMOVE THE PASSWORD RESET TOKEN
	$request = array();
	$request['user_id'] = $userData['id'];
	$request['password'] = $_POST['password'];
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $base_url . '/api/index.php/reset_password');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
	$response = curl_exec($ch);
	curl_close($ch);
	
	// Log the user in with their new password
	$_SESSION['user_id'] = $userData['id'];
	$_SESSION['authentication_mode'] = 'custom';

	// Stay logged in
	setcookie('rememberCookie',true);
	
	// Redirect to the main page
	header('Location: index.php');
	exit;
}

?>

<!DOCTYPE html>
<html>

<head>
	<title>Snap-2-Ask : Update Password</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	<link rel="shortcut icon" type="image/x-icon" href="res/favicon.ico">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="js/validateUpdatePassword.js" type="text/javascript"></script>    
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
	<?php include_once("ganalytics.php") ?>
	<header class="tall">
		<img id="logoTall" src="res/logo.png" alt="Snap-2-Ask Logo"/>
	</header>

	<div id="content">

		<div id="updatePasswordContainer">

			<h2>Update Password</h2>

			<?php

			if(isset($_SESSION['msg']['update-password-err']))
			{
				// Display the login error message
				echo '<div class="error">'.$_SESSION['msg']['update-password-err'].'</div>';
				unset($_SESSION['msg']['update-password-err']);
			}

			?>
			<!-- Login Form in html that sends email and pass to corresponding php script -->
			<form id="updatePasswordForm" method="POST" action="#">
				<input type="password" id="password" name="password" placeholder="Password" title="Password must be at least 8 characters" required autocomplete="on" />
				<input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" title="The two passwords must match" required autocomplete="on" />
				<input type="submit" name="submit" value="Update Password" />
			</form>
			
		</div>
	</div>
	
	<?php include('footer.php') ?>
	
</body>
</html>