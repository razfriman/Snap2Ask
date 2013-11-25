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

$categories = getCategories();

if ($responseObj['authentication_mode'] != 'custom') {
	header('Location: index.php');
	exit;
}

if (isset($_POST['currentPassword']) && isset($_POST['newPassword'])) {

	
	// UPDATE THE USER PASSWORD VIA REST API
	$request = array(
		'current_password' => $_POST['currentPassword'],
		'new_password' => $_POST['newPassword']
		);
	

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $base_url . '/api/index.php/users/' . $responseObj['id'] . '/changePassword');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
	$updateResponse = curl_exec($ch);
	curl_close($ch);
	$updateResponseObj = json_decode($updateResponse,true);
	
	if($updateResponseObj['success'])
	{
		$_SESSION['msg']['change_password_msg'] = 'Your password was successfully updated';
	} else {
		$_SESSION['msg']['change_password_msg_err'] = $updateResponseObj['reason'];
	}
	
	header('Location: changePassword.php');
	exit;
	
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Snap-2-Ask | Change Password</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" type="image/x-icon" href="res/favicon.ico">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
	<script src="js/validateChangePassword.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

	<?php include('header.php') ?>


	<div id="content">
		
		<div id="linksNav">
			<ul>
				<li><a href="browse.php" >Browse</a></li>
				<li><a href="balance.php" >Balance</a></li>
				<li class="selected" ><a href="profile.php" >Profile</a></li>
				<li><a href="viewAnswers.php" >My Answers</a></li>
			</ul>
		</div>
		
		
		
		<div id="mainContent">
			<h1>CHANGE PASSWORD</h1>
			<!--POPULATE PROFILE INFORMATION HERE-->
			
			<?php

			if(isset($_SESSION['msg']['change_password_msg']))
			{
				// Display the login error message
				echo '<label class="message">'.$_SESSION['msg']['change_password_msg'].'</label>';
				unset($_SESSION['msg']['change_password_msg']);
			}
			
			if(isset($_SESSION['msg']['change_password_msg_err']))
			{
				// Display the login error message
				echo '<label class="error">'.$_SESSION['msg']['change_password_msg_err'].'</label>';
				unset($_SESSION['msg']['change_password_msg_err']);
			}
			?>
			
			<form id="changePassword" action="#" method="post">		
				<label>Current Password</label><input type="password" placeholder="Current Password" autocomplete="off" name="currentPassword" />
				<label>New Password</label><input type="password" name="newPassword" id="newPassword" placeholder="New Password" title="Password must be at least 8 characters" />
				<label>Confirm New Password</label><input type="password" placeholder="Confirm New Password" name="confirmNewPassword" />
				<input type="submit" id="submitButton" value="Change Password" />
			</form>
		</div>
	</div>

	<?php include('footer.php') ?>

</body>
</html>
