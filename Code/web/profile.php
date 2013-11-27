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
				
					echo sprintf("<div class='profileItem'><label>SnapTutor Rating:</label><div class='ratingContainer'><span class='ratingStars'>%s</span> (%s total ratings)</div></div>", $stars, $responseObj['total_answers']);
				}

				echo "<div class='profileItem'><label>Preferred Categories:</label>";

				$count = 0;
				foreach ($responseObj['verified_categories'] as $verified_category) {
					foreach ($categories as $category) {
						if($verified_category['category_id'] == $category['id'] && $verified_category['is_preferred'])
						{
							$catname = $category['name'];
                            $icon_url = sprintf('res/icons/%s.png',$catname); 
                            echo sprintf("<a href='browse.php?search=%s'>",$catname);
                            echo sprintf('<span class="certified"><img src="%s" alt="%s" />%s</span>',$icon_url,$catname,$catname);
                            echo '</a><br />';
							$count++;
						}
					}
				}
					
				if ($count == 0)
				{
					echo '<p>Add preferred categories <a href="editprofile.php">here</a></p>';
				}

				echo "</div>";
				?>
                <div class='profileItem'><label>Certified Categories:</label>
					<!--Populate list-->
                    <?php
						foreach ($responseObj['verified_categories'] as $vc){

                            $icon_url = sprintf('res/icons/%s.png',$vc['name']); 
                            $vcatname = $vc['name'];
                            if(file_exists($icon_url) ) {
                                echo sprintf("<a title='Browse %s questions' href='browse.php?search=%s'>",$vcatname,$vcatname);
                                echo sprintf("<span><img src='%s' alt='%s'/>%s</span>",$icon_url,$vcatname,$vcatname);
                                echo "</a>";
                            } else {
                                echo sprintf("<a title='Browse %s questions' href='http://snap2ask.com/git/snap2ask/Code/web/browse.php?search=%s'>", $vcatname, $vcatname);
                                echo sprintf("<span><img src='res/icons/Other.png' alt='other' /> %s </span>", $vc['name']);
                                echo '</a>';
                            }
                        }/*end foreach*/
					?>
			</div>					
			</form>
			
			<?php 
			
			if (sizeof($responseObj['verified_categories']) < sizeof($categories)) {
			
			?>	
			
			<div class="divider"></div>

			<h1>GET CERTIFIED</h1>
            <h3>When you pass our the subject test, you will be paid more for that subject.</h3>

            <form id="categoryTestForm" action="#" method="post">
    			<select name="category">
					<option value="Select Category" selected disabled>Select Category</option>
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
							echo sprintf('<option value="%s">%s</option> ', $category['id'],$category['name']);
						}
					}
					
					?>
				</select>
				<input id="tutortestbutton" type="submit" name="submit" value="Take Test"/>
			</form>			
			<div class="divider"></div>
			
			<?php
			
			}
			
			?>
			<h1>MANAGE SNAPACCOUNT</h1>
            <form id="manageprofile" action="editprofile.php" method="get">
                <input type="submit" value="Edit Profile" title="Edit your name and e-mail address"/>
                <input type="submit" value="Add Preferred Categories" title="These categories will appear in the 'Familiar' search"/>
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
			<form id="deleteAccountForm" action="profile.php" method="post">
				<input id="deleteAccountButton" type="submit" name="submit" value="Delete Account" />
			</form>
			
		</div>
	</div>

	<?php include('footer.php') ?>

</body>
</html>
