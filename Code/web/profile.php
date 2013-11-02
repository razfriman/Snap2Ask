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

$categories = getCategories();

if (isset($_POST['submit']) && $_POST['submit'] == 'Delete Account')
{

	// DELETE ACCOUNT VIA REST API
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $base_url . '/api/index.php/users/' . $responseObj['id']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
	$deleteResponse = curl_exec($ch);
	curl_close($ch);
	
	// Redirect to the logout page	
	header('Location: logout.php');
	exit;
}

?>

<!DOCTYPE html>
<html>

<head>
	<title>Snap-2-Ask | Profile</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" type="image/x-icon" href="res/favicon.ico">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	
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
			</ul>
		</div>
	
		
	
		<div id="mainContent">

		<h1>VIEW PROFILE</h1>
		
			<!--POPULATE PROFILE INFORMATION HERE-->
            <form id="tutorprofile" action="editprofile.php" method="get">
			
<?php
// Echo the information using sprintf
// Escape special html characters to enhance XSS security
echo sprintf("<div class='profileItem'><label>First Name:</label><p>%s</p></div>", htmlspecialchars($responseObj['first_name']));
echo sprintf("<div class='profileItem'><label>Last Name:</label><p>%s</p></div>", htmlspecialchars($responseObj['last_name']));
echo sprintf("<div class='profileItem'><label>Email:</label><p>%s</p></div>", htmlspecialchars($responseObj['email']));

foreach ($categories as $category) {
	if ($responseObj['preferred_category_id'] == $category['id']) {
		echo sprintf("<div class='profileItem'><label>Expert in:</label><p>%s</p></div>", htmlspecialchars($category['name']));		
	}
}
?>
		        
				<input type="submit" value="Edit Profile">

        	</form>
        	
        	<form id="deleteAccountForm" action="profile.php" method="post">
        		<input type="submit" name="submit" value="Delete Account" />
        	</form>
        	
		</div><!--end container div-->
	</div>

	<?php include('footer.php') ?>

</body>
</html>
