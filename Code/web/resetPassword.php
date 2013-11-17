<?php

// Start the named session
session_name('loginSession');
session_start();

// Allow the included files to be executed
define('inc_file', TRUE);

// Require the functions file
require_once('functions.php');

if (isset($_SESSION['user_id'])) {
	
	// Can't click forgot password if you are already logged in
	header('Location: index.php');
	exit;
}

if(isset($_POST['submit']) && $_POST['submit'] == 'Reset Password')
{
	// Handle Reset Password
	// CALL REST API
	$request = array();
	$request['email'] = $_POST['email'];
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $base_url . '/api/index.php/reset_password');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
	$response = curl_exec($ch);
	curl_close($ch);
	$responseObj = json_decode($response,true);
	
	if ($responseObj['success']) {
		$email = $_POST['email'];
		$user_id = $responseObj['user_id'];
		$reset_token = $responseObj['token'];
		
		$reset_url = $base_url . '/updatePassword.php?user_id=' . $user_id . '&token=' . $reset_token;
		
		$to = $email;
		$subject = "Snap-2-Ask Password Reset";
		$message = sprintf('<a href="%s" >Click here</a> to reset your password.', $reset_url);
		$from = "support@snap2ask.com";
		$headers = "From:" . $from . "\r\n";
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
		mail($to,$subject,$message,$headers);
		
		$_SESSION['msg']['forgot-password-err'] = 'A link has been sent to ' . $to . ' to reset your password.' . '<br />';
		header('Location: resetPassword.php');
		exit;
	} else {
		$_SESSION['msg']['forgot-password-err'] =  $responseObj['reason'] . '<br />';
		header('Location: resetPassword.php');
		exit;
	}
	
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Snap-2-Ask : Forgot Password</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	<link rel="shortcut icon" type="image/x-icon" href="res/favicon.ico">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="js/validateForgotPassword.js" type="text/javascript"></script>    
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
	<?php include_once("ganalytics.php") ?>
	<header class="tall">
		<a href="index.php"> <img id="logoTall" src="res/logo.png" alt="Snap-2-Ask Logo"/> </a>
	</header>

	<div id="content">

		<div id="forgotPasswordContainer" >

			<h2>Forgot Password</h2>

			<?php

			if(isset($_SESSION['msg']['forgot-password-err']))
			{
				// Display the login error message
				echo '<div class="message">'.$_SESSION['msg']['forgot-password-err'].'</div>';
				unset($_SESSION['msg']['forgot-password-err']);
			}

			?>
			<!-- Login Form in html that sends email and pass to corresponding php script -->
			<form id="forgotPasswordForm" method="POST" action="#">
				<input type="email" name="email" placeholder="Email" title="Please enter a valid email" required autocomplete="on" />
				<input type="submit" name="submit" value="Reset Password" />
				<a href="index.php">Go Back</a>
			</form>
			
		</div>
	</div>
	
	<?php include('footer.php') ?>
</body>
</html>
