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
	$deleteResponse = curl_exec($ch);
	curl_close($ch);
	
	// Redirect to the logout page	
	header('Location: logout.php');
	exit;
}

if (isset($_POST['submit']) && $_POST['submit'] == 'Take Test') {
	$category_id = $_POST['category'];
	header(sprintf('Location: subjectTest.php?category_id=%s', $category_id));
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
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
	<script src="js/validateSelectTest.js" type="text/javascript"></script>
    <script src="js/popups.js" type="text/javascript"></script>

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

			<h1>YOUR PROFILE</h1>
			
			<!--POPULATE PROFILE INFORMATION HERE-->
			<form id="tutorprofile" action="editprofile.php" method="get">
				
				<?php
				// Echo the information using sprintf
				// Escape special html characters to enhance XSS security
				echo sprintf("<div class='profileItem'><label>First Name:</label><p>%s</p></div>", htmlspecialchars($responseObj['first_name']));
				echo sprintf("<div class='profileItem'><label>Last Name:</label><p>%s</p></div>", htmlspecialchars($responseObj['last_name']));
				echo sprintf("<div class='profileItem'><label>Email:</label><p>%s</p></div>", htmlspecialchars($responseObj['email']));
				
				
				if (isset($responseObj['average_rating'])) {
				
				$stars = '';
				
				for ($i = 0; $i < $responseObj['average_rating']; $i++) {
					$stars .= '&#9733; ';
				}
				
					echo sprintf("<div class='profileItem'><label>Rating:</label><div class='ratingContainer'><span class='ratingStars'>%s</span> (%s)</div></div>", $stars, $responseObj['total_answers']);
				}

				echo "<div class='profileItem'><label>Preferred Categories:</label>";

				
				$count = 0;
				foreach ($responseObj['verified_categories'] as $verified_category) {
					foreach ($categories as $category) {
						if($verified_category['category_id'] == $category['id'] && $verified_category['is_preferred'])
						{
							echo sprintf("<p>%s</p>",$category['name']);
							echo '<br />';
							$count++;
						}
					}
				}
					
				if ($count == 0)
				{
					echo '<p>&lt;None&gt;</p>';
				}

				echo "</div>";
				?>
				
				<input type="submit" value="Edit Profile" />
				
			</form>
			
			<?php
			if ($responseObj['authentication_mode'] == 'custom') {
			?>
			<form id="changePasswordForm" action="changePassword.php" method="post">
				<input id="changePasswordButton" type="submit" name="submit" value="Change Password" />
			</form>
			<?php
			}
			?>
			
			
			
			<?php 
			
			if (sizeof($responseObj['verified_categories']) < sizeof($categories)) {
			
			?>	
			
			<div class="divider"></div>

			<h1>YOUR VERIFIED CATEGORIES</h1>
			<div id="verifiedCategories">
				<p>You are currently certified in the following categories:</p>
				<ul>
					<!--Populate list-->
					<?php
						foreach ($responseObj['verified_categories'] as $vc){
							echo "<li>" . $vc['name'] . "</li>";
						}
					?>
				</ul>
			</div>
			
			<div class="divider"></div>
			
			<h1>ADD VERIFIED CATEGORY</h1>
			<form id="categoryTestForm" action="#" method="post">
				<select name="category">
					<option value="Select Category" selected="true" disabled="disabled">Select Category</option>
					<!-- Populate menu -->
					<?php
					
					foreach($categories as $category)
					{
						$isVerified = false;
						foreach ($responseObj['verified_categories'] as $verified_category)
						{
							if ($category['id'] == $verified_category['category_id']) {
								$isVerified = true;
							}
						}
						
						if (!$isVerified)
						{
							echo sprintf('<option value="%s">%s</option>', $category['id'],$category['name']);
						}
					}
					
					?>
				</select>
				<input id="tutortestbutton" type="submit" name="submit" value="Take Test"/>
			</form>
			
			<?php
			
			}
			
			?>
			
			<div class="divider"></div>
			<h1>ACCOUNT ACTIONS</h1>
			<form id="deleteAccountForm" action="profile.php" method="post">
				<input id="deleteAccountButton" type="submit" name="submit" value="Delete Account" />
			</form>
			
		</div>
	</div>

	<?php include('footer.php') ?>

</body>
</html>
