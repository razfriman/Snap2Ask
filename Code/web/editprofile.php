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

if (isset($_POST['first_name']) && isset($_POST['last_name'])) {

		
	// UPDATE THE USER INFO VIA REST API
	$request = array(
			'last_name' => $_POST['last_name'],
			'first_name' => $_POST['first_name'],
			'balance' => $responseObj['balance'],
			'is_tutor' => $responseObj['is_tutor'],
			'rating' => $responseObj['rating'],
			'preferred_category_id' => 1
		);



		//cURL used to collect login information
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $base_url . '/api/index.php/users/' . $responseObj['id']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
		$updateResponse = curl_exec($ch);
		curl_close($ch);
	
		
		//sent to the be decoded
		$updateResponseObj = json_decode($updateResponse,true);

		//depending on the response we either ask for different credentials or log the user in
		if($updateResponseObj['success'])
		{
			header('Location: profile.php');
		} else {
			die($updateResponseObj['reason']);
		}
}


?>

<!DOCTYPE html>
<html>

<head>
	<title>Snap-2-Ask | Profile</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" type="image/x-icon" href="res/favicon.ico">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="js/validateEditProfile.js" type="text/javascript"></script>
	
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
            <h1>EDIT PROFILE</H1>
			<!--POPULATE PROFILE INFORMATION HERE-->
			<form id="edittutorprofile" action="#" method="post">		
<?php
// Echo the information using sprintf
// Escape special html characters to enhance XSS security
echo sprintf("<label>First Name</label><input type='text' name='first_name' value='%s'>", htmlspecialchars($responseObj['first_name']));
echo sprintf("<label>Last Name</label><input type='text'  name='last_name' value='%s'>", htmlspecialchars($responseObj['last_name']));
?>
        <input type="submit" value="Save Changes to Name">
        <div><a href="reset.php">Send Password Reset E-mail</a></div>
				<a href="deact.php">Deactivate account</a>
			</form>
<form name="hsfavs">
<?php
echo "<h2>Choose up to 5 High School Subjects:</h2>";
echo"<select name='hs1'><option label='Choose High School Subject 1'>";
include ("choosehsfavorites.php");
echo"<select name='hs2'><option label='Choose High School Subject 2'>";
include ("choosehsfavorites.php");
echo"<select name='hs3'><option label='Choose High School Subject 3'>";
include ("choosehsfavorites.php");
echo"<select name='hs4'><option label='Choose High School Subject 4'>";
include ("choosehsfavorites.php");
echo"<select name='hs5'><option label='Choose High School Subject 4'>";
include ("choosehsfavorites.php");
echo "<input type = 'submit' value='Save High School Subjects'>
</form>";
echo "<h2>Choose up to 5 College Subjects:</h2>";
include ("choosecollegefavorites.php");
echo "<form name='collegefavs'>";
echo "<select name='college1'><option label='Choose College Subject 1'>";
include ("choosecollegefavorites.php");
echo "<select name='college2'><option label='Choose College Subject 2'>";
include ("choosecollegefavorites.php");
echo "<select name='college3'><option label='Choose College Subject 3'>";
include ("choosecollegefavorites.php");
echo "<select name='college4'><option label='Choose College Subject 4'>";
include ("choosecollegefavorites.php");
echo "<select name='college5'><option label='Choose College Subject 5'>";
include ("choosecollegefavorites.php");
echo "<input type = 'submit' value='Save College Subjects'>
</form>";
?>

		</div>

	</div>

	<?php include('footer.php') ?>

</body>
</html>
