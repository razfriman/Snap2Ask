<?php

// Start the named session
session_name('loginSession');
session_start();

if(isset($_SESSION['user_id'])) {
	
	$redirect_now = $_SESSION['authentication_mode'] == 'custom';
	
	// Clear the session
	$_SESSION = array();
	session_destroy();
	
	if ($redirect_now) {
		header('Location: index.php');
	}
} else {
	header('Location: index.php');
}

?>

<html>

<head>
	<title>Snap-2-Ask</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
    <script type="text/javascript"> 
     
     function signinCallback(authResult) {
     	// LOGOUT
	 	gapi.auth.signOut();
	 	
	 	// Redirect to the main page
	 	window.location.replace("index.php");
     }
     
     window.setTimeout(function() {
		 // Redirect to the main page
		 window.location.replace("index.php");
		 }, 5000);

     
     

     
     </script>
</head>
<body>
Logging Out... <a href="index.php">Click here</a> if you are not redirected in up to 5 seconds.
<span id="signinButton" style="display: none">
			  <span
			    class="g-signin"
			    data-callback="signinCallback"
			    data-clientid="324181753300-40vh714rcp23n69pqu9mi2c2v605cj86.apps.googleusercontent.com"
			    data-cookiepolicy="single_host_origin"
			    data-requestvisibleactions="http://schemas.google.com/AddActivity"
			    data-scope="https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email">
			  </span>
			</span>
</body>
</html>