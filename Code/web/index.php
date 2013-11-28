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

// 3rd Party Authentication
if (isset($_POST['authentication_mode']))
{
	$request = array(
		'last_name' => $_POST['last_name'],
		'first_name' => $_POST['first_name'],
		'email' => $_POST['email'],
		'oauth_id' => $_POST['oauth_id'],
		'password' => $_POST['token'],
		'is_tutor' => 1,
		'register_or_login' => 1,
		'authentication_mode' => $_POST['authentication_mode']
		);


		//cURL used to collect login information
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $base_url . '/api/index.php/users');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
	$response = curl_exec($ch);
	curl_close($ch);
	

	
		//sent to the be decoded
	$responseObj = json_decode($response,true);

	echo var_dump($responseObj);
	
		//depending on the response we either ask for different credentials or log the user in
	if($responseObj['success'])
	{
		$_SESSION['user_id'] = $responseObj['user_id'];
		$_SESSION['authentication_mode'] = $_POST['authentication_mode'];

			// Stay logged in
		setcookie('rememberCookie',true);
		
		if ($responseObj['new_user'] == true) {
			
			//Redirect new users to the test page
			header('Location: testChoice.php');
			exit;
		}
	}
}

// Login / Register
if (isset($_POST['submit'])) {

	if($_POST['submit'] == 'Log in')
	{
		// Checking whether the Login form has been submitted

		$err = array();
		// Will hold our errors

		$request = array(
			'account_identifier' => $_POST['email'],
			'password' => $_POST['password'],
			'authentication_mode' => 'custom'
			);

		//cURL used to collect login information
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $base_url . '/api/index.php/login');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
		$response = curl_exec($ch);
		curl_close($ch);

		//sent to the be decoded
		$responseObj = json_decode($response,true);

		//depending on the response we either ask for different credentials or log the user in
		if($responseObj['success'])
		{
			$_SESSION['user_id'] = $responseObj['user_id'];
			$_SESSION['authentication_mode'] = 'custom';
			
			// Stay logged in
			setcookie('rememberCookie',true);
		}
		else
		{
			$err[]='Invalid email/password.';
		}

		if($err)
		{
			// Save the error messages in the session
			$_SESSION['msg']['login-err'] = implode('<br />',$err);
		}

		header("Location: index.php");
		exit;

	} else if($_POST['submit'] == 'Sign Up') {
		
		// Will hold our errors
		$err = array();
		

		$request = array(
			'email' => $_POST['email'],
			'password' => $_POST['password'],
			'first_name' => $_POST['first_name'],
			'last_name' => $_POST['last_name'],
			'is_tutor' => 1,
			'authentication_mode' => 'custom',
			'register_or_login' => false
			);

		//cURL used to collect login information
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $base_url . '/api/index.php/users');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
		$response = curl_exec($ch);
		curl_close($ch);

		//sent to the be decoded
		$responseObj = json_decode($response,true);

		//depending on the response we either ask for different credentials or log the user in
		if($responseObj['success'])
		{
			$_SESSION['user_id'] = $responseObj['user_id'];		
			$_SESSION['authentication_mode'] = 'custom';	
			setcookie('rememberCookie',true);
		}
		else
		{
			$err[]='Could not create account.';
			$err[]=$responseObj['reason'];
		}

		if($err)
		{
			// Save the error messages in the session
			$_SESSION['msg']['register-err'] = implode('<br />',$err);
		}
		
		header('Location: testChoice.php');
		//header("Location: index.php");
		exit;
	}
}

if (isset($_SESSION['user_id']))
{

	// Refresh the session information (name, balanace)
	getUserInfo(true);
	
	// User is already logged in.
	
	header('Location: browse.php');
	exit;
}

?>

<!DOCTYPE html>
<html>

<head>
	<title>Snap-2-Ask</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	<link rel="shortcut icon" type="image/x-icon" href="res/favicon.ico">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="js/googleLogin.js" type="text/javascript"></script> 
	<script src="js/validateInput.js" type="text/javascript"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
	<?php include_once("ganalytics.php") ?>
	<header class="tall">

		<img id="logoTall" src="res/logo.png" alt="Snap-2-Ask Logo"/>

		<div id="loginContainer" >



			<?php

			if(isset($_SESSION['msg']['login-err']))
			{
// Display the login error message
				echo '<label class="error">'.$_SESSION['msg']['login-err'].'</label>';
				unset($_SESSION['msg']['login-err']);
			}

			?>
			<label id="loginError" class="error"></label>

			<!-- Login Form in html that sends email and pass to corresponding php script -->
			<form id="loginForm" method="POST" action="index.php">
				
				<input type="email" name="email" placeholder="Email" title="Please enter a valid email" required autocomplete="on" />
				<input type="password" name="password" placeholder="Password" title="Please enter a password" required autocomplete="on" />
				<input  id="loginButton" class="button" type="submit" name="submit" value="Log in" />
				<a id="forgotPassword" href="resetPassword.php">Forgot Password</a>
			</form>
			
			<!-- Google+ Signin Button -->
			<span id="signinButton">
				<span
				class="g-signin"
				data-callback="signinCallback"
				data-clientid="324181753300-40vh714rcp23n69pqu9mi2c2v605cj86.apps.googleusercontent.com"
				data-cookiepolicy="single_host_origin"
				data-requestvisibleactions="http://schemas.google.com/AddActivity"
				data-scope="https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email">
			</span>
		</span>
		
		<div id="fb-root"></div>
		<script src="js/facebookLogin.js" type="text/javascript"></script>
		<fb:login-button perms="email" id="facebookLoginButton" size="large"></fb:login-button>
		
	</div>

</header>

<div id="content">
	
	<div id="infoContainer">

		<img src="res/icons/SAT.png" />		
		<h2>Snap-2-Ask helps answer your academic questions</h2>
		
		<img src="res/icons/Academic.png" />
		<h2>All Questions on Snap-2-Ask are picture based and answered by certified tutors.</h2>
		
		<img src="res/dollars.png" />
		<h2> Tutors get rewarded with SnapCash for answering questions.</h2>
	</div>

	<div id="registerContainer">

		<h2>Create an Account</h2>
		
		<?php

		if(isset($_SESSION['msg']['register-err']))
		{
			// Display the login error message
			echo '<div class="error">'.$_SESSION['msg']['register-err'].'</div>';
			unset($_SESSION['msg']['register-err']);
		}

		?>
		<form id="registerForm" method="POST" action="index.php">

			<input type="text" name="first_name" placeholder="First Name" title="Please enter your first name" required autocomplete="on" />
			<input type="text" name="last_name" placeholder="Last Name" title="Please enter your last name" required autocomplete="on" />

			<input type="email" name="email" placeholder="Email" required autocomplete="on" title="Please enter a valid email address" />
			<input type="password" name="password" id="password" placeholder="Password" title="Password must be at least 8 characters" required autocomplete="on" />
			<input type="password" name="confirm_password" placeholder="Confirm Password" title="Password does not match" required autocomplete="on" />


			<input class="button" type="submit" name="submit" value="Sign Up" />
		</form>
	</div>
</div>

<?php include('footer.php') ?>
</body>
</html>
