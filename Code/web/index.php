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
			'is_tutor' => true,
			'register_or_login' => true,
			'authentication_mode' => $_POST['authentication_mode'],
			'preferred_category_id' => 1
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
			$_SESSION['authentication_mode'] = $_POST['authentication_mode'];

			// Stay logged in
			setcookie('rememberCookie',true);
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
		

		// preferred_category_id is TEMPORARY
		$request = array(
			'email' => $_POST['email'],
			'password' => $_POST['password'],
			'first_name' => $_POST['first_name'],
			'last_name' => $_POST['last_name'],
			'is_tutor' => true,
			'authentication_mode' => 'custom',
			'register_or_login' => false,
			'preferred_category_id' => 1
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
		
		header("Location: index.php");
		exit;
	}
}

if (isset($_SESSION['user_id']))
{

	// Refresh the session information (name, balanace)
	getUserInfo(true);
	
	// User is already logged in.
	// Redirect to the browse questions page
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
    <script type="text/javascript">
      (function() {
       var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
       po.src = 'https://apis.google.com/js/client:plusone.js';
       var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
     })();
     
          
     // LOGOUT
     //gapi.auth.signOut();
     
     function signinCallback(authResult) {
     
	  if (authResult['access_token']) {
	
		  // Update the app to reflect a signed in user
		  // Hide the sign-in button now that the user is authorized, for example:
		  document.getElementById('signinButton').setAttribute('style', 'display: none');

		  gapi.auth.setToken(authResult);
	  
		  console.log('Sign-in state: SUCCESS');
		  
		  // Load Profile Info
		  gapi.client.load('plus','v1', function(){	
			  var request = gapi.client.plus.people.get({
			   'userId': 'me'
			 });
			 request.execute(function(resp1) {
			 
			 var firstName  = resp1.name.familyName;
			 var lastName = resp1.name.givenName;
			 var oauthId = resp1.id;
			 
			 console.log('First Name: ' + resp1.name.familyName);
			 console.log('Last Name: ' + resp1.name.givenName);
			 console.log('OAuth ID: ' + resp1.id);
			 
			 // Load Email
			  gapi.client.load('oauth2', 'v2', function() {
				  gapi.client.oauth2.userinfo.get().execute(function(resp2) {
				    
				    var email = resp2.email;
				    
				    console.log('Email: ' + resp2.email);
				    
				    // REDIRECT				    
			       var form = $('<form></form>');
			   	   $(form).hide().attr('method','post').attr('action','index.php');
			   	   $(form).append($('<input type="hidden" />').attr('name','token').val(authResult['access_token']));
			   	   $(form).append($('<input type="hidden" />').attr('name','authentication_mode').val('google'));
			   	   $(form).append($('<input type="hidden" />').attr('name','first_name').val(firstName));
			   	   $(form).append($('<input type="hidden" />').attr('name','last_name').val(lastName));
			   	   $(form).append($('<input type="hidden" />').attr('name','oauth_id').val(oauthId));
			   	   $(form).append($('<input type="hidden" />').attr('name','email').val(email));
			 	   $(form).appendTo('body').submit();
				 	   
				  })
				});
			 });
			});
       
       }
       else if (authResult['error']) {
	    // Update the app to reflect a signed out user
	    // Possible error values:
	    //   "user_signed_out" - User is signed-out
	    //   "access_denied" - User denied access to your app
	    //   "immediate_failed" - Could not automatically log in the user
	    console.log('Sign-in state: ' + authResult['error']);
	  }
	}
    </script>
    <script src="js/validateInput.js" type="text/javascript"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.min.js"></script>

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
    
    <div id="infoContainer">
        <h2>Snap-2-Ask helps you get your academic questions answered and make money answering questions</h2>
        <h3>All Questions on Snap-2-Ask are picture based and answered by certified tutors.</h3>
        <p>
            Snap-2-Ask is an Academic Question and Answer Forum where you can submit questions 
            through our mobile app, while answering other's question through this website.
            You can start earning SnapCash by answering user submitted questions today!
            All you have to do is create an account.
            Certification exams will soon be avaliable for more accurate answers and better pay.
        <p>
    </div>

	<div id="mainContainer">

		<div class="divider"></div>

		<div id="loginContainer" >

			<h2>Login</h2>

<?php

if(isset($_SESSION['msg']['login-err']))
{
	// Display the login error message
	echo '<div class="error">'.$_SESSION['msg']['login-err'].'</div>';
	unset($_SESSION['msg']['login-err']);
}

?>
				<!-- Login Form in html that sends email and pass to corresponding php script -->
			<form id="loginForm" method="POST" action="index.php">
				<input type="email" name="email" placeholder="Email" title="Please enter a valid email" required autocomplete="on" />
				<input type="password" name="password" placeholder="Password" title="Please enter a password" required autocomplete="on" />
				<input  class="button" type="submit" name="submit" value="Log in" />
				<a href="resetpass.php">Forgot Password</a>
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
<script>
  window.fbAsyncInit = function() {
  FB.init({
    appId      : '162185813987423', // App ID
    status     : false, // check login status
    cookie     : true, // enable cookies to allow the server to access the session
    xfbml      : true  // parse XFBML
  });

  // Here we subscribe to the auth.authResponseChange JavaScript event. This event is fired
  // for any authentication related change, such as login, logout or session refresh. This means that
  // whenever someone who was previously logged out tries to log in again, the correct case below 
  // will be handled. 
  FB.Event.subscribe('auth.authResponseChange', function(authResponse) {
    // Here we specify what we do with the response anytime this event occurs. 
    if (authResponse.status === 'connected') {
      // The response object is returned with a status field that lets the app know the current
      // login status of the person. In this case, we're handling the situation where they 
      // have logged in to the app.
      
      FB.api('/me', function(response) {
      
	      	// REDIRECT				    
			var form = $('<form></form>');
			$(form).hide().attr('method','post').attr('action','index.php');
			$(form).append($('<input type="hidden" />').attr('name','token').val(authResponse.authResponse.accessToken));
			$(form).append($('<input type="hidden" />').attr('name','authentication_mode').val('facebook'));
			$(form).append($('<input type="hidden" />').attr('name','first_name').val(response.first_name));
			$(form).append($('<input type="hidden" />').attr('name','last_name').val(response.last_name));
			$(form).append($('<input type="hidden" />').attr('name','oauth_id').val(response.id));
			$(form).append($('<input type="hidden" />').attr('name','email').val(response.email));
			$(form).appendTo('body').submit();
		   
		  //console.log(authResponse.authResponse);
	      //console.log(response);
	    });
    } else if (authResponse.status === 'not_authorized') {
      // In this case, the person is logged into Facebook, but not into the app, so we call
      // FB.login() to prompt them to do so. 
      // In real-life usage, you wouldn't want to immediately prompt someone to login 
      // like this, for two reasons:
      // (1) JavaScript created popup windows are blocked by most browsers unless they 
      // result from direct interaction from people using the app (such as a mouse click)
      // (2) it is a bad experience to be continually prompted to login upon page load.
      FB.login();
    } else {
      // In this case, the person is not logged into Facebook, so we call the login() 
      // function to prompt them to do so. Note that at this stage there is no indication
      // of whether they are logged into the app. If they aren't then they'll see the Login
      // dialog right after they log in to Facebook. 
      // The same caveats as above apply to the FB.login() call here.
      FB.login();
    }
  });
  };

  // Load the SDK asynchronously
  (function(d){
   var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement('script'); js.id = id; js.async = true;
   js.src = "//connect.facebook.net/en_US/all.js";
   ref.parentNode.insertBefore(js, ref);
  }(document));

</script>

<!--
  Below we include the Login Button social plugin. This button uses the JavaScript SDK to
  present a graphical Login button that triggers the FB.login() function when clicked.

  Learn more about options for the login button plugin:
  /docs/reference/plugins/login/ -->

<fb:login-button size="xlarge"></fb:login-button>
			
			
		</div>



		<div class="divider"></div>

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
</body>
</html>
