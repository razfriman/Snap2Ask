<!DOCTYPE html>
<html>

<head>
	<title>Snap-2-Ask</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	
	<!--
	
	 From CustomCupcakes
	
	<script src="js/validateInput.js" type="text/javascript"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.min.js"></script>
	-->
	
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

	<header>

		<h1><img src="res/temp_logo.png" alt="Snap-2-Ask Logo" id="logo"/></h1>
		
		<!-- content -->
		<div id="slogan">
				<h2>Snap. Ask. Done.</h2>
		</div>
		
	</header>
	
	<div id="mainContainer">
		
		<div class="divider"></div>
			
		<div id="loginContainer" >
	
			<h2>Login</h2>
				 
				<div id="login_error" class="error">
				</div>
				
				<!-- Login Form in html that sends email and pass to corresponding php script -->
			<form id="loginForm" method="POST" action="index.php">
				<input type="email" name="email" placeholder="Email Address" title="Please enter a valid email" required autocomplete="on" />
				<input type="password" name="password" placeholder="Password" title="Password must be at least 8 characters" required autocomplete="on" />
				<input  class="button" type="submit" name="submit" value="Log in" />
			</form>
		</div>
	
		
		
		<div class="divider"></div>
	
		<div id="registerContainer">
		
		<h2>Create an Account</h2>
		
			 
			
			<!-- account creation form -->
			<div id="register_error" class="error">
			</div>
	
			
			<form id="registerForm" method="POST" action="index.php">
	
				<input type="text" name="first_name" placeholder="First Name" title="Please enter your first name" required autocomplete="on" />
			    <input type="text" name="last_name" placeholder="Last Name" title="Please enter your last name" required autocomplete="on" />
				
				<input type="email" name="email" placeholder="Email Address" required autocomplete="on" title="Please enter a valid email address" />			
				<input type="password" name="password" placeholder="Password" title="Password must be at least 8 characters" required autocomplete="on" />
				<input type="password" name="confirm_password" placeholder="Confirm Password" title="Password must be at least 8 characters" required autocomplete="on" />
				
				
				<input class="button" type="submit" name="submit" value="Sign Up" />
				
			</form>
		</div>
	</div>
</body>
</html>
