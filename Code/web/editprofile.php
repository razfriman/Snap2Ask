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

if (isset($_POST['first_name']) && isset($_POST['last_name'])) {

	
	// UPDATE THE USER INFO VIA REST API
	$request = array(
		'last_name' => $_POST['last_name'],
		'first_name' => $_POST['first_name'],
		'balance' => $responseObj['balance'],
		'is_tutor' => $responseObj['is_tutor'],
		'rating' => $responseObj['rating']
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
	$updateResponseObj = json_decode($updateResponse,true);
	
	foreach ($responseObj['verified_categories'] as $category) {	
		$addedCategory = false;
		
		$request = array(
				'category_id' => $category['category_id'],
				'is_preferred' => 0,
				);

		foreach($_POST['preferredCategories'] as $preferredCategory)
		{
			if ($category['category_id'] == $preferredCategory) {
				// ADD CATEGORY
				$request['is_preferred'] = 1;
			}
		}
				
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $base_url . '/api/index.php/users/' . $responseObj['id'] . '/verified_categories');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
		$response = curl_exec($ch);
		curl_close($ch);
		
	}

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


	<div id="content">
		
		<div id="linksNav">
			<ul>
				<li><a href="browse.php" >Browse</a></li>
				<li><a href="balance.php" >Balance</a></li>
				<li class="selected" ><a href="profile.php" >Profile</a></li>
			</ul>
		</div>
		
		
		
		<div id="mainContent">
			<h1>EDIT PROFILE</h1>
			<!--POPULATE PROFILE INFORMATION HERE-->
			<form id="editTutorProfile" action="#" method="post">		
				<?php
						// Echo the information using sprintf
						// Escape special html characters to enhance XSS security
				echo sprintf("<label>First Name</label><input type='text' name='first_name' value='%s' />", htmlentities($responseObj['first_name'],ENT_QUOTES));
				echo sprintf("<label>Last Name</label><input type='text'  name='last_name' value='%s' />", htmlentities($responseObj['last_name'],ENT_QUOTES));
				?>

				<?php
				echo "<label>Preferred Subjects</label>";
					
					if (sizeof($responseObj['verified_categories']) == 0)
					{
						echo '<p>&lt;None&gt;</p>';
					} else {
					
						foreach($responseObj['verified_categories'] as $verifiedCategory)
						{
							$checked = '';
							
							if ($verifiedCategory['is_preferred']) {
								$checked = "checked";			
							}
						
						
						echo sprintf("<div class='editProfileItem'>   <input type='checkbox' name='preferredCategories[]' %s id='category_%s' value='%s' />   <label for='category_%s' />%s</label>    </div>", $checked, $verifiedCategory['category_id'], $verifiedCategory['category_id'], $verifiedCategory['category_id'], $verifiedCategory['name']);
						}
					}
				
				?>

				<input type="submit" id="submitButton" value="Save Changes" />
			</form>
		</div>
	</div>

	<?php include('footer.php') ?>

</body>
</html>
