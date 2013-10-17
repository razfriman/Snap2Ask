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

// Create the dynamic base_url for the REST API request
$prefix = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
$domain = $_SERVER['HTTP_HOST'];
$base_url = $prefix . $domain . dirname($_SERVER['PHP_SELF']);

// Use official REST API
$base_url = "http://snap2ask.com/git/snap2ask/Code/web";



$user_id = $_SESSION['user_id'];
	
// Load the user information to populate the name and balance for the user
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $base_url . '/api/index.php/users/' . $user_id);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
$response = curl_exec($ch);
curl_close($ch);

//sent to the be decoded
$responseObj = json_decode($response,true);

$_SESSION['first_name'] = $responseObj['first_name'];
$_SESSION['balance'] = $responseObj['balance'];



?>

<!DOCTYPE html>
<html>

<head>
	<title>Snap-2-Ask | Profile</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

	<?php include('header.php') ?>

	<div id="container">
	
		<div id="linksNav">
			<ul>
				<li><a href="browse.php" >Browse</a></li>
				<li><a href="balance.php" >Balance</a></li>
				<li class="selected" ><a href="profile.php" >Profile</a></li>
			</ul>
		</div>
	
		
	
		<div id="mainContent">
			<!--POPULATE PROFILE INFORMATION HERE-->
<?php

// Echo the information using sprintf
// Escape special html characters to enhance XSS security
echo sprintf("<label>First Name</label><input readonly='YES' value='%s'>", htmlspecialchars($responseObj['first_name']));
echo sprintf("<label>Last Name</label><input readonly='YES' value='%s'>", htmlspecialchars($responseObj['last_name']));
echo sprintf("<label>Email</label><input readonly='YES' value='%s'>", htmlspecialchars($responseObj['email']));

?>
		</div>

	</div>

	<?php include('footer.php') ?>

</body>
</html>
